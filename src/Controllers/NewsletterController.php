<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Csrf;
use App\Mailer;
use App\RecaptchaVerifier;
use App\Repositories\SubmissionRepository;
use Throwable;

final class NewsletterController
{
    public function submit(): void
    {
        Csrf::verify($_POST['_csrf'] ?? null);
        $return = safe_return_path($_POST['source_page'] ?? '/blog', '/blog');
        if (trim((string) ($_POST['website'] ?? '')) !== '') {
            flash_set('newsletter_feedback', ['success' => '¡Suscripción exitosa!']);
            redirect($return . '#newsletter');
        }
        $values = [
            'name' => mb_substr(trim((string) ($_POST['name'] ?? '')), 0, 200),
            'email' => mb_substr(trim((string) ($_POST['email'] ?? '')), 0, 200),
        ];
        $errors = [];
        if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Ingresa un correo válido.';
        if (!(new RecaptchaVerifier())->verify($_POST['g-recaptcha-response'] ?? null, $_SERVER['REMOTE_ADDR'] ?? null)) {
            $errors['recaptcha'] = 'Verifica que no eres un robot.';
        }
        if ($errors) {
            flash_set('newsletter_feedback', ['errors' => $errors, 'old' => $values]);
            redirect($return . '#newsletter');
        }
        (new SubmissionRepository())->create($values + [
            'type' => 'newsletter', 'source_page' => $return, 'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);
        try {
            $body = '<h2>Nueva suscripción al newsletter</h2><p><strong>Nombre:</strong> ' . e($values['name'] ?: 'No indicado') . '</p><p><strong>Correo:</strong> ' . e($values['email']) . '</p><p><strong>Origen:</strong> ' . e($return) . '</p>';
            (new Mailer())->send((string) config('mail.to'), 'Nueva suscripción — Hidrocinco', $body, $values['email']);
        } catch (Throwable $exception) {
            error_log('Hidrocinco mail newsletter error: ' . $exception->getMessage());
        }
        flash_set('newsletter_feedback', ['success' => '¡Suscripción exitosa!']);
        redirect($return . '#newsletter');
    }
}
