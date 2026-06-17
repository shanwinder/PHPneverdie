<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Services\QuizService;

class QuizController extends Controller
{
    public function show(Request $request): void
    {
        $lesson = $this->lesson((int) $request->params['id']);
        $quiz = QuizService::quizForLesson((int) $lesson['id']);
        if (!$quiz) {
            http_response_code(404);
            view('errors/404', ['title' => 'Quiz not found']);
            return;
        }

        $this->render('quizzes/show', ['title' => $quiz['title'], 'lesson' => $lesson, 'quiz' => $quiz]);
    }

    public function submit(Request $request): void
    {
        $this->requireCsrf();
        $lesson = $this->lesson((int) $request->params['id']);
        $quiz = QuizService::quizForLesson((int) $lesson['id']);
        if (!$quiz) {
            http_response_code(404);
            view('errors/404', ['title' => 'Quiz not found']);
            return;
        }

        $result = QuizService::submit((int) current_user()['id'], $quiz, $_POST['answers'] ?? []);
        $this->render('quizzes/result', ['title' => 'ผล Quiz', 'lesson' => $lesson, 'quiz' => $quiz, 'result' => $result]);
    }

    private function lesson(int $id): array
    {
        $lesson = Database::first(
            'SELECT lessons.*, modules.title AS module_title, courses.title AS course_title, courses.slug AS course_slug
             FROM lessons
             JOIN modules ON modules.id = lessons.module_id
             JOIN courses ON courses.id = modules.course_id
             WHERE lessons.id = :id
               AND lessons.is_published = 1 AND modules.is_published = 1 AND courses.is_published = 1',
            ['id' => $id]
        );
        if (!$lesson) {
            http_response_code(404);
            view('errors/404', ['title' => 'Lesson not found']);
            exit;
        }
        return $lesson;
    }
}
