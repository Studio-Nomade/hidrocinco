<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;

final class PostRepository
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::connection();
    }

    /** @return list<array<string, mixed>> */
    public function published(): array
    {
        return $this->queryMany("SELECT * FROM posts WHERE status = 'published' AND published_at IS NOT NULL ORDER BY published_at DESC, id DESC");
    }

    /** @return list<array<string, mixed>> */
    public function all(): array
    {
        return $this->queryMany('SELECT * FROM posts ORDER BY created_at DESC, id DESC');
    }

    /** @return array<string, mixed>|null */
    public function findBySlug(string $slug, bool $publishedOnly = false): ?array
    {
        $sql = 'SELECT * FROM posts WHERE slug = :slug' . ($publishedOnly ? " AND status = 'published'" : '') . ' LIMIT 1';
        return $this->queryOne($sql, ['slug' => $slug]);
    }

    /** @return array<string, mixed>|null */
    public function findById(int $id): ?array
    {
        return $this->queryOne('SELECT * FROM posts WHERE id = :id LIMIT 1', ['id' => $id]);
    }

    /** @return list<array<string, mixed>> */
    public function related(int $excludeId, int $limit = 3): array
    {
        $statement = $this->db->prepare("SELECT * FROM posts WHERE status = 'published' AND id <> :id ORDER BY published_at DESC, id DESC LIMIT :limit");
        $statement->bindValue(':id', $excludeId, PDO::PARAM_INT);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }

    /** @return array<string, mixed>|null */
    public function nextPublished(string $publishedAt, int $id): ?array
    {
        return $this->queryOne("SELECT * FROM posts WHERE status='published' AND (published_at > :date OR (published_at = :date AND id > :id)) ORDER BY published_at, id LIMIT 1", ['date' => $publishedAt, 'id' => $id]);
    }

    /** @param array<string, mixed> $data */
    public function create(array $data): int
    {
        $sql = 'INSERT INTO posts (slug,title,excerpt,body_html,featured_image,status,published_at)
                VALUES (:slug,:title,:excerpt,:body_html,:featured_image,:status,:published_at)';
        $this->db->prepare($sql)->execute($this->payload($data));
        return (int) $this->db->lastInsertId();
    }

    /** @param array<string, mixed> $data */
    public function update(int $id, array $data): void
    {
        $payload = $this->payload($data);
        $payload['id'] = $id;
        $sql = 'UPDATE posts SET slug=:slug,title=:title,excerpt=:excerpt,body_html=:body_html,
                featured_image=:featured_image,status=:status,published_at=:published_at,
                updated_at=CURRENT_TIMESTAMP WHERE id=:id';
        $this->db->prepare($sql)->execute($payload);
    }

    public function delete(int $id): void
    {
        $this->db->prepare('DELETE FROM posts WHERE id = :id')->execute(['id' => $id]);
    }

    /** @param array<string, mixed> $data @return array<string, mixed> */
    private function payload(array $data): array
    {
        return [
            'slug' => (string) ($data['slug'] ?? ''), 'title' => (string) ($data['title'] ?? ''),
            'excerpt' => $data['excerpt'] ?? null, 'body_html' => (string) ($data['body_html'] ?? ''),
            'featured_image' => $data['featured_image'] ?? null, 'status' => $data['status'] ?? 'draft',
            'published_at' => ($data['published_at'] ?? '') ?: null,
        ];
    }

    /** @param array<string, mixed> $params @return array<string, mixed>|null */
    private function queryOne(string $sql, array $params = []): ?array
    {
        $statement = $this->db->prepare($sql);
        $statement->execute($params);
        $row = $statement->fetch();
        return $row ?: null;
    }

    /** @return list<array<string, mixed>> */
    private function queryMany(string $sql): array
    {
        return $this->db->query($sql)->fetchAll();
    }
}
