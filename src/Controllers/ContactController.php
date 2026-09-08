<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Csrf;
use App\Mailer;
use App\RecaptchaVerifier;
use App\Repositories\SubmissionRepository;
use Throwable;

final class ContactController
{
    public function show(): void
    {
        view('contact', ['title' => 'Contacto — Hidrocinco', 'description' => 'Conversemos sobre las necesidades hidráulicas de tu proyecto.', 'canonical' => url('/contacto')]);
    }

    public function submit(): void
    {
        Csrf::verify($_POST['_csrf'] ?? null);
        $return = safe_return_path($_POST['source_page'] ?? '/', '/');
        if (trim((string) ($_POST['website'] ?? '')) !== '') {
            flash_set('contact_feedback', ['success' => '¡Gracias! Nos comunicaremos contigo pronto.']);
            redirect($return . '#contacto');
        }

        $values = [
            'name' => mb_substr(trim((string) ($_POST['name'] ?? '')), 0, 200),
            'phone' => mb_substr(trim((string) ($_POST['phone'] ?? '')), 0, 60),
            'email' => mb_substr(trim((string) ($_POST['email'] ?? '')), 0, 200),
            'message' => mb_substr(trim((string) ($_POST['message'] ?? '')), 0, 5000),
        ];
        $errors = [];
        if (mb_strlen($values['name']) < 2) $errors['name'] = 'Ingresa tu nombre.';
        if (!preg_match('/^[0-9+() .-]{6,60}$/', $values['phone'])) $errors['phone'] = 'Ingresa un teléfono válido.';
        if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Ingresa un correo válido.';
        if (mb_strlen($values['message']) < 10) $errors['message'] = 'El mensaje debe tener al menos 10 caracteres.';
        if (!(new RecaptchaVerifier())->verify($_POST['g-recaptcha-response'] ?? null, $_SERVER['REMOTE_ADDR'] ?? null)) {
            $errors['recaptcha'] = 'Verifica que no eres un robot.';
        }
        if ($errors) {
            flash_set('contact_feedback', ['errors' => $errors, 'old' => $values]);
            redirect($return . '#contacto');
        }

        (new SubmissionRepository())->create($values + [
            'type' => 'contact', 'source_page' => $return, 'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);
        $body = '<h2>Nuevo mensaje de contacto</h2><p><strong>Nombre:</strong> ' . e($values['name']) . '</p><p><strong>Teléfono:</strong> ' . e($values['phone']) . '</p><p><strong>Correo:</strong> ' . e($values['email']) . '</p><p><strong>Mensaje:</strong><br>' . nl2br(e($values['message'])) . '</p><p><strong>Origen:</strong> ' . e($return) . '</p>';
        try {
            (new Mailer())->send((string) config('mail.to'), 'Nuevo mensaje de contacto — Hidrocinco', $body, $values['email']);
        } catch (Throwable $exception) {
            error_log('Hidrocinco mail contact error: ' . $exception->getMessage());
        }
        flash_set('contact_feedback', ['success' => '¡Gracias! Nos comunicaremos contigo pronto.']);
        redirect($return . '#contacto');
    }
}
