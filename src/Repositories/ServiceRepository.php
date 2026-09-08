<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;

final class ServiceRepository
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::connection();
    }

    /** @return list<array<string, mixed>> */
    public function allPublishedOrdered(): array
    {
        return $this->many('SELECT * FROM services WHERE is_published = 1 ORDER BY sort_order, id');
    }

    /** @return list<array<string, mixed>> */
    public function all(): array
    {
        return $this->many('SELECT * FROM services ORDER BY sort_order, id');
    }

    /** @return array<string, mixed>|null */
    public function findBySlug(string $slug, bool $publishedOnly = false): ?array
    {
        $sql = 'SELECT * FROM services WHERE slug = :slug' . ($publishedOnly ? ' AND is_published = 1' : '') . ' LIMIT 1';
        return $this->one($sql, ['slug' => $slug]);
    }

    /** @return array<string, mixed>|null */
    public function findById(int $id): ?array
    {
        return $this->one('SELECT * FROM services WHERE id = :id LIMIT 1', ['id' => $id]);
    }

    /** @param array<string, mixed> $data */
    public function create(array $data): int
    {
        $sql = 'INSERT INTO services (slug,title,icon,hero_image,card_summary,content_json,sort_order,is_published)
                VALUES (:slug,:title,:icon,:hero_image,:card_summary,:content_json,:sort_order,:is_published)';
        $this->db->prepare($sql)->execute($this->payload($data));
        return (int) $this->db->lastInsertId();
    }

    /** @param array<string, mixed> $data */
    public function update(int $id, array $data): void
    {
        $payload = $this->payload($data);
        $payload['id'] = $id;
        $sql = 'UPDATE services SET slug=:slug,title=:title,icon=:icon,hero_image=:hero_image,
                card_summary=:card_summary,content_json=:content_json,sort_order=:sort_order,
                is_published=:is_published,updated_at=CURRENT_TIMESTAMP WHERE id=:id';
        $this->db->prepare($sql)->execute($payload);
    }

    public function delete(int $id): void
    {
        $this->db->prepare('DELETE FROM services WHERE id = :id')->execute(['id' => $id]);
    }

    /** @param list<int> $idsInOrder */
    public function updateOrder(array $idsInOrder): void
    {
        $this->db->beginTransaction();
        try {
            $statement = $this->db->prepare('UPDATE services SET sort_order = :sort_order WHERE id = :id');
            foreach ($idsInOrder as $index => $id) {
                $statement->execute(['sort_order' => $index + 1, 'id' => $id]);
            }
            $this->db->commit();
        } catch (\Throwable $exception) {
            $this->db->rollBack();
            throw $exception;
        }
    }

    /** @param array<string, mixed> $data @return array<string, mixed> */
    private function payload(array $data): array
    {
        return [
            'slug' => (string) ($data['slug'] ?? ''),
            'title' => (string) ($data['title'] ?? ''),
            'icon' => $data['icon'] ?? null,
            'hero_image' => $data['hero_image'] ?? null,
            'card_summary' => $data['card_summary'] ?? null,
            'content_json' => json_encode($data['content'] ?? $data['content_json'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_published' => !empty($data['is_published']) ? 1 : 0,
        ];
    }

    /** @param array<string, mixed> $params @return array<string, mixed>|null */
    private function one(string $sql, array $params = []): ?array
    {
        $statement = $this->db->prepare($sql);
        $statement->execute($params);
        $row = $statement->fetch();
        return $row ? $this->hydrate($row) : null;
    }

    /** @return list<array<string, mixed>> */
    private function many(string $sql): array
    {
        $rows = $this->db->query($sql)->fetchAll();
        return array_map(fn (array $row): array => $this->hydrate($row), $rows);
    }

    /** @param array<string, mixed> $row @return array<string, mixed> */
    private function hydrate(array $row): array
    {
        $row['content'] = json_decode((string) $row['content_json'], true, 512, JSON_THROW_ON_ERROR);
        $row['is_published'] = (bool) $row['is_published'];
        return $row;
    }
}
