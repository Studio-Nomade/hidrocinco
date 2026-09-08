<?php

declare(strict_types=1);

namespace App\Controllers;

final class AppsCincoController
{
    public function index(): void
    {
        view('appscinco', [
            'title' => 'AppsCinco — Plataforma de Hidrocinco',
            'description' => 'Supervisamos, analizamos y predecimos el comportamiento de tus sistemas hidráulicos en tiempo real con AppsCinco.',
            'canonical' => url('/appscinco'),
            'structuredData' => [[
                '@context' => 'https://schema.org',
                '@type' => 'WebPage',
                'name' => 'AppsCinco — Plataforma de Hidrocinco',
                'url' => url('/appscinco'),
                'description' => 'Plataforma de telemetría y software de mantenimiento de Hidrocinco.',
            ]],
        ]);
    }
}
