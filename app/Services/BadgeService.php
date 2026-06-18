<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

class BadgeService
{
    public static function evaluateForUser(int $userId): array
    {
        $stats = [
            'challenge_count' => (int) (Database::first(
                'SELECT COUNT(DISTINCT challenge_id) AS total FROM challenge_submissions WHERE user_id = :user_id AND status = "passed"',
                ['user_id' => $userId]
            )['total'] ?? 0),
            'lesson_count' => (int) (Database::first(
                'SELECT COUNT(*) AS total FROM lesson_progress WHERE user_id = :user_id AND status = "completed"',
                ['user_id' => $userId]
            )['total'] ?? 0),
            'xp_total' => XpService::currentXp($userId),
        ];

        $badges = Database::select('SELECT * FROM badges WHERE is_active = 1 ORDER BY sort_order, id');
        $new = [];
        foreach ($badges as $badge) {
            if (!self::qualifies($badge, $stats)) {
                continue;
            }

            $statement = Database::connection()->prepare(
                'INSERT IGNORE INTO user_badges (user_id, badge_id, awarded_at) VALUES (:user_id, :badge_id, NOW())'
            );
            $statement->execute(['user_id' => $userId, 'badge_id' => $badge['id']]);
            if ($statement->rowCount() > 0) {
                XpService::addOnce($userId, 'badge_awarded', (int) $badge['id'], 10, 'ได้รับ Badge: ' . $badge['name']);
                $new[] = $badge;
            }
        }

        return $new;
    }

    private static function qualifies(array $badge, array $stats): bool
    {
        $value = (int) ($badge['rule_value'] ?? 0);
        return match ($badge['rule_type']) {
            'challenge_count' => $stats['challenge_count'] >= $value,
            'lesson_count' => $stats['lesson_count'] >= $value,
            'xp_total' => $stats['xp_total'] >= $value,
            default => false,
        };
    }
}
