<?php

declare(strict_types=1);

use App\Database;

require dirname(__DIR__) . '/src/bootstrap.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit(1);
}

$email = trim((string) getenv('ADMIN_EMAIL'));
$password = (string) getenv('ADMIN_PASSWORD');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    fwrite(STDERR, "Define ADMIN_EMAIL con un correo válido.\n");
    exit(1);
}
if (strlen($password) < 14) {
    fwrite(STDERR, "ADMIN_PASSWORD debe tener al menos 14 caracteres.\n");
    exit(1);
}

$statement = Database::connection()->prepare('UPDATE admin_users SET password_hash = :password_hash WHERE email = :email');
$statement->execute([
    'email' => $email,
    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
]);

if ($statement->rowCount() !== 1) {
    fwrite(STDERR, "No existe un administrador con ese correo.\n");
    exit(1);
}

echo "Contraseña de administrador actualizada.\n";
