# Hidrocinco — sitio oficial

Sitio público y backoffice liviano en PHP 8 + MySQL, preparado para SiteGround GoGeek.

## Inicio local

```bash
cp config.example.php config.php
php -S localhost:8000 -t public
```

Abre `http://localhost:8000`. El front controller también funciona con Apache mediante
`public/.htaccess`.

## Base de datos

Configura `DB_HOST`, `DB_NAME`, `DB_USER` y `DB_PASS` en `config.php` o mediante variables de
entorno. Después ejecuta:

```bash
php db/migrate.php
php db/seed.php
php db/check.php
```

Para verificación local sin MySQL se admite explícitamente `DB_DSN=sqlite:/ruta/db.sqlite`; el
destino de producción sigue siendo MySQL.

El seed crea `admin@hidrocinco.cl` con la contraseña inicial documentada en el roadmap. Debe
cambiarse inmediatamente en cada entorno real.
