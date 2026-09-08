<?php

declare(strict_types=1);

namespace App;

final class Router
{
    /** @var array<string, list<array{pattern:string, handler:callable|array}>> */
    private array $routes = ['GET' => [], 'POST' => []];

    public function get(string $pattern, callable|array $handler): void
    {
        $this->add('GET', $pattern, $handler);
    }

    public function post(string $pattern, callable|array $handler): void
    {
        $this->add('POST', $pattern, $handler);
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = rawurldecode(parse_url($uri, PHP_URL_PATH) ?: '/');
        $path = $path !== '/' ? rtrim($path, '/') : $path;

        foreach ($this->routes[strtoupper($method)] ?? [] as $route) {
            $regex = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $route['pattern']);
            if (!preg_match('#^' . $regex . '$#', $path, $matches)) {
                continue;
            }

            $arguments = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            $this->invoke($route['handler'], array_values($arguments));
            return;
        }

        http_response_code(404);
        view('404', ['title' => 'Página no encontrada — Hidrocinco']);
    }

    private function add(string $method, string $pattern, callable|array $handler): void
    {
        $normalized = $pattern !== '/' ? rtrim($pattern, '/') : $pattern;
        $this->routes[$method][] = ['pattern' => $normalized, 'handler' => $handler];
    }

    /** @param list<string> $arguments */
    private function invoke(callable|array $handler, array $arguments): void
    {
        if (is_array($handler) && is_string($handler[0])) {
            $handler = [new $handler[0](), $handler[1]];
        }
        call_user_func_array($handler, $arguments);
    }
}
