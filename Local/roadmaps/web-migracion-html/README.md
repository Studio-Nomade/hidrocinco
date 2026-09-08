# Módulo: Migración Web Hidrocinco (WordPress/Elementor → HTML + PHP ligero)

Roadmap de implementación para migrar `hidrocinco.cl` desde WordPress + Elementor Pro a un sitio
propio, liviano y mantenible por código, desplegado en **SiteGround GoGeek**.

## Decisión estratégica

- **Salir de WordPress/Elementor**: sitio más ligero, sin bloatware de plugins, mantenido por el
  equipo directamente en código. No requiere ser autoadministrable como WP.
- **Stack: PHP + MySQL** (nativo en SiteGround GoGeek; **no** hay soporte Node.js server-side). El
  front es **HTML/CSS/JS limpios renderizados por PHP** — sin page builders ni frameworks pesados.
- **Backoffice mínimo**: solo **Servicios** (plantilla reutilizable) y **Blog** (plantilla + editor
  enriquecido) son dinámicos/gestionables. Home, Visión, footer y datos de contacto viven en el
  código.
- **Formularios** (contacto + newsletter): se **envían por email** a Hidrocinco **y** se **guardan
  en DB** (visibles en admin), con **reCAPTCHA v2 + honeypot**. El envío usa **PHPMailer vía SMTP
  autenticado** (mejor entregabilidad; SiteGround lo recomienda sobre `mail()`).
- **Composer**: el proyecto arranca sin dependencias (Hitos 0–7); el **Hito 8 introduce Composer** con
  una única dependencia (**PHPMailer**). `vendor/` no se commitea: se genera en GitHub Actions y se
  sincroniza al servidor (ver Hito 10). GoGeek es 100% compatible con Composer.
- **Deploy**: **GitHub Actions → SSH/rsync** a GoGeek al hacer push a la rama de producción.

## Metodología (recordatorio)

- **Claude = PM Senior / arquitecto + auditor.** Emite un prompt completo y autocontenido por hito.
  No escribe el código.
- **Codex = ejecutor.** Corta **rama nueva por feature SIEMPRE desde `develop`**, implementa hasta la
  DoD, **verifica en preview local** (`php -S localhost:8000 -t public`), y **NO abre PR**.
- **Ciclo:** Codex termina → Sebastián devuelve la rama a Claude → Claude audita → si OK, PR a
  `develop` → siguiente hito.
- **Idioma del producto:** español (Chile). Nombres de archivos/código en inglés; contenido y UI en
  español.

## Sistema de diseño (fuente de verdad — extraído del sitio en vivo)

**Colores** (del kit Elementor `post-341.css`):

| Rol | Hex |
|---|---|
| Navy primario (títulos, botones, barra) | `#002F62` |
| Azul barra superior / degradados hero | `#1D4387` |
| Azul-violeta acento | `#4054B2` |
| Verde brillante (acento / hover / "!") | `#61CE70` |
| Verde oscuro (logo "CINCO", "contacto", checks) | `#23A455` |
| Texto cuerpo (gris) | `#7A7A7A` |
| Texto oscuro | `#2F2F2F` |
| Fondo claro | `#FAFAFA` |
| Blanco / Negro | `#FFFFFF` / `#000000` |

**Tipografías** (Google Fonts, **self-hosteadas** — no depender de Google en runtime):
- **Poppins** → principal (títulos + cuerpo).
- **Roboto Slab** → secundaria (acentos de títulos donde aplique).
- **Roboto** → acento.

**Estructura global** (se repite en todas las páginas):
- **Barra superior** (navy `#002F62`): `Tutorial Fractal` · `Acceso Clientes` · `⚡ Emergencias 24/7`.
- **Header** (blanco, sticky): logo Hidrocinco + nav `Inicio · Servicios · Nosotros · Vision · Blog`
  + botón `Contacto` (pill outline navy).
- **Bloque de contacto reutilizable**: panel azul `¡Hablemos!` + `Formulario de contacto`
  (Nombre, Teléfono, Correo electrónico, Mensaje, reCAPTCHA, botón `Enviar`).
- **Footer** (gris claro `#FAFAFA`): logo + `Oficinas comerciales` (Av. Portugal 1797, Santiago,
  Chile) + `Informaciones de contacto` (hidrocinco@hidrocinco.cl · (+562) 2556 1859) +
  `Redes sociales` (Instagram, LinkedIn).
- Botón flotante `Contáctanos` abajo a la derecha (link simple a WhatsApp/contacto; **sin** el plugin
  Chaty).

**Los 7 servicios** (se conservan los slugs actuales para no romper SEO/enlaces):
`pozos-profundos` · `lavado-de-estanques` · `plantas-de-tratamientos-de-aguas-servidas-ptas` ·
`sala-de-calderas` · `taller-y-servicio-tecnico` · `limpia-fosas` · `sala-de-bombas`.

**Contexto visual disponible:** capturas del sitio actual en
`Local/archivos de contexto/01_Web Actual/` (home, cada servicio, blog index y una nota). Úsalas como
referencia visual junto al sitio en vivo `https://hidrocinco.cl/`.

## Arquitectura objetivo (resumen)

```
public/                 # docroot (SiteGround apunta aquí)
  index.php             # front controller + router
  .htaccess             # rewrite a index.php + headers/cache
  assets/               # css, js, fonts (self-hosted), img
  uploads/              # imágenes subidas desde admin (no en VCS)
src/
  Router.php, helpers.php (e(), url(), asset())
  Database.php          # PDO singleton
  Repositories/         # ServiceRepository, PostRepository, SubmissionRepository
  Controllers/          # Home, Service, Blog, Contact, Admin/*
views/
  layouts/base.php      # <head>, topbar, header, footer, chat button
  partials/             # topbar, header, footer, contact-block, service-card, post-card
  pages/                # home, service, blog-index, blog-post, 404
  admin/                # login, dashboard, services/*, posts/*, submissions
db/
  migrations/*.sql      # esquema versionado
  seed.php              # 7 servicios + 1 nota + usuario admin
config.example.php      # plantilla; config.php real fuera de VCS
```

- **Rutas limpias:** `/`, `/servicios/<slug>`, `/blog`, `/blog/<slug>`, `/contacto`, `/admin/...`.
  Mantener **redirect 301** de los slugs planos actuales de servicios (`/pozos-profundos/` →
  `/servicios/pozos-profundos`) para preservar SEO.
- **Sin toolchain Node en runtime.** CSS/JS a mano; si se minifica, se hace de forma que no requiera
  build en el servidor.

## Índice de hitos

| # | Hito | Estado | Rama sugerida |
|---|---|---|---|
| 0 | [Fundaciones (estructura, router, layout, tokens)](hito-0-fundaciones.md) | ⬜ Pendiente | `feature/h0-fundaciones` |
| 1 | [Capa de datos y modelo de contenido](hito-1-capa-datos.md) | ⬜ Pendiente | `feature/h1-capa-datos` |
| 2 | [Home / Landing](hito-2-home.md) | ⬜ Pendiente | `feature/h2-home` |
| 3 | [Plantilla de Servicios](hito-3-plantilla-servicios.md) | ⬜ Pendiente | `feature/h3-servicios` |
| 4 | [Blog (index + detalle)](hito-4-blog.md) | ⬜ Pendiente | `feature/h4-blog` |
| 5 | [Backoffice: auth + shell](hito-5-admin-auth.md) | ⬜ Pendiente | `feature/h5-admin-auth` |
| 6 | [Backoffice: CRUD Servicios](hito-6-admin-servicios.md) | ⬜ Pendiente | `feature/h6-admin-servicios` |
| 7 | [Backoffice: CRUD Blog (editor enriquecido)](hito-7-admin-blog.md) | ⬜ Pendiente | `feature/h7-admin-blog` |
| 8 | [Formularios (contacto + newsletter) + correo](hito-8-formularios.md) | ⬜ Pendiente | `feature/h8-formularios` |
| 9 | [SEO, performance, responsividad y QA](hito-9-seo-perf-qa.md) | ⬜ Pendiente | `feature/h9-seo-qa` |
| 10 | [Deploy GitHub → SiteGround GoGeek](hito-10-deploy-siteground.md) | 🟦 En auditoría | `feature/h10-deploy` |
| 11 | [Recursos gráficos reales + tipografía + página AppsCinco](hito-11-recursos-tipografia-appscinco.md) | ⬜ Pendiente | `feature/h11-recursos-tipografia-appscinco` |

**Anexos:** [Migración de DNS a SiteGround (Opción B)](anexo-dns-siteground.md) — zona completa
(web + Microsoft 365 + Brevo + `ws.hidrocinco.cl`/ACM de AppsCinco), qué se elimina (cPanel + 3 DKIM
de SES remanentes) y secuencia de corte de nameservers en NIC.cl.

Leyenda: ⬜ Pendiente · 🟨 En curso · 🟦 En auditoría · ✅ Aprobado (mergeado a `develop`).

## Pauta de auditoría (global, para cada hito)

Al recibir una rama, Claude verifica:
1. **DoD cumplida** — todos los criterios de aceptación del hito.
2. **Fidelidad de diseño** — colores/tipos/espaciados coherentes con el sistema de diseño y las
   capturas; comparación visual contra `hidrocinco.cl`.
3. **Responsividad** — breakpoints móvil (≤480), tablet (≤768), desktop; sin scroll horizontal;
   nav móvil funcional.
4. **Compatibilidad SiteGround** — solo PHP/MySQL estándar; nada de dependencias que requieran Node
   en el servidor ni extensiones PHP no disponibles en GoGeek.
5. **Seguridad básica** — escape de salida (`e()`), sentencias preparadas (PDO), validación de
   subida de archivos, protección de rutas admin, hashing de passwords, tokens CSRF en formularios de
   admin.
6. **Convenciones** — rama desde `develop`, sin PR abierto por Codex, estructura de carpetas
   respetada, sin secretos commiteados (`config.php` ignorado).
7. **Verificación local reproducible** — corre con `php -S` según los pasos del hito.

## Convenciones de ejecución (para Codex)

- Rama: `git checkout develop && git pull && git checkout -b feature/h<N>-<slug>`.
- Verificar en local con `php -S localhost:8000 -t public` (o el docroot indicado).
- No abrir PR. Al terminar, dejar la rama lista y avisar.
- `config.php`, `public/uploads/` y `vendor/` (si aplica) van en `.gitignore`.
- Contenido en español; código y nombres en inglés.
