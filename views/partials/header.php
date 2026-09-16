<header class="site-header" data-site-header>
    <div class="container site-header__inner">
        <a class="brand" href="<?= e(url('/')) ?>" aria-label="Hidrocinco, inicio">
            <img src="<?= e(asset('img/logo.svg')) ?>" alt="Hidrocinco" width="190" height="58">
        </a>
        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="main-nav">
            <span class="sr-only">Abrir menú</span><span></span><span></span><span></span>
        </button>
        <nav id="main-nav" class="main-nav" aria-label="Navegación principal">
            <a href="<?= e(url('/')) ?>">Inicio</a>
            <a href="<?= e(url('/#servicios')) ?>">Servicios</a>
            <a href="<?= e(url('/#nosotros')) ?>">Nosotros</a>
            <a href="<?= e(url('/#vision')) ?>">Visión</a>
            <a href="<?= e(url('/blog')) ?>">Blog</a>
            <a href="<?= e(url('/appscinco')) ?>">AppsCinco</a>
            <a class="btn btn--outline" href="<?= e(url('/contacto')) ?>">Contacto</a>
        </nav>
    </div>
</header>
