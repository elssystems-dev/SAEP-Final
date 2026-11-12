<?php
declare(strict_types=1);

namespace VetVida\Core;

use PDO;
use RuntimeException;

final class Database {
    private static ?PDO $connection = null;

    private function __construct() {}
    private function __clone(): void {}

    public static function getConnection(): PDO {
        if (self::$connection === null) {
            $configFile = dirname(__DIR__, 2) . '/config/database.ini';
            if (!file_exists($configFile)) {
                throw new RuntimeException("Arquivo de configuracao do banco nao encontrado: {$configFile}");
            }

            $config = parse_ini_file($configFile, true);
            if (!isset($config['database'])) {
                throw new RuntimeException("Secao [database] ausente no arquivo database.ini.");
            }

            $db = $config['database'];
            $dsn = sprintf(
                "%s:host=%s;port=%s;dbname=%s",
                $db['db_driver'] ?? 'pgsql',
                $db['db_host'] ?? 'localhost',
                $db['db_port'] ?? '5432',
                $db['db_name'] ?? 'saep_db'
            );

            self::$connection = new PDO(
                $dsn,
                $db['db_user'] ?? 'postgres',
                $db['db_pass'] ?? 'postgres',
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false
                ]
            );
        }

        return self::$connection;
    }

    public static function beginTransaction(): bool {
        return self::getConnection()->beginTransaction();
    }

    public static function commit(): bool {
        return self::getConnection()->commit();
    }

    public static function rollBack(): bool {
        return self::getConnection()->rollBack();
    }
}
