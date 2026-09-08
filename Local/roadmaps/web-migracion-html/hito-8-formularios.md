# Hito 8 — Formularios (contacto + newsletter) con correo y anti-spam

> **Para Codex.** Prompt autocontenido. Rama **desde `develop`** (`feature/h8-formularios`), hasta la
> DoD, verificación local, **sin PR**. Requiere Hitos 1, 2, 4 y 5 en `develop`.

## Objetivo

Hacer funcionales el **formulario de contacto** (bloque reutilizable, presente en home y cada
servicio) y la **suscripción al newsletter** (en el blog): validación server-side, **reCAPTCHA v2 +
honeypot**, **envío por email** a Hidrocinco y **guardado en `submissions`** (visible en el admin).

## Decisiones confirmadas

- Cada envío de contacto: **se envía por correo** a Hidrocinco **y** se **guarda en DB**.
- Newsletter: se guarda en DB (type `newsletter`) y opcionalmente se notifica por correo.
- Anti-spam: **reCAPTCHA v2 checkbox** + **honeypot** (campo oculto que los bots rellenan).

## Rutas

```
POST /contacto        -> ContactController@submit    (form de contacto)
POST /newsletter      -> NewsletterController@submit  (suscripción)
GET  /contacto        -> (opcional) página de contacto dedicada con el bloque
```
- Ambos aceptan envío normal (POST con recarga y mensaje de éxito/error) **y** opcionalmente fetch/AJAX
  para respuesta sin recargar (progressive enhancement). Prioridad: que funcione sin JS.

## Validación server-side

- Contacto: `name` (requerido), `phone` (requerido, formato laxo), `email` (requerido, válido),
  `message` (requerido, longitud mínima). Rechazar si falta algo → volver al form con errores y
  valores preservados.
- Newsletter: `name` (opcional/requerido según diseño), `email` (requerido, válido).
- Normalizar/trim; limitar longitudes; escapar al mostrar.

## reCAPTCHA v2 + honeypot

- **reCAPTCHA v2 "No soy un robot"**: incluir el script oficial de Google en el front (permitido en
  runtime del navegador) y **verificar el token en el servidor** contra
  `https://www.google.com/recaptcha/api/siteverify` con `RECAPTCHA_SECRET`. Claves en `config.php`
  (`RECAPTCHA_SITE_KEY`, `RECAPTCHA_SECRET`); añadirlas a `config.example.php`.
  - Documentar en README cómo obtener las claves (Google reCAPTCHA admin) y que el dominio debe
    incluir `hidrocinco.cl` y `localhost` para pruebas.
- **Honeypot**: campo oculto (p. ej. `<input name="website">` oculto por CSS). Si viene relleno →
  descartar como spam silenciosamente (respuesta de éxito falso para no informar al bot).
- Si reCAPTCHA falla la verificación → error "Verifica que no eres un robot".

## Envío de correo (decisión tomada: PHPMailer vía SMTP autenticado)

- **Usar PHPMailer con SMTP autenticado** (SiteGround provee SMTP con las cuentas de correo del
  dominio). Es la mejor opción de entregabilidad y GoGeek es 100% compatible con Composer. **No** usar
  `mail()`.
- **Composer**: este hito **introduce Composer** en el proyecto (primera y —por ahora— única
  dependencia). Crear `composer.json` requiriendo `phpmailer/phpmailer` (versión estable actual).
  - `vendor/` va en `.gitignore` (**no** se commitea). Se genera en el pipeline de deploy con
    `composer install --no-dev --optimize-autoloader` — **coordinado con Hito 10** (allí se ejecuta en
    GitHub Actions y se sincroniza `vendor/` al servidor).
  - En **local**, el dev corre `composer install` una vez (documentar en el README).
  - Cargar el autoload de Composer en el bootstrap (`require 'vendor/autoload.php'`) de forma segura
    (si no existe `vendor/`, error claro pidiendo `composer install`).
- Config en `config.php`: `MAIL_HOST`, `MAIL_PORT`, `MAIL_USER`, `MAIL_PASS`, `MAIL_FROM`, `MAIL_TO`
  (destino: `hidrocinco@hidrocinco.cl`). Añadirlas a `config.example.php`.
- Implementar un wrapper `src/Mailer.php` sobre PHPMailer (configura SMTP desde `config.php`, expone
  `send($to, $subject, $htmlBody, $replyTo)`), para no acoplar los controladores a la librería.
- Email de contacto → asunto "Nuevo mensaje de contacto — Hidrocinco", cuerpo con los campos +
  `source_page`. `Reply-To` = email del remitente.
- Guardar SIEMPRE en `submissions` aunque el correo falle (registrar el fallo en log); no perder el
  lead.

## Guardado en DB y vista en admin

- `SubmissionRepository::create()` con `type`, campos, `source_page`, `ip`, `created_at`.
- **Admin → Mensajes** (`/admin/mensajes`): lista de submissions (filtro por tipo contact/newsletter),
  con fecha, datos y mensaje. Solo lectura (marcar leído es opcional). Protegido por auth (Hito 5).
  Completa el enlace "Mensajes" que quedó placeholder en el shell del admin.

## UX de estados

- Éxito: mensaje "¡Gracias! Nos comunicaremos contigo pronto." (contacto) / "¡Suscripción exitosa!"
  (newsletter). Idealmente anclar al bloque del formulario.
- Error: mostrar mensajes por campo + error general; preservar lo escrito.
- CSRF: aunque son formularios públicos, incluir token de sesión ayuda; como mínimo, honeypot +
  reCAPTCHA. (No bloquear a usuarios sin cookies; evaluar.)

## Criterios de aceptación (DoD)

1. Enviar el formulario de contacto (home y desde un servicio) con datos válidos + reCAPTCHA:
   **llega el correo** a la casilla configurada **y** queda registrado en `submissions`, visible en
   `/admin/mensajes`.
2. Validación server-side: envíos incompletos vuelven con errores y datos preservados.
3. Honeypot relleno → descartado como spam (no correo, no registro real / o registro marcado spam).
4. reCAPTCHA inválido → rechazado con mensaje.
5. Newsletter funciona (guarda type `newsletter`, aparece en admin).
6. `source_page` refleja desde dónde se envió.
7. Claves y credenciales solo en `config.php` (no en el repo). `config.example.php` actualizado.
8. `composer.json` versionado; `vendor/` en `.gitignore`; `Mailer.php` envuelve PHPMailer; el
   bootstrap carga `vendor/autoload.php` con error claro si falta.
9. Responsive y accesible (labels asociadas, foco visible).

## Pasos de verificación local

```bash
# Configurar RECAPTCHA_* y MAIL_* en config.php (usar claves de prueba de reCAPTCHA y un SMTP real o
# de pruebas tipo Mailtrap para no depender de envío real en local).
php -S localhost:8000 -t public
# Enviar contacto desde / y desde /servicios/pozos-profundos
#   → verificar registro en /admin/mensajes y llegada del correo (o captura en Mailtrap)
# Rellenar honeypot por DevTools → confirmar descarte
# Enviar sin completar reCAPTCHA → error
# Suscribir newsletter desde /blog → aparece en admin
```

> **Coordinación con Hito 10:** el pipeline de deploy ejecuta `composer install --no-dev
> --optimize-autoloader` en GitHub Actions y sincroniza `vendor/` al servidor (ver Hito 10). En local,
> el dev corre `composer install` una vez.

---

## Estado y auditoría

- **Estado:** ⬜ Pendiente
- **Rama:** `feature/h8-formularios`
- **Notas de auditoría:** _(las completa Claude al revisar)_
