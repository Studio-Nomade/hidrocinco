# Hito 11 — Recursos gráficos reales + ajuste tipográfico + página AppsCinco

> **Para Codex.** Prompt autocontenido. Rama **desde `develop`**
> (`feature/h11-recursos-tipografia-appscinco`), hasta la DoD, verificación local, **sin PR**.
> Requiere Hitos 0–10 en `develop`. **No** tocar la rama `feature/h10-deploy` (está en auditoría).

## Objetivo

Tres frentes, en un mismo hito de refinamiento de diseño:
1. **Reemplazar los placeholders por los recursos gráficos reales** (logos, favicon, fondos, fotos de
   servicios, gota de marca, portada de video, portada de blog) en su lugar correcto.
2. **Ajustar la tipografía**: cargar las fuentes reales self-hosted y **reducir los tamaños**
   (actualmente se ven grandes) para acercarse al sitio actual.
3. **Crear la subpágina AppsCinco** (plataforma de Hidrocinco) en HTML/CSS según la maqueta, e
   incorporarla al sitio con su ítem de navegación.

Fuentes de verdad:
- Recursos: `Local/archivos de contexto/02_Recursos Gráficos/`
- CSS/fuentes de referencia: `Local/archivos de contexto/03_Elementos de diseño y estilos/`
- Maqueta AppsCinco: PDF entregado (una sola página) — el detalle textual está transcrito abajo.
- Referencia visual general: capturas en `Local/archivos de contexto/01_Web Actual/` y `hidrocinco.cl`.

---

## Parte A — Fuentes reales self-hosted

Hoy `public/assets/css/main.css` usa woff2 **placeholder** (`poppins-regular.woff2`, etc.). Reemplazar
por las fuentes reales que están en `03_Elementos de diseño y estilos/google-fonts/fonts/` (formato
woff2 con nombres hasheados de Google) + sus `@font-face` en
`03_.../google-fonts/css/{poppins,roboto,robotoslab}.css`.

- Copiar a `public/assets/fonts/` los woff2 necesarios de **Poppins** (400, 500, 600, 700), **Roboto**
  (400, 500) y **Roboto Slab** (400, 600, 700). Renombrarlos a nombres limpios
  (`poppins-400.woff2`, `poppins-600.woff2`, …) o conservar el hash; lo importante es que carguen
  **local** (nada de fonts.googleapis.com en runtime).
- Reescribir los `@font-face` en `main.css` apuntando a los archivos reales, con `font-display:swap`.
  Incluir los pesos que el diseño realmente usa (400 cuerpo, 500 nav/labels, 600/700 títulos).
- Mantener las variables `--font-primary: "Poppins"`, `--font-secondary: "Roboto Slab"`,
  `--font-accent: "Roboto"`. (Montserrat/Open Sans del kit original **no** se usan en el front nuevo;
  no cargarlas salvo que se detecte un uso real.)
- Precargar la principal en `<head>` (`<link rel="preload" as="font" ... crossorigin>` para
  `poppins-400` y `poppins-600`).

## Parte B — Ajuste del sistema tipográfico (reducir tamaños)

El feedback es que **los textos se ven grandes**. Bajar la escala tipográfica manteniendo la jerarquía.
Valores objetivo en `main.css` (ajustar los clamp/rem existentes a estos máximos, ~15–25% menos):

| Elemento | Actual | **Nuevo** |
|---|---|---|
| `body` | `16px/1.65` | `15px/1.6` (o `16px/1.55`) |
| Home hero `h1` | `clamp(2.6rem,5vw,4.25rem)` | `clamp(2.1rem,4.2vw,3.25rem)` |
| Home hero copy `>p` | `1.55rem` | `1.15rem` (móvil `1.05rem`) |
| Service hero `h1` | `clamp(2.5rem,6vw,4.8rem)` | `clamp(2rem,4.5vw,3.2rem)` |
| `page-heading h1` (Blog/Contacto) | `clamp(2.8rem,6vw,5rem)` | `clamp(2.2rem,4.5vw,3.3rem)` |
| `about-copy h2` | `clamp(2rem,4vw,3.3rem)` | `clamp(1.7rem,3vw,2.4rem)` |
| `service-block h2` | `clamp(2rem,4vw,3.35rem)` | `clamp(1.6rem,3vw,2.3rem)` |
| `vision-section h2` | `2.5rem` | `2rem` |
| `related-posts h2` / `newsletter h2` | `2.5rem` / `clamp(...,3rem)` | `2rem` / `clamp(1.7rem,3vw,2.2rem)` |
| `blog-detail__header h1` | `clamp(2.2rem,5vw,4.3rem)` | `clamp(1.9rem,4vw,3rem)` |
| `service-card h2` | `1rem` | `.95rem` |
| `post-card h2` | `1.35rem` | `1.15rem` |
| `service-block .lead` | `1.15rem` | `1.05rem` |
| `post-body` / `>p:first-child` | `1.05rem` / `1.15rem` | `1rem` / `1.1rem` |

- Revisar que en móvil (≤480) los títulos no queden apretados; ajustar mínimos de los clamp.
- **Criterio:** comparar contra las capturas de `01_Web Actual/` — el objetivo es acercarse a la
  densidad de texto del sitio actual, sin romper el layout.

## Parte C — Reemplazo de recursos gráficos

Copiar los archivos elegidos desde `02_Recursos Gráficos/…` a `public/assets/img/` (o subcarpetas) y
actualizar las referencias en vistas/CSS/seed. Usar las variantes responsive disponibles (`-768x…`,
`-1024x…`, master) para `srcset` donde ya exista. Convertir a `.png`/`.jpg` cuando el destino lo exija
(p. ej. favicon).

### C.1 Logos e isologo
| Destino en código | Archivo fuente (`02_.../Recursos Visuales` o `/Logotipos`) |
|---|---|
| `assets/img/logo.svg` (header) | `logo-navbar.svg` |
| `assets/img/logo-footer.svg` (footer — actualizar `footer.php`) | `logo-hidrocinco-footer.svg` |
| `assets/img/logo-mark.svg` (OG default en `helpers.php`, admin) | `hidrocinco-logo-color.svg` |
| Logo **blanco** (para hero AppsCinco / fondos oscuros) | `Logotipos/Logotipo_sin-bajada@blanco.webp` |
| Gota de marca (decor) → `assets/img/decor-gota.svg` | `elemento-de-marca.svg` (o `Elemento-de-marca-gota.svg`) |

### C.2 Favicon (base.php ya referencia `favicon.ico` + `favicon-32.png`)
| Destino | Fuente |
|---|---|
| `assets/img/favicon-32.png` | `cropped-Favicon_Isologo@completo-32x32.webp` (convertir a PNG) |
| `assets/img/apple-touch-icon.png` (180) — añadir `<link>` | `cropped-Favicon_Isologo@completo-180x180.webp` (→PNG) |
| `favicon.ico` (raíz o assets) | generar desde `Favicon_Isologo@completo.webp` (16/32/48) |

### C.3 Fondos de secciones
| Destino en código | Fuente |
|---|---|
| Home hero `--img/hero-agua.jpg` | `banner-hero-hidrocinco.jpg` (+ `-768x432`, `-1024x576`) |
| Vision `--img/fondo-agua.webp` | `background-vision-min.jpg` (+ responsive) — ajustar ref/nombre en CSS |
| Contact pitch (usa `hero-agua.jpg`) → nuevo `img/fondo-contacto.webp` | `Fondo_web@completo.webp` |

### C.4 Portada de video (sección "Somos Hidrocinco")
| Destino | Fuente |
|---|---|
| `img/somos-hidrocinco-640.webp` | `Portadas de Video/Portada-video-Hidrocinco-768x433.webp` |
| `img/somos-hidrocinco-1200.webp` | `Portada-video-Hidrocinco-1024x577.webp` (o master) |

### C.5 Imágenes de servicios (hero + foto de bloque intro)
Copiar a `public/assets/img/servicios/` y **actualizar el seed** (`db/seed.php`): campo `hero_image`
(cabecera del detalle) y el `image` del bloque `intro` de cada servicio. Mapeo (verificar contra las
capturas de cada servicio en `01_Web Actual/`):

| Servicio (slug) | Hero (`header_*`) | Foto intro (bloque) |
|---|---|---|
| `pozos-profundos` | `header_pozosprofundos-min.jpg` | `istockphoto-1255149617-2048x2048-1-min.jpg` |
| `lavado-de-estanques` | `header_lavadodeestanques.jpg` | `featured-img-lavado-de-estanques.jpg` |
| `plantas-de-tratamientos-de-aguas-servidas-ptas` | `header_ptas.jpg` | `featured_img_ptas.jpg` |
| `sala-de-calderas` | `header_salacalderas.jpg` | `featured_img_salacalderas.jpg` |
| `taller-y-servicio-tecnico` | `header_serviciotecnico-min.jpg` | `istockphoto-1421886683-1024x1024-1.jpg` |
| `limpia-fosas` | `header_limpiafosas-min.jpg` | `featured_img_limpiafosas-min.jpg` (o `Camion Limpiafosa/DSC_6719-1024x683.jpg`) |
| `sala-de-bombas` | `banner_header_salabombas-min.jpg` | `imagen-sala-bombas.jpg` |

> Tras editar `db/seed.php`, re-ejecutar el seed en local para actualizar las filas
> (`php db/seed.php`). En prod las imágenes se cargan/editan por el admin.

### C.6 Blog
| Destino | Fuente |
|---|---|
| Nota seed "Guía de mantenimiento" `featured_image` → `img/blog/guia-mantenimiento.jpg` | `Portadas de Blog/Blog_Guia-Mantenimiento-1024x1024.jpg` |

> Las demás portadas (`Blog_Futuro`, `Blog_Checklist-Esencial`, `Blog_Fracttal`,
> `Blog_Inteligencia-Artificial`, `Blog_Sostenibilidad`, `Blog_Sistemas-para-el-futuro`) quedan
> disponibles para cuando se carguen esas notas desde el admin; **no** hay que crearlas ahora.

### C.7 Ícono de alarma (barra superior "Emergencias 24/7")
Opcional: usar `Recursos Visuales/mdi_alarm-light.svg` para el ícono de "⚡ Emergencias 24/7" en la
topbar, en vez del emoji.

## Parte D — Subpágina AppsCinco

Nueva página estática (fija, **no** gestionable por admin, igual que Home/Visión). 

- **Ruta:** `GET /appscinco` → nuevo `AppsCincoController@index` + vista `views/pages/appscinco.php`.
- **Navegación:** añadir ítem en `header.php`. La maqueta lo rotula **"Clientes"**; recomendado usar
  **"AppsCinco"** para no confundir con "Acceso Clientes" de la topbar (login externo). Decisión menor
  — dejar `AppsCinco` salvo indicación contraria. Colocarlo entre `Blog` y `Contacto`.
- Reutiliza layout base + `contact-block.php` + footer.

### Estructura y contenido (transcrito de la maqueta, verbatim)

1. **Hero** (banda navy a la izquierda con gota **blanca** + título **"AppsCinco"**, sobre imagen de
   manos con tablet). Imagen hero: `02_.../AppsCinco/Banner.webp` (o `BannerApps.webp` / `Banner-scaled.webp`).

2. **"Operación digital"** (palabra "digital" en negrita/bold):
   - Píldora con degradado verde→teal: **"Supervisamos, analizamos y predecimos el comportamiento de
     tus sistemas en tiempo real."**
   - P1: "Nuestra plataforma no solo monitorea, también conecta con nuestro equipo especializado,
     garantizando una atención personalizada y resolutiva para cada sistema."
   - P2: "Detrás de cada dato y alerta existe un equipo técnico disponible 24/7, preparado para actuar
     de forma inmediata ante cualquier eventualidad."
   - Derecha: **mockup de dashboard** (laptop + teléfono con la plataforma). ⚠️ *Asset pendiente* (ver
     "Recursos a confirmar"). Usar placeholder o `Banner-Digital.webp` mientras tanto.

3. **"Continuidad Operacional"** (fondo claro):
   - Card con **borde degradado** (verde/teal) que contiene:
     - Título "Continuidad **Operacional**".
     - "Concentramos toda la información operacional de los sistemas."
     - "De esta manera contamos con un control en línea y una gestión predictiva de los activos
       hidráulicos."
     - Subtítulo "Sistema de telemetría y software de mantenimiento".
     - Checklist (íconos check verdes): "Anticipamos las fallas de nuestros equipos" · "Registramos las
       asistencias en tiempo real" · "Reportes automáticos para nuestros clientes."
   - Derecha: imagen (persona de traje con teléfono). ⚠️ *Asset pendiente*.

4. **Bloque navy — dos columnas:**
   - **"Funciones principales"** ("principales" en verde). Lista con líder en negrita + detalle:
     - **Monitoreo remoto de parámetros críticos** — *Caudal, presión, nivel de estanque, consumo
       eléctrico, estado de bombas y tableros* (en itálica).
     - **Registro histórico y trazabilidad completa** de cada instalación.
     - **Alertas y notificaciones** automáticas de fallas o desviaciones.
     - **Informes digitales accesibles** desde cualquier dispositivo.
     - **Integración con sistemas de mantenimiento** preventivo y correctivo.
   - **"Beneficios"**. Lista con check:
     - **Mayor disponibilidad y confiabilidad** de los equipos.
     - **Reducción de tiempos de respuesta** ante contingencias.
     - **Optimización** del mantenimiento y **uso eficiente** de recursos.
     - **Transparencia y trazabilidad total** en la operación.
   - Botón degradado teal **"DESCARGA NUESTRO BROCHURE AQUÍ"** + subtexto "Y conoce más información
     acerca de como seguimos innovando en HidroCinco". Enlaza al brochure
     `02_.../AppsCinco/Hidrocinco-Brochure-AppsCinco.pdf` → copiar a `public/assets/docs/` y enlazar
     (`target="_blank"`, `rel="noopener"`).

5. **Bloque de contacto** reutilizable (`contact-block.php`), con el campo "¿En qué podemos ayudar?"
   como en la maqueta (o el mensaje estándar). Igual comportamiento que el resto (Hito 8).

6. **Footer** estándar.

- **SEO:** `<title>` "AppsCinco — Plataforma de Hidrocinco", meta description con la bajada de
  "Operación digital". Añadir a `sitemap.xml` (Hito 9). JSON-LD opcional `WebPage`/`SoftwareApplication`.
- **Responsive:** todas las secciones apilan en móvil; el bloque navy pasa a 1 columna; el hero
  mantiene legibilidad del título sobre la imagen.

## Recursos a confirmar (gaps — reportar, no bloquear)

1. **Íconos monoline blancos de las tarjetas de servicio** (home hero + hero de servicio): no vienen en
   `02_Recursos Gráficos`. Opciones: (a) extraerlos como SVG del sitio en vivo `hidrocinco.cl` para
   fidelidad, o (b) mantener los placeholders actuales. Recomendado (a). Dejar anotado si se hace (a).
2. **AppsCinco: mockup de dashboard (laptop+teléfono)** y **foto "persona de traje con teléfono"**: no
   están claramente en los assets. Usar placeholder/`Banner-Digital.webp` y **reportar** para que
   Sebastián los entregue o se extraigan del PDF de la maqueta.

## Restricciones (SiteGround)

- PHP puro + CSS/JS a mano. Fuentes self-hosted (sin Google en runtime). Nada de build Node.
- Optimizar peso de imágenes (preferir webp donde exista; dimensionar; `loading="lazy"` bajo el fold).

## Criterios de aceptación (DoD)

1. **Fuentes reales** cargando self-hosted (verificable: sin peticiones a fonts.googleapis.com); pesos
   correctos en títulos/cuerpo.
2. **Tipografía reducida** según la tabla; el sitio se acerca a la densidad del actual (comparar con
   `01_Web Actual/`); sin desbordes ni títulos apretados en 360/768/1280.
3. **Logos, favicon, gota, fondos, portada de video** reemplazados por los reales en su lugar correcto;
   footer con su logo; favicon visible en pestaña.
4. **Los 7 servicios** muestran su **hero** real y su **foto de intro** real (seed actualizado; verificar
   contra capturas).
5. Nota de blog "Guía de mantenimiento" con su **portada real**.
6. **Página `/appscinco`** completa y fiel a la maqueta, enlazada en la nav, con bloque de contacto
   funcional y **brochure descargable**; responsive.
7. Gaps (íconos de servicio, mockups AppsCinco) reportados en la entrega.
8. Sin errores PHP/JS; sin scroll horizontal; Lighthouse no empeora respecto a Hito 9.

## Pasos de verificación local

```bash
php db/seed.php            # aplica nuevas imágenes de servicios/blog
php -S localhost:8000 -t public
# / → logos reales, hero real, tarjetas, video (Portada-video), tipografía más compacta
# /servicios/pozos-profundos (y los 7) → hero + foto intro reales
# /blog y la nota → portada real
# /appscinco → nueva página fiel a la maqueta + brochure descarga
# DevTools: sin requests a Google Fonts; responsive 360/768/1280
```

---

## Estado y auditoría

- **Estado:** 🟦 Implementado (por Claude directo, no Codex) — pendiente auditoría/QA de Sebastián.
- **Rama:** `feature/h11-recursos-tipografia-appscinco` (creada desde `feature/h9-seo-qa`, que tiene el
  front completo; `develop` aún no tiene mergeados los hitos). Commits: `docs(h11)…` + `feat(h11)…`.
- **Gaps resueltos:**
  - Íconos de servicio: eran los 7 PNG monoline blancos (`Pozos-Profundos-1.png`, etc.); Codex ya los
    tenía cargados en `public/assets/img/icons/` desde hitos previos → sin cambio necesario.
  - AppsCinco (dashboard, hero, foto continuidad): **extraídos del PDF de la maqueta** con
    `pdfimages`/`pdftoppm` y optimizados a webp. El dashboard se recompuso sobre blanco (el JPEG
    embebido traía fondo negro en las zonas transparentes).
- **Notas de verificación:** home, servicios (7), blog y `/appscinco` responden 200; fuentes
  self-hosted sin peticiones a Google; tipografía reducida aplicada; verificado en preview local
  (`http://localhost:8000`). Falta pase de QA responsive fino y revisión visual de Sebastián.
