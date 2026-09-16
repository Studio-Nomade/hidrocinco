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

function media_url(?string $path, string $fallback = 'img/logo-mark.svg'): string
{
    $path = $path ?: $fallback;
    return str_starts_with($path, 'uploads/') ? url('/' . $path) : asset($path);
}

function truncate_text(string $text, int $length = 180): string
{
    $plain = trim(preg_replace('/\s+/u', ' ', strip_tags($text)) ?? '');
    if (mb_strlen($plain) <= $length) {
        return $plain;
    }
    return rtrim(mb_substr($plain, 0, $length - 1)) . '…';
}

function fecha_es(?string $date): string
{
    if (!$date) {
        return '';
    }
    $months = [1 => 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    $timestamp = strtotime($date);
    if ($timestamp === false) {
        return '';
    }
    return (int) date('j', $timestamp) . ' de ' . $months[(int) date('n', $timestamp)] . ', ' . date('Y', $timestamp);
}

function slugify(string $value): string
{
    $ascii = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', mb_strtolower(trim($value))) ?: '';
    return trim((string) preg_replace('/[^a-z0-9]+/', '-', $ascii), '-');
}

function not_found(): void
{
    http_response_code(404);
    view('404', ['title' => 'Página no encontrada — Hidrocinco']);
}

function redirect(string $path, int $status = 302): never
{
    header('Location: ' . (str_starts_with($path, 'http') ? $path : url($path)), true, $status);
    exit;
}

/** @param array<string, mixed> $data */
function admin_view(string $name, array $data = []): void
{
    extract($data, EXTR_SKIP);
    $viewFile = ROOT_PATH . '/views/admin/' . $name . '.php';
    if (!is_file($viewFile)) {
        throw new RuntimeException('Vista admin inexistente: ' . $name);
    }
    ob_start();
    require $viewFile;
    $content = (string) ob_get_clean();
    require ROOT_PATH . '/views/admin/layout.php';
}

function csrf_field(): string
{
    return \App\Csrf::field();
}

function flash_set(string $key, mixed $value): void
{
    $_SESSION['_flash'][$key] = $value;
}

function flash_get(string $key, mixed $default = null): mixed
{
    $value = $_SESSION['_flash'][$key] ?? $default;
    unset($_SESSION['_flash'][$key]);
    return $value;
}

function safe_return_path(?string $path, string $fallback = '/'): string
{
    $path = parse_url((string) $path, PHP_URL_PATH) ?: $fallback;
    return str_starts_with($path, '/') && !str_starts_with($path, '//') ? $path : $fallback;
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
