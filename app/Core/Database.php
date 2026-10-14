<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static ?PDO $instance = null;

    /**
     * Establish singleton database connection
     */
    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $config = require ROOT_PATH . '/config/config.php';
            $dbConfig = $config['db'];

            $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET SESSION sql_mode='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION,ONLY_FULL_GROUP_BY'",
            ];

            try {
                self::$instance = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], $options);
            } catch (PDOException $e) {
                error_log("Database Connection Failed: " . $e->getMessage());
                throw new \Exception("Database connection failure. Please review server log entries.");
            }
        }

        return self::$instance;
    }
}
