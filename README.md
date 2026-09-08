# Hidrocinco — sitio oficial

Sitio público y backoffice liviano en PHP 8 + MySQL, preparado para SiteGround GoGeek.

## Inicio local

```bash
cp config.example.php config.php
composer install
php -S localhost:8000 -t public
```

Abre `http://localhost:8000`. El front controller también funciona con Apache mediante
`public/.htaccess`.

## Base de datos

Configura `DB_HOST`, `DB_NAME`, `DB_USER` y `DB_PASS` en `config.php` o mediante variables de
entorno. Después ejecuta:

```bash
php db/migrate.php
read -rsp 'Contraseña inicial del admin: ' ADMIN_SEED_PASSWORD && echo
export ADMIN_SEED_PASSWORD
php db/seed.php
unset ADMIN_SEED_PASSWORD
php db/check.php
```

Para verificación local sin MySQL se admite explícitamente `DB_DSN=sqlite:/ruta/db.sqlite`; el
destino de producción sigue siendo MySQL.

El seed crea `admin@hidrocinco.cl` usando `ADMIN_SEED_PASSWORD` (mínimo 14 caracteres). La clave no
queda en el repositorio y debe cambiarse inmediatamente en cada entorno real.

## Formularios y correo

Los formularios requieren claves reCAPTCHA v2 y una cuenta SMTP. Crea las claves en la consola de
Google reCAPTCHA, registra `hidrocinco.cl` y `localhost`, y configura `RECAPTCHA_SITE_KEY`,
`RECAPTCHA_SECRET_KEY`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USER`, `MAIL_PASS`, `MAIL_FROM` y `MAIL_TO`.
El correo usa PHPMailer por SMTP; los envíos se guardan en la base aunque SMTP no esté disponible.

Para pruebas automatizadas exclusivamente en `APP_ENV=development`, define
`RECAPTCHA_TEST_MODE=1` y envía el token `test-pass`. Este bypass no opera en producción.

## Deploy

Los pushes a `main` despliegan a producción mediante GitHub Actions, SSH y rsync. El workflow
construye `vendor/`, conserva `config.php` y `public/uploads/`, y luego ejecuta las migraciones
idempotentes. Staging y las simulaciones se ejecutan manualmente desde Actions.

La provisión inicial, los secrets requeridos, la promoción staging → producción y el rollback están
documentados en [docs/DEPLOY.md](docs/DEPLOY.md). No ejecutes `db/seed.php` automáticamente ni
guardes credenciales en el repositorio.
