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

        $this->render('dashboard/index', [
            'title' => 'Dashboard',
            'summary' => $summary,
            'nextLesson' => $nextLesson,
            'latestLesson' => $latestLesson,
            'lessons' => $lessons,
            'levelName' => XpService::levelName((int) $user['level']),
            'xpToNext' => XpService::xpToNextLevel((int) $user['xp']),
        ]);
    }
}
