<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\ServiceRepository;

final class ServiceController
{
    private ServiceRepository $services;

    public function __construct()
    {
        $this->services = new ServiceRepository();
    }

    public function show(string $slug): void
    {
        $service = $this->services->findBySlug($slug, true);
        if (!$service) {
            not_found();
            return;
        }

        $description = '';
        foreach ($service['content'] as $block) {
            if (!empty($block['paragraphs'][0])) {
                $description = truncate_text((string) $block['paragraphs'][0], 155);
                break;
            }
        }

        view('service', [
            'title' => $service['title'] . ' — Hidrocinco',
            'description' => $description ?: (string) $service['card_summary'],
            'service' => $service,
        ]);
    }

    public function redirectLegacy(string $legacySlug): void
    {
        $service = $this->services->findBySlug($legacySlug, true);
        if (!$service) {
            not_found();
            return;
        }

        header('Location: ' . url('/servicios/' . $service['slug']), true, 301);
    }
}
