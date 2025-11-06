<?php
/**
 * Project: Liga-OLV
 * File: MigrationManager.php
 *
 * Beschreibung:
 *
 * PHP version 8.2
 *
 * @category  Ligaverwaltung
 * @package   LVO
 * @author    Torsten Hofmann <webmaster@bastel-code.de>
 * @copyright 2025 Torsten Hofmann
 * @license   MIT License
 * @link      https://olv.bastel-code.de/
 */
namespace App\Database;

use PDO;

class MigrationManager
{
    private PDO $db;
    private bool $importData;

    public function __construct(PDO $db, bool $importData = true)
    {
        $this->db = $db;
        $this->importData = $importData;
        $this->createMigrationsTable();
    }

    private function createMigrationsTable(): void
    {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function runMigrations(string $migrationDir): void
    {
        $applied = $this->getAppliedMigrations();
        $files = glob($migrationDir . '/*');
        sort($files);
        
        foreach ($files as $file) {
            $migrationName = basename($file);
            if (!in_array($migrationName, $applied)) {
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                
                try {
                    if ($ext === 'sql') {
                        $this->applySQLMigration($file);
                    }
                    // CSV nur importieren, wenn $importData = true
                    elseif ($ext === 'csv' && $this->importData) {
                        $this->importCSV($file);
                    }
                    
                    $this->recordMigration($migrationName);
                    echo "✅ Migration $migrationName ausgeführt.\n";
                } catch (\Exception $e) {
                    echo "❌ Fehler bei Migration $migrationName: " . $e->getMessage() . "\n";
                }
            }
        }
        
    }

    private function getAppliedMigrations(): array
    {
        $stmt = $this->db->query("SELECT migration FROM migrations");
        return $stmt ? $stmt->fetchAll(PDO::FETCH_COLUMN) : [];
    }
        
    private function recordMigration(string $migrationName): void
    {
        $stmt = $this->db->prepare("INSERT INTO migrations (migration) VALUES (:migration)");
        $stmt->execute(['migration' => $migrationName]);
    }
        
    private function applySQLMigration(string $file): void
    {
        $sql = file_get_contents($file);
        if ($sql !== false) {
            $this->db->exec($sql);
        }
    }
    
    private function importCSV(string $file): void
    {
        // Tabellenname aus Dateiname extrahieren
        $filename = pathinfo($file, PATHINFO_FILENAME); // 2025-11-06_lb_liga
        $table = substr($filename, strpos($filename, '_') + 1); // lb_liga
            
        // Prüfen, ob Tabelle existiert
        $stmt = $this->db->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() === 0) {
            throw new \Exception("Tabelle '$table' existiert nicht.");
        }
            
        // CSV-Datei öffnen
        if (($handle = fopen($file, 'r')) === false) {
            throw new \Exception("Datei '$file' kann nicht geöffnet werden.");
        }
            
        $header = fgetcsv($handle);
        if ($header === false) {
            throw new \Exception("CSV-Datei '$file' hat keine Header-Zeile.");
        }
            
        // Prüfen, ob alle Spalten existieren
        $columnsStmt = $this->db->query("SHOW COLUMNS FROM `$table`");
        $tableColumns = $columnsStmt->fetchAll(PDO::FETCH_COLUMN);
        foreach ($header as $col) {
            if (!in_array($col, $tableColumns)) {
                throw new \Exception("Spalte '$col' existiert nicht in Tabelle '$table'.");
            }
        }
            
        fclose($handle);
            
        // FK temporär deaktivieren
        $this->db->exec("SET FOREIGN_KEY_CHECKS=0");
            
        // Daten importieren
        $this->db->exec("
            LOAD DATA LOCAL INFILE '" . addslashes($file) . "'
            INTO TABLE `$table`
            FIELDS TERMINATED BY ',' 
            ENCLOSED BY '\"'
            LINES TERMINATED BY '\n'
            IGNORE 1 LINES
        ");
            
        $this->db->exec("SET FOREIGN_KEY_CHECKS=1");
    }
        
}
