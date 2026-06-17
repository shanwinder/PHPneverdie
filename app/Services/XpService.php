<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDOException;

class XpService
{
    public static function addOnce(int $userId, string $eventType, ?int $eventId, int $amount, string $description): bool
    {
        try {
            Database::insert(
                'INSERT INTO xp_logs (user_id, event_type, event_id, xp_amount, description, created_at)
                 VALUES (:user_id, :event_type, :event_id, :xp_amount, :description, NOW())',
                [
                    'user_id' => $userId,
                    'event_type' => $eventType,
                    'event_id' => $eventId,
                    'xp_amount' => $amount,
                    'description' => $description,
                ]
            );
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                return false;
            }
            throw $exception;
        }

        Database::execute(
            'UPDATE users SET xp = xp + :amount, level = :level, updated_at = NOW() WHERE id = :user_id',
            [
                'amount' => $amount,
                'level' => self::levelFor(self::currentXp($userId) + $amount),
                'user_id' => $userId,
            ]
        );

        if (($user = current_user()) && (int) $user['id'] === $userId) {
            $fresh = Database::first(
                'SELECT users.id, users.name, users.email, users.xp, users.level, roles.name AS role
                 FROM users JOIN roles ON roles.id = users.role_id WHERE users.id = :id',
                ['id' => $userId]
            );
            if ($fresh) {
                \App\Core\Session::put('user', $fresh);
            }
        }

        return true;
    }

    public static function currentXp(int $userId): int
    {
        $row = Database::first('SELECT xp FROM users WHERE id = :id', ['id' => $userId]);
        return (int) ($row['xp'] ?? 0);
    }

    public static function levelFor(int $xp): int
    {
        return match (true) {
            $xp >= 1000 => 5,
            $xp >= 500 => 4,
            $xp >= 250 => 3,
            $xp >= 100 => 2,
            default => 1,
        };
    }

    public static function levelName(int $level): string
    {
        return [
            1 => 'PHP Newbie',
            2 => 'PHP Apprentice',
            3 => 'PHP Explorer',
            4 => 'PHP Builder',
            5 => 'PHP Developer',
        ][$level] ?? 'PHP Newbie';
    }

    public static function xpToNextLevel(int $xp): int
    {
        foreach ([100, 250, 500, 1000] as $threshold) {
            if ($xp < $threshold) {
                return $threshold - $xp;
            }
        }
        return 0;
    }
}
