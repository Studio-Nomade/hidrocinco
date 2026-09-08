<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Hidrocinco — Expertos en agua') ?></title>
    <meta name="description" content="<?= e($description ?? 'Soluciones hidráulicas integrales para Chile.') ?>">
    <link rel="icon" href="<?= e(asset('img/logo-mark.svg')) ?>" type="image/svg+xml">
    <link rel="stylesheet" href="<?= e(asset('css/main.css')) ?>">
</head>
<body>
    <a class="skip-link" href="#contenido">Saltar al contenido</a>
    <?php require ROOT_PATH . '/views/partials/topbar.php'; ?>
    <?php require ROOT_PATH . '/views/partials/header.php'; ?>
    <main id="contenido"><?= $content ?></main>
    <?php require ROOT_PATH . '/views/partials/footer.php'; ?>
    <?php require ROOT_PATH . '/views/partials/chat-button.php'; ?>
    <script src="<?= e(asset('js/main.js')) ?>" defer></script>
</body>
</html>
