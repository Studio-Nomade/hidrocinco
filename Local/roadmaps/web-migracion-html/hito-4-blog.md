# Hito 4 — Blog (index + detalle de nota)

> **Para Codex.** Prompt autocontenido. Rama **desde `develop`** (`feature/h4-blog`), hasta la DoD,
> verificación local, **sin PR**. Requiere Hitos 0–3 en `develop`.

## Objetivo

Construir el **blog público**: index con grid de tarjetas y detalle de nota, ambos desde la DB
(Hito 1). Migrar la nota de ejemplo "Guía de mantenimiento | Sistemas Hidráulicos en edificios".

## Referencia visual

`screencapture-hidrocinco-cl-blog-...png` (index) y
`screencapture-hidrocinco-cl-guia-de-mantenimiento-...png` (detalle), y el sitio en vivo.

## Rutas y controlador

- `GET /blog` → `BlogController@index`: `PostRepository::published()` ordenados por
  `published_at DESC`. Solo `status='published'`.
- `GET /blog/{slug}` → `BlogController@show`: `findBySlug`; si no existe o es borrador → 404.

## Index `views/pages/blog-index.php`

- Título centrado **"Blog"** (navy).
- **Grid de tarjetas** (3 columnas desktop, 2 tablet, 1 móvil). Cada tarjeta:
  - Imagen destacada (`featured_image` o placeholder), esquinas redondeadas.
  - **Título** de la nota (navy, bold).
  - **Extracto** (`excerpt`, gris; truncar a ~180 caracteres si hace falta).
  - Link **"Leer Más"** (itálica, con línea) → `/blog/<slug>`.
  - **Fecha** en español bajo una línea divisoria: formato `"15 de enero, 2024"` o el estilo del sitio
    (`enero 15, 2024`). Implementar un helper `fecha_es($date)` que formatee meses en español sin
    depender de `setlocale` (mapa de meses), para que funcione igual en local y en SiteGround.
- Si hay más notas que las de ejemplo, se listan todas (paginación no requerida ahora; si superan ~12,
  dejar `TODO` de paginación).

## Detalle `views/pages/blog-post.php`

Fiel a la captura de la nota:
- **Hero** superior: banda con forma/onda azul (decorativa) — puede ser un fondo simple con la curva.
- **Título** centrado (navy, grande) + **fecha** centrada debajo.
- **Cuerpo** (`body_html`): contenedor de contenido con tipografía legible (Poppins), ancho de lectura
  cómodo (~720px), estilos para `<h3>`, `<p>`, `<ol>/<ul>`, `<strong>`. Imprimir el HTML tal cual
  (viene sanitizado del admin / seed).
- **"Comparte esta nota:"** con íconos de LinkedIn, correo, Threads y X (enlaces de compartir por URL;
  usar los share links estándar con la URL de la nota). No requiere librería.
- **"Nota Siguiente"** (link a la siguiente nota por fecha; si es la única, ocultar o deshabilitar).
- **"Notas relacionadas"**: `PostRepository::related($post['id'], 3)` (otras publicadas). Mismas
  tarjetas del index.
- **"Suscríbete a nuestro newsletter"**: bloque con campos `Nombre` + `Email` + botón **"Suscribir"**.
  En este hito el submit puede ser stub (la lógica va en Hito 8). Marca `TODO`.
- Bloque de contacto reutilizable **no** es obligatorio aquí (el actual usa newsletter); seguir la
  referencia (footer normal después del newsletter).

## Detalles
- `<title>` index: "Blog — Hidrocinco". Detalle: `"<Título nota> — Hidrocinco"`, meta description =
  `excerpt`.
- Escapar `title`, `excerpt`, fecha con `e()`; `body_html` se imprime como HTML confiable.
- Enlace "Blog" del header ya apunta a `/blog`.

## Restricciones (SiteGround)
- PHP puro; formateo de fecha en español con helper propio (no depender de `IntlDateFormatter` ni
  `setlocale`, que pueden no estar configurados en el server). Nada de Node.

## Criterios de aceptación (DoD)

1. `/blog` muestra el grid con la(s) nota(s) publicada(s) desde la DB, con fecha en español correcta.
2. La nota de ejemplo se ve completa y fiel en `/blog/guia-de-mantenimiento-sistemas-hidraulicos-en-edificios`
   (intro destacada + secciones numeradas + cierre).
3. "Comparte esta nota" genera enlaces de compartir válidos con la URL de la nota.
4. "Notas relacionadas" y "Nota Siguiente" funcionan (o se ocultan con gracia si solo hay 1 nota).
5. Bloque newsletter presente (submit stub con `TODO`).
6. Borradores (`status='draft'`) **no** aparecen en index ni son accesibles por URL (404).
7. Responsive 360 / 768 / 1280: grid colapsa a 1 columna en móvil; cuerpo de nota legible.

## Pasos de verificación local

```bash
php -S localhost:8000 -t public
# /blog → tarjeta(s) con fecha "enero 15, 2024"
# /blog/guia-de-mantenimiento-sistemas-hidraulicos-en-edificios → nota completa
# Crear temporalmente una nota draft en DB → confirmar que NO aparece y su URL da 404
```

---

## Estado y auditoría

- **Estado:** ⬜ Pendiente
- **Rama:** `feature/h4-blog`
- **Notas de auditoría:** _(las completa Claude al revisar)_
