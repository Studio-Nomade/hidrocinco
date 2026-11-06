<?php

declare(strict_types=1);

return [
    'app' => [
        'base_url' => getenv('BASE_URL') ?: 'http://localhost:8000',
        'env' => getenv('APP_ENV') ?: 'development',
    ],
    'database' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => getenv('DB_PORT') ?: '3306',
        'name' => getenv('DB_NAME') ?: 'hidrocinco',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',
        'charset' => 'utf8mb4',
        'dsn' => getenv('DB_DSN') ?: null,
    ],
    'mail' => [
        'host' => getenv('MAIL_HOST') ?: '',
        'port' => (int) (getenv('MAIL_PORT') ?: 587),
        'user' => getenv('MAIL_USER') ?: '',
        'pass' => getenv('MAIL_PASS') ?: '',
        'encryption' => getenv('MAIL_ENCRYPTION') ?: 'tls',
        // Remitente: debe ser un sender verificado en Brevo (hoy contacto@hidrocinco.cl).
        'from' => getenv('MAIL_FROM') ?: 'contacto@hidrocinco.cl',
        'from_name' => getenv('MAIL_FROM_NAME') ?: 'Hidrocinco',
        // Destinatarios: los valores por defecto están en src/MailRecipients.php
        // (grupos 'forms' y 'emergency'). Para cambiarlos sin tocar código:
        // 'recipients' => [
        //     'emergency' => ['to' => ['...'], 'cc' => ['...'], 'bcc' => ['...']],
        // ],
    ],
    'recaptcha' => [
        'site_key' => getenv('RECAPTCHA_SITE_KEY') ?: '',
        'secret_key' => getenv('RECAPTCHA_SECRET_KEY') ?: (getenv('RECAPTCHA_SECRET') ?: ''),
    ],
];
