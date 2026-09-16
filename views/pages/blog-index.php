<section class="blog-index section">
    <div class="container">
        <header class="page-heading center"><p class="eyebrow">Actualidad Hidrocinco</p><h1>Blog</h1></header>
        <?php if (!$posts): ?>
            <p class="empty-state center">Pronto publicaremos nuevas notas.</p>
        <?php else: ?>
            <div class="posts-grid">
                <?php foreach ($posts as $postCard): ?><?php require ROOT_PATH . '/views/partials/post-card.php'; ?><?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if (count($posts) > 12): ?><!-- TODO: agregar paginación cuando el volumen lo requiera. --><?php endif; ?>
    </div>
</section>
