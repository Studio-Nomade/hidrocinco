# Hito 7 — Backoffice: CRUD de Blog (editor de texto enriquecido)

> **Para Codex.** Prompt autocontenido. Rama **desde `develop`** (`feature/h7-admin-blog`), hasta la
> DoD, verificación local, **sin PR**. Requiere Hitos 1, 4 y 5 en `develop`.

## Objetivo

Gestionar las **notas de blog** desde el admin con un **editor de texto enriquecido**, imagen
destacada, slug, fecha y estado borrador/publicado. Una nota publicada aquí debe aparecer en `/blog`
y `/blog/<slug>` (plantilla del Hito 4).

## Rutas (protegidas por auth del Hito 5)

```
GET  /admin/blog                 -> lista de notas
GET  /admin/blog/nueva           -> form crear
POST /admin/blog                 -> guardar nueva
GET  /admin/blog/{id}/editar     -> form editar
POST /admin/blog/{id}            -> actualizar
POST /admin/blog/{id}/eliminar   -> eliminar (confirmación)
```
Controlador: `src/Controllers/Admin/PostAdminController.php`. Usa `PostRepository` (Hito 1).

## Lista (`views/admin/posts/index.php`)

- Tabla: título, slug, estado (borrador/publicado con badge), fecha (`published_at`), acciones
  (Editar, Eliminar). Filtro simple por estado (opcional). Botón "Nueva nota".

## Formulario crear/editar (`views/admin/posts/form.php`)

- **Título** (requerido). **Slug** (auto desde título con JS, editable; único; formato `[a-z0-9-]`).
- **Extracto** (`excerpt`, textarea corta; si se deja vacío, autogenerar desde el cuerpo al guardar).
- **Imagen destacada**: subida (misma política de subida del Hito 6 → `public/uploads/blog/`).
- **Cuerpo**: **editor enriquecido Quill** (self-hosted: descargar `quill.min.js` + `quill.snow.css`
  a `assets/vendor/quill/` — **no** CDN en runtime, para no depender de terceros ni romper CSP en
  SiteGround). Toolbar: encabezados (h2/h3), negrita, itálica, listas (ol/ul), enlaces, blockquote.
  El HTML del editor se envía en un input oculto.
- **Estado**: borrador / publicado (`status`).
- **Fecha de publicación** (`published_at`, date input; por defecto hoy al publicar).
- Token CSRF.

## Sanitización (seguridad — importante)

- El HTML de Quill **debe sanitizarse en el servidor** antes de guardar en `body_html`, con una
  **allowlist** de tags/atributos: `p, br, h2, h3, strong, em, u, ul, ol, li, a[href,title],
  blockquote`. Eliminar `<script>`, `<style>`, `on*` handlers, `javascript:` en href, iframes, etc.
  - Preferir una función de sanitización propia y estricta (o, si se admite Composer más adelante y es
    compatible con GoGeek, una librería como `ezyang/htmlpurifier`; en este hito, **implementación
    propia sin dependencias** para no romper el "cero build" — documentar el enfoque).
- Escapar título/slug/excerpt con `e()` en las vistas; `body_html` se imprime como HTML confiable ya
  saneado.

## Reglas de negocio

- `status='draft'` → **no** visible en `/blog` ni por URL pública (404), pero editable/visible en
  admin (ya cubierto en Hito 4; verificar).
- Al pasar a `published` sin `published_at`, setear la fecha actual.
- Slug único; al cambiar el slug de una nota publicada, advertir sobre enlaces existentes.
- "Notas relacionadas"/"Nota siguiente" del front (Hito 4) usan estas notas automáticamente.

## Criterios de aceptación (DoD)

1. Se pueden **crear, editar y eliminar** notas desde el admin.
2. El **editor Quill** funciona self-hosted (sin peticiones a CDNs de terceros) y produce HTML.
3. Una nota **publicada** aparece en `/blog` (con su fecha en español) y su detalle renderiza el
   cuerpo enriquecido correctamente.
4. Una nota **borrador** no aparece en el front ni es accesible por URL (404), pero sí en el admin.
5. El `body_html` guardado está **sanitizado** (un intento de guardar `<script>` o `onerror=` queda
   eliminado/escapado).
6. Subida de imagen destacada funciona con la política de validación (tipo/tamaño); inválidos
   rechazados.
7. Slug único validado; CSRF activo; rutas protegidas.

## Pasos de verificación local

```bash
php -S localhost:8000 -t public
# Login → /admin/blog → "Nueva nota"
# Escribir con Quill (h2, negrita, lista, enlace), subir imagen, guardar como borrador
#   → no aparece en /blog; sí en /admin/blog
# Cambiar a "publicado" → aparece en /blog y su detalle se ve bien
# Intentar pegar <script>alert(1)</script> en el cuerpo → verificar que NO se ejecuta ni persiste
```

---

## Estado y auditoría

- **Estado:** ⬜ Pendiente
- **Rama:** `feature/h7-admin-blog`
- **Notas de auditoría:** _(las completa Claude al revisar)_
