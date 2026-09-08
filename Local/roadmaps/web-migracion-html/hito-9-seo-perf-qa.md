# Hito 9 — SEO, performance, responsividad final y QA

> **Para Codex.** Prompt autocontenido. Rama **desde `develop`** (`feature/h9-seo-qa`), hasta la DoD,
> verificación local, **sin PR**. Requiere Hitos 0–8 en `develop`.

## Objetivo

Pulir el sitio para producción: metadatos/SEO, datos estructurados, `sitemap.xml`, `robots.txt`,
favicon, página 404 fiel, optimización de imágenes y caché, y una pasada final de responsividad y
accesibilidad en todas las páginas.

## SEO y metadatos

- **`<title>` y `<meta name="description">`** únicos por página (home, cada servicio, blog index,
  cada nota, contacto). Ya parcialmente en hitos previos → verificar/completar.
- **Open Graph + Twitter Cards** en el layout base: `og:title`, `og:description`, `og:image`
  (imagen destacada de la nota / hero del servicio / imagen por defecto), `og:type`, `og:url`,
  `twitter:card=summary_large_image`. Variables por página con fallback global.
- **Canonical** por página (`<link rel="canonical">`) con la URL limpia.
- **Datos estructurados (JSON-LD)**:
  - `Organization` global (Hidrocinco, logo, dirección Av. Portugal 1797 Santiago, teléfono
    +562 2556 1859, email, sameAs redes).
  - `Service` en cada detalle de servicio.
  - `BlogPosting` en cada nota (headline, datePublished, image, author=Organization).
- **`robots.txt`** (`public/robots.txt`): permitir todo salvo `/admin`; enlazar `Sitemap:`.
- **`sitemap.xml`**: generado dinámicamente (`GET /sitemap.xml` → controlador) con home, los 7
  servicios (rutas `/servicios/<slug>`), blog index y notas publicadas, con `lastmod`. Debe
  actualizarse solo al agregar servicios/notas.
- Mantener los **redirects 301** de los slugs antiguos (Hito 3) — verificar que siguen activos.
- Idioma `lang="es"`, `hreflang` no necesario (sitio monolingüe).

## Favicon y assets de marca

- Favicon (`.ico` + PNG 32/180 apple-touch-icon) desde el logo de Hidrocinco. `site.webmanifest`
  básico opcional.
- Imagen OG por defecto (`assets/img/og-default.jpg`) con marca.

## Performance (dentro de lo que permite GoGeek)

- **Imágenes**: convertir/servir en **WebP** con fallback; dimensionar correctamente; `loading="lazy"`
  en imágenes bajo el fold; `width`/`height` para evitar CLS.
- **CSS/JS**: minificar de forma estática (archivos `.min.css`/`.min.js` versionados; sin toolchain
  en el server). Combinar donde tenga sentido. Cargar JS con `defer`.
- **Fuentes**: self-hosted (ya), `font-display: swap`, precargar la principal (Poppins 400/600).
- **`.htaccess`**: compresión GZIP/Brotli (si el módulo está disponible), `Expires`/`Cache-Control`
  para assets estáticos (imágenes/fuentes/css/js con TTL largo + fingerprint/versión en el nombre o
  querystring), y cabeceras de seguridad: `X-Content-Type-Options: nosniff`,
  `X-Frame-Options: SAMEORIGIN` (o CSP), `Referrer-Policy: strict-origin-when-cross-origin`.
- Aprovechable en prod: SG Optimizer / dynamic cache de SiteGround (documentar en Hito 10; no
  configurable desde el repo).

## Página 404

- `views/pages/404.php` con diseño de marca (header/footer normales), mensaje amable y enlaces a home
  / servicios / blog. Status HTTP 404.

## QA responsivo y accesibilidad (todas las páginas)

- Revisar home, los 7 servicios, blog index, nota, admin (login/dashboard/listas/forms) en **360,
  768, 1024, 1280**: sin scroll horizontal, tap targets adecuados, nav móvil OK, formularios usables.
- Accesibilidad básica: contraste suficiente (navy/verde sobre blanco OK; revisar texto sobre
  imágenes del hero — añadir overlay/sombra), `alt` en imágenes, labels en inputs, foco visible,
  jerarquía de encabezados correcta (un `<h1>` por página), `aria` en el menú móvil.
- Verificar que no haya llamadas a recursos externos innecesarios (Google Fonts, CDNs) salvo el script
  de reCAPTCHA.

## Criterios de aceptación (DoD)

1. Cada página tiene `title`, `description`, OG/Twitter y canonical correctos y únicos.
2. JSON-LD válido (Organization + Service + BlogPosting) — validable con el test de resultados
   enriquecidos / validador de schema.
3. `/sitemap.xml` lista home, 7 servicios, blog y notas publicadas, y se regenera al agregar
   contenido; `robots.txt` correcto y bloquea `/admin`.
4. Favicon y OG por defecto presentes.
5. Imágenes en WebP con lazy-load y dimensiones; sin CLS notorio.
6. `.htaccess` aplica compresión, cache de estáticos y cabeceras de seguridad.
7. 404 con diseño de marca y status 404.
8. QA responsive/accesibilidad aprobado en 360/768/1024/1280; Lighthouse (móvil) con Performance,
   SEO, Accessibility y Best Practices en verde (objetivo ≥90 donde sea realista en local).

## Pasos de verificación local

```bash
php -S localhost:8000 -t public
curl -s http://localhost:8000/sitemap.xml | head       # revisar URLs
curl -s http://localhost:8000/robots.txt               # Disallow: /admin + Sitemap:
curl -I http://localhost:8000/pozos-profundos          # 301 sigue activo
# Lighthouse (Chrome DevTools) en / , /servicios/pozos-profundos , /blog , una nota
# Validar JSON-LD copiándolo al validador de schema.org / Rich Results Test
# Revisar responsive 360/768/1024/1280
```

---

## Estado y auditoría

- **Estado:** ⬜ Pendiente
- **Rama:** `feature/h9-seo-qa`
- **Notas de auditoría:** _(las completa Claude al revisar)_
