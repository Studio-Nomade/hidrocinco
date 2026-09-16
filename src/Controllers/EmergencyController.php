<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Csrf;
use App\Mailer;
use App\Repositories\SubmissionRepository;
use Throwable;

final class EmergencyController
{
    public function submit(): void
    {
        Csrf::verify($_POST['_csrf'] ?? null);
        $return = safe_return_path($_POST['source_page'] ?? '/', '/');

        // Honeypot: descartar bots silenciosamente.
        if (trim((string) ($_POST['website'] ?? '')) !== '') {
            flash_set('emergency_feedback', ['success' => 'Recibimos tu emergencia. Te contactaremos de inmediato.']);
            redirect($return);
        }

        $values = [
            'cliente' => mb_substr(trim((string) ($_POST['cliente'] ?? '')), 0, 200),
            'sucursal' => mb_substr(trim((string) ($_POST['sucursal'] ?? '')), 0, 200),
            'sistema' => mb_substr(trim((string) ($_POST['sistema'] ?? '')), 0, 200),
            'persona' => mb_substr(trim((string) ($_POST['persona'] ?? '')), 0, 200),
            'phone' => mb_substr(trim((string) ($_POST['phone'] ?? '')), 0, 60),
            'email' => mb_substr(trim((string) ($_POST['email'] ?? '')), 0, 200),
            'message' => mb_substr(trim((string) ($_POST['message'] ?? '')), 0, 5000),
        ];

        $errors = [];
        if (mb_strlen($values['cliente']) < 2) $errors['cliente'] = 'Indica el cliente.';
        if (mb_strlen($values['persona']) < 2) $errors['persona'] = 'Indica quién reporta.';
        if (!preg_match('/^[0-9+() .-]{6,60}$/', $values['phone'])) $errors['phone'] = 'Teléfono inválido.';
        if ($values['email'] !== '' && !filter_var($values['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Correo inválido.';
        if (mb_strlen($values['message']) < 5) $errors['message'] = 'Describe la emergencia.';
        if ($errors) {
            flash_set('emergency_feedback', ['errors' => $errors, 'old' => $values]);
            redirect($return);
        }

        $composed = "EMERGENCIA 24/7\n"
            . "Cliente: {$values['cliente']}\n"
            . "Sucursal: {$values['sucursal']}\n"
            . "Sistema: {$values['sistema']}\n"
            . "Reporta: {$values['persona']}\n"
            . "Detalle: {$values['message']}";

        (new SubmissionRepository())->create([
            'type' => 'contact',
            'name' => $values['persona'],
            'phone' => $values['phone'],
            'email' => $values['email'] !== '' ? $values['email'] : null,
            'message' => $composed,
            'source_page' => 'emergencia',
            'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);

        $body = '<h2>🚨 Emergencia 24/7</h2>'
            . '<p><strong>Cliente:</strong> ' . e($values['cliente']) . '</p>'
            . '<p><strong>Sucursal:</strong> ' . e($values['sucursal']) . '</p>'
            . '<p><strong>Sistema:</strong> ' . e($values['sistema']) . '</p>'
            . '<p><strong>Reporta:</strong> ' . e($values['persona']) . '</p>'
            . '<p><strong>Teléfono:</strong> ' . e($values['phone']) . '</p>'
            . '<p><strong>Correo:</strong> ' . e($values['email']) . '</p>'
            . '<p><strong>Emergencia:</strong><br>' . nl2br(e($values['message'])) . '</p>';
        try {
            (new Mailer())->send((string) config('mail.to'), '🚨 EMERGENCIA 24/7 — Hidrocinco', $body, $values['email'] !== '' ? $values['email'] : null);
        } catch (Throwable $exception) {
            error_log('Hidrocinco mail emergency error: ' . $exception->getMessage());
        }

        flash_set('emergency_feedback', ['success' => 'Recibimos tu emergencia. Te contactaremos de inmediato.']);
        redirect($return);
    }
}
