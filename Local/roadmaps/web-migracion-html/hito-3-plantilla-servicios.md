# Hito 3 — Plantilla de Servicios (detalle dinámico + redirects SEO)

> **Para Codex.** Prompt autocontenido. Rama **desde `develop`** (`feature/h3-servicios`), hasta la
> DoD, verificación local, **sin PR**. Requiere Hitos 0–2 en `develop`.

## Objetivo

Crear la **plantilla de detalle de servicio** que renderiza cualquier servicio a partir de sus
bloques (`content_json`), de modo que los 7 servicios actuales se muestren desde la DB y **futuros
servicios creados en el admin (Hito 6) funcionen sin tocar código**. Incluye los redirects 301 de los
slugs antiguos para preservar SEO.

## Referencia visual

Capturas de cada servicio en `Local/archivos de contexto/01_Web Actual/` (pozos, lavado de estanques,
PTAS, sala de calderas, taller, limpia fosas, sala de bombas) y el sitio en vivo. Todas comparten
patrón: **hero** (banda azul con ícono + título del servicio sobre imagen) → **bloques de contenido**
(intro con imagen lateral, features, listas) → **bloque de contacto reutilizable** → footer.

## Ruta y controlador

- Ruta: `GET /servicios/{slug}` → `ServiceController@show`.
- `show($slug)`: `ServiceRepository::findBySlug($slug)`; si no existe o no publicado → 404.
- Decodifica `content_json` y pasa los bloques a la vista `views/pages/service.php`.
- `<title>`: `"<Título del servicio> — Hidrocinco"`. Meta description: primer párrafo del primer
  bloque (truncado).

## Redirects 301 (SEO)

Los slugs actuales son planos (`https://hidrocinco.cl/pozos-profundos/`). Para no perder
posicionamiento, agregar **redirect 301** de cada slug plano a la nueva ruta:
`/pozos-profundos` → `/servicios/pozos-profundos` (y los 7). Implementar en el router (o `.htaccess`),
de forma **data-driven**: si el path coincide con el `slug` de un servicio existente, 301 a
`/servicios/<slug>`. Así, servicios nuevos creados en el admin también obtienen su alias.

## Plantilla `views/pages/service.php`

### Hero del servicio
- Banda con imagen de fondo (`services.hero_image` o placeholder) + overlay azul.
- Ícono del servicio (grande, blanco) arriba del **título** (`services.title`) en blanco, grande.
- Alto moderado (no tan alto como el hero del home).

### Render de bloques (`views/partials/service-blocks/` o un switch en la vista)
Iterar `content_json` y renderizar según `type`:
- **`intro`**: layout 2 columnas → izquierda título (`--color-navy`, grande) + párrafos (el primer
  párrafo puede ir destacado/darker si viene marcado); derecha imagen (`block.image` o placeholder)
  con esquinas redondeadas. En móvil apila.
- **`feature`**: banda de fondo claro (`--color-bg`); si `decor:true`, mostrar la **imagen decorativa
  de gota** de agua a la izquierda (asset `assets/img/decor-gota.svg`/png, como en el sitio actual);
  a la derecha título + párrafos.
- **`list`**: título + `intro` opcional + `<ul>` de `items` con viñetas estilizadas (color navy/verde).
- **`richtext`**: imprimir `block.html` (ya sanitizado en el admin) tal cual dentro de un contenedor
  de contenido.
- Escapar textos con `e()` salvo `richtext.html` (que es HTML confiable generado en el admin).

### Bloque de contacto
- Incluir `views/partials/contact-block.php` (creado en Hito 2) al final, antes del footer.

## Estilos
- Reutiliza tokens y estilos globales. Añade estilos de la plantilla de servicio (hero, bloques,
  listas, imagen decorativa) en `main.css` o `service.css`.
- Consistencia entre servicios simples (Pozos, Limpia Fosas: solo `intro`) y complejos (PTAS, Taller:
  varios `list`/`feature`). La plantilla debe verse bien en ambos extremos.

## Restricciones (SiteGround)
- PHP puro. Redirects 301 correctos (status 301, `Location`). Nada de Node.

## Criterios de aceptación (DoD)

1. Los **7 servicios** renderizan correctamente en `/servicios/<slug>` con su contenido real desde la
   DB, cada uno fiel a su captura (incluyendo listas de PTAS y Taller).
2. La plantilla es **genérica**: si se agrega un servicio nuevo en la DB con bloques válidos, se
   renderiza sin cambios de código.
3. Redirect **301** de los 7 slugs planos antiguos a `/servicios/<slug>` (verificable con
   `curl -I`).
4. Slug inexistente o no publicado → 404.
5. Bloque de contacto presente en cada detalle.
6. Responsive 360 / 768 / 1280 sin scroll horizontal; bloques `intro`/`feature` apilan en móvil.
7. `richtext` se imprime como HTML; el resto escapado con `e()`.

## Pasos de verificación local

```bash
php -S localhost:8000 -t public
# Recorrer los 7:
#  /servicios/pozos-profundos
#  /servicios/lavado-de-estanques
#  /servicios/plantas-de-tratamientos-de-aguas-servidas-ptas
#  /servicios/sala-de-calderas
#  /servicios/taller-y-servicio-tecnico
#  /servicios/limpia-fosas
#  /servicios/sala-de-bombas
curl -I http://localhost:8000/pozos-profundos   # espera 301 → /servicios/pozos-profundos
```

---

## Estado y auditoría

- **Estado:** ⬜ Pendiente
- **Rama:** `feature/h3-servicios`
- **Notas de auditoría:** _(las completa Claude al revisar)_
