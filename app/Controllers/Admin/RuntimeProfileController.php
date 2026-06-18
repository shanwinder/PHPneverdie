<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;

class RuntimeProfileController extends Controller
{
    public function index(Request $request): void
    {
        $profiles = Database::select('SELECT * FROM runtime_profiles ORDER BY id');
        $this->render('admin/runtime_profiles/index', ['title' => 'Runtime Profiles', 'profiles' => $profiles, 'profile' => null], 'admin');
    }

    public function edit(Request $request): void
    {
        $profiles = Database::select('SELECT * FROM runtime_profiles ORDER BY id');
        $profile = Database::first('SELECT * FROM runtime_profiles WHERE id = :id', ['id' => $request->params['id']]);
        $this->render('admin/runtime_profiles/index', ['title' => 'Runtime Profiles', 'profiles' => $profiles, 'profile' => $profile], 'admin');
    }

    public function store(Request $request): void
    {
        $this->requireCsrf();
        Database::execute(
            'INSERT INTO runtime_profiles (name, language, version, docker_image, timeout_ms, memory_mb, cpu_quota, network_enabled, max_output_bytes, max_code_bytes, is_active, created_at, updated_at)
             VALUES (:name, "php", :version, :docker_image, :timeout_ms, :memory_mb, :cpu_quota, :network_enabled, :max_output_bytes, :max_code_bytes, :is_active, NOW(), NOW())',
            $this->data($request)
        );
        Session::flash('success', 'บันทึก runtime profile แล้ว');
        redirect('/admin/runtime-profiles');
    }

    public function update(Request $request): void
    {
        $this->requireCsrf();
        Database::execute(
            'UPDATE runtime_profiles SET name = :name, version = :version, docker_image = :docker_image, timeout_ms = :timeout_ms, memory_mb = :memory_mb,
                cpu_quota = :cpu_quota, network_enabled = :network_enabled, max_output_bytes = :max_output_bytes, max_code_bytes = :max_code_bytes, is_active = :is_active, updated_at = NOW()
             WHERE id = :id',
            ['id' => $request->params['id']] + $this->data($request)
        );
        Session::flash('success', 'อัปเดต runtime profile แล้ว');
        redirect('/admin/runtime-profiles');
    }

    private function data(Request $request): array
    {
        return [
            'name' => trim((string) $request->input('name')),
            'version' => trim((string) $request->input('version', '8.3')),
            'docker_image' => trim((string) $request->input('docker_image', 'php-mastery-sandbox-php:8.3')),
            'timeout_ms' => max(500, (int) $request->input('timeout_ms', 3000)),
            'memory_mb' => max(16, (int) $request->input('memory_mb', 64)),
            'cpu_quota' => trim((string) $request->input('cpu_quota', '0.5')),
            'network_enabled' => $request->boolean('network_enabled'),
            'max_output_bytes' => max(1000, (int) $request->input('max_output_bytes', 20000)),
            'max_code_bytes' => max(1000, (int) $request->input('max_code_bytes', 50000)),
            'is_active' => $request->boolean('is_active'),
        ];
    }
}

