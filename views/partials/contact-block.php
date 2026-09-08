<?php $contactFeedback = flash_get('contact_feedback', []); $contactOld = $contactFeedback['old'] ?? []; $contactErrors = $contactFeedback['errors'] ?? []; ?>
<section class="contact-section section" id="contacto">
    <div class="container contact-shell">
        <div class="contact-pitch">
            <div><h2>¡Hablemos<span>!</span></h2><p>Escríbenos y nos comunicaremos contigo</p></div>
        </div>
        <div class="contact-form-card">
            <h2>Formulario de <span>contacto</span></h2>
            <form class="contact-form" action="<?= e(url('/contacto')) ?>" method="post">
                <?= csrf_field() ?><input type="hidden" name="source_page" value="<?= e(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/') ?>">
                <div class="honeypot" aria-hidden="true"><label for="contact-website">No completar<input id="contact-website" type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
                <?php if (!empty($contactFeedback['success'])): ?><p class="form-success" role="status"><?= e($contactFeedback['success']) ?></p><?php endif; ?>
                <?php if ($contactErrors): ?><p class="form-error-public" role="alert">Revisa los campos indicados.</p><?php endif; ?>
                <label for="contact-name">Nombre <b>*</b><input id="contact-name" type="text" name="name" value="<?= e($contactOld['name'] ?? '') ?>" placeholder="Nombre" required></label><?php if (isset($contactErrors['name'])): ?><small class="field-error"><?= e($contactErrors['name']) ?></small><?php endif; ?>
                <label for="contact-phone">Teléfono <b>*</b><input id="contact-phone" type="tel" name="phone" value="<?= e($contactOld['phone'] ?? '') ?>" placeholder="Teléfono" required></label><?php if (isset($contactErrors['phone'])): ?><small class="field-error"><?= e($contactErrors['phone']) ?></small><?php endif; ?>
                <label for="contact-email">Correo electrónico <b>*</b><input id="contact-email" type="email" name="email" value="<?= e($contactOld['email'] ?? '') ?>" placeholder="Correo electrónico" required></label><?php if (isset($contactErrors['email'])): ?><small class="field-error"><?= e($contactErrors['email']) ?></small><?php endif; ?>
                <label for="contact-message">Mensaje <b>*</b><textarea id="contact-message" name="message" rows="4" placeholder="Mensaje" required><?= e($contactOld['message'] ?? '') ?></textarea></label><?php if (isset($contactErrors['message'])): ?><small class="field-error"><?= e($contactErrors['message']) ?></small><?php endif; ?>
                <?php if ((string) config('recaptcha.site_key', '') !== ''): ?><div class="g-recaptcha" data-sitekey="<?= e(config('recaptcha.site_key')) ?>"></div><?php else: ?><p class="form-config-note">Configura reCAPTCHA para habilitar envíos reales.</p><?php endif; ?>
                <?php if (isset($contactErrors['recaptcha'])): ?><small class="field-error"><?= e($contactErrors['recaptcha']) ?></small><?php endif; ?>
                <button class="btn" type="submit">Enviar</button>
            </form>
        </div>
    </div>
</section>
