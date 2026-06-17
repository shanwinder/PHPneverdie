<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

class QuizService
{
    public static function quizForLesson(int $lessonId): ?array
    {
        $quiz = Database::first(
            'SELECT * FROM quizzes WHERE lesson_id = :lesson_id AND is_published = 1',
            ['lesson_id' => $lessonId]
        );

        if (!$quiz) {
            return null;
        }

        $quiz['questions'] = self::questions((int) $quiz['id']);
        return $quiz;
    }

    public static function questions(int $quizId): array
    {
        $questions = Database::select(
            'SELECT * FROM quiz_questions WHERE quiz_id = :quiz_id ORDER BY sort_order, id',
            ['quiz_id' => $quizId]
        );

        foreach ($questions as &$question) {
            $question['choices'] = Database::select(
                'SELECT id, question_id, choice_text, sort_order FROM quiz_choices WHERE question_id = :question_id ORDER BY sort_order, id',
                ['question_id' => $question['id']]
            );
        }

        return $questions;
    }

    public static function submit(int $userId, array $quiz, array $answers): array
    {
        $questions = Database::select(
            'SELECT * FROM quiz_questions WHERE quiz_id = :quiz_id ORDER BY sort_order, id',
            ['quiz_id' => $quiz['id']]
        );

        $total = count($questions);
        $correct = 0;
        $answerRows = [];

        foreach ($questions as $question) {
            $choiceId = isset($answers[$question['id']]) ? (int) $answers[$question['id']] : 0;
            $choice = Database::first(
                'SELECT * FROM quiz_choices WHERE id = :id AND question_id = :question_id',
                ['id' => $choiceId, 'question_id' => $question['id']]
            );
            $isCorrect = (int) ($choice['is_correct'] ?? 0) === 1;
            if ($isCorrect) {
                $correct++;
            }
            $answerRows[] = [
                'question_id' => (int) $question['id'],
                'choice_id' => $choiceId ?: null,
                'is_correct' => $isCorrect ? 1 : 0,
            ];
        }

        $score = $total > 0 ? round(($correct / $total) * 100, 2) : 0;
        $passed = $score >= (float) $quiz['passing_score'];

        $attemptId = Database::insert(
            'INSERT INTO quiz_attempts (user_id, quiz_id, score, total_questions, correct_answers, passed, started_at, submitted_at)
             VALUES (:user_id, :quiz_id, :score, :total_questions, :correct_answers, :passed, NOW(), NOW())',
            [
                'user_id' => $userId,
                'quiz_id' => $quiz['id'],
                'score' => $score,
                'total_questions' => $total,
                'correct_answers' => $correct,
                'passed' => $passed ? 1 : 0,
            ]
        );

        foreach ($answerRows as $row) {
            Database::execute(
                'INSERT INTO quiz_answers (attempt_id, question_id, choice_id, is_correct, created_at)
                 VALUES (:attempt_id, :question_id, :choice_id, :is_correct, NOW())',
                ['attempt_id' => $attemptId] + $row
            );
        }

        if ($passed) {
            XpService::addOnce($userId, 'quiz_passed', (int) $quiz['id'], 20, 'ผ่าน Quiz: ' . $quiz['title']);
            ProgressService::completeLesson($userId, (int) $quiz['lesson_id']);
        }

        return [
            'attempt_id' => $attemptId,
            'score' => $score,
            'correct' => $correct,
            'total' => $total,
            'passed' => $passed,
            'passing_score' => (int) $quiz['passing_score'],
        ];
    }
}
