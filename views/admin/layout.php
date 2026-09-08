<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Hidrocinco Admin') ?></title>
    <link rel="icon" href="<?= e(asset('img/logo-mark.svg')) ?>" type="image/svg+xml">
    <link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
</head>
<body class="<?= !empty($loginPage) ? 'admin-login-page' : 'admin-page' ?>">
<?php if (!empty($loginPage)): ?>
    <main class="login-main"><?= $content ?></main>
<?php else: ?>
    <aside class="admin-sidebar">
        <a class="admin-brand" href="<?= e(url('/admin')) ?>"><img src="<?= e(asset('img/logo.svg')) ?>" alt="Hidrocinco" width="180" height="50"><span>Admin</span></a>
        <nav aria-label="Administración">
            <a class="is-active" href="<?= e(url('/admin')) ?>">Dashboard</a>
            <span aria-disabled="true">Servicios <small>Próximamente</small></span>
            <span aria-disabled="true">Blog <small>Próximamente</small></span>
            <span aria-disabled="true">Mensajes <small>Próximamente</small></span>
        </nav>
        <form action="<?= e(url('/admin/logout')) ?>" method="post"><?= csrf_field() ?><button type="submit">Cerrar sesión</button></form>
    </aside>
    <main class="admin-main"><?= $content ?></main>
<?php endif; ?>
</body>
</html>
