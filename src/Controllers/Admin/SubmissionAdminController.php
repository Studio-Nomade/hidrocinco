<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth;
use App\Repositories\SubmissionRepository;

final class SubmissionAdminController
{
    public function __construct()
    {
        Auth::requireAuth();
    }

    public function index(): void
    {
        $type = in_array($_GET['type'] ?? '', ['contact', 'newsletter'], true) ? $_GET['type'] : null;
        admin_view('submissions/index', [
            'title' => 'Mensajes — Hidrocinco Admin',
            'submissions' => (new SubmissionRepository())->all($type), 'type' => $type,
        ]);
    }
}
