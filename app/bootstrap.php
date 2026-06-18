<?php

declare(strict_types=1);

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file = __DIR__ . '/' . $relative . '.php';
    if (is_file($file)) {
        require $file;
    }
});

require __DIR__ . '/Helpers/helpers.php';

load_env(dirname(__DIR__) . '/.env');

if (config('app.debug')) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
}

$sessionName = env('SESSION_NAME', 'pmq_session');
session_name($sessionName);
$sessionPath = session_save_path();
if ($sessionPath !== '' && (!is_dir($sessionPath) || !is_writable($sessionPath))) {
    session_save_path(sys_get_temp_dir());
}
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
]);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
