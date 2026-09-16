<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database;
use PDO;

final class SubmissionRepository
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::connection();
    }

    /** @param array<string, mixed> $data */
    public function create(array $data): int
    {
        $sql = 'INSERT INTO submissions (type,name,phone,email,message,source_page,ip)
                VALUES (:type,:name,:phone,:email,:message,:source_page,:ip)';
        $this->db->prepare($sql)->execute([
            'type' => $data['type'] ?? 'contact', 'name' => $data['name'] ?? null,
            'phone' => $data['phone'] ?? null, 'email' => $data['email'] ?? null,
            'message' => $data['message'] ?? null, 'source_page' => $data['source_page'] ?? null,
            'ip' => $data['ip'] ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    /** @return list<array<string, mixed>> */
    public function all(?string $type = null): array
    {
        if ($type === null) {
            return $this->db->query('SELECT * FROM submissions ORDER BY created_at DESC, id DESC')->fetchAll();
        }
        $statement = $this->db->prepare('SELECT * FROM submissions WHERE type = :type ORDER BY created_at DESC, id DESC');
        $statement->execute(['type' => $type]);
        return $statement->fetchAll();
    }
}
