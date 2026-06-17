<?php

declare(strict_types=1);

use App\Core\Session;

function base_path(string $path = ''): string
{
    return dirname(__DIR__, 2) . ($path ? '/' . ltrim($path, '/') : '');
}

function load_env(string $path): void
{
    if (!is_file($path)) {
        return;
    }

    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");
        $_ENV[$key] = $value;
        putenv($key . '=' . $value);
    }
}

function env(string $key, mixed $default = null): mixed
{
    $value = $_ENV[$key] ?? getenv($key);
    if ($value === false || $value === null || $value === '') {
        return $default;
    }

    return match (strtolower((string) $value)) {
        'true' => true,
        'false' => false,
        'null' => null,
        default => $value,
    };
}

function config(string $key, mixed $default = null): mixed
{
    static $config = [];
    if ($config === []) {
        foreach (glob(base_path('config/*.php')) as $file) {
            $config[basename($file, '.php')] = require $file;
        }
    }

    $segments = explode('.', $key);
    $value = $config;
    foreach ($segments as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }

    return $value;
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function url(string $path = ''): string
{
    $root = rtrim(str_replace('/index.php', '', $_SERVER['SCRIPT_NAME'] ?? ''), '/');
    return ($root ?: '') . '/' . ltrim($path, '/');
}

function redirect(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

function view(string $template, array $data = [], string $layout = 'app'): void
{
    extract($data, EXTR_SKIP);

    ob_start();
    require base_path('app/Views/' . $template . '.php');
    $content = ob_get_clean();

    require base_path('app/Views/layouts/' . $layout . '.php');
}

function csrf_token(): string
{
    if (!Session::has('_csrf_token')) {
        Session::put('_csrf_token', bin2hex(random_bytes(32)));
    }

    return Session::get('_csrf_token');
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void
{
    $token = $_POST['_token'] ?? '';
    if (!is_string($token) || !hash_equals((string) Session::get('_csrf_token', ''), $token)) {
        http_response_code(419);
        view('errors/419', ['title' => 'Session expired']);
        exit;
    }
}

function current_user(): ?array
{
    return Session::get('user');
}

function user_is_admin(): bool
{
    $user = current_user();
    return ($user['role'] ?? '') === 'admin';
}

function old(string $key, mixed $default = ''): mixed
{
    return Session::getOld($key, $default);
}
