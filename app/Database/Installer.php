<?php
/**
 * Project: Liga-OLV
 * File: Installer.php
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

class Installer
{
    /**
     * Führt alle Migrationen aus.
     *
     * @param PDO $db
     * @param bool $importData Ob CSV-Daten importiert werden sollen (true = ja, false = nur Struktur)
     */
    public static function install(PDO $db, bool $importData = true): void
    {
        $migrationDir = __DIR__ . '/migrations';
        $manager = new MigrationManager($db, $importData);
        $manager->runMigrations($migrationDir);
    }
    
}
