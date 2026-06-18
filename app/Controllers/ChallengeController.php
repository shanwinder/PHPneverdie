<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;
use App\Services\ChallengeSubmissionService;
use App\Services\XpService;

class ChallengeController extends Controller
{
    public function index(Request $request): void
    {
        $userId = (int) current_user()['id'];
        $challenges = Database::select(
            'SELECT challenges.*, lessons.title AS lesson_title, lessons.slug AS lesson_slug,
                MAX(CASE WHEN challenge_submissions.status = "passed" THEN 1 ELSE 0 END) AS passed,
                COUNT(challenge_submissions.id) AS attempts
             FROM challenges
             JOIN lessons ON lessons.id = challenges.lesson_id
             JOIN modules ON modules.id = lessons.module_id
             JOIN courses ON courses.id = modules.course_id
             LEFT JOIN challenge_submissions ON challenge_submissions.challenge_id = challenges.id AND challenge_submissions.user_id = :user_id
             WHERE challenges.is_published = 1 AND lessons.is_published = 1 AND modules.is_published = 1 AND courses.is_published = 1
             GROUP BY challenges.id, lessons.title, lessons.slug
             ORDER BY lessons.sort_order, challenges.sort_order, challenges.id',
            ['user_id' => $userId]
        );

        $this->render('challenges/index', ['title' => 'Challenges', 'challenges' => $challenges]);
    }

    public function lessonPractice(Request $request): void
    {
        $challenge = Database::first(
            'SELECT * FROM challenges WHERE lesson_id = :lesson_id AND is_published = 1 ORDER BY sort_order, id LIMIT 1',
            ['lesson_id' => $request->params['id']]
        );
        if (!$challenge) {
            Session::flash('error', 'บทเรียนนี้ยังไม่มี challenge ที่เผยแพร่');
            redirect('/dashboard');
        }

        redirect('/challenges/' . $challenge['slug']);
    }

    public function show(Request $request): void
    {
        $challenge = $this->challengeBySlug((string) $request->params['slug']);
        if (!$challenge) {
            http_response_code(404);
            view('errors/404', ['title' => 'Challenge not found']);
            return;
        }

        $userId = (int) current_user()['id'];
        XpService::addOnce($userId, 'challenge_started', (int) $challenge['id'], 5, 'เริ่ม Challenge: ' . $challenge['title']);
        $this->render('challenges/show', [
            'title' => $challenge['title'],
            'challenge' => $challenge,
            'history' => $this->historyRows($userId, (int) $challenge['id'], 5),
            'hints' => $this->visibleHints((int) $challenge['id']),
        ]);
    }

    public function submit(Request $request): void
    {
        $this->requireCsrf();
        $challenge = $this->challengeById((int) $request->params['id']);
        if (!$challenge) {
            http_response_code(404);
            view('errors/404', ['title' => 'Challenge not found']);
            return;
        }

        $code = (string) $request->input('code', '');
        $hintsUsed = (int) Session::get($this->hintKey((int) $challenge['id']), 0);
        $service = new ChallengeSubmissionService();
        $result = $service->submit((int) current_user()['id'], $challenge, $code, $hintsUsed);
        Session::put('last_challenge_result', $result);
        Session::flash($result['status'] === 'passed' ? 'success' : 'error', $result['feedback']);
        redirect('/challenges/' . $challenge['slug'] . '/result?submission=' . $result['submission_id']);
    }

    public function result(Request $request): void
    {
        $submission = Database::first(
            'SELECT challenge_submissions.*, challenges.title AS challenge_title, challenges.slug AS challenge_slug
             FROM challenge_submissions
             JOIN challenges ON challenges.id = challenge_submissions.challenge_id
             WHERE challenge_submissions.id = :id AND challenge_submissions.user_id = :user_id',
            ['id' => (int) $request->input('submission'), 'user_id' => current_user()['id']]
        );
        if (!$submission) {
            http_response_code(404);
            view('errors/404', ['title' => 'Submission not found']);
            return;
        }

        $checks = Database::select('SELECT * FROM challenge_submission_checks WHERE submission_id = :id ORDER BY id', ['id' => $submission['id']]);
        $lastResult = Session::get('last_challenge_result', []);
        $this->render('challenges/result', [
            'title' => 'Submission Result',
            'submission' => $submission,
            'checks' => $checks,
            'newBadges' => is_array($lastResult) ? ($lastResult['new_badges'] ?? []) : [],
        ]);
    }

    public function history(Request $request): void
    {
        $challenge = $this->challengeById((int) $request->params['id']);
        if (!$challenge) {
            http_response_code(404);
            view('errors/404', ['title' => 'Challenge not found']);
            return;
        }

        $this->render('challenges/history', [
            'title' => 'Submission History',
            'challenge' => $challenge,
            'history' => $this->historyRows((int) current_user()['id'], (int) $challenge['id'], 50),
        ]);
    }

    public function hint(Request $request): void
    {
        $this->requireCsrf();
        $challenge = $this->challengeById((int) $request->params['id']);
        if (!$challenge) {
            http_response_code(404);
            view('errors/404', ['title' => 'Challenge not found']);
            return;
        }

        $key = $this->hintKey((int) $challenge['id']);
        $total = count($challenge['hints']);
        $current = min($total, (int) Session::get($key, 0) + 1);
        Session::put($key, $current);
        Session::flash('success', $current < $total ? 'เปิดคำใบ้ถัดไปแล้ว' : 'เปิดคำใบ้ครบแล้ว');
        redirect('/challenges/' . $challenge['slug']);
    }

    private function challengeBySlug(string $slug): ?array
    {
        $challenge = Database::first(
            'SELECT challenges.*, lessons.title AS lesson_title, lessons.slug AS lesson_slug, courses.slug AS course_slug
             FROM challenges
             JOIN lessons ON lessons.id = challenges.lesson_id
             JOIN modules ON modules.id = lessons.module_id
             JOIN courses ON courses.id = modules.course_id
             WHERE challenges.slug = :slug AND challenges.is_published = 1 AND lessons.is_published = 1 AND modules.is_published = 1 AND courses.is_published = 1',
            ['slug' => $slug]
        );

        return $challenge ? $this->withRules($challenge) : null;
    }

    private function challengeById(int $id): ?array
    {
        $challenge = Database::first(
            'SELECT challenges.*, lessons.title AS lesson_title, lessons.slug AS lesson_slug, courses.slug AS course_slug
             FROM challenges
             JOIN lessons ON lessons.id = challenges.lesson_id
             JOIN modules ON modules.id = lessons.module_id
             JOIN courses ON courses.id = modules.course_id
             WHERE challenges.id = :id AND challenges.is_published = 1',
            ['id' => $id]
        );

        return $challenge ? $this->withRules($challenge) : null;
    }

    private function withRules(array $challenge): array
    {
        $challenge['required_keywords'] = Database::select('SELECT * FROM challenge_required_keywords WHERE challenge_id = :id ORDER BY sort_order, id', ['id' => $challenge['id']]);
        $challenge['forbidden_keywords'] = Database::select('SELECT * FROM challenge_forbidden_keywords WHERE challenge_id = :id ORDER BY sort_order, id', ['id' => $challenge['id']]);
        $challenge['hints'] = Database::select('SELECT * FROM challenge_hints WHERE challenge_id = :id ORDER BY sort_order, id', ['id' => $challenge['id']]);
        return $challenge;
    }

    private function visibleHints(int $challengeId): array
    {
        $count = (int) Session::get($this->hintKey($challengeId), 0);
        if ($count <= 0) {
            return [];
        }
        return Database::select(
            'SELECT * FROM challenge_hints WHERE challenge_id = :id ORDER BY sort_order, id LIMIT ' . $count,
            ['id' => $challengeId]
        );
    }

    private function historyRows(int $userId, int $challengeId, int $limit): array
    {
        return Database::select(
            'SELECT * FROM challenge_submissions WHERE user_id = :user_id AND challenge_id = :challenge_id ORDER BY submitted_at DESC LIMIT ' . max(1, min(50, $limit)),
            ['user_id' => $userId, 'challenge_id' => $challengeId]
        );
    }

    private function hintKey(int $challengeId): string
    {
        return 'challenge_hint_' . $challengeId;
    }
}
