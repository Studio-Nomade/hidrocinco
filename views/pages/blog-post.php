<?php
$postUrl = url('/blog/' . $post['slug']);
$encodedUrl = rawurlencode($postUrl);
$encodedTitle = rawurlencode((string) $post['title']);
?>
<article class="blog-detail">
    <div class="blog-wave" aria-hidden="true"></div>
    <header class="container narrow blog-detail__header center">
        <p class="eyebrow">Guías y consejos</p>
        <h1><?= e($post['title']) ?></h1>
        <time datetime="<?= e($post['published_at']) ?>"><?= e(fecha_es($post['published_at'])) ?></time>
    </header>
    <div class="container prose post-body"><?= $post['body_html'] ?></div>
    <footer class="container narrow post-footer">
        <div class="share-row"><strong>Comparte esta nota:</strong>
            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= e($encodedUrl) ?>" target="_blank" rel="noopener" aria-label="Compartir en LinkedIn">in</a>
            <a href="mailto:?subject=<?= e($encodedTitle) ?>&amp;body=<?= e($encodedUrl) ?>" aria-label="Compartir por correo">✉</a>
            <a href="https://www.threads.net/intent/post?text=<?= e($encodedTitle . '%20' . $encodedUrl) ?>" target="_blank" rel="noopener" aria-label="Compartir en Threads">@</a>
            <a href="https://x.com/intent/post?text=<?= e($encodedTitle) ?>&amp;url=<?= e($encodedUrl) ?>" target="_blank" rel="noopener" aria-label="Compartir en X">𝕏</a>
        </div>
        <?php if ($nextPost): ?><a class="next-post" href="<?= e(url('/blog/' . $nextPost['slug'])) ?>"><span>Nota Siguiente</span><strong><?= e($nextPost['title']) ?> →</strong></a><?php endif; ?>
    </footer>
</article>

<?php if ($related): ?>
<section class="related-posts section"><div class="container"><h2>Notas relacionadas</h2><div class="posts-grid">
    <?php foreach ($related as $postCard): ?><?php require ROOT_PATH . '/views/partials/post-card.php'; ?><?php endforeach; ?>
</div></div></section>
<?php endif; ?>

<section class="newsletter-section section">
    <div class="container newsletter-shell">
        <div><p class="eyebrow">Mantente al día</p><h2>Suscríbete a nuestro newsletter</h2></div>
        <!-- TODO Hito 8: persistir suscripción y enviar confirmación. -->
        <form action="<?= e(url('/newsletter')) ?>" method="post">
            <label><span class="sr-only">Nombre</span><input type="text" name="name" placeholder="Nombre" required></label>
            <label><span class="sr-only">Email</span><input type="email" name="email" placeholder="Email" required></label>
            <button class="btn" type="submit">Suscribir</button>
        </form>
    </div>
</section>
