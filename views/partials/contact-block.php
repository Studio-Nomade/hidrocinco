<section class="contact-section section" id="contacto">
    <div class="container contact-shell">
        <div class="contact-pitch">
            <div><h2>¡Hablemos<span>!</span></h2><p>Escríbenos y nos comunicaremos contigo</p></div>
        </div>
        <div class="contact-form-card">
            <h2>Formulario de <span>contacto</span></h2>
            <!-- TODO Hito 8: persistencia, correo, honeypot y validación reCAPTCHA. -->
            <form class="contact-form" action="<?= e(url('/contacto')) ?>" method="post">
                <label>Nombre <b>*</b><input type="text" name="name" placeholder="Nombre" required></label>
                <label>Teléfono <b>*</b><input type="tel" name="phone" placeholder="Teléfono" required></label>
                <label>Correo electrónico <b>*</b><input type="email" name="email" placeholder="Correo electrónico" required></label>
                <label>Mensaje <b>*</b><textarea name="message" rows="4" placeholder="Mensaje" required></textarea></label>
                <div class="recaptcha-placeholder" aria-label="reCAPTCHA pendiente de integración"><span aria-hidden="true"></span> No soy un robot <small>reCAPTCHA</small></div>
                <button class="btn" type="submit">Enviar</button>
            </form>
        </div>
    </div>
</section>
