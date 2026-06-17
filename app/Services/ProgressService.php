<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

class ProgressService
{
    public static function markInProgress(int $userId, int $lessonId): void
    {
        $existing = Database::first(
            'SELECT status FROM lesson_progress WHERE user_id = :user_id AND lesson_id = :lesson_id',
            ['user_id' => $userId, 'lesson_id' => $lessonId]
        );

        if (!$existing) {
            Database::execute(
                'INSERT INTO lesson_progress (user_id, lesson_id, status, started_at, last_accessed_at, created_at, updated_at)
                 VALUES (:user_id, :lesson_id, "in_progress", NOW(), NOW(), NOW(), NOW())',
                ['user_id' => $userId, 'lesson_id' => $lessonId]
            );
            XpService::addOnce($userId, 'lesson_started', $lessonId, 5, 'เปิดบทเรียนครั้งแรก');
            return;
        }

        Database::execute(
            'UPDATE lesson_progress
             SET last_accessed_at = NOW(), updated_at = NOW()
             WHERE user_id = :user_id AND lesson_id = :lesson_id',
            ['user_id' => $userId, 'lesson_id' => $lessonId]
        );
    }

    public static function completeLesson(int $userId, int $lessonId): void
    {
        Database::execute(
            'INSERT INTO lesson_progress (user_id, lesson_id, status, started_at, completed_at, last_accessed_at, created_at, updated_at)
             VALUES (:user_id, :lesson_id, "completed", NOW(), NOW(), NOW(), NOW(), NOW())
             ON DUPLICATE KEY UPDATE
                status = "completed",
                completed_at = IF(completed_at IS NULL, NOW(), completed_at),
                last_accessed_at = NOW(),
                updated_at = NOW()',
            ['user_id' => $userId, 'lesson_id' => $lessonId]
        );

        $lesson = Database::first('SELECT title, xp_reward, module_id FROM lessons WHERE id = :id', ['id' => $lessonId]);
        if ($lesson) {
            XpService::addOnce($userId, 'lesson_completed', $lessonId, (int) $lesson['xp_reward'], 'เรียนจบบท: ' . $lesson['title']);
            self::rewardModuleIfComplete($userId, (int) $lesson['module_id']);
        }
    }

    public static function summary(int $userId): array
    {
        $total = (int) (Database::first(
            'SELECT COUNT(*) AS total FROM lessons
             JOIN modules ON modules.id = lessons.module_id
             JOIN courses ON courses.id = modules.course_id
             WHERE lessons.is_published = 1 AND modules.is_published = 1 AND courses.is_published = 1'
        )['total'] ?? 0);

        $completed = (int) (Database::first(
            'SELECT COUNT(*) AS total
             FROM lesson_progress
             JOIN lessons ON lessons.id = lesson_progress.lesson_id
             JOIN modules ON modules.id = lessons.module_id
             JOIN courses ON courses.id = modules.course_id
             WHERE lesson_progress.user_id = :user_id
               AND lesson_progress.status = "completed"
               AND lessons.is_published = 1 AND modules.is_published = 1 AND courses.is_published = 1',
            ['user_id' => $userId]
        )['total'] ?? 0);

        $passedQuizzes = (int) (Database::first(
            'SELECT COUNT(DISTINCT quiz_id) AS total FROM quiz_attempts WHERE user_id = :user_id AND passed = 1',
            ['user_id' => $userId]
        )['total'] ?? 0);

        return [
            'total_lessons' => $total,
            'completed_lessons' => $completed,
            'passed_quizzes' => $passedQuizzes,
            'percent' => $total > 0 ? (int) round(($completed / $total) * 100) : 0,
        ];
    }

    public static function statusMap(int $userId): array
    {
        $rows = Database::select(
            'SELECT lesson_id, status FROM lesson_progress WHERE user_id = :user_id',
            ['user_id' => $userId]
        );

        return array_column($rows, 'status', 'lesson_id');
    }

    public static function nextLesson(int $userId): ?array
    {
        return Database::first(
            'SELECT lessons.*, modules.title AS module_title, courses.title AS course_title
             FROM lessons
             JOIN modules ON modules.id = lessons.module_id
             JOIN courses ON courses.id = modules.course_id
             LEFT JOIN lesson_progress ON lesson_progress.lesson_id = lessons.id AND lesson_progress.user_id = :user_id
             WHERE lessons.is_published = 1 AND modules.is_published = 1 AND courses.is_published = 1
               AND (lesson_progress.status IS NULL OR lesson_progress.status != "completed")
             ORDER BY courses.sort_order, modules.sort_order, lessons.sort_order
             LIMIT 1',
            ['user_id' => $userId]
        );
    }

    public static function latestLesson(int $userId): ?array
    {
        return Database::first(
            'SELECT lessons.*, lesson_progress.status
             FROM lesson_progress
             JOIN lessons ON lessons.id = lesson_progress.lesson_id
             WHERE lesson_progress.user_id = :user_id
             ORDER BY lesson_progress.last_accessed_at DESC
             LIMIT 1',
            ['user_id' => $userId]
        );
    }

    private static function rewardModuleIfComplete(int $userId, int $moduleId): void
    {
        $total = (int) (Database::first(
            'SELECT COUNT(*) AS total FROM lessons WHERE module_id = :module_id AND is_published = 1',
            ['module_id' => $moduleId]
        )['total'] ?? 0);

        if ($total === 0) {
            return;
        }

        $completed = (int) (Database::first(
            'SELECT COUNT(*) AS total
             FROM lesson_progress
             JOIN lessons ON lessons.id = lesson_progress.lesson_id
             WHERE lesson_progress.user_id = :user_id
               AND lessons.module_id = :module_id
               AND lessons.is_published = 1
               AND lesson_progress.status = "completed"',
            ['user_id' => $userId, 'module_id' => $moduleId]
        )['total'] ?? 0);

        if ($completed >= $total) {
            XpService::addOnce($userId, 'module_completed', $moduleId, 50, 'เรียนจบโมดูล');
        }
    }
}
