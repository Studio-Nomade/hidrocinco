<header class="admin-header"><div><p class="kicker">Contenido</p><h1>Servicios</h1><p>Administra el orden y la información publicada.</p></div><a class="admin-primary" href="<?= e(url('/admin/servicios/nuevo')) ?>">+ Nuevo servicio</a></header>
<?php if (!empty($_GET['saved'])): ?><p class="admin-notice" role="status">Servicio guardado correctamente.</p><?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?><p class="admin-notice" role="status">Servicio eliminado.</p><?php endif; ?>
<?php if (!empty($_GET['ordered'])): ?><p class="admin-notice" role="status">Nuevo orden guardado.</p><?php endif; ?>
<?php if (!empty($error)): ?><p class="form-error" role="alert"><?= e($error) ?></p><?php endif; ?>
<form action="<?= e(url('/admin/servicios/orden')) ?>" method="post">
    <?= csrf_field() ?>
    <div class="admin-table-wrap"><table class="admin-table">
        <thead><tr><th>Orden</th><th>Servicio</th><th>Slug</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php foreach ($services as $service): ?>
            <tr>
                <td><span class="drag-handle" aria-hidden="true">↕</span><input class="order-input" type="number" name="display_order[]" value="<?= e($service['sort_order']) ?>" min="1" aria-label="Orden de <?= e($service['title']) ?>"><input type="hidden" name="ids[]" value="<?= e($service['id']) ?>"></td>
                <td><strong><?= e($service['title']) ?></strong></td><td><code><?= e($service['slug']) ?></code></td>
                <td><span class="status-badge <?= $service['is_published'] ? 'is-published' : 'is-draft' ?>"><?= $service['is_published'] ? 'Publicado' : 'Oculto' ?></span></td>
                <td class="table-actions"><a href="<?= e(url('/admin/servicios/' . $service['id'] . '/editar')) ?>">Editar</a>
                    <button type="submit" form="delete-service-<?= e($service['id']) ?>">Eliminar</button></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
    <button class="admin-button" type="submit">Guardar orden</button>
</form>
<?php foreach ($services as $service): ?>
<form id="delete-service-<?= e($service['id']) ?>" action="<?= e(url('/admin/servicios/' . $service['id'] . '/eliminar')) ?>" method="post" onsubmit="return confirm('¿Eliminar este servicio? Esta acción no se puede deshacer.')"><?= csrf_field() ?></form>
<?php endforeach; ?>
