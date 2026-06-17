<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $pdo = null;

    public static function connection(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $driver = (string) config('database.connection', 'mysql');
        $charset = (string) config('database.charset', 'utf8mb4');

        if ($driver === 'sqlite') {
            $dsn = 'sqlite:' . base_path((string) config('database.database'));
            $username = null;
            $password = null;
        } else {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                config('database.host'),
                config('database.port'),
                config('database.database'),
                $charset
            );
            $username = config('database.username');
            $password = config('database.password');
        }

        try {
            self::$pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $exception) {
            if (config('app.debug')) {
                throw $exception;
            }

            http_response_code(500);
            view('errors/500', ['title' => 'Database error']);
            exit;
        }

        return self::$pdo;
    }

    public static function select(string $sql, array $params = []): array
    {
        $statement = self::connection()->prepare($sql);
        $statement->execute($params);
        return $statement->fetchAll();
    }

    public static function first(string $sql, array $params = []): ?array
    {
        $rows = self::select($sql, $params);
        return $rows[0] ?? null;
    }

    public static function execute(string $sql, array $params = []): bool
    {
        $statement = self::connection()->prepare($sql);
        return $statement->execute($params);
    }

    public static function insert(string $sql, array $params = []): int
    {
        self::execute($sql, $params);
        return (int) self::connection()->lastInsertId();
    }
}
