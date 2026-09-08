# Hito 2 — Home / Landing

> **Para Codex.** Prompt autocontenido. Rama **desde `develop`** (`feature/h2-home`), hasta la DoD,
> verificación local, **sin PR**. Requiere Hitos 0 y 1 en `develop`.

## Objetivo

Construir la **home** fiel al diseño actual, consumiendo los 7 servicios desde la base de datos
(Hito 1). Secciones: hero con grid de tarjetas de servicio, "Somos Hidrocinco" con video, "Visión",
y el bloque de contacto reutilizable. Totalmente responsive.

## Referencia visual

`Local/archivos de contexto/01_Web Actual/screencapture-hidrocinco-cl-2026-09-07-...png` y el sitio
en vivo `https://hidrocinco.cl/`. Respetar colores/tipografías del sistema de diseño (ver README del
módulo).

## Secciones (de arriba hacia abajo)

### 1. Hero (`views/pages/home.php` → sección hero)
- Fondo: imagen submarina azul con degradado navy→azul (`--color-navy`/`--color-blue`). Usar una
  imagen de fondo (placeholder en `assets/img/hero-agua.jpg`) con overlay para legibilidad.
- Izquierda: título grande en blanco **"Expertos en sistemas de agua"**, subtítulo **"Con más de 40
  años de experiencia"**, y botón pill con degradado azul-verde **"Sobre nosotros"** (ancla a
  `#nosotros`).
- Derecha: **grid de 7 tarjetas de servicio** (2 filas: 3 arriba, 4 abajo, como el actual — que en
  desktop se vea balanceado; en móvil, 1–2 columnas). Cada tarjeta:
  - Fondo navy translúcido (`rgba(0,47,98,.85)`), esquinas redondeadas.
  - Ícono (SVG) arriba, **título del servicio** centrado, y abajo un link **"Ver más →"**.
  - Toda la tarjeta enlaza a `/servicios/<slug>`.
  - **Se generan desde `ServiceRepository::allPublishedOrdered()`** (no hardcodear los 7). El ícono
    sale de `services.icon`.
- Responsive: en móvil el hero apila (texto arriba, tarjetas debajo en grid de 2 o 1 columna).

### 2. "Somos Hidrocinco" (`#nosotros`)
- Dos columnas: a la izquierda un **video/imagen** (placeholder con botón play — el sitio usa un
  video; puede ser un `<video>` o un thumbnail con overlay play que abra un modal/enlace). A la
  derecha:
  - Eyebrow verde "Sobre nosotros".
  - Título **"Somos Hidro<span verde>cinco</span>"** (la palabra "cinco" en verde `--color-green-dark`).
  - Párrafo: "Somos una empresa de servicio, con más de 40 años de experiencia en el Mercado de Soluciones Hidráulicas. Mantenemos, Reparamos y Mejoramos los sistemas de Extracción, Acumulación, Impulsión y Tratamiento de AGUA en los sectores rurales y urbanos. Buscamos asegurar la continuidad operacional de estos sistemas con respuesta 24/7"
- Responsive: en móvil apila (imagen arriba, texto abajo).

### 3. "Visión" (`#vision`)
- Banda de fondo azul con imagen submarina (similar al hero, más sobria).
- Centrado, en blanco: título **"Visión"** y párrafo:
  "Ser una empresa LÍDER a nivel Nacional en el mercado de Soluciones Hidráulicas, utilizando herramientas tecnológicas que nos permitan predecir el buen funcionamiento de los sistemas Hidráulicos (extracción, acumulación, impulsión, tratamiento y riego)."

### 4. Bloque de contacto reutilizable (`views/partials/contact-block.php`)
- **Crear este partial aquí** (se reutiliza en cada servicio en Hito 3).
- Dos columnas:
  - Izquierda: panel azul (`--color-navy`/imagen submarina) con **"¡Hablemos!"** (la "!" en verde) y
    subtítulo "Escríbenos y nos comunicaremos contigo".
  - Derecha: card clara "Formulario de **contacto**" (palabra "contacto" en verde) con campos:
    `Nombre*`, `Teléfono*`, `Correo electrónico*`, `Mensaje*` (textarea), widget **reCAPTCHA**
    (placeholder visual en este hito — la lógica real llega en Hito 8), botón **"Enviar"** (navy).
  - Los asteriscos `*` en rojo. Estilos de inputs con línea inferior (underline) como el actual.
- En este hito el form puede **no** enviar todavía (o hacer `POST` a una ruta stub); la funcionalidad
  completa es Hito 8. Marca con un `TODO` claro.
- Responsive: apila en móvil (panel arriba, formulario abajo).

## Detalles de implementación

- `HomeController@index` obtiene los servicios (`allPublishedOrdered()`) y pasa a la vista.
- Reutiliza el layout base y partials del Hito 0. Agrega estilos de home en `main.css` (o un
  `home.css` incluido solo en la home si prefieres modularizar; mantenlo simple).
- Anclas: el botón "Sobre nosotros" y los ítems de nav `Nosotros`/`Vision` scrollean a `#nosotros` /
  `#vision`. Añade `scroll-behavior:smooth` y `scroll-margin-top` para compensar el header sticky.
- `<title>`: "Hidrocinco — Expertos en sistemas de agua". Meta description con el párrafo "Somos...".
- Todas las salidas dinámicas con `e()`.

## Restricciones (SiteGround)

- PHP puro; sin dependencias JS externas salvo el placeholder de reCAPTCHA (que en Hito 8 será el
  script oficial de Google, permitido en runtime del navegador). Nada de build Node.

## Criterios de aceptación (DoD)

1. `/` renderiza las 4 secciones fieles a la referencia, con los 7 servicios saliendo de la DB.
2. Cada tarjeta del hero enlaza a `/servicios/<slug>` correcto.
3. Anclas `#nosotros` y `#vision` funcionan desde la nav con offset correcto (no tapadas por el
   header sticky).
4. `contact-block.php` existe como partial reutilizable y se ve fiel (aunque el envío sea stub).
5. Responsive verificado en 360 / 768 / 1280: sin scroll horizontal; hero apila correctamente; grid
   de tarjetas se adapta.
6. Sin errores PHP/JS en consola.

## Pasos de verificación local

```bash
php -S localhost:8000 -t public
# / → revisar hero + tarjetas (deben ser 7, desde DB), Nosotros, Visión, contacto
# Click en una tarjeta → debe apuntar a /servicios/<slug> (aunque 404 hasta Hito 3)
# Probar responsive 360/768/1280
```

---

## Estado y auditoría

- **Estado:** ⬜ Pendiente
- **Rama:** `feature/h2-home`
- **Notas de auditoría:** _(las completa Claude al revisar)_
