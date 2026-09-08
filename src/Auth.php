<?php

declare(strict_types=1);

namespace App;

final class Auth
{
    public static function attempt(string $email, string $password): bool
    {
        $statement = Database::connection()->prepare('SELECT id,email,name,password_hash FROM admin_users WHERE email = :email LIMIT 1');
        $statement->execute(['email' => mb_strtolower(trim($email))]);
        $user = $statement->fetch();
        if (!$user || !password_verify($password, (string) $user['password_hash'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['admin_user'] = ['id' => (int) $user['id'], 'email' => $user['email'], 'name' => $user['name']];
        unset($_SESSION['login_failures'], $_SESSION['login_lock_until']);
        return true;
    }

    public static function check(): bool
    {
        return isset($_SESSION['admin_user']['id']);
    }

    /** @return array<string, mixed>|null */
    public static function user(): ?array
    {
        return self::check() ? $_SESSION['admin_user'] : null;
    }

    public static function requireAuth(): void
    {
        self::adminHeaders();
        if (!self::check()) {
            redirect('/admin/login');
        }
    }

    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }

    public static function adminHeaders(): void
    {
        header('X-Frame-Options: DENY');
        header("Content-Security-Policy: frame-ancestors 'none'");
        header('X-Content-Type-Options: nosniff');
    }

    public static function isRateLimited(): bool
    {
        return (int) ($_SESSION['login_lock_until'] ?? 0) > time();
    }

    public static function registerFailure(): void
    {
        $windowStart = time() - 300;
        $failures = array_values(array_filter($_SESSION['login_failures'] ?? [], static fn (int $time): bool => $time >= $windowStart));
        $failures[] = time();
        $_SESSION['login_failures'] = $failures;
        if (count($failures) >= 5) {
            $_SESSION['login_lock_until'] = time() + 60;
        }
    }
}
