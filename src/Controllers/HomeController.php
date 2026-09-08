<?php

declare(strict_types=1);

namespace App\Controllers;

final class HomeController
{
    public function index(): void
    {
        view('home', [
            'title' => 'Hidrocinco — Expertos en sistemas de agua',
            'description' => 'Más de 40 años entregando soluciones hidráulicas en Chile.',
        ]);
    }
}
