# Hito 0 — Fundaciones (estructura, router, layout base, tokens de diseño)

> **Para Codex.** Prompt autocontenido. Lee todo antes de empezar. Metodología: crea **rama nueva
> desde `develop`** (`feature/h0-fundaciones`), implementa hasta la DoD, **verifica en preview local**
> y **NO abras PR**.

## Objetivo

Levantar el esqueleto del sitio Hidrocinco en **PHP puro + HTML/CSS/JS**, listo para SiteGround
GoGeek: estructura de carpetas, front controller con router, layout base con las partes globales
(barra superior, header/nav, footer, botón flotante), sistema de tokens de diseño en CSS y fuentes
self-hosteadas. Al final debe verse el "cascarón" con header y footer correctos y una home
placeholder, corriendo en local.

## Contexto del proyecto

Migración de `hidrocinco.cl` (hoy WordPress + Elementor) a un sitio propio y liviano. **Destino:
SiteGround GoGeek** → solo **PHP + MySQL**; **sin Node.js server-side**, sin page builders, sin
frameworks pesados. Este hito NO toca base de datos todavía (viene en Hito 1); usa datos placeholder.

## Sistema de diseño (usar exactamente estos valores)

Colores (definir como CSS custom properties en `:root`):
```
--color-navy:        #002F62;  /* primario: títulos, botones, barra superior */
--color-blue:        #1D4387;  /* degradados hero, azul medio */
--color-blue-accent: #4054B2;  /* acento azul-violeta */
--color-green:       #61CE70;  /* verde brillante: hover, acentos, "!" */
--color-green-dark:  #23A455;  /* verde logo "CINCO", palabra "contacto", checks */
--color-text:        #7A7A7A;  /* cuerpo */
--color-text-dark:   #2F2F2F;
--color-bg:          #FAFAFA;
--color-white:       #FFFFFF;
```
Tipografías (self-hosted; descargar los `.woff2` y servirlos desde `public/assets/fonts/`, con
`@font-face`; **no** enlazar a Google Fonts en runtime):
- **Poppins** (400, 500, 600, 700) → fuente principal (títulos y cuerpo).
- **Roboto Slab** (400, 600, 700) → secundaria.
- **Roboto** (400, 500) → acento.
Definir variables: `--font-primary: "Poppins", system-ui, sans-serif;`
`--font-secondary: "Roboto Slab", Georgia, serif;` `--font-accent: "Roboto", system-ui, sans-serif;`

## Estructura de carpetas a crear

```
public/
  index.php                 # front controller
  .htaccess                 # rewrite todo a index.php + headers básicos
  assets/
    css/main.css            # reset + tokens + estilos globales (topbar, header, footer, botones)
    js/main.js              # nav móvil (toggle), utilidades
    fonts/                  # .woff2 self-hosted + @font-face en main.css (o fonts.css)
    img/                    # logo, íconos, imágenes globales (placeholders por ahora)
  uploads/                  # vacío (con .gitkeep); en .gitignore su contenido
src/
  Router.php                # router mínimo por patrón de ruta
  helpers.php               # e($s) escape, url($path), asset($path), view($name, $data)
  Controllers/
    HomeController.php       # render home placeholder
views/
  layouts/base.php          # doctype, <head>, incluye partials + $content
  partials/
    topbar.php
    header.php
    footer.php
    chat-button.php
  pages/
    home.php                 # placeholder ("En construcción" ok)
    404.php
config.example.php          # BASE_URL, DB_* (placeholders), MAIL_*, RECAPTCHA_* (vacíos aún)
.gitignore                  # config.php, public/uploads/*, vendor/, .DS_Store
README.md                   # cómo correr en local
```

> `config.php` real (copia de `config.example.php`) **no** se commitea. En este hito solo se usa
> `BASE_URL`.

## Requisitos de implementación

### Router y front controller
- `public/index.php`: carga `config.php` (o `config.example.php` como fallback en dev),
  `src/helpers.php`, registra rutas y despacha.
- `src/Router.php`: soporta rutas exactas y con parámetro (`/servicios/{slug}`, `/blog/{slug}`).
  Método `get($pattern, $handler)` y `dispatch($uri)`. Si no hay match → 404 (render `pages/404.php`
  con status 404).
- Rutas registradas en este hito: `/` → `HomeController@index`. (Las demás se agregan en sus hitos.)
- `.htaccess`: si el archivo/directorio existe lo sirve directo (assets); si no, reescribe a
  `index.php`. Incluir cabeceras básicas y compresión/expires si están disponibles. Ejemplo base:
  ```apache
  <IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} -f [OR]
    RewriteCond %{REQUEST_FILENAME} -d
    RewriteRule ^ - [L]
    RewriteRule ^ index.php [L]
  </IfModule>
  ```

### Helpers
- `e($string)` → `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`. **Usar en toda salida dinámica.**
- `url($path='')` → concatena `BASE_URL` + path (rutas absolutas correctas en local y prod).
- `asset($path)` → `url('/assets/'.$path)`.
- `view($name, $data=[])` → extrae `$data`, hace `require` de `views/pages/$name.php` dentro de
  `views/layouts/base.php` (capturando el contenido en `$content`).

### Layout base y partials (fieles al sitio actual)
- **`<head>`**: charset UTF-8, viewport responsive, `<title>` por página (variable `$title` con
  fallback "Hidrocinco — Expertos en agua"), meta description (variable), favicon placeholder, link a
  `main.css`. `lang="es"`.
- **Barra superior** (`topbar.php`): fondo navy `--color-navy`, texto blanco pequeño, alineado a la
  derecha en desktop: `Tutorial Fractal` · `Acceso Clientes` · `⚡ Emergencias 24/7`. En móvil puede
  colapsar/ocultar los dos primeros y mantener "Emergencias 24/7".
- **Header** (`header.php`): blanco, **sticky** al hacer scroll. Izquierda: logo Hidrocinco (usar un
  placeholder SVG/PNG en `assets/img/logo.svg` — el logo real se reemplaza luego). Derecha: nav
  `Inicio` (→ `/`), `Servicios` (→ `#`/dropdown más adelante), `Nosotros` (→ `/#nosotros`),
  `Vision` (→ `/#vision`), `Blog` (→ `/blog`), y botón **`Contacto`** como pill con borde navy
  (→ `/contacto`). En móvil: menú hamburguesa que despliega la nav (toggle en `main.js`).
- **Botón flotante** (`chat-button.php`): pill abajo-derecha "Contáctanos" enlazando a WhatsApp
  (`https://wa.me/56225561859`) o a `/contacto`. Ícono simple. **Sin plugin Chaty.**
- **Footer** (`footer.php`): fondo `--color-bg`, 4 columnas responsivas:
  1. Logo Hidrocinco.
  2. **Oficinas comerciales** — "Av. Portugal 1797, Santiago, Chile."
  3. **Informaciones de contacto** — ✉ `hidrocinco@hidrocinco.cl` · ☎ `(+562) 2556 1859`.
  4. **Redes sociales** — íconos Instagram y LinkedIn (enlaces placeholder `#`).

### CSS global (`main.css`)
- Reset ligero (box-sizing border-box, márgenes 0, `img{max-width:100%;display:block}`).
- Tokens en `:root` (colores + fuentes + un par de escalas de espaciado y radios).
- Contenedor central `.container { max-width: 1200px; margin-inline:auto; padding-inline: 20px; }`.
- Estilos de topbar, header (sticky + sombra al hacer scroll opcional), nav, botón pill `.btn`
  (variante navy sólido y outline), footer, botón flotante.
- **Mobile-first y responsivo**: breakpoints `@media (max-width:768px)` y `(max-width:480px)`. Nav
  colapsa a hamburguesa. Sin scroll horizontal en ningún ancho.
- Tipografía base: cuerpo Poppins 16px color `--color-text`; títulos Poppins 600/700 color
  `--color-navy`.

### JS (`main.js`)
- Toggle del menú móvil (abrir/cerrar, aria-expanded).
- (Opcional) sombra del header al hacer scroll.
- Vanilla JS, sin librerías.

### Home placeholder (`pages/home.php`)
- Basta un `<section class="container">` con un `<h1>` "Hidrocinco — Expertos en sistemas de agua" y
  un párrafo "Sitio en construcción". Lo importante de este hito es el cascarón (topbar/header/footer)
  correcto y responsivo. La home real llega en Hito 2.

## Restricciones (SiteGround GoGeek)

- Solo **PHP 8.x + Apache/.htaccess**. Nada de Node.js server-side, ni procesos persistentes.
- No introducir un motor de plantillas externo (Twig/Blade) ni Composer con dependencias pesadas;
  PHP plano con `include`/`require`. (Composer solo se permitirá más adelante si algún hito lo
  requiere y es compatible; en este hito, cero dependencias.)
- Fuentes **self-hosted** (no llamadas a Google en runtime).

## Criterios de aceptación (DoD)

1. `php -S localhost:8000 -t public` levanta el sitio sin errores.
2. `/` renderiza layout completo: barra superior, header con nav + botón Contacto, home placeholder,
   footer con las 4 columnas y botón flotante.
3. Los tokens de color y las 3 familias tipográficas se aplican correctamente (fuentes self-hosted
   cargan, verificable en la pestaña de red sin peticiones a fonts.googleapis.com).
4. Responsivo: en ≤768px la nav colapsa a hamburguesa funcional; sin scroll horizontal en 360px,
   768px, 1280px.
5. Una URL inexistente (`/no-existe`) devuelve la página 404 con status HTTP 404.
6. `config.php` y `public/uploads/*` están en `.gitignore`; no hay secretos commiteados.
7. `README.md` explica cómo copiar `config.example.php` → `config.php` y correr en local.

## Pasos de verificación local

```bash
cp config.example.php config.php   # ajustar BASE_URL=http://localhost:8000
php -S localhost:8000 -t public
# Abrir http://localhost:8000  → revisar header/footer/responsive
# Abrir http://localhost:8000/no-existe → 404
```
Revisar en DevTools: (a) sin requests a Google Fonts; (b) sin errores JS; (c) reflow correcto en
móvil (DevTools responsive 360/768/1280).

## Notas de implementación

- Deja los enlaces de nav que aún no existen apuntando a `#` o al ancla correspondiente; se
  completan en sus hitos.
- El logo y las imágenes reales se incorporan cuando estén disponibles; usa placeholders limpios
  (un SVG de gota azul/verde sirve como marcador).

---

## Estado y auditoría

- **Estado:** ⬜ Pendiente
- **Rama:** `feature/h0-fundaciones`
- **Notas de auditoría:** _(las completa Claude al revisar)_
