<?php
$canonicalUrl = $canonical ?? url(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
$pageTitle = $title ?? 'Hidrocinco — Expertos en agua';
$pageDescription = $description ?? 'Soluciones hidráulicas integrales para Chile.';
$socialImage = $ogImage ?? asset('img/og-default.jpg');
$organizationSchema = [
    '@context' => 'https://schema.org', '@type' => 'Organization', 'name' => 'Hidrocinco',
    'url' => url('/'), 'logo' => asset('img/logo.svg'), 'image' => asset('img/og-default.jpg'),
    'email' => 'hidrocinco@hidrocinco.cl', 'telephone' => '+56225561859',
    'address' => ['@type' => 'PostalAddress', 'streetAddress' => 'Av. Portugal 1797', 'addressLocality' => 'Santiago', 'addressCountry' => 'CL'],
    'sameAs' => ['https://www.instagram.com/hidrocinco/', 'https://www.linkedin.com/company/hidrocinco/'],
];
$schemas = array_merge([$organizationSchema], $structuredData ?? []);
?>
<!doctype html>
<html lang="es">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-KMF45R3D');</script>
    <!-- End Google Tag Manager -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e($pageDescription) ?>">
    <link rel="canonical" href="<?= e($canonicalUrl) ?>">
    <meta property="og:locale" content="es_CL"><meta property="og:site_name" content="Hidrocinco">
    <meta property="og:type" content="<?= e($ogType ?? 'website') ?>"><meta property="og:title" content="<?= e($pageTitle) ?>">
    <meta property="og:description" content="<?= e($pageDescription) ?>"><meta property="og:url" content="<?= e($canonicalUrl) ?>">
    <meta property="og:image" content="<?= e($socialImage) ?>"><meta property="og:image:width" content="1200"><meta property="og:image:height" content="630">
    <meta name="twitter:card" content="summary_large_image"><meta name="twitter:title" content="<?= e($pageTitle) ?>"><meta name="twitter:description" content="<?= e($pageDescription) ?>"><meta name="twitter:image" content="<?= e($socialImage) ?>">
    <link rel="icon" href="<?= e(url('/favicon.ico')) ?>" sizes="any">
    <link rel="icon" href="<?= e(asset('img/favicon-32.png')) ?>" type="image/png" sizes="32x32">
    <link rel="apple-touch-icon" href="<?= e(asset('img/apple-touch-icon.png')) ?>">
    <link rel="manifest" href="<?= e(url('/site.webmanifest')) ?>">
    <link rel="preload" href="<?= e(asset('fonts/poppins-400.woff2')) ?>" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?= e(asset('fonts/poppins-600.woff2')) ?>" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="<?= e(asset('css/main.min.css?v=20260908e')) ?>">
    <?php foreach ($schemas as $schema): ?><script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script><?php endforeach; ?>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KMF45R3D" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <a class="skip-link" href="#contenido">Saltar al contenido</a>
    <?php require ROOT_PATH . '/views/partials/topbar.php'; ?>
    <?php require ROOT_PATH . '/views/partials/header.php'; ?>
    <main id="contenido"><?= $content ?></main>
    <?php require ROOT_PATH . '/views/partials/footer.php'; ?>
    <?php require ROOT_PATH . '/views/partials/chat-button.php'; ?>
    <?php require ROOT_PATH . '/views/partials/modals.php'; ?>
    <script src="<?= e(asset('js/main.min.js?v=20260908c')) ?>" defer></script>
    <?php if ((string) config('recaptcha.site_key', '') !== ''): ?><script src="https://www.google.com/recaptcha/api.js" async defer></script><?php endif; ?>
</body>
</html>
