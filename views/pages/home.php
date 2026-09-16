<section class="home-hero" id="servicios">
    <div class="container home-hero__inner">
        <div class="home-hero__copy">
            <h1>Expertos en<br>sistemas de agua</h1>
            <p>Con más de 40 años de<br>experiencia</p>
            <a class="btn btn--gradient" href="#nosotros">Sobre nosotros</a>
        </div>
        <div class="service-grid" aria-label="Nuestros servicios">
            <?php foreach ($services as $service): ?>
                <a class="service-card" href="<?= e(url('/servicios/' . $service['slug'])) ?>">
                    <img src="<?= e(media_url($service['icon'])) ?>" alt="" width="52" height="52">
                    <h2><?= e($service['title']) ?></h2>
                    <span>Ver más <b aria-hidden="true">→</b></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="about-section section" id="nosotros">
    <div class="container split-layout">
        <button type="button" class="video-card" data-video-open data-video-id="6zTrRqoJLb0" aria-label="Ver video de Hidrocinco">
            <img src="<?= e(asset('img/somos-hidrocinco-1200.webp')) ?>" srcset="<?= e(asset('img/somos-hidrocinco-640.webp')) ?> 640w, <?= e(asset('img/somos-hidrocinco-1200.webp')) ?> 1200w" sizes="(max-width: 768px) calc(100vw - 40px), 50vw" alt="Técnico de Hidrocinco revisando un sistema hidráulico" width="1200" height="676" loading="lazy">
            <span class="play-button" aria-hidden="true">▶</span>
        </button>
        <div class="about-copy">
            <p class="eyebrow">Sobre nosotros</p>
            <h2>Somos Hidro<span>cinco</span></h2>
            <p>Somos una empresa de servicio, con más de 40 años de experiencia en el Mercado de Soluciones Hidráulicas. Mantenemos, Reparamos y Mejoramos los sistemas de Extracción, Acumulación, Impulsión y Tratamiento de AGUA en los sectores rurales y urbanos. Buscamos asegurar la continuidad operacional de estos sistemas con respuesta 24/7</p>
        </div>
    </div>
</section>

<section class="vision-section section" id="vision">
    <div class="container narrow center">
        <h2>Visión</h2>
        <p>Ser una empresa LÍDER a nivel Nacional en el mercado de Soluciones Hidráulicas, utilizando herramientas tecnológicas que nos permitan predecir el buen funcionamiento de los sistemas Hidráulicos (extracción, acumulación, impulsión, tratamiento y riego).</p>
    </div>
</section>

<?php require ROOT_PATH . '/views/partials/contact-block.php'; ?>
