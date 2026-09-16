<section class="login-card">
    <a href="<?= e(url('/')) ?>"><img src="<?= e(asset('img/logo.svg')) ?>" alt="Hidrocinco" width="210" height="58"></a>
    <div><p class="kicker">Backoffice</p><h1>Bienvenido</h1><p>Ingresa tus credenciales para administrar el contenido.</p></div>
    <?php if (!empty($error)): ?><p class="form-error" role="alert"><?= e($error) ?></p><?php endif; ?>
    <form action="<?= e(url('/admin/login')) ?>" method="post">
        <?= csrf_field() ?>
        <label for="email">Correo electrónico</label>
        <input id="email" name="email" type="email" value="<?= e($email ?? '') ?>" autocomplete="username" required autofocus>
        <label for="password">Contraseña</label>
        <input id="password" name="password" type="password" autocomplete="current-password" required>
        <button type="submit">Entrar</button>
    </form>
</section>
