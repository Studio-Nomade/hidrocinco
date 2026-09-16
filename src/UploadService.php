<?php

declare(strict_types=1);

namespace App;

use RuntimeException;

final class UploadService
{
    private const MAX_BYTES = 3 * 1024 * 1024;
    private const EXTENSIONS = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

    /** @param array<string, mixed>|null $file */
    public function store(?array $file, string $folder): ?string
    {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            throw new RuntimeException('No fue posible recibir el archivo.');
        }
        if ((int) ($file['size'] ?? 0) > self::MAX_BYTES) {
            throw new RuntimeException('La imagen supera el máximo permitido de 3 MB.');
        }
        $temporary = (string) ($file['tmp_name'] ?? '');
        if (!is_uploaded_file($temporary)) {
            throw new RuntimeException('El archivo recibido no es una subida válida.');
        }
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($temporary);
        if (!isset(self::EXTENSIONS[$mime])) {
            throw new RuntimeException('Formato no permitido. Usa JPG, PNG o WebP.');
        }

        $safeFolder = trim((string) preg_replace('/[^a-z0-9-]/', '', strtolower($folder)), '-');
        $directory = ROOT_PATH . '/public/uploads/' . $safeFolder;
        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new RuntimeException('No fue posible preparar el directorio de subida.');
        }
        $filename = bin2hex(random_bytes(16)) . '.' . self::EXTENSIONS[$mime];
        if (!move_uploaded_file($temporary, $directory . '/' . $filename)) {
            throw new RuntimeException('No fue posible guardar la imagen.');
        }
        return 'uploads/' . $safeFolder . '/' . $filename;
    }
}
