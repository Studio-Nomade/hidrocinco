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
                    <img src="<?= e(asset($service['icon'] ?: 'img/logo-mark.svg')) ?>" alt="" width="52" height="52">
                    <h2><?= e($service['title']) ?></h2>
                    <span>Ver más <b aria-hidden="true">→</b></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="about-section section" id="nosotros">
    <div class="container split-layout">
        <a class="video-card" href="https://youtu.be/6zTrRqoJLb0" target="_blank" rel="noopener" aria-label="Ver video de Hidrocinco">
            <img src="<?= e(asset('img/somos-hidrocinco.webp')) ?>" alt="Técnico de Hidrocinco revisando un sistema hidráulico" width="2560" height="1442">
            <span class="play-button" aria-hidden="true">▶</span>
        </a>
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
