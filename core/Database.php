<?php

namespace app\core;

use PDO;
use PDOStatement;

/**
 * Class Database
 *
 * @author Daishitie <daishitie@gmail.com>
 * @package app\core
 */
class Database
{
    public PDO $pdo;

    /**
     * Database constructor.
     */
    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';

        $this->pdo = new PDO($dsn, $user, $password);
        $this->pdo->setAttribute(
            attribute: PDO::ATTR_ERRMODE,
            value: PDO::ERRMODE_EXCEPTION
        );
    }

    /**
     * Database apply migrations.
     */
    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $newMigrations = [];
        $files = scandir(Application::$ROOT_DIR . "/migrations");
        $toApplyMigrations = array_diff($files, $appliedMigrations);

        foreach ($toApplyMigrations as $migration) {
            if ($migration === '.' || $migration === '..') {
                continue;
            }

            require_once Application::$ROOT_DIR . "/migrations/$migration";
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();

            $this->log(message: "Applying migration $migration" . PHP_EOL);
            $instance->up();

            $this->log(message: "Applied migration $migration" . PHP_EOL);
            $newMigrations[] = $migration;
        }

        if (!empty($newMigrations)) {
            $this->saveMigrations(migrations: $newMigrations);
        } else {
            $this->log(message: "All migrations are applied");
        }
    }

    /**
     * Database create migrations table.
     */
    public function createMigrationsTable()
    {
        $this->pdo->exec(statement: "CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=INNODB;");
    }

    /**
     * Database get applied migrations.
     *
     * @return array|false
     */
    public function getAppliedMigrations(): array|false
    {
        $statement = $this->pdo->prepare(query: "SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(mode: PDO::FETCH_COLUMN);
    }

    /**
     * Database save migrations.
     *
     * @param array $migrations
     */
    public function saveMigrations(array $migrations)
    {
        $migrations = implode(",", array_map(fn ($m) => "('$m')", $migrations));
        $statement = $this->pdo->prepare(query: "INSERT INTO migrations (migration) VALUES $migrations");
        $statement->execute();
    }

    /**
     * Database pdo prepare.
     *
     * @param string $sql
     * @param array $options
     * @return PDOStatement|false
     */
    public function prepare(string $query, array $options = []): PDOStatement|false
    {
        return $this->pdo->prepare(query: $query, options: $options);
    }

    /**
     * Database logger.
     *
     * @param string $message
     */
    protected function log(string $message)
    {
        echo '[' . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
    }
}
