<?php
namespace App\Database;

use PDO;

class MigrationManager
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->createMigrationsTable();
    }

    private function createMigrationsTable()
    {
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function runMigrations(string $migrationDir)
    {
        $applied = $this->getAppliedMigrations();
        $files = glob($migrationDir . '/*.sql');
        sort($files); // wichtig: Migrationen in Reihenfolge ausführen

        foreach ($files as $file) {
            $migrationName = basename($file);
            if (!in_array($migrationName, $applied)) {
                $sql = file_get_contents($file);
                if ($sql !== false) {
                    $this->db->exec($sql);
                    $this->recordMigration($migrationName);
                    echo "✅ Migration $migrationName ausgeführt.\n";
                }
            }
        }
    }

    private function getAppliedMigrations(): array
    {
        $stmt = $this->db->query("SELECT migration FROM migrations");
        return $stmt ? $stmt->fetchAll(PDO::FETCH_COLUMN) : [];
    }

    private function recordMigration(string $migrationName)
    {
        $stmt = $this->db->prepare("INSERT INTO migrations (migration) VALUES (:migration)");
        $stmt->execute(['migration' => $migrationName]);
    }
}
