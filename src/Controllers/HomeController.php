<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\ServiceRepository;

final class HomeController
{
    public function index(): void
    {
        $services = (new ServiceRepository())->allPublishedOrdered();
        view('home', [
            'title' => 'Hidrocinco — Expertos en sistemas de agua',
            'description' => 'Somos una empresa de servicio, con más de 40 años de experiencia en soluciones hidráulicas para Chile.',
            'services' => $services,
        ]);
    }
}
