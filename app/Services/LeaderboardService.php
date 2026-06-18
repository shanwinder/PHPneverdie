<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

class LeaderboardService
{
    public static function top(int $limit = 50): array
    {
        return Database::select(
            'SELECT users.id, users.name, users.xp, users.level,
                COUNT(DISTINCT CASE WHEN challenge_submissions.status = "passed" THEN challenge_submissions.challenge_id END) AS passed_challenges,
                COUNT(DISTINCT CASE WHEN quiz_attempts.passed = 1 THEN quiz_attempts.quiz_id END) AS passed_quizzes,
                COUNT(DISTINCT user_badges.badge_id) AS badge_count
             FROM users
             JOIN roles ON roles.id = users.role_id
             LEFT JOIN challenge_submissions ON challenge_submissions.user_id = users.id
             LEFT JOIN quiz_attempts ON quiz_attempts.user_id = users.id
             LEFT JOIN user_badges ON user_badges.user_id = users.id
             WHERE roles.name = "student" AND users.status = "active"
             GROUP BY users.id, users.name, users.xp, users.level
             ORDER BY users.xp DESC, passed_challenges DESC, passed_quizzes DESC, users.id ASC
             LIMIT ' . max(1, min(100, $limit))
        );
    }
}
