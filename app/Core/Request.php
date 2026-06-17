<?php

declare(strict_types=1);

namespace App\Core;

class Request
{
    public function __construct(
        public readonly string $method,
        public readonly string $uri,
        public array $params = []
    ) {
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    public function only(array $keys): array
    {
        $data = [];
        foreach ($keys as $key) {
            $data[$key] = $this->input($key);
        }
        return $data;
    }

    public function boolean(string $key): int
    {
        return isset($_POST[$key]) ? 1 : 0;
    }
}
