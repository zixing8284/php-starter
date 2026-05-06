<?php

namespace App;

class Database
{
    private \PDO $pdo;

    public function __construct(string $dbPath)
    {
        $dir = dirname($dbPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $this->pdo = new \PDO("sqlite:$dbPath");
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function initialize(): void
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS visitors (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $count = $this->pdo->query("SELECT COUNT(*) FROM visitors")->fetchColumn();
        if ($count == 0) {
            $this->addVisitor('World');
        }
    }

    public function getVisitors(): array
    {
        $stmt = $this->pdo->query("SELECT id, name, created_at FROM visitors ORDER BY id DESC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function addVisitor(string $name): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO visitors (name) VALUES (:name)");
        $stmt->execute(['name' => $name]);
    }
}
