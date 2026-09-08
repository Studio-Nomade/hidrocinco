<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth;
use App\Csrf;

final class AuthController
{
    public function showLogin(): void
    {
        Auth::adminHeaders();
        if (Auth::check()) {
            redirect('/admin');
        }
        admin_view('login', ['title' => 'Acceso — Hidrocinco Admin', 'loginPage' => true]);
    }

    public function login(): void
    {
        Auth::adminHeaders();
        Csrf::verify($_POST['_csrf'] ?? null);

        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        if (!Auth::isRateLimited() && Auth::attempt($email, $password)) {
            redirect('/admin');
        }

        if (!Auth::isRateLimited()) {
            Auth::registerFailure();
        }
        admin_view('login', [
            'title' => 'Acceso — Hidrocinco Admin',
            'loginPage' => true,
            'email' => $email,
            'error' => Auth::isRateLimited() ? 'Demasiados intentos. Espera un minuto e inténtalo nuevamente.' : 'Credenciales inválidas',
        ]);
    }

    public function logout(): void
    {
        Auth::requireAuth();
        Csrf::verify($_POST['_csrf'] ?? null);
        Auth::logout();
        redirect('/admin/login');
    }
}
