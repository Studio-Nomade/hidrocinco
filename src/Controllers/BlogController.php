<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\PostRepository;

final class BlogController
{
    private PostRepository $posts;

    public function __construct()
    {
        $this->posts = new PostRepository();
    }

    public function index(): void
    {
        view('blog-index', [
            'title' => 'Blog — Hidrocinco',
            'description' => 'Consejos, guías y novedades sobre sistemas hidráulicos.',
            'posts' => $this->posts->published(),
        ]);
    }

    public function show(string $slug): void
    {
        $post = $this->posts->findBySlug($slug, true);
        if (!$post) {
            not_found();
            return;
        }

        view('blog-post', [
            'title' => $post['title'] . ' — Hidrocinco',
            'description' => (string) $post['excerpt'],
            'post' => $post,
            'related' => $this->posts->related((int) $post['id'], 3),
            'nextPost' => $this->posts->nextPublished((string) $post['published_at'], (int) $post['id']),
        ]);
    }
}
