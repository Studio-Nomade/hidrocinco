<?php

declare(strict_types=1);

use App\Database;

require dirname(__DIR__) . '/src/bootstrap.php';

$db = Database::connection();
$driver = $db->getAttribute(PDO::ATTR_DRIVER_NAME);
$db->exec($driver === 'mysql'
    ? 'CREATE TABLE IF NOT EXISTS migrations (migration VARCHAR(255) PRIMARY KEY, applied_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    : 'CREATE TABLE IF NOT EXISTS migrations (migration TEXT PRIMARY KEY, applied_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP)');

$directory = __DIR__ . ($driver === 'sqlite' ? '/sqlite' : '/migrations');
$files = glob($directory . '/*.sql') ?: [];
sort($files, SORT_STRING);
$check = $db->prepare('SELECT 1 FROM migrations WHERE migration = :migration');
$record = $db->prepare('INSERT INTO migrations (migration) VALUES (:migration)');

foreach ($files as $file) {
    $name = basename($file);
    $check->execute(['migration' => $name]);
    if ($check->fetchColumn()) {
        echo "Omitida {$name} (ya aplicada)\n";
        continue;
    }
    $db->beginTransaction();
    try {
        $db->exec((string) file_get_contents($file));
        $record->execute(['migration' => $name]);
        $db->commit();
        echo "Aplicada {$name}\n";
    } catch (Throwable $exception) {
        if ($db->inTransaction()) {
            $db->rollBack();
        }
        throw $exception;
    }
}
