<?php

namespace Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $config = Config::get('database');

        try {
            if ($config['driver'] === 'sqlite') {
                $dsn = "sqlite:" . $config['sqlite_path'];
            } else {
                $dsn = "mysql:host={$config['host']};dbname={$config['name']};charset={$config['charset']}";
            }

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $this->pdo = new PDO($dsn, $config['user'] ?? null, $config['pass'] ?? null, $options);
            
            // Enable foreign keys for SQLite
            if ($config['driver'] === 'sqlite') {
                $this->pdo->exec("PRAGMA foreign_keys = ON;");
            }

        } catch (PDOException $e) {
            die("Database Connection Error: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}
