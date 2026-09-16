<?php

declare(strict_types=1);

namespace App;

use PDO;

final class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $dsn = config('database.dsn');
        if (!$dsn) {
            $host = config('database.host');
            $port = config('database.port', '3306');
            $name = config('database.name');
            $charset = config('database.charset', 'utf8mb4');
            $dsn = "mysql:host={$host};port={$port};dbname={$name};charset={$charset}";
        }

        self::$connection = new PDO((string) $dsn, (string) config('database.user', ''), (string) config('database.pass', ''), [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        if (str_starts_with((string) $dsn, 'sqlite:')) {
            self::$connection->exec('PRAGMA foreign_keys = ON');
        }

        return self::$connection;
    }

    public static function reset(): void
    {
        self::$connection = null;
    }
}
