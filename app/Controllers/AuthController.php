<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;
use App\Services\XpService;

class AuthController extends Controller
{
    public function showLogin(Request $request): void
    {
        $this->render('auth/login', ['title' => 'เข้าสู่ระบบ']);
    }

    public function login(Request $request): void
    {
        $this->requireCsrf();

        $email = trim((string) $request->input('email'));
        $password = (string) $request->input('password');

        $user = Database::first(
            'SELECT users.*, roles.name AS role
             FROM users JOIN roles ON roles.id = users.role_id
             WHERE users.email = :email AND users.status = "active"',
            ['email' => $email]
        );

        if (!$user || !password_verify($password, $user['password'])) {
            Session::withOld(['email' => $email]);
            $this->flash('error', 'อีเมลหรือรหัสผ่านไม่ถูกต้อง');
            redirect('/login');
        }

        session_regenerate_id(true);
        Database::execute('UPDATE users SET last_login_at = NOW(), updated_at = NOW() WHERE id = :id', ['id' => $user['id']]);
        Session::put('user', [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'xp' => (int) $user['xp'],
            'level' => XpService::levelFor((int) $user['xp']),
        ]);

        redirect($user['role'] === 'admin' ? '/admin' : '/dashboard');
    }

    public function showRegister(Request $request): void
    {
        $this->render('auth/register', ['title' => 'สมัครสมาชิก']);
    }

    public function register(Request $request): void
    {
        $this->requireCsrf();

        $name = trim((string) $request->input('name'));
        $email = strtolower(trim((string) $request->input('email')));
        $password = (string) $request->input('password');
        $confirmation = (string) $request->input('password_confirmation');
        $errors = [];

        if (mb_strlen($name) < 2) {
            $errors[] = 'กรุณากรอกชื่ออย่างน้อย 2 ตัวอักษร';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'รูปแบบอีเมลไม่ถูกต้อง';
        }
        if (mb_strlen($password) < 8) {
            $errors[] = 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร';
        }
        if ($password !== $confirmation) {
            $errors[] = 'ยืนยันรหัสผ่านไม่ตรงกัน';
        }
        if (Database::first('SELECT id FROM users WHERE email = :email', ['email' => $email])) {
            $errors[] = 'อีเมลนี้ถูกใช้งานแล้ว';
        }

        if ($errors) {
            Session::withOld(['name' => $name, 'email' => $email]);
            $this->flash('error', implode('<br>', array_map('e', $errors)));
            redirect('/register');
        }

        $role = Database::first('SELECT id FROM roles WHERE name = "student"');
        $userId = Database::insert(
            'INSERT INTO users (role_id, name, email, password, xp, level, status, created_at, updated_at)
             VALUES (:role_id, :name, :email, :password, 0, 1, "active", NOW(), NOW())',
            [
                'role_id' => $role['id'],
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ]
        );

        session_regenerate_id(true);
        Session::put('user', [
            'id' => $userId,
            'name' => $name,
            'email' => $email,
            'role' => 'student',
            'xp' => 0,
            'level' => 1,
        ]);
        $this->flash('success', 'สมัครสมาชิกสำเร็จ เริ่มเรียน PHP กันได้เลย');
        redirect('/dashboard');
    }

    public function logout(Request $request): void
    {
        $this->requireCsrf();
        Session::destroy();
        redirect('/');
    }
}
