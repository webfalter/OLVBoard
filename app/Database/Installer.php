<?php
namespace App\Database;

use PDO;

class Installer
{
    public static function install(PDO $db)
    {
        $migrationDir = __DIR__ . '/migrations';
        $manager = new MigrationManager($db);
        $manager->runMigrations($migrationDir);
    }
}
