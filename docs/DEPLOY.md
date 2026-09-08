# Deploy de Hidrocinco en SiteGround

Este runbook cubre el despliegue por GitHub Actions hacia SiteGround GoGeek. No contiene valores
reales de hosts, usuarios, rutas, claves, base de datos, SMTP ni reCAPTCHA.

## Arquitectura y ramas

- `develop` integra hitos auditados.
- `main` representa producción; cada push dispara `.github/workflows/deploy.yml`.
- Staging se despliega manualmente desde Actions, seleccionando el environment `staging`.
- `DEPLOY_PATH` es la raíz privada de la aplicación. El document root del dominio debe ser
  `DEPLOY_PATH/public`, de modo que `src/`, `views/`, `db/`, `vendor/` y `config.php` no sean web.

El runner instala las dependencias con Composer, valida PHP y sincroniza el proyecto con rsync.
`vendor/` sí viaja al servidor. `.deployignore` excluye datos locales y protege `config.php`,
`public/uploads/` y `var/` de transferencia y borrado. La única pieza administrada dentro de
`uploads` es su `.htaccess`, que bloquea código PHP. Un despliegue real termina ejecutando
`php db/migrate.php`; nunca ejecuta el seed.

## GitHub Environments y secrets

Crea los environments `staging` y `production`. Configura los mismos cinco secrets en cada uno:

| Secret | Contenido |
| --- | --- |
| `SSH_HOST` | Host SSH mostrado por SiteGround. |
| `SSH_PORT` | Puerto SSH numérico. |
| `SSH_USER` | Usuario de despliegue. |
| `SSH_PRIVATE_KEY` | Clave privada dedicada, sin passphrase, con acceso mínimo. |
| `DEPLOY_PATH` | Ruta privada absoluta de la aplicación, sin espacios. |

Antes de cargar los secrets, compara manualmente la huella SSH publicada por SiteGround con
`ssh-keyscan -p PUERTO HOST`. La clave pública dedicada se instala en Site Tools; la privada solo se
guarda en GitHub. Para producción conviene exigir aprobación en el environment y restringirlo a
`main`.

## Provisión inicial

1. En Site Tools crea una base MySQL y un usuario con permisos solo sobre esa base.
2. Habilita SSH, instala la clave pública de deploy y confirma la huella del host.
3. Crea `DEPLOY_PATH`, `DEPLOY_PATH/public/uploads` y `DEPLOY_PATH/var`; otorga escritura al usuario
   PHP únicamente donde corresponda.
4. Copia `config.example.php` como `DEPLOY_PATH/config.php`, aplica permisos `600` y configura:
   `APP_ENV=production`, `BASE_URL=https://...`, MySQL, SMTP y reCAPTCHA. El archivo no se commitea.
5. Configura el document root del dominio de staging como `DEPLOY_PATH/public`.
6. Crea el environment `staging` y sus secrets. Ejecuta el workflow manualmente primero con
   `dry_run=true`; revisa la salida de rsync y luego repite con `dry_run=false`.
7. En el primer despliegue el workflow crea el esquema con `db/migrate.php`. Carga los siete
   servicios, la nota y el administrador una sola vez usando una clave temporal que no quede en el
   historial. No vuelvas a ejecutar el seed sobre contenido editado en producción:

   ```bash
   read -rsp 'Contraseña inicial: ' ADMIN_SEED_PASSWORD && echo
   export ADMIN_SEED_PASSWORD
   php db/seed.php
   unset ADMIN_SEED_PASSWORD
   ```
8. Cambia inmediatamente la contraseña inicial del administrador sin escribirla en el historial:

   ```bash
   read -rsp 'Nueva contraseña: ' ADMIN_PASSWORD && echo
   export ADMIN_PASSWORD
   ADMIN_EMAIL='admin@hidrocinco.cl' php db/set-admin-password.php
   unset ADMIN_PASSWORD
   ```

9. Activa Let's Encrypt, prueba HTTPS y confirma el redirect HTTP → HTTPS. Valida reCAPTCHA con el
   dominio real y confirma que un contacto llega por SMTP y también aparece en `/admin/mensajes`.
10. Repite el despliegue para comprobar que `config.php`, uploads y sesiones persisten. Purga la
    caché dinámica manualmente desde Site Tools cuando un cambio publicado no aparezca.

## Promoción a producción

Antes de cambiar DNS, valida en staging: home, siete servicios, blog, nota, formulario, correo,
administración, sitemap, robots, favicon y redirects antiguos. Después:

1. Crea/configura el environment `production`, preferentemente con aprobación obligatoria.
2. Provisiona una raíz y un `config.php` de producción independientes.
3. Integra el commit auditado en `main`; el push inicia el despliegue productivo.
4. Revisa el job, abre el sitio por HTTPS y ejecuta el checklist anterior.
5. Solo entonces realiza el corte DNS siguiendo el anexo del roadmap y conserva los registros de
   Microsoft 365 y Brevo. La modificación de nameservers se hace fuera de este workflow.

## Rollback

El rollback preferido es `git revert` del cambio defectuoso sobre `main` y push: crea un nuevo
despliegue auditable sin reescribir historia. Para una urgencia también puede reejecutarse un job
exitoso asociado al commit anterior. En ambos casos:

- `config.php`, uploads y sesiones no se revierten ni se eliminan.
- Las migraciones son solo hacia adelante; este flujo no revierte esquema ni datos.
- Si el cambio incluyó una migración incompatible, prepara primero una migración correctiva.
- Tras el rollback, purga la caché de SiteGround y repite el smoke test de producción.

## Operación habitual

- Push a `main`: deploy real al environment `production` y migraciones.
- Actions → Run workflow → `staging`, `dry_run=true`: previsualiza cambios sin modificar archivos.
- Actions → Run workflow → `staging`, `dry_run=false`: despliega staging y migra.
- El sitemap es dinámico; no necesita cron. Tampoco hay tareas periódicas obligatorias actualmente.
- Nunca copies bases, credenciales o uploads de producción al repositorio.
