<header class="admin-header"><div><p class="kicker">Panel de control</p><h1>Hola, <?= e($user['name'] ?? 'Administrador') ?></h1><p>Este es el resumen actual del sitio.</p></div><a href="<?= e(url('/')) ?>" target="_blank" rel="noopener">Ver sitio ↗</a></header>
<section class="metric-grid" aria-label="Resumen de contenido">
    <article><span>Servicios</span><strong><?= e($counts['services']) ?></strong><small>Total configurado</small></article>
    <article><span>Notas publicadas</span><strong><?= e($counts['published']) ?></strong><small>Visibles en el blog</small></article>
    <article><span>Borradores</span><strong><?= e($counts['drafts']) ?></strong><small>Pendientes de publicar</small></article>
    <article><span>Mensajes</span><strong><?= e($counts['messages']) ?></strong><small>Contactos y newsletter</small></article>
</section>
<section class="welcome-panel"><h2>Administración de contenidos</h2><p>Los módulos de Servicios y Blog se habilitarán en los próximos hitos. La autenticación, protección CSRF y estructura del panel ya están activas.</p></section>
