<?php

declare(strict_types=1);

function config(string $key, mixed $default = null): mixed
{
    $value = $GLOBALS['config'] ?? [];
    foreach (explode('.', $key) as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }
    return $value;
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function url(string $path = ''): string
{
    $base = rtrim((string) config('app.base_url', ''), '/');
    return $base . ($path === '' ? '' : '/' . ltrim($path, '/'));
}

function asset(string $path): string
{
    return url('/assets/' . ltrim($path, '/'));
}

/** @param array<string, mixed> $data */
function view(string $name, array $data = []): void
{
    extract($data, EXTR_SKIP);
    $viewFile = ROOT_PATH . '/views/pages/' . $name . '.php';
    if (!is_file($viewFile)) {
        throw new RuntimeException('Vista inexistente: ' . $name);
    }
    ob_start();
    require $viewFile;
    $content = (string) ob_get_clean();
    require ROOT_PATH . '/views/layouts/base.php';
}
