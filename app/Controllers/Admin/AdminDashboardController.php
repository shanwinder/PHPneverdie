<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request): void
    {
        $stats = [
            'users' => Database::first('SELECT COUNT(*) AS total FROM users')['total'] ?? 0,
            'lessons' => Database::first('SELECT COUNT(*) AS total FROM lessons')['total'] ?? 0,
            'published_lessons' => Database::first('SELECT COUNT(*) AS total FROM lessons WHERE is_published = 1')['total'] ?? 0,
            'quizzes' => Database::first('SELECT COUNT(*) AS total FROM quizzes')['total'] ?? 0,
            'challenges' => Database::first('SELECT COUNT(*) AS total FROM challenges')['total'] ?? 0,
            'submissions' => Database::first('SELECT COUNT(*) AS total FROM challenge_submissions')['total'] ?? 0,
            'active_users' => Database::first('SELECT COUNT(*) AS total FROM users WHERE last_login_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)')['total'] ?? 0,
            'average_progress' => Database::first(
                'SELECT COALESCE(ROUND(AVG(user_progress.percent_complete)), 0) AS total
                 FROM (
                    SELECT users.id, (COUNT(CASE WHEN lesson_progress.status = "completed" THEN 1 END) / NULLIF((SELECT COUNT(*) FROM lessons WHERE is_published = 1), 0)) * 100 AS percent_complete
                    FROM users
                    LEFT JOIN lesson_progress ON lesson_progress.user_id = users.id
                    GROUP BY users.id
                 ) user_progress'
            )['total'] ?? 0,
        ];

        $recentAttempts = Database::select(
            'SELECT quiz_attempts.*, users.name AS user_name, quizzes.title AS quiz_title
             FROM quiz_attempts
             JOIN users ON users.id = quiz_attempts.user_id
             JOIN quizzes ON quizzes.id = quiz_attempts.quiz_id
             ORDER BY quiz_attempts.submitted_at DESC
             LIMIT 8'
        );

        $recentSubmissions = Database::select(
            'SELECT challenge_submissions.*, users.name AS user_name, challenges.title AS challenge_title
             FROM challenge_submissions
             JOIN users ON users.id = challenge_submissions.user_id
             JOIN challenges ON challenges.id = challenge_submissions.challenge_id
             ORDER BY challenge_submissions.submitted_at DESC
             LIMIT 8'
        );

        $this->render('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'stats' => $stats,
            'recentAttempts' => $recentAttempts,
            'recentSubmissions' => $recentSubmissions,
        ], 'admin');
    }
}
