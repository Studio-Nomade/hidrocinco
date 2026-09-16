<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth;
use App\Repositories\PostRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\SubmissionRepository;

final class DashboardController
{
    public function index(): void
    {
        Auth::requireAuth();
        $posts = (new PostRepository())->all();
        $counts = [
            'services' => count((new ServiceRepository())->all()),
            'published' => count(array_filter($posts, static fn (array $post): bool => $post['status'] === 'published')),
            'drafts' => count(array_filter($posts, static fn (array $post): bool => $post['status'] === 'draft')),
            'messages' => count((new SubmissionRepository())->all()),
        ];
        admin_view('dashboard', ['title' => 'Dashboard — Hidrocinco Admin', 'counts' => $counts, 'user' => Auth::user()]);
    }
}
