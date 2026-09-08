<?php $hero = $service['hero_image'] ? asset($service['hero_image']) : asset('img/hero-agua.jpg'); ?>
<section class="service-hero" style="--service-hero:url('<?= e($hero) ?>')">
    <div class="container center">
        <img src="<?= e(asset($service['icon'] ?: 'img/logo-mark.svg')) ?>" alt="" width="72" height="72">
        <h1><?= e($service['title']) ?></h1>
    </div>
</section>

<div class="service-content">
    <?php foreach ($service['content'] as $index => $block): ?>
        <?php $type = (string) ($block['type'] ?? ''); ?>
        <?php if ($type === 'intro'): ?>
            <section class="service-block service-block--intro section">
                <div class="container service-split">
                    <div>
                        <h2><?= e($block['title'] ?? '') ?></h2>
                        <?php foreach (($block['paragraphs'] ?? []) as $paragraphIndex => $paragraph): ?>
                            <p class="<?= !empty($block['highlight_first']) && $paragraphIndex === 0 ? 'lead' : '' ?>"><?= e($paragraph) ?></p>
                        <?php endforeach; ?>
                    </div>
                    <img src="<?= e(isset($block['image']) ? asset($block['image']) : asset('img/somos-hidrocinco.webp')) ?>" alt="" width="800" height="500">
                </div>
            </section>
        <?php elseif ($type === 'feature'): ?>
            <section class="service-block service-block--feature section">
                <div class="container service-split <?= empty($block['decor']) ? 'service-split--compact' : '' ?>">
                    <?php if (!empty($block['decor'])): ?><img class="decor-drop" src="<?= e(asset('img/decor-gota.svg')) ?>" alt="" width="220" height="260"><?php endif; ?>
                    <div>
                        <h2><?= e($block['title'] ?? '') ?></h2>
                        <?php foreach (($block['paragraphs'] ?? []) as $paragraph): ?><p><?= e($paragraph) ?></p><?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php elseif ($type === 'list'): ?>
            <section class="service-block service-block--list section">
                <div class="container narrow">
                    <h2><?= e($block['title'] ?? '') ?></h2>
                    <?php if (!empty($block['intro'])): ?><p><?= e($block['intro']) ?></p><?php endif; ?>
                    <ul><?php foreach (($block['items'] ?? []) as $item): ?><li><?= e($item) ?></li><?php endforeach; ?></ul>
                </div>
            </section>
        <?php elseif ($type === 'richtext'): ?>
            <section class="service-block service-block--richtext section"><div class="container prose"><?= $block['html'] ?? '' ?></div></section>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<?php require ROOT_PATH . '/views/partials/contact-block.php'; ?>
