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

## Envío de correo

- Preferir **SMTP autenticado** (SiteGround provee SMTP con las cuentas de correo del dominio) sobre
  `mail()` para mejor entregabilidad. Config en `config.php`: `MAIL_HOST`, `MAIL_PORT`, `MAIL_USER`,
  `MAIL_PASS`, `MAIL_FROM`, `MAIL_TO` (destino: `hidrocinco@hidrocinco.cl`).
  - Implementar un `Mailer` mínimo. Opciones aceptables en GoGeek: PHPMailer vía Composer (compatible
    con SiteGround) **o** `mail()` como fallback. **Recomendado: PHPMailer con SMTP** (es la primera
    dependencia Composer del proyecto; si se introduce Composer, `vendor/` va en `.gitignore` y el
    deploy debe correr `composer install --no-dev` — coordinar con Hito 10). Si se prefiere cero
    dependencias, usar `mail()` con headers correctos y aceptar menor entregabilidad; dejar el punto
    documentado para decidir.
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
8. Responsive y accesible (labels asociadas, foco visible).

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

> **Nota de coordinación con Hito 10:** si se adopta PHPMailer (Composer), el pipeline de deploy debe
> ejecutar `composer install --no-dev --optimize-autoloader` o subir `vendor/`. Anotarlo en Hito 10.

---

## Estado y auditoría

- **Estado:** ⬜ Pendiente
- **Rama:** `feature/h8-formularios`
- **Notas de auditoría:** _(las completa Claude al revisar)_
