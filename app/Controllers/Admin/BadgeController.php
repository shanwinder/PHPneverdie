<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;

class BadgeController extends Controller
{
    public function index(Request $request): void
    {
        $badges = Database::select(
            'SELECT badges.*, COUNT(user_badges.id) AS awarded_count
             FROM badges
             LEFT JOIN user_badges ON user_badges.badge_id = badges.id
             GROUP BY badges.id
             ORDER BY badges.sort_order, badges.id'
        );
        $this->render('admin/badges/index', ['title' => 'Manage Badges', 'badges' => $badges, 'edit' => null], 'admin');
    }

    public function edit(Request $request): void
    {
        $edit = Database::first('SELECT * FROM badges WHERE id = :id', ['id' => $request->params['id']]);
        $badges = Database::select('SELECT badges.*, 0 AS awarded_count FROM badges ORDER BY sort_order, id');
        $this->render('admin/badges/index', ['title' => 'Edit Badge', 'badges' => $badges, 'edit' => $edit], 'admin');
    }

    public function store(Request $request): void
    {
        $this->requireCsrf();
        Database::execute(
            'INSERT INTO badges (code, name, description, icon, rule_type, rule_value, sort_order, is_active, created_at, updated_at)
             VALUES (:code, :name, :description, :icon, :rule_type, :rule_value, :sort_order, :is_active, NOW(), NOW())',
            $this->data($request)
        );
        $this->saved('/admin/badges');
    }

    public function update(Request $request): void
    {
        $this->requireCsrf();
        Database::execute(
            'UPDATE badges SET code = :code, name = :name, description = :description, icon = :icon, rule_type = :rule_type,
                rule_value = :rule_value, sort_order = :sort_order, is_active = :is_active, updated_at = NOW() WHERE id = :id',
            ['id' => $request->params['id']] + $this->data($request)
        );
        $this->saved('/admin/badges');
    }

    public function delete(Request $request): void
    {
        $this->requireCsrf();
        Database::execute('DELETE FROM badges WHERE id = :id', ['id' => $request->params['id']]);
        $this->saved('/admin/badges');
    }

    private function data(Request $request): array
    {
        $ruleType = in_array($request->input('rule_type'), ['challenge_count', 'lesson_count', 'module_complete', 'xp_total', 'manual'], true)
            ? $request->input('rule_type')
            : 'manual';
        return [
            'code' => trim((string) $request->input('code')),
            'name' => trim((string) $request->input('name')),
            'description' => trim((string) $request->input('description')),
            'icon' => trim((string) $request->input('icon', '🏅')),
            'rule_type' => $ruleType,
            'rule_value' => (int) $request->input('rule_value', 0),
            'sort_order' => (int) $request->input('sort_order', 0),
            'is_active' => $request->boolean('is_active'),
        ];
    }

    private function saved(string $path): never
    {
        Session::flash('success', 'บันทึกข้อมูลเรียบร้อย');
        redirect($path);
    }
}
