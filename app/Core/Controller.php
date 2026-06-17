<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function render(string $template, array $data = [], string $layout = 'app'): void
    {
        view($template, $data, $layout);
    }

    protected function db(): \PDO
    {
        return Database::connection();
    }

    protected function requireCsrf(): void
    {
        verify_csrf();
    }

    protected function flash(string $type, string $message): void
    {
        Session::flash($type, $message);
    }
}
