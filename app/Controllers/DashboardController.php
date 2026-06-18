<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Services\ProgressService;
use App\Services\XpService;

class DashboardController extends Controller
{
    public function index(Request $request): void
    {
        $user = current_user();
        $summary = ProgressService::summary((int) $user['id']);
        $nextLesson = ProgressService::nextLesson((int) $user['id']);
        $latestLesson = ProgressService::latestLesson((int) $user['id']);
        $lessons = Database::select(
            'SELECT lessons.*, modules.title AS module_title, COALESCE(lesson_progress.status, "not_started") AS status
             FROM lessons
             JOIN modules ON modules.id = lessons.module_id
             JOIN courses ON courses.id = modules.course_id
             LEFT JOIN lesson_progress ON lesson_progress.lesson_id = lessons.id AND lesson_progress.user_id = :user_id
             WHERE lessons.is_published = 1 AND modules.is_published = 1 AND courses.is_published = 1
             ORDER BY courses.sort_order, modules.sort_order, lessons.sort_order
             LIMIT 8',
            ['user_id' => $user['id']]
        );
        $challengeSummary = Database::first(
            'SELECT COUNT(DISTINCT challenges.id) AS total_challenges,
                COUNT(DISTINCT CASE WHEN challenge_submissions.status = "passed" THEN challenge_submissions.challenge_id END) AS passed_challenges
             FROM challenges
             LEFT JOIN challenge_submissions ON challenge_submissions.challenge_id = challenges.id AND challenge_submissions.user_id = :user_id
             WHERE challenges.is_published = 1',
            ['user_id' => $user['id']]
        );
        $recommendedChallenge = Database::first(
            'SELECT challenges.*, lessons.title AS lesson_title
             FROM challenges
             JOIN lessons ON lessons.id = challenges.lesson_id
             LEFT JOIN challenge_submissions ON challenge_submissions.challenge_id = challenges.id
                AND challenge_submissions.user_id = :user_id
                AND challenge_submissions.status = "passed"
             WHERE challenges.is_published = 1 AND challenge_submissions.id IS NULL
             ORDER BY lessons.sort_order, challenges.sort_order, challenges.id
             LIMIT 1',
            ['user_id' => $user['id']]
        );
        $latestBadges = Database::select(
            'SELECT badges.*, user_badges.awarded_at
             FROM user_badges
             JOIN badges ON badges.id = user_badges.badge_id
             WHERE user_badges.user_id = :user_id
             ORDER BY user_badges.awarded_at DESC
             LIMIT 3',
            ['user_id' => $user['id']]
        );

        $this->render('dashboard/index', [
            'title' => 'Dashboard',
            'summary' => $summary,
            'nextLesson' => $nextLesson,
            'latestLesson' => $latestLesson,
            'lessons' => $lessons,
            'levelName' => XpService::levelName((int) $user['level']),
            'xpToNext' => XpService::xpToNextLevel((int) $user['xp']),
            'challengeSummary' => $challengeSummary,
            'recommendedChallenge' => $recommendedChallenge,
            'latestBadges' => $latestBadges,
        ]);
    }
}
