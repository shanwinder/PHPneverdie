<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Services\ProgressService;
use App\Services\QuizService;

class LessonController extends Controller
{
    public function show(Request $request): void
    {
        $lesson = Database::first(
            'SELECT lessons.*, modules.title AS module_title, courses.id AS course_id, courses.title AS course_title, courses.slug AS course_slug
             FROM lessons
             JOIN modules ON modules.id = lessons.module_id
             JOIN courses ON courses.id = modules.course_id
             WHERE lessons.slug = :slug
               AND lessons.is_published = 1 AND modules.is_published = 1 AND courses.is_published = 1',
            ['slug' => $request->params['slug']]
        );

        if (!$lesson) {
            http_response_code(404);
            view('errors/404', ['title' => 'Lesson not found']);
            return;
        }

        ProgressService::markInProgress((int) current_user()['id'], (int) $lesson['id']);

        $pathLessons = Database::select(
            'SELECT lessons.id, lessons.slug, lessons.title
             FROM lessons
             JOIN modules ON modules.id = lessons.module_id
             WHERE modules.course_id = :course_id AND lessons.is_published = 1 AND modules.is_published = 1
             ORDER BY modules.sort_order, lessons.sort_order',
            ['course_id' => $lesson['course_id']]
        );
        $currentIndex = array_search((int) $lesson['id'], array_map('intval', array_column($pathLessons, 'id')), true);
        $prev = $currentIndex !== false && $currentIndex > 0 ? $pathLessons[$currentIndex - 1] : null;
        $next = $currentIndex !== false && $currentIndex < count($pathLessons) - 1 ? $pathLessons[$currentIndex + 1] : null;

        $quiz = QuizService::quizForLesson((int) $lesson['id']);

        $this->render('lessons/show', [
            'title' => $lesson['title'],
            'lesson' => $lesson,
            'prev' => $prev,
            'next' => $next,
            'quiz' => $quiz,
        ]);
    }

    public function start(Request $request): void
    {
        $this->requireCsrf();
        $lesson = Database::first('SELECT id, slug FROM lessons WHERE id = :id AND is_published = 1', ['id' => $request->params['id']]);
        if (!$lesson) {
            http_response_code(404);
            view('errors/404', ['title' => 'Lesson not found']);
            return;
        }

        ProgressService::markInProgress((int) current_user()['id'], (int) $lesson['id']);
        redirect('/lessons/' . $lesson['slug']);
    }
}
