<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $pattern, array|callable $handler, array $middleware = []): void
    {
        $this->add('GET', $pattern, $handler, $middleware);
    }

    public function post(string $pattern, array|callable $handler, array $middleware = []): void
    {
        $this->add('POST', $pattern, $handler, $middleware);
    }

    private function add(string $method, string $pattern, array|callable $handler, array $middleware): void
    {
        $this->routes[] = compact('method', 'pattern', 'handler', 'middleware');
    }

    public function dispatch(string $method, string $uri): void
    {
        $method = $method === 'HEAD' ? 'GET' : $method;
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $path = '/' . trim($path, '/');
        $path = $path === '//' ? '/' : $path;

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $params = $this->match($route['pattern'], $path);
            if ($params === null) {
                continue;
            }

            $this->runMiddleware($route['middleware']);
            $request = new Request($method, $path, $params);
            $this->call($route['handler'], $request);
            Session::clearOld();
            return;
        }

        http_response_code(404);
        view('errors/404', ['title' => 'Page not found']);
    }

    private function match(string $pattern, string $path): ?array
    {
        $variables = [];
        $regex = preg_replace_callback('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', function (array $matches) use (&$variables): string {
            $variables[] = $matches[1];
            return '([^/]+)';
        }, '/' . trim($pattern, '/'));
        $regex = $regex === '' ? '/' : $regex;
        $regex = '#^' . $regex . '$#';

        if (!preg_match($regex, $path, $matches)) {
            return null;
        }

        array_shift($matches);
        return array_combine($variables, array_map('urldecode', $matches)) ?: [];
    }

    private function runMiddleware(array $middleware): void
    {
        foreach ($middleware as $name) {
            if ($name === 'auth' && !current_user()) {
                Session::flash('error', 'กรุณาเข้าสู่ระบบก่อนใช้งานหน้านี้');
                redirect('/login');
            }

            if ($name === 'admin') {
                if (!current_user()) {
                    Session::flash('error', 'กรุณาเข้าสู่ระบบก่อนเข้าใช้งานหลังบ้าน');
                    redirect('/login');
                }
                if (!user_is_admin()) {
                    http_response_code(403);
                    view('errors/403', ['title' => 'Access denied']);
                    exit;
                }
            }
        }
    }

    private function call(array|callable $handler, Request $request): void
    {
        if (is_callable($handler) && !is_array($handler)) {
            $handler($request);
            return;
        }

        [$class, $method] = $handler;
        $controller = new $class();
        $controller->{$method}($request);
    }
}
