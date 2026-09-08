<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth;
use App\Csrf;
use App\HtmlSanitizer;
use App\Repositories\ServiceRepository;
use App\UploadService;
use RuntimeException;

final class ServiceAdminController
{
    private ServiceRepository $services;
    private UploadService $uploads;

    public function __construct()
    {
        Auth::requireAuth();
        $this->services = new ServiceRepository();
        $this->uploads = new UploadService();
    }

    public function index(): void
    {
        admin_view('services/index', ['title' => 'Servicios — Hidrocinco Admin', 'services' => $this->services->all()]);
    }

    public function create(): void
    {
        $this->form(null);
    }

    public function store(): void
    {
        Csrf::verify($_POST['_csrf'] ?? null);
        $this->persist(null);
    }

    public function edit(string $id): void
    {
        $service = $this->services->findById((int) $id);
        if (!$service) {
            not_found();
            return;
        }
        $this->form($service);
    }

    public function update(string $id): void
    {
        Csrf::verify($_POST['_csrf'] ?? null);
        $service = $this->services->findById((int) $id);
        if (!$service) {
            not_found();
            return;
        }
        $this->persist($service);
    }

    public function destroy(string $id): void
    {
        Csrf::verify($_POST['_csrf'] ?? null);
        if (!$this->services->findById((int) $id)) {
            not_found();
            return;
        }
        $this->services->delete((int) $id);
        redirect('/admin/servicios?deleted=1');
    }

    public function reorder(): void
    {
        Csrf::verify($_POST['_csrf'] ?? null);
        $ids = array_values(array_filter(array_map('intval', (array) ($_POST['ids'] ?? [])), static fn (int $id): bool => $id > 0));
        $orders = array_map('intval', (array) ($_POST['display_order'] ?? []));
        $known = array_map(static fn (array $service): int => (int) $service['id'], $this->services->all());
        if (count($ids) !== count($known) || count($orders) !== count($ids) || array_diff($ids, $known) || array_diff($known, $ids)) {
            http_response_code(422);
            admin_view('services/index', ['title' => 'Servicios — Hidrocinco Admin', 'services' => $this->services->all(), 'error' => 'El orden recibido no contiene todos los servicios.']);
            return;
        }
        array_multisort($orders, SORT_ASC, SORT_NUMERIC, $ids, SORT_ASC, SORT_NUMERIC);
        $this->services->updateOrder($ids);
        redirect('/admin/servicios?ordered=1');
    }

    /** @param array<string, mixed>|null $service */
    private function form(?array $service, array $errors = [], array $old = []): void
    {
        admin_view('services/form', [
            'title' => ($service ? 'Editar' : 'Nuevo') . ' servicio — Hidrocinco Admin',
            'service' => $service, 'errors' => $errors, 'old' => $old,
            'scripts' => ['js/admin-services.js'],
            'icons' => [
                'img/icons/pozos.png' => 'Pozos profundos', 'img/icons/estanque.png' => 'Estanque',
                'img/icons/ptas.png' => 'Planta de tratamiento', 'img/icons/caldera.png' => 'Caldera',
                'img/icons/taller.png' => 'Servicio técnico', 'img/icons/limpia-fosas.png' => 'Limpia fosas',
                'img/icons/bombas.png' => 'Sala de bombas',
            ],
        ]);
    }

    /** @param array<string, mixed>|null $existing */
    private function persist(?array $existing): void
    {
        $data = $this->validated($existing);
        if (isset($data['errors'])) {
            $this->form($existing, $data['errors'], $_POST);
            return;
        }
        try {
            $hero = $this->uploads->store($_FILES['hero_image'] ?? null, 'servicios');
            if ($hero) {
                $data['hero_image'] = $hero;
            }
            foreach ($data['content'] as $index => &$block) {
                if (($block['type'] ?? '') !== 'intro') {
                    continue;
                }
                $image = $this->uploads->store($_FILES['block_image_' . $index] ?? null, 'servicios');
                if ($image) {
                    $block['image'] = $image;
                }
            }
            unset($block);
        } catch (RuntimeException $exception) {
            $this->form($existing, ['upload' => $exception->getMessage()], $_POST);
            return;
        }
        $existing ? $this->services->update((int) $existing['id'], $data) : $this->services->create($data);
        redirect('/admin/servicios?saved=1');
    }

    /** @param array<string, mixed>|null $existing @return array<string, mixed> */
    private function validated(?array $existing): array
    {
        $title = trim((string) ($_POST['title'] ?? ''));
        $slug = trim((string) ($_POST['slug'] ?? '')) ?: slugify($title);
        $errors = [];
        if ($title === '') {
            $errors['title'] = 'El título es obligatorio.';
        }
        if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) {
            $errors['slug'] = 'Usa solo letras minúsculas, números y guiones.';
        }
        $duplicate = $this->services->findBySlug($slug);
        if ($duplicate && (!$existing || (int) $duplicate['id'] !== (int) $existing['id'])) {
            $errors['slug'] = 'Este slug ya está en uso.';
        }
        try {
            $blocks = json_decode((string) ($_POST['content_json'] ?? '[]'), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            $blocks = [];
        }
        if (!is_array($blocks) || count($blocks) < 1) {
            $errors['content'] = 'Agrega al menos un bloque de contenido.';
            $blocks = [];
        }
        $allowed = ['intro', 'feature', 'list', 'richtext'];
        foreach ($blocks as $index => &$block) {
            if (!is_array($block) || !in_array($block['type'] ?? '', $allowed, true)) {
                $errors['content'] = 'El bloque ' . ($index + 1) . ' no es válido.';
                continue;
            }
            $block['title'] = trim((string) ($block['title'] ?? ''));
            if (isset($block['paragraphs'])) {
                $block['paragraphs'] = array_values(array_filter(array_map(static fn ($value): string => trim((string) $value), (array) $block['paragraphs'])));
            }
            if (isset($block['items'])) {
                $block['items'] = array_values(array_filter(array_map(static fn ($value): string => trim((string) $value), (array) $block['items'])));
            }
            if (($block['type'] ?? '') === 'richtext') {
                $block['html'] = HtmlSanitizer::clean((string) ($block['html'] ?? ''));
            }
        }
        unset($block);
        if ($errors) {
            return ['errors' => $errors];
        }
        return [
            'title' => mb_substr($title, 0, 200), 'slug' => $slug,
            'icon' => (string) ($_POST['icon'] ?? 'img/logo-mark.svg'),
            'hero_image' => $existing['hero_image'] ?? null,
            'card_summary' => mb_substr(trim((string) ($_POST['card_summary'] ?? '')), 0, 300),
            'content' => $blocks, 'sort_order' => $existing['sort_order'] ?? count($this->services->all()) + 1,
            'is_published' => ($_POST['is_published'] ?? '0') === '1',
        ];
    }
}
