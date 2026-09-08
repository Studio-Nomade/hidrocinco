<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth;
use App\Csrf;
use App\HtmlSanitizer;
use App\Repositories\PostRepository;
use App\UploadService;
use RuntimeException;

final class PostAdminController
{
    private PostRepository $posts;
    private UploadService $uploads;

    public function __construct()
    {
        Auth::requireAuth();
        $this->posts = new PostRepository();
        $this->uploads = new UploadService();
    }

    public function index(): void
    {
        $status = in_array($_GET['status'] ?? '', ['draft', 'published'], true) ? $_GET['status'] : null;
        $posts = $this->posts->all();
        if ($status) {
            $posts = array_values(array_filter($posts, static fn (array $post): bool => $post['status'] === $status));
        }
        admin_view('posts/index', ['title' => 'Blog — Hidrocinco Admin', 'posts' => $posts, 'status' => $status]);
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
        $post = $this->posts->findById((int) $id);
        if (!$post) {
            not_found();
            return;
        }
        $this->form($post);
    }

    public function update(string $id): void
    {
        Csrf::verify($_POST['_csrf'] ?? null);
        $post = $this->posts->findById((int) $id);
        if (!$post) {
            not_found();
            return;
        }
        $this->persist($post);
    }

    public function destroy(string $id): void
    {
        Csrf::verify($_POST['_csrf'] ?? null);
        if (!$this->posts->findById((int) $id)) {
            not_found();
            return;
        }
        $this->posts->delete((int) $id);
        redirect('/admin/blog?deleted=1');
    }

    /** @param array<string, mixed>|null $post */
    private function form(?array $post, array $errors = [], array $old = []): void
    {
        admin_view('posts/form', [
            'title' => ($post ? 'Editar' : 'Nueva') . ' nota — Hidrocinco Admin',
            'post' => $post, 'errors' => $errors, 'old' => $old,
            'styles' => ['vendor/quill/quill.snow.css', 'css/admin-blog.css'],
            'scripts' => ['vendor/quill/quill.min.js', 'js/admin-posts.js'],
        ]);
    }

    /** @param array<string, mixed>|null $existing */
    private function persist(?array $existing): void
    {
        $validation = $this->validated($existing);
        if ($validation['errors']) {
            $old = $_POST;
            $old['body_html'] = $validation['data']['body_html'];
            $this->form($existing, $validation['errors'], $old);
            return;
        }
        $data = $validation['data'];
        try {
            $image = $this->uploads->store($_FILES['featured_image'] ?? null, 'blog');
            if ($image) {
                $data['featured_image'] = $image;
            }
        } catch (RuntimeException $exception) {
            $old = $_POST;
            $old['body_html'] = $data['body_html'];
            $this->form($existing, ['upload' => $exception->getMessage()], $old);
            return;
        }
        $existing ? $this->posts->update((int) $existing['id'], $data) : $this->posts->create($data);
        redirect('/admin/blog?saved=1');
    }

    /** @param array<string, mixed>|null $existing @return array{errors:array<string,string>,data:array<string,mixed>} */
    private function validated(?array $existing): array
    {
        $title = trim((string) ($_POST['title'] ?? ''));
        $slug = trim((string) ($_POST['slug'] ?? '')) ?: slugify($title);
        $body = HtmlSanitizer::clean((string) ($_POST['body_html'] ?? ''));
        $status = ($_POST['status'] ?? 'draft') === 'published' ? 'published' : 'draft';
        $publishedAt = trim((string) ($_POST['published_at'] ?? ''));
        if ($status === 'published' && $publishedAt === '') {
            $publishedAt = date('Y-m-d');
        }
        $errors = [];
        if ($title === '') {
            $errors['title'] = 'El título es obligatorio.';
        }
        if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) {
            $errors['slug'] = 'Usa solo letras minúsculas, números y guiones.';
        }
        $duplicate = $this->posts->findBySlug($slug);
        if ($duplicate && (!$existing || (int) $duplicate['id'] !== (int) $existing['id'])) {
            $errors['slug'] = 'Este slug ya está en uso.';
        }
        if (trim(strip_tags($body)) === '') {
            $errors['body'] = 'El cuerpo de la nota es obligatorio.';
        }
        if ($publishedAt !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $publishedAt)) {
            $errors['published_at'] = 'La fecha no es válida.';
        }
        $excerpt = trim((string) ($_POST['excerpt'] ?? ''));
        if ($excerpt === '') {
            $excerpt = truncate_text($body, 300);
        }
        return ['errors' => $errors, 'data' => [
            'title' => mb_substr($title, 0, 255), 'slug' => $slug,
            'excerpt' => mb_substr($excerpt, 0, 500), 'body_html' => $body,
            'featured_image' => $existing['featured_image'] ?? null, 'status' => $status,
            'published_at' => $publishedAt ?: null,
        ]];
    }
}
