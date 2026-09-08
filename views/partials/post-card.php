<article class="post-card">
    <a class="post-card__image" href="<?= e(url('/blog/' . $postCard['slug'])) ?>">
        <img src="<?= e(asset($postCard['featured_image'] ?: 'img/blog/sistemas-hidraulicos.svg')) ?>" alt="" width="1200" height="675">
    </a>
    <div class="post-card__body">
        <h2><a href="<?= e(url('/blog/' . $postCard['slug'])) ?>"><?= e($postCard['title']) ?></a></h2>
        <p><?= e(truncate_text((string) $postCard['excerpt'], 180)) ?></p>
        <a class="read-more" href="<?= e(url('/blog/' . $postCard['slug'])) ?>">Leer Más</a>
        <time datetime="<?= e($postCard['published_at']) ?>"><?= e(fecha_es($postCard['published_at'])) ?></time>
    </div>
</article>
