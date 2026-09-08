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
        'to' => getenv('MAIL_TO') ?: 'hidrocinco@hidrocinco.cl',
        'from' => getenv('MAIL_FROM') ?: 'web@hidrocinco.cl',
    ],
    'recaptcha' => [
        'site_key' => getenv('RECAPTCHA_SITE_KEY') ?: '',
        'secret_key' => getenv('RECAPTCHA_SECRET_KEY') ?: '',
    ],
];
