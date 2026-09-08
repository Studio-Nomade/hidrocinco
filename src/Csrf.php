<?php

declare(strict_types=1);

namespace App;

final class Csrf
{
    public static function token(): string
    {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return (string) $_SESSION['_csrf'];
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_csrf" value="' . e(self::token()) . '">';
    }

    public static function verify(?string $token): void
    {
        if (!$token || !hash_equals(self::token(), $token)) {
            http_response_code(419);
            header('Content-Type: text/plain; charset=UTF-8');
            echo 'La sesión del formulario expiró. Recarga la página e inténtalo nuevamente.';
            exit;
        }
    }
}
