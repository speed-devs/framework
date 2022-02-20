<?php

namespace speedweb\core;

use PDO;

class Database
{

    public PDO $pdo;

    public function __construct(array $config)
    {
        $dsn = 'mysql:host=' . $config['HOST'] . ';dbname=' . $config['DB_NAME'] . ';port=' . $config['PORT'] . ';charset=utf8';
        $username = $config['DB_USERNAME'];
        $password = $config['DB_PASSWORD'];

        $this->pdo = new PDO($dsn, $username, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $newMigrations = [];
        $files = scandir(Application::$ROOT_DIR . '/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);
        foreach ($toApplyMigrations as $toApplyMigration) {
            if ($toApplyMigration === '.' || $toApplyMigration === '..') {
                continue;
            }

            require_once Application::$ROOT_DIR . '/migrations/' . $toApplyMigration;
            $className = pathinfo($toApplyMigration, PATHINFO_FILENAME);
            $instance = new $className();
            $this->log("Applying migration $toApplyMigration");
            $instance->up();
            $this->log("Applied migration $toApplyMigration");
            $newMigrations[] = $toApplyMigration;
        }

        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        }else{
            $this->log("All migrations are applied");
        }
    }

    public function createMigrationsTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations ( id INT AUTO_INCREMENT PRIMARY KEY, migration VARCHAR(255), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ) ENGINE=INNODB;");
    }

    public function getAppliedMigrations()
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    public function saveMigrations(array $migrations)
    {
        $str = implode(",", array_map(fn($m) => "('$m')", $migrations));
        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $str");
        $statement->execute();
    }

    protected function log(string $message): void
    {
        echo '[' . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
    }
}