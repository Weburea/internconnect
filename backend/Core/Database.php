<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            // Load environment configuration
            $dbConnection = $_ENV['DB_CONNECTION'] ?? 'sqlite';
            $dbPath = $_ENV['DB_DATABASE'] ?? 'database/database.sqlite';

            try {
                if ($dbConnection === 'sqlite') {
                    $dsn = "sqlite:" . dirname(__DIR__, 2) . '/' . $dbPath;
                    self::$instance = new PDO($dsn);
                } else {
                    // Placeholder for other connections like MySQL
                    $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
                    $port = $_ENV['DB_PORT'] ?? '3306';
                    $dbname = $_ENV['DB_DATABASE'] ?? 'internconnect';
                    $username = $_ENV['DB_USERNAME'] ?? 'root';
                    $password = $_ENV['DB_PASSWORD'] ?? '';

                    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
                    self::$instance = new PDO($dsn, $username, $password);
                }

                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                error_log("Database connection failure: " . $e->getMessage());
                die("Database connection failed. Please check configurations.");
            }
        }

        return self::$instance;
    }
}
