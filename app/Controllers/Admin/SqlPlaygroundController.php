<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;

class SqlPlaygroundController extends Controller
{
    public function index(Request $request): void
    {
        $playgrounds = Database::select('SELECT * FROM sql_playgrounds ORDER BY id DESC');
        $lessons = Database::select('SELECT id, title FROM lessons ORDER BY sort_order, id');
        $this->render('admin/sql_playgrounds/index', ['title' => 'SQL Playgrounds', 'playgrounds' => $playgrounds, 'lessons' => $lessons, 'playground' => null], 'admin');
    }

    public function edit(Request $request): void
    {
        $playgrounds = Database::select('SELECT * FROM sql_playgrounds ORDER BY id DESC');
        $lessons = Database::select('SELECT id, title FROM lessons ORDER BY sort_order, id');
        $playground = Database::first('SELECT * FROM sql_playgrounds WHERE id = :id', ['id' => $request->params['id']]);
        $this->render('admin/sql_playgrounds/index', ['title' => 'SQL Playgrounds', 'playgrounds' => $playgrounds, 'lessons' => $lessons, 'playground' => $playground], 'admin');
    }

    public function store(Request $request): void
    {
        $this->requireCsrf();
        Database::execute(
            'INSERT INTO sql_playgrounds (lesson_id, title, slug, description, schema_sql, seed_sql, expected_result_json, allowed_statement, xp_reward, is_published, created_at, updated_at)
             VALUES (:lesson_id, :title, :slug, :description, :schema_sql, :seed_sql, :expected_result_json, "select_only", :xp_reward, :is_published, NOW(), NOW())',
            $this->data($request)
        );
        Session::flash('success', 'บันทึก SQL playground แล้ว');
        redirect('/admin/sql-playgrounds');
    }

    public function update(Request $request): void
    {
        $this->requireCsrf();
        Database::execute(
            'UPDATE sql_playgrounds SET lesson_id = :lesson_id, title = :title, slug = :slug, description = :description, schema_sql = :schema_sql,
                seed_sql = :seed_sql, expected_result_json = :expected_result_json, xp_reward = :xp_reward, is_published = :is_published, updated_at = NOW()
             WHERE id = :id',
            ['id' => $request->params['id']] + $this->data($request)
        );
        Session::flash('success', 'อัปเดต SQL playground แล้ว');
        redirect('/admin/sql-playgrounds');
    }

    public function delete(Request $request): void
    {
        $this->requireCsrf();
        Database::execute('DELETE FROM sql_playgrounds WHERE id = :id', ['id' => $request->params['id']]);
        Session::flash('success', 'ลบ SQL playground แล้ว');
        redirect('/admin/sql-playgrounds');
    }

    private function data(Request $request): array
    {
        $title = trim((string) $request->input('title'));
        return [
            'lesson_id' => $request->input('lesson_id') !== '' ? (int) $request->input('lesson_id') : null,
            'title' => $title,
            'slug' => $this->slug($request->input('slug') ?: $title),
            'description' => trim((string) $request->input('description')),
            'schema_sql' => (string) $request->input('schema_sql'),
            'seed_sql' => (string) $request->input('seed_sql'),
            'expected_result_json' => trim((string) $request->input('expected_result_json')) ?: null,
            'xp_reward' => (int) $request->input('xp_reward', 50),
            'is_published' => $request->boolean('is_published'),
        ];
    }

    private function slug(mixed $value): string
    {
        $slug = strtolower(trim((string) $value));
        $slug = preg_replace('/[^a-z0-9ก-๙]+/iu', '-', $slug) ?: 'sql-playground';
        return trim($slug, '-');
    }
}

