<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Hidrocinco Admin') ?></title>
    <link rel="icon" href="<?= e(asset('img/logo-mark.svg')) ?>" type="image/svg+xml">
    <link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
    <?php foreach (($styles ?? []) as $style): ?><link rel="stylesheet" href="<?= e(asset($style)) ?>"><?php endforeach; ?>
</head>
<body class="<?= !empty($loginPage) ? 'admin-login-page' : 'admin-page' ?>">
<?php if (!empty($loginPage)): ?>
    <main class="login-main"><?= $content ?></main>
<?php else: ?>
    <aside class="admin-sidebar">
        <a class="admin-brand" href="<?= e(url('/admin')) ?>"><img src="<?= e(asset('img/logo.svg')) ?>" alt="Hidrocinco" width="180" height="50"><span>Admin</span></a>
        <nav aria-label="Administración">
            <a href="<?= e(url('/admin')) ?>">Dashboard</a>
            <a href="<?= e(url('/admin/servicios')) ?>">Servicios</a>
            <a href="<?= e(url('/admin/blog')) ?>">Blog</a>
            <a href="<?= e(url('/admin/mensajes')) ?>">Mensajes</a>
        </nav>
        <form action="<?= e(url('/admin/logout')) ?>" method="post"><?= csrf_field() ?><button type="submit">Cerrar sesión</button></form>
    </aside>
    <main class="admin-main"><?= $content ?></main>
<?php endif; ?>
<?php foreach (($scripts ?? []) as $script): ?><script src="<?= e(asset($script)) ?>" defer></script><?php endforeach; ?>
</body>
</html>
