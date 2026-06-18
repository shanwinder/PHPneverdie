<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;

class ChallengeController extends Controller
{
    public function index(Request $request): void
    {
        $rows = Database::select(
            'SELECT challenges.*, lessons.title AS lesson_title,
                COUNT(challenge_submissions.id) AS submissions,
                SUM(CASE WHEN challenge_submissions.status = "passed" THEN 1 ELSE 0 END) AS passed_submissions
             FROM challenges
             JOIN lessons ON lessons.id = challenges.lesson_id
             LEFT JOIN challenge_submissions ON challenge_submissions.challenge_id = challenges.id
             GROUP BY challenges.id, lessons.title
             ORDER BY lessons.sort_order, challenges.sort_order, challenges.id'
        );
        $this->render('admin/challenges/index', ['title' => 'Manage Challenges', 'challenges' => $rows], 'admin');
    }

    public function create(Request $request): void
    {
        $this->form(null);
    }

    public function edit(Request $request): void
    {
        $challenge = Database::first('SELECT * FROM challenges WHERE id = :id', ['id' => $request->params['id']]);
        if (!$challenge) {
            http_response_code(404);
            view('errors/404', ['title' => 'Challenge not found']);
            return;
        }
        $this->form($this->withRules($challenge));
    }

    public function store(Request $request): void
    {
        $this->requireCsrf();
        $id = Database::insert(
            'INSERT INTO challenges (lesson_id, title, slug, description, instructions, starter_code, expected_text, expected_output, checking_mode, difficulty, xp_reward, sort_order, is_published, created_at, updated_at)
             VALUES (:lesson_id, :title, :slug, :description, :instructions, :starter_code, :expected_text, :expected_output, :checking_mode, :difficulty, :xp_reward, :sort_order, :is_published, NOW(), NOW())',
            $this->data($request)
        );
        $this->syncRules($id, $request);
        $this->saved('/admin/challenges');
    }

    public function update(Request $request): void
    {
        $this->requireCsrf();
        Database::execute(
            'UPDATE challenges SET lesson_id = :lesson_id, title = :title, slug = :slug, description = :description, instructions = :instructions,
                starter_code = :starter_code, expected_text = :expected_text, expected_output = :expected_output, checking_mode = :checking_mode,
                difficulty = :difficulty, xp_reward = :xp_reward, sort_order = :sort_order, is_published = :is_published, updated_at = NOW()
             WHERE id = :id',
            ['id' => $request->params['id']] + $this->data($request)
        );
        $this->syncRules((int) $request->params['id'], $request);
        $this->saved('/admin/challenges/' . $request->params['id'] . '/edit');
    }

    public function delete(Request $request): void
    {
        $this->requireCsrf();
        Database::execute('DELETE FROM challenges WHERE id = :id', ['id' => $request->params['id']]);
        $this->saved('/admin/challenges');
    }

    public function submissions(Request $request): void
    {
        $challenge = Database::first('SELECT * FROM challenges WHERE id = :id', ['id' => $request->params['id']]);
        if (!$challenge) {
            http_response_code(404);
            view('errors/404', ['title' => 'Challenge not found']);
            return;
        }
        $submissions = Database::select(
            'SELECT challenge_submissions.*, users.name AS user_name
             FROM challenge_submissions
             JOIN users ON users.id = challenge_submissions.user_id
             WHERE challenge_submissions.challenge_id = :id
             ORDER BY challenge_submissions.submitted_at DESC
             LIMIT 100',
            ['id' => $challenge['id']]
        );
        $this->render('admin/challenges/submissions', [
            'title' => 'Challenge Submissions',
            'challenge' => $challenge,
            'submissions' => $submissions,
        ], 'admin');
    }

    private function form(?array $challenge): void
    {
        $lessons = Database::select(
            'SELECT lessons.id, lessons.title, modules.title AS module_title
             FROM lessons JOIN modules ON modules.id = lessons.module_id
             ORDER BY modules.sort_order, lessons.sort_order'
        );
        $this->render('admin/challenges/form', [
            'title' => $challenge ? 'Edit Challenge' : 'Create Challenge',
            'challenge' => $challenge,
            'lessons' => $lessons,
        ], 'admin');
    }

    private function data(Request $request): array
    {
        $mode = in_array($request->input('checking_mode'), ['text', 'keyword', 'pattern', 'manual'], true) ? $request->input('checking_mode') : 'keyword';
        $difficulty = in_array($request->input('difficulty'), ['beginner', 'intermediate', 'advanced'], true) ? $request->input('difficulty') : 'beginner';
        return [
            'lesson_id' => (int) $request->input('lesson_id'),
            'title' => trim((string) $request->input('title')),
            'slug' => $this->slug($request->input('slug') ?: $request->input('title')),
            'description' => trim((string) $request->input('description')),
            'instructions' => trim((string) $request->input('instructions')),
            'starter_code' => (string) $request->input('starter_code'),
            'expected_text' => trim((string) $request->input('expected_text')),
            'expected_output' => trim((string) $request->input('expected_output')),
            'checking_mode' => $mode,
            'difficulty' => $difficulty,
            'xp_reward' => (int) $request->input('xp_reward', 40),
            'sort_order' => (int) $request->input('sort_order', 0),
            'is_published' => $request->boolean('is_published'),
        ];
    }

    private function syncRules(int $challengeId, Request $request): void
    {
        Database::execute('DELETE FROM challenge_required_keywords WHERE challenge_id = :id', ['id' => $challengeId]);
        Database::execute('DELETE FROM challenge_forbidden_keywords WHERE challenge_id = :id', ['id' => $challengeId]);
        Database::execute('DELETE FROM challenge_hints WHERE challenge_id = :id', ['id' => $challengeId]);

        $this->insertLines('challenge_required_keywords', 'keyword', $challengeId, (string) $request->input('required_keywords', ''));
        $this->insertLines('challenge_forbidden_keywords', 'keyword', $challengeId, (string) $request->input('forbidden_keywords', ''));
        $this->insertLines('challenge_hints', 'hint_text', $challengeId, (string) $request->input('hints', ''));
    }

    private function insertLines(string $table, string $column, int $challengeId, string $lines): void
    {
        $items = array_values(array_filter(array_map('trim', preg_split('/\R/', $lines) ?: [])));
        foreach ($items as $index => $item) {
            Database::execute(
                "INSERT INTO {$table} (challenge_id, {$column}, sort_order, created_at, updated_at) VALUES (:challenge_id, :value, :sort_order, NOW(), NOW())",
                ['challenge_id' => $challengeId, 'value' => $item, 'sort_order' => $index + 1]
            );
        }
    }

    private function withRules(array $challenge): array
    {
        $challenge['required_keywords'] = Database::select('SELECT * FROM challenge_required_keywords WHERE challenge_id = :id ORDER BY sort_order, id', ['id' => $challenge['id']]);
        $challenge['forbidden_keywords'] = Database::select('SELECT * FROM challenge_forbidden_keywords WHERE challenge_id = :id ORDER BY sort_order, id', ['id' => $challenge['id']]);
        $challenge['hints'] = Database::select('SELECT * FROM challenge_hints WHERE challenge_id = :id ORDER BY sort_order, id', ['id' => $challenge['id']]);
        return $challenge;
    }

    private function saved(string $path): never
    {
        Session::flash('success', 'บันทึกข้อมูลเรียบร้อย');
        redirect($path);
    }

    private function slug(mixed $value): string
    {
        $slug = strtolower(trim((string) $value));
        $slug = preg_replace('/[^a-z0-9ก-๙]+/iu', '-', $slug) ?: 'challenge';
        return trim($slug, '-');
    }
}
