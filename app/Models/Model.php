<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

abstract class Model
{
    protected static string $table;

    public static function find(int $id): ?array
    {
        return Database::first('SELECT * FROM ' . static::$table . ' WHERE id = :id', ['id' => $id]);
    }

    public static function all(string $orderBy = 'id'): array
    {
        return Database::select('SELECT * FROM ' . static::$table . ' ORDER BY ' . $orderBy);
    }
}
