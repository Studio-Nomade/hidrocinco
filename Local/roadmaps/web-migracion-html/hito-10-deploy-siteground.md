# Hito 10 — Deploy GitHub → SiteGround GoGeek (CI/CD)

> **Para Codex.** Prompt autocontenido. Rama **desde `develop`** (`feature/h10-deploy`), hasta la DoD,
> **sin PR**. Requiere Hitos 0–9 en `develop`. Este hito toca configuración de repo/infra: **coordina
> con Sebastián** los datos sensibles (host SSH, credenciales, ruta del sitio) — NO los inventes ni
> commitees; se cargan como **GitHub Secrets**.

## Objetivo

Automatizar el despliegue a **SiteGround GoGeek** vía **GitHub Actions (SSH/rsync)** al hacer push a
la rama de producción, con provisión de base de datos en producción, `.htaccess`/config de prod, y
documentación del flujo staging→producción. SiteGround **no** trae CD automático de fábrica; lo
montamos nosotros.

## Modelo de ramas

- `develop`: integración (aquí se mergean los hitos auditados).
- `main`: producción. **Push a `main` dispara el deploy.**
- (Opcional) `staging` → sitio de staging de SiteGround, si se usa.

## Estrategia de despliegue (SSH + rsync)

SiteGround GoGeek permite **SSH** y crear **claves SSH**. El deploy sincroniza los archivos del repo
al docroot del sitio y ejecuta pasos post-deploy (migraciones).

- **Docroot**: en SiteGround el sitio suele servirse desde `~/www/<dominio>/public_html`. Como nuestro
  front controller vive en `public/`, dos opciones (elegir y documentar):
  1. Apuntar el dominio/document root a la subcarpeta `public/` del deploy (preferible; SiteGround
     permite cambiar el document root a una subcarpeta), dejando `src/`, `views/`, `db/`,
     `config.php` **fuera** del docroot (más seguro). **Recomendado.**
  2. Si no se puede mover el docroot, ubicar `index.php` + `.htaccess` en `public_html` y el resto en
     una carpeta hermana fuera de la web, ajustando rutas. Documentar.
- **`config.php` en producción**: NO viaja en el repo. Se coloca **una vez** manualmente en el server
  (fuera del docroot) con credenciales reales (DB de prod, SMTP, reCAPTCHA). El rsync debe
  **excluirlo** para no sobrescribirlo.
- **`public/uploads/`**: contenido subido por el admin **no** debe borrarse en cada deploy → excluir de
  rsync (`--exclude`), o usar `rsync` sin `--delete` sobre esa ruta. Definir claramente.
- **`vendor/`** (si se adoptó PHPMailer en Hito 8): o se commitea, o el pipeline corre
  `composer install --no-dev --optimize-autoloader` en el runner y sincroniza `vendor/`. Elegir y
  documentar (SiteGround tiene Composer, pero es más simple construir en el runner y subir).

## Workflow GitHub Actions (`.github/workflows/deploy.yml`)

Pasos:
1. Trigger: `on: push: branches: [main]`.
2. `actions/checkout`.
3. (Si aplica) setup PHP + `composer install --no-dev --optimize-autoloader`.
4. (Si aplica) minificar assets estáticos (paso opcional; o ya versionados desde Hito 9).
5. Cargar clave SSH desde secret (`webfactory/ssh-agent` o `appleboy/ssh-action` / `rsync`).
6. **rsync** del proyecto al server por SSH, con `--exclude` de: `.git`, `.github`, `Local/`,
   `config.php`, `public/uploads/`, `node_modules`, archivos de dev, y `--delete` **solo** en las
   rutas seguras (código), nunca en `uploads`.
7. Post-deploy por SSH: `php db/migrate.php` (idempotente) en el server. **No** correr `seed.php` en
   prod (los datos de prod los gestiona el admin); documentar el seeding inicial como paso manual
   controlado la primera vez.
8. (Opcional) purgar caché dinámica de SiteGround si hay CLI/endpoint disponible; si no, documentar
   purga manual desde Site Tools.

**GitHub Secrets requeridos** (Sebastián los carga; documentarlos en el README de deploy):
`SSH_HOST`, `SSH_PORT`, `SSH_USER`, `SSH_PRIVATE_KEY`, `DEPLOY_PATH`. (Nada de esto en el repo.)

## Provisión inicial en producción (documentar como runbook, no automatizar la primera vez)

1. Crear DB MySQL + usuario en SiteGround Site Tools; anotar credenciales.
2. Subir/crear `config.php` en el server (fuera del docroot) con DB/SMTP/reCAPTCHA de prod.
3. Ajustar document root del dominio a `.../public/` (opción 1).
4. Primer deploy (push a `main`) → rsync + `php db/migrate.php`.
5. **Seed inicial controlado**: correr `php db/seed.php` **una sola vez** para cargar los 7 servicios
   y la nota (o cargar el contenido vía admin). Luego el admin gestiona todo.
6. Crear el usuario admin real y **cambiar la password** del seed.
7. Configurar SSL (Let's Encrypt en SiteGround), forzar HTTPS (redirect en `.htaccess`).
8. Verificar reCAPTCHA (dominio `hidrocinco.cl` en las claves) y envío de correo real.

## Documentación (entregable de este hito)

- `docs/DEPLOY.md`: modelo de ramas, cómo funciona el workflow, lista de secrets, runbook de
  provisión inicial, cómo hacer rollback (redeploy de un commit anterior / `git revert`), y notas de
  SiteGround (document root, uploads persistentes, caché, cron si se necesita para sitemap/limpiezas).
- Actualizar el `README.md` raíz del proyecto con "Desarrollo local" + "Deploy".

## Seguridad
- `config.php`, claves y `.env` nunca en el repo. `.gitignore` cubre `config.php`, `uploads/`,
  `vendor/` (si no se commitea).
- Clave SSH dedicada al deploy (no la personal), con acceso mínimo.
- Bloquear acceso web a `src/`, `views/`, `db/`, `config.php` (si quedan bajo el docroot en opción 2)
  vía `.htaccess`/deny.

## Criterios de aceptación (DoD)

1. `.github/workflows/deploy.yml` existe y, con los secrets configurados, un push a `main` despliega
   por SSH/rsync a GoGeek sin pasos manuales (salvo la provisión inicial documentada).
2. El deploy **no** sobrescribe `config.php` ni borra `public/uploads/` en el server.
3. `php db/migrate.php` corre en post-deploy y es idempotente.
4. `docs/DEPLOY.md` permite a otra persona reproducir provisión, deploy y rollback.
5. Tras el primer deploy real, el sitio queda en vivo por HTTPS con contenido correcto (verificar los
   7 servicios, blog, formularios y admin en producción).
6. Sin secretos en el repositorio.

## Pasos de verificación

```bash
# En local: validar sintaxis del workflow y del rsync (dry-run).
rsync -avn --exclude 'config.php' --exclude 'public/uploads' ./ usuario@host:/ruta/   # simulación
# Configurar secrets en GitHub → push a main → observar Actions
# Verificar en el navegador: https://hidrocinco.cl (o staging) home/servicios/blog/admin
# Probar formulario de contacto real (llega correo + queda en /admin/mensajes)
# Confirmar que un segundo deploy conserva uploads y config.php
```

> **Antes de apuntar el dominio productivo**, hacer el primer deploy a **staging** de SiteGround y
> validar todo; recién entonces migrar DNS/document root del dominio principal, evitando downtime.

---

## Estado y auditoría

- **Estado:** ⬜ Pendiente
- **Rama:** `feature/h10-deploy`
- **Notas de auditoría:** _(las completa Claude al revisar)_
