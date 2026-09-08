<section class="apps-hero">
    <div class="apps-hero__panel">
        <img src="<?= e(asset('img/logo-white.webp')) ?>" alt="" width="120" height="120" loading="eager">
        <h1>AppsCinco</h1>
    </div>
</section>

<section class="section">
    <div class="container apps-digital">
        <div class="apps-digital__copy">
            <h2>Operación <b>digital</b></h2>
            <p class="apps-pill">Supervisamos, analizamos y predecimos el comportamiento de tus sistemas en tiempo real.</p>
            <p>Nuestra plataforma no solo monitorea, también conecta con nuestro equipo especializado, garantizando una atención personalizada y resolutiva para cada sistema.</p>
            <p>Detrás de cada dato y alerta existe un equipo técnico disponible 24/7, preparado para actuar de forma inmediata ante cualquier eventualidad.</p>
        </div>
        <div class="apps-digital__media">
            <img src="<?= e(asset('img/appscinco/appscinco-dashboard.webp')) ?>" alt="Plataforma AppsCinco en computador y teléfono mostrando telemetría de una sala de bombas" width="1030" height="936" loading="lazy">
        </div>
    </div>
</section>

<section class="section apps-continuidad">
    <div class="container service-split">
        <div class="apps-card">
            <h2>Continuidad <b>Operacional</b></h2>
            <p><strong>Concentramos toda la información operacional de los sistemas.</strong></p>
            <p>De esta manera contamos con un control en línea y una gestión predictiva de los activos hidráulicos.</p>
            <h3>Sistema de telemetría y software de mantenimiento</h3>
            <ul class="apps-check">
                <li>Anticipamos las fallas de nuestros equipos</li>
                <li>Registramos las asistencias en tiempo real</li>
                <li>Reportes automáticos para nuestros clientes.</li>
            </ul>
        </div>
        <div class="apps-continuidad__media">
            <img src="<?= e(asset('img/appscinco/appscinco-continuidad.webp')) ?>" alt="Profesional revisando la operación desde su teléfono" width="1400" height="938" loading="lazy">
        </div>
    </div>
</section>

<section class="section apps-features">
    <div class="container apps-features__grid">
        <div>
            <h2>Funciones <span>principales</span></h2>
            <ul class="apps-list">
                <li><b>Monitoreo remoto de parámetros críticos</b> <em>Caudal, presión, nivel de estanque, consumo eléctrico, estado de bombas y tableros.</em></li>
                <li><b>Registro histórico y trazabilidad completa</b> de cada instalación.</li>
                <li><b>Alertas y notificaciones</b> automáticas de fallas o desviaciones.</li>
                <li><b>Informes digitales accesibles</b> desde cualquier dispositivo.</li>
                <li><b>Integración con sistemas de mantenimiento</b> preventivo y correctivo.</li>
            </ul>
        </div>
        <div>
            <h2>Beneficios</h2>
            <ul class="apps-list">
                <li><b>Mayor disponibilidad y confiabilidad</b> de los equipos.</li>
                <li><b>Reducción de tiempos de respuesta</b> ante contingencias.</li>
                <li><b>Optimización</b> del mantenimiento y <b>uso eficiente</b> de recursos.</li>
                <li><b>Transparencia y trazabilidad total</b> en la operación.</li>
            </ul>
            <div class="apps-brochure">
                <a class="btn" href="<?= e(asset('docs/Hidrocinco-Brochure-AppsCinco.pdf')) ?>" target="_blank" rel="noopener">Descarga nuestro brochure aquí</a>
                <p>Y conoce más información acerca de cómo seguimos innovando en Hidrocinco.</p>
            </div>
        </div>
    </div>
</section>

<?php require ROOT_PATH . '/views/partials/contact-block.php'; ?>
