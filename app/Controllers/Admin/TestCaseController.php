<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;

class TestCaseController extends Controller
{
    public function index(Request $request): void
    {
        $challenge = Database::first('SELECT * FROM challenges WHERE id = :id', ['id' => $request->params['id']]);
        if (!$challenge) {
            http_response_code(404);
            view('errors/404', ['title' => 'Challenge not found']);
            return;
        }
        $cases = Database::select('SELECT * FROM challenge_test_cases WHERE challenge_id = :id ORDER BY sort_order, id', ['id' => $challenge['id']]);
        $this->render('admin/test_cases/index', ['title' => 'Challenge Test Cases', 'challenge' => $challenge, 'cases' => $cases], 'admin');
    }

    public function store(Request $request): void
    {
        $this->requireCsrf();
        Database::execute(
            'INSERT INTO challenge_test_cases (challenge_id, name, input_data, expected_output, expected_pattern, comparison_mode, is_hidden, weight, sort_order, created_at, updated_at)
             VALUES (:challenge_id, :name, :input_data, :expected_output, :expected_pattern, :comparison_mode, :is_hidden, :weight, :sort_order, NOW(), NOW())',
            ['challenge_id' => $request->params['id']] + $this->data($request)
        );
        Session::flash('success', 'เพิ่ม test case แล้ว');
        redirect('/admin/challenges/' . $request->params['id'] . '/test-cases');
    }

    public function update(Request $request): void
    {
        $this->requireCsrf();
        $case = Database::first('SELECT * FROM challenge_test_cases WHERE id = :id', ['id' => $request->params['id']]);
        Database::execute(
            'UPDATE challenge_test_cases SET name = :name, input_data = :input_data, expected_output = :expected_output, expected_pattern = :expected_pattern,
                comparison_mode = :comparison_mode, is_hidden = :is_hidden, weight = :weight, sort_order = :sort_order, updated_at = NOW()
             WHERE id = :id',
            ['id' => $request->params['id']] + $this->data($request)
        );
        Session::flash('success', 'อัปเดต test case แล้ว');
        redirect('/admin/challenges/' . $case['challenge_id'] . '/test-cases');
    }

    public function delete(Request $request): void
    {
        $this->requireCsrf();
        $case = Database::first('SELECT * FROM challenge_test_cases WHERE id = :id', ['id' => $request->params['id']]);
        Database::execute('DELETE FROM challenge_test_cases WHERE id = :id', ['id' => $request->params['id']]);
        Session::flash('success', 'ลบ test case แล้ว');
        redirect('/admin/challenges/' . $case['challenge_id'] . '/test-cases');
    }

    private function data(Request $request): array
    {
        $mode = in_array($request->input('comparison_mode'), ['exact', 'trimmed', 'contains', 'regex'], true) ? $request->input('comparison_mode') : 'trimmed';
        return [
            'name' => trim((string) $request->input('name', 'Case')),
            'input_data' => (string) $request->input('input_data', ''),
            'expected_output' => (string) $request->input('expected_output', ''),
            'expected_pattern' => (string) $request->input('expected_pattern', ''),
            'comparison_mode' => $mode,
            'is_hidden' => $request->boolean('is_hidden'),
            'weight' => max(1, (int) $request->input('weight', 1)),
            'sort_order' => (int) $request->input('sort_order', 0),
        ];
    }
}

