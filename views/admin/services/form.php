<?php
$values = array_merge($service ?? [], $old);
$blocks = $service['content'] ?? [];
if (!empty($old['content_json'])) {
    $decoded = json_decode((string) $old['content_json'], true);
    if (is_array($decoded)) { $blocks = $decoded; }
}
?>
<header class="admin-header"><div><p class="kicker">Servicios</p><h1><?= $service ? 'Editar servicio' : 'Nuevo servicio' ?></h1><p>Los cambios publicados se reflejan inmediatamente en el sitio.</p></div><a href="<?= e(url('/admin/servicios')) ?>">← Volver</a></header>
<?php if ($errors): ?><div class="form-error" role="alert"><strong>Revisa el formulario:</strong><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form class="admin-form" data-service-form action="<?= e($service ? url('/admin/servicios/' . $service['id']) : url('/admin/servicios')) ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <section class="form-panel"><h2>Información general</h2><div class="form-grid">
        <label>Título *<input name="title" value="<?= e($values['title'] ?? '') ?>" required data-slug-source></label>
        <label>Slug *<input name="slug" value="<?= e($values['slug'] ?? '') ?>" pattern="[a-z0-9]+(?:-[a-z0-9]+)*" required data-slug-target><small>Modificarlo puede romper enlaces existentes.</small></label>
        <label>Ícono<select name="icon"><?php foreach ($icons as $path => $label): ?><option value="<?= e($path) ?>" <?= ($values['icon'] ?? '') === $path ? 'selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?></select></label>
        <label>Estado<select name="is_published"><option value="1" <?= !isset($values['is_published']) || !empty($values['is_published']) ? 'selected' : '' ?>>Publicado</option><option value="0" <?= isset($values['is_published']) && empty($values['is_published']) ? 'selected' : '' ?>>Oculto</option></select></label>
        <label class="form-span-2">Resumen de tarjeta<textarea name="card_summary" rows="3" maxlength="300"><?= e($values['card_summary'] ?? '') ?></textarea></label>
        <label class="form-span-2">Imagen hero (JPG, PNG o WebP; máximo 3 MB)<input type="file" name="hero_image" accept="image/jpeg,image/png,image/webp"><?php if (!empty($service['hero_image'])): ?><small>Actual: <?= e($service['hero_image']) ?></small><?php endif; ?></label>
    </div></section>
    <section class="form-panel"><div class="panel-heading"><div><h2>Bloques de contenido</h2><p>Agrega y ordena los bloques de la página.</p></div><label>Tipo<select data-new-block-type><option value="intro">Introducción</option><option value="feature">Destacado</option><option value="list">Lista</option><option value="richtext">Texto enriquecido</option></select></label><button class="admin-button admin-button--secondary" type="button" data-add-block>Agregar bloque</button></div>
        <div class="blocks-editor" data-blocks-editor></div>
        <input type="hidden" name="content_json" value="<?= e(json_encode($blocks, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) ?>" data-content-json>
    </section>
    <div class="form-actions"><a href="<?= e(url('/admin/servicios')) ?>">Cancelar</a><button class="admin-button" type="submit">Guardar servicio</button></div>
</form>
