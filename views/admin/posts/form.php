<?php $values = array_merge($post ?? [], $old); ?>
<header class="admin-header"><div><p class="kicker">Blog</p><h1><?= $post ? 'Editar nota' : 'Nueva nota' ?></h1><p>Redacta, revisa y publica contenido.</p></div><a href="<?= e(url('/admin/blog')) ?>">← Volver</a></header>
<?php if ($errors): ?><div class="form-error" role="alert"><strong>Revisa el formulario:</strong><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form class="admin-form" data-post-form action="<?= e($post ? url('/admin/blog/' . $post['id']) : url('/admin/blog')) ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <section class="form-panel"><h2>Información de la nota</h2><div class="form-grid">
        <label>Título *<input name="title" value="<?= e($values['title'] ?? '') ?>" required data-slug-source></label>
        <label>Slug *<input name="slug" value="<?= e($values['slug'] ?? '') ?>" pattern="[a-z0-9]+(?:-[a-z0-9]+)*" required data-slug-target><small>Modificarlo puede romper enlaces existentes.</small></label>
        <label>Estado<select name="status"><option value="draft" <?= ($values['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Borrador</option><option value="published" <?= ($values['status'] ?? '') === 'published' ? 'selected' : '' ?>>Publicada</option></select></label>
        <label>Fecha de publicación<input type="date" name="published_at" value="<?= e($values['published_at'] ?? '') ?>"><small>Si publicas sin fecha, se usará la fecha actual.</small></label>
        <label class="form-span-2">Extracto<textarea name="excerpt" rows="4" maxlength="500"><?= e($values['excerpt'] ?? '') ?></textarea><small>Si queda vacío, se genera desde el cuerpo.</small></label>
        <label class="form-span-2">Imagen destacada (JPG, PNG o WebP; máximo 3 MB)<input type="file" name="featured_image" accept="image/jpeg,image/png,image/webp"><?php if (!empty($post['featured_image'])): ?><small>Actual: <?= e($post['featured_image']) ?></small><?php endif; ?></label>
    </div></section>
    <section class="form-panel"><h2>Cuerpo</h2><p>Selecciona texto para aplicar enlaces o usa la barra de formato.</p><div data-quill-editor><?= $values['body_html'] ?? '' ?></div><textarea class="sr-field" name="body_html" data-body-html aria-label="HTML del cuerpo de la nota"><?= e($values['body_html'] ?? '') ?></textarea></section>
    <div class="form-actions"><a href="<?= e(url('/admin/blog')) ?>">Cancelar</a><button class="admin-button" type="submit">Guardar nota</button></div>
</form>
