<header class="admin-header"><div><p class="kicker">Contenido</p><h1>Blog</h1><p>Crea y publica notas para el sitio.</p></div><a class="admin-primary" href="<?= e(url('/admin/blog/nueva')) ?>">+ Nueva nota</a></header>
<?php if (!empty($_GET['saved'])): ?><p class="admin-notice" role="status">Nota guardada correctamente.</p><?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?><p class="admin-notice" role="status">Nota eliminada.</p><?php endif; ?>
<nav class="filter-tabs" aria-label="Filtrar notas"><a href="<?= e(url('/admin/blog')) ?>" <?= !$status ? 'aria-current="page"' : '' ?>>Todas</a><a href="<?= e(url('/admin/blog?status=published')) ?>" <?= $status === 'published' ? 'aria-current="page"' : '' ?>>Publicadas</a><a href="<?= e(url('/admin/blog?status=draft')) ?>" <?= $status === 'draft' ? 'aria-current="page"' : '' ?>>Borradores</a></nav>
<div class="admin-table-wrap"><table class="admin-table">
    <thead><tr><th>Título</th><th>Slug</th><th>Estado</th><th>Fecha</th><th>Acciones</th></tr></thead>
    <tbody><?php foreach ($posts as $post): ?><tr>
        <td><strong><?= e($post['title']) ?></strong></td><td><code><?= e($post['slug']) ?></code></td>
        <td><span class="status-badge <?= $post['status'] === 'published' ? 'is-published' : 'is-draft' ?>"><?= $post['status'] === 'published' ? 'Publicada' : 'Borrador' ?></span></td>
        <td><?= e($post['published_at'] ? fecha_es($post['published_at']) : '—') ?></td>
        <td class="table-actions"><a href="<?= e(url('/admin/blog/' . $post['id'] . '/editar')) ?>">Editar</a><button type="submit" form="delete-post-<?= e($post['id']) ?>">Eliminar</button></td>
    </tr><?php endforeach; ?></tbody>
</table></div>
<?php foreach ($posts as $post): ?><form id="delete-post-<?= e($post['id']) ?>" action="<?= e(url('/admin/blog/' . $post['id'] . '/eliminar')) ?>" method="post" onsubmit="return confirm('¿Eliminar esta nota? Esta acción no se puede deshacer.')"><?= csrf_field() ?></form><?php endforeach; ?>
