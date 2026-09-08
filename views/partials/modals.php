<?php $sos = flash_get('emergency_feedback', []); $sosOld = $sos['old'] ?? []; $sosErr = $sos['errors'] ?? []; ?>
<!-- Modal de Emergencia 24/7 -->
<div class="modal" id="emergencia-modal" data-emergency-modal<?= empty($sos) ? ' hidden' : '' ?>>
    <div class="modal__overlay" data-emergency-close></div>
    <div class="modal__dialog" role="dialog" aria-modal="true" aria-labelledby="emergencia-title">
        <button type="button" class="modal__close" data-emergency-close aria-label="Cerrar">×</button>
        <h2 id="emergencia-title" class="modal__title">⚡ Emergencia 24/7</h2>
        <p class="modal__intro">Cuéntanos qué ocurre y te contactaremos de inmediato. También puedes escribirnos o llamarnos directamente.</p>
        <?php if (!empty($sos['success'])): ?><p class="form-success" role="status"><?= e($sos['success']) ?></p><?php endif; ?>
        <?php if ($sosErr): ?><p class="form-error-public" role="alert">Revisa los campos indicados.</p><?php endif; ?>
        <form class="emergency-form" action="<?= e(url('/emergencia')) ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="source_page" value="<?= e(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/') ?>">
            <div class="honeypot" aria-hidden="true"><label for="emg-website">No completar<input id="emg-website" type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
            <div class="emergency-grid">
                <label>Nombre cliente <b>*</b><input type="text" name="cliente" value="<?= e($sosOld['cliente'] ?? '') ?>" required></label>
                <label>Sucursal<input type="text" name="sucursal" value="<?= e($sosOld['sucursal'] ?? '') ?>"></label>
                <label>Sistema<input type="text" name="sistema" value="<?= e($sosOld['sistema'] ?? '') ?>"></label>
                <label>Nombre de quien reporta <b>*</b><input type="text" name="persona" value="<?= e($sosOld['persona'] ?? '') ?>" required></label>
                <label>Teléfono <b>*</b><input type="tel" name="phone" value="<?= e($sosOld['phone'] ?? '') ?>" required></label>
                <label>Correo<input type="email" name="email" value="<?= e($sosOld['email'] ?? '') ?>"></label>
            </div>
            <label class="emergency-message">Mensaje o emergencia <b>*</b><textarea name="message" rows="3" required><?= e($sosOld['message'] ?? '') ?></textarea></label>
            <button type="submit" class="btn">Enviar emergencia</button>
        </form>
        <div class="modal__contacts">
            <a class="modal__contact modal__contact--wa" href="https://wa.me/56225561859" target="_blank" rel="noopener">WhatsApp</a>
            <a class="modal__contact modal__contact--tel" href="tel:+56225561859">Llamar</a>
            <a class="modal__contact modal__contact--mail" href="mailto:hidrocinco@hidrocinco.cl?subject=Emergencia%2024%2F7">Correo</a>
        </div>
    </div>
</div>
<!-- Lightbox de video -->
<div class="modal video-modal" id="video-modal" data-video-modal hidden>
    <div class="modal__overlay" data-video-close></div>
    <div class="video-modal__frame">
        <button type="button" class="modal__close" data-video-close aria-label="Cerrar video">×</button>
        <div class="video-modal__player" data-video-player></div>
    </div>
</div>
