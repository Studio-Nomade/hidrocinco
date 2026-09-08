# Hito 1 — Capa de datos y modelo de contenido (MySQL + repositorios + seeds)

> **Para Codex.** Prompt autocontenido. Crea **rama nueva desde `develop`** (`feature/h1-capa-datos`),
> implementa hasta la DoD, verifica en local y **NO abras PR**. Requiere Hito 0 ya mergeado en
> `develop`.

## Objetivo

Definir el modelo de contenido en **MySQL** y la capa de acceso a datos (PDO + repositorios), con
**migraciones versionadas** y **seeds** que cargan los **7 servicios** con su contenido real, **1
nota de blog** de ejemplo y un **usuario admin** inicial. Al final, los repositorios deben poder
leer/escribir y el front (en hitos siguientes) consumirá estos datos.

## Contexto

Solo **Servicios** y **Blog** son dinámicos. El modelo de servicio debe ser una **plantilla flexible**
porque los servicios varían mucho: algunos tienen bloques con lista de bullets largos (PTAS, Taller),
otros son simples (Pozos, Limpia Fosas). Usaremos un campo de **contenido estructurado por bloques**
(JSON) para dar flexibilidad sin sobre-ingeniería.

## Esquema de base de datos (`db/migrations/001_init.sql`)

Motor InnoDB, charset `utf8mb4_unicode_ci`.

```sql
CREATE TABLE services (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  slug          VARCHAR(160) NOT NULL UNIQUE,
  title         VARCHAR(200) NOT NULL,          -- ej: "Pozos profundos"
  icon          VARCHAR(120) NULL,              -- nombre/clase de ícono o ruta a svg
  hero_image    VARCHAR(255) NULL,              -- imagen de cabecera del detalle
  card_summary  VARCHAR(300) NULL,              -- texto corto para la tarjeta del home (opcional)
  content_json  LONGTEXT NOT NULL,              -- bloques (ver formato abajo)
  sort_order    INT NOT NULL DEFAULT 0,         -- orden en home/listados
  is_published  TINYINT(1) NOT NULL DEFAULT 1,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE posts (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  slug           VARCHAR(200) NOT NULL UNIQUE,
  title          VARCHAR(255) NOT NULL,
  excerpt        VARCHAR(500) NULL,             -- para la tarjeta del index
  body_html      LONGTEXT NOT NULL,             -- HTML del editor enriquecido (sanitizado)
  featured_image VARCHAR(255) NULL,
  status         ENUM('draft','published') NOT NULL DEFAULT 'draft',
  published_at   DATE NULL,                     -- fecha visible (ej: 2024-01-15)
  created_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE submissions (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  type         ENUM('contact','newsletter') NOT NULL,
  name         VARCHAR(200) NULL,
  phone        VARCHAR(60) NULL,
  email        VARCHAR(200) NULL,
  message      TEXT NULL,
  source_page  VARCHAR(255) NULL,              -- desde qué página se envió
  ip           VARCHAR(45) NULL,
  created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admin_users (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  email         VARCHAR(200) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,          -- password_hash() PHP
  name          VARCHAR(120) NULL,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
```

### Formato de `content_json` (bloques del servicio)

Array de bloques; cada bloque tiene `type` y campos. Tipos soportados (la plantilla del Hito 3 los
renderiza):
```json
[
  { "type": "intro", "title": "Sobre el servicio", "paragraphs": ["...", "..."], "image": "assets/img/servicios/pozos.jpg" },
  { "type": "feature", "title": "Reglamento ...", "paragraphs": ["..."], "decor": true },
  { "type": "list", "title": "Servicio de operación ...", "intro": "...", "items": ["Medición de pH", "..."] },
  { "type": "richtext", "html": "<p>...</p>" }
]
```
- `intro`: bloque principal (título + párrafos + imagen lateral).
- `feature`: bloque destacado sobre fondo claro, con imagen decorativa de gota si `decor:true`.
- `list`: título + intro opcional + lista de bullets.
- `richtext`: HTML libre (para casos que no calcen en los anteriores).

## Capa de acceso a datos

- `src/Database.php`: clase `Database` con PDO singleton (`ERRMODE_EXCEPTION`,
  `FETCH_ASSOC`, `utf8mb4`). Lee credenciales de `config.php` (`DB_HOST`, `DB_NAME`, `DB_USER`,
  `DB_PASS`, `DB_CHARSET`).
- `src/Repositories/ServiceRepository.php`: `allPublishedOrdered()`, `all()`, `findBySlug($slug)`,
  `findById($id)`, `create($data)`, `update($id,$data)`, `delete($id)`, `updateOrder($idsInOrder)`.
  `content_json` se codifica/decodifica (json_encode/decode) dentro del repo (expone arrays a la
  vista).
- `src/Repositories/PostRepository.php`: `published()`, `all()`, `findBySlug()`, `findById()`,
  `related($excludeId,$limit=3)`, `create/update/delete`.
- `src/Repositories/SubmissionRepository.php`: `create($data)`, `all($type=null)`.
- Todas las consultas con **sentencias preparadas** (PDO). Nada de interpolación directa.

## Migraciones y seeds (runner CLI)

- `db/migrate.php`: ejecuta en orden los `.sql` de `db/migrations/` (registrar aplicadas en una tabla
  `migrations` para idempotencia). Ejecutable con `php db/migrate.php`.
- `db/seed.php`: inserta los 7 servicios (contenido abajo), la nota de ejemplo y el usuario admin.
  Idempotente (usar `INSERT ... ON DUPLICATE KEY UPDATE` por `slug`/`email` o comprobar existencia).
  Ejecutable con `php db/seed.php`.
- **Usuario admin seed:** email `admin@hidrocinco.cl`, password inicial `Hidrocinco2026!` guardado con
  `password_hash($pass, PASSWORD_DEFAULT)`. Documentar que debe cambiarse. (El login llega en Hito 5.)

## Contenido real de los 7 servicios (seed — texto verbatim del sitio actual)

> Los textos provienen del sitio en vivo y de las capturas en
> `Local/archivos de contexto/01_Web Actual/`. Respeta el texto tal cual (incluye alguna errata del
> original, p. ej. "mantenimineot", "prevee" — se conservan salvo indicación de corregir en QA).
> `sort_order` sigue el orden del home actual.

**1. `pozos-profundos` — "Pozos profundos"** (`sort_order: 1`)
- Bloque `intro` (título "Sobre el servicio"):
  - P1: "Contamos con la capacidad de ejecutar pozos profundos, con su respectiva implementación que consiste en suministro de motobombas, tuberías, tablero control y fuerza."
  - P2: "Para pozos existentes, tenemos la capacidad de evaluar el buen funcionamiento de los pozos, mediante filmación y posterior corrección. Consisten en el reemplazo de motobomba, tuberías y en caso de ser necesario, profundizar los pozos existentes."

**2. `lavado-de-estanques` — "Lavado de estanques"** (`sort_order: 2`)
- Bloque `intro` (título "Servicio de Lavado de estanques de agua potable"):
  - P1: "El Servicio de Lavado de Pozos de Aguas Servidas es fundamental para lograr el buen funcionamiento de los sistemas de evacuación de aguas servidas, con el fin de evitar que las bombas sumergidas de elevación de aguas servidas se obstruyan."
- Bloque `feature` (título "\"Reglamento de los servicios de agua destinados al consumo humano\"", `decor:true`):
  - P1: "Según el decreto 76 que modifica decreto 735 del \"Reglamento de los servicios de agua destinados al consumo humano\" Es necesario ejecutar lavados a los estanques de agua potable al menos una vez al año."

**3. `plantas-de-tratamientos-de-aguas-servidas-ptas` — "Plantas de tratamiento (PTAS)"** (`sort_order: 3`)
- Bloque `intro` (título "Plan de mantenimiento"):
  - P1: "Hidrocinco ofrece a sus clientes un plan de mantenimiento, que incluye la revisión operativa de los equipos, el estudio in situ de la calidad del lodo y el efluente, la medición de los principales parámetros operacionales de las plantas y el análisis a las aguas residuales de acuerdo al Decreto supremo 90, que regula su descarga a cursos superficiales de agua."
  - P2: "Hidrocinco, además ofrece un plan de mejoras que permite optimizar los requerimientos operacionales de las plantas existente, por medio de estudios técnicos. Contamos con experiencia en plantas de lodos activados, aireación extendida y lechos empacados, desde su diseño, construcción y mantenimiento."
  - P3: "Con el plan de mantenimiento, la asesoría técnica del equipo de ingeniería y la amplia red de móviles de emergencia, desplegados desde La IV Región hasta la X Región, nuestra empresa logra entregar una solución integral y un servicio orientado a lograr la continuidad operacional de los sistemas de tratamientos de aguas servidas."
- Bloque `list` (título "Servicio de operación de Plantas de Tratamientos"):
  - intro: "El servicio de mantención preventiva logra un Biproceso controlado y funcionando al nivel de desempeño para obtener un efluente con bajos niveles de contaminación, mediante el control de los parámetros relevantes directos e indirectos, entre los cuales se puede mencionar:"
  - items: ["Medición de pH", "Medición de Oxígeno disuelto", "Medición de temperatura", "Medición de manto de lodos", "Medición de sedimentabilidad de lodos", "Medición de Cloro Libre residual en el efluente", "Medición de solidos suspendidos totales"]
- Bloque `feature` (título "Desarrollo de proyectos e instalaciones"):
  - P1: "Hidrocinco cuenta con un departamento de Ingeniería especializado en Plantas de tratamiento y que es capaz de dar soluciones orientadas al saneamiento ambiental, de los recurso hídricos, con el objetivo de dar cumplimiento a las normativas ambientales vigentes y cuidar el medio ambiente."

**4. `sala-de-calderas` — "Sala de Calderas"** (`sort_order: 4`)
- Bloque `intro` (título "Mantención"):
  - P1 (destacado): "Hidrocinco se encuentra con equipos de técnicos capacitados para otorgar el mantenimiento preventivo de calderas"
  - P2: "que se complementa con el servicio de mantenimineot de las salas de bomba, con un plan de mantenimiento que prevee el deterioro del sistema, con un servicio de atención de emergencias 24/7"

**5. `taller-y-servicio-tecnico` — "Taller y Servicio Técnico"** (`sort_order: 5`)
- Bloque `list` (título "Taller y Servicio Técnico"):
  - items: ["Reparación de bombas", "Fabricación de membranas de caucho para cilindros hidroneumáticos", "Sopladores", "Motorreductores", "Acondicionamiento de piezas para bombas y accesorios para el equipamiento de plantas de tratamiento y Plantas elevadoras de aguas servidas.", "Bridas", "Difusores", "Cadenas para Biodiscos", "Canastillos de pozos de aguas servidas"]
- Bloque `list` (título "Taller eléctrico"):
  - items: ["Diseño y fabricación de tableros de fuerza y control", "Programación de sistemas PLC", "Fabricación de variadores de frecuencia", "Instalaciones eléctricas de sistema de bombeo"]
- Bloque `list` (título "Taller metalmecánico"):
  - intro: "Nuestra empresa cuenta con servicio mecanizado, diseño y fabricación de:"
  - items: ["Cilindros hidroneumáticos", "Manifold de sistema de bombeo en cobre, fierro, polipropileno random (PPR o termofusión) y policloruro de vinilo (PVC)", "Bridas", "Difusores", "Cadenas para Biodiscos", "Canastillos de pozos de aguas servidas", "Soportes y estructuras de mejoramiento para sistemas de bombeos y plantas de tratamiento."]

**6. `limpia-fosas` — "Limpia Fosas"** (`sort_order: 6`)
- Bloque `intro` (título "Sobre el servicio"):
  - P1: "Este servicio comprende el transporte, manejo y disposición final de residuos no peligrosos provenientes de cámaras desgrasadoras, además de la mantención y limpieza de redes, retiro de aguas servidas y destape de alcantarillado."
  - P2: "Nuestro proceso se encuentra certificado y contamos con todas las resoluciones sanitarias requeridas para el transporte, gestión y disposición de residuos."

**7. `sala-de-bombas` — "Sala de bombas"** (`sort_order: 7`)
- Bloque `intro` (título "Mantención"):
  - P1 (destacado): "Nuestra empresa cuenta con el Servicio de Mantenimiento Preventivo y Correctivo, para ello, contamos con equipos de Técnicos en terreno con conocimientos eléctricos y mecánicos."
  - P2: "Contamos con un software de mantenimiento que nos permite controlar sus activos y con ello asegurar la continuidad operacional de sus sistemas. Nos encontramos capacitados para ofrecer nuestro software que nos permite el monitoreo, alerta, alarmas y notificaciones, orientadas a la generación de protocolos de mantenimiento predictivo."
- Bloque `feature` (título "Desarrollo de proyectos e instalaciones"):
  - P1: "Estamos capacitados para el desarrollo de Proyectos, sistemas de bombeo, Manifold, fabricación de tableros y sistemas de control y automatización."

> Las imágenes de hero/intro de cada servicio pueden quedar como placeholders (`hero_image` NULL o
> ruta a un placeholder en `assets/img/servicios/`); las imágenes reales se sustituyen cuando estén
> disponibles. Los íconos de las tarjetas del home replican los del sitio actual (pozo, estanque,
> planta, caldera, engranaje, camión, bombas) — usar SVGs simples/placeholder consistentes.

## Nota de blog de ejemplo (seed)

- `slug`: `guia-de-mantenimiento-sistemas-hidraulicos-en-edificios`
- `title`: "Guía de mantenimiento | Sistemas Hidráulicos en edificios"
- `status`: `published`, `published_at`: `2024-01-15`
- `excerpt`: "Los sistemas hidráulicos en edificios desempeñan un papel crucial en el funcionamiento diario de las instalaciones. Desde el suministro de agua potable hasta el drenaje de aguas residuales, estos sistemas deben mantenerse en condiciones óptimas para garantizar la comodidad y la seguridad de los ocupantes."
- `body_html`: usar el **texto completo verbatim** de la nota, disponible en la captura
  `screencapture-hidrocinco-cl-guia-de-mantenimiento-...png` y en el sitio en vivo
  `https://hidrocinco.cl/guia-de-mantenimiento-sistemas-hidraulicos-en-edificios/`. Estructura: intro
  en negrita + secciones numeradas (1. Inspecciones regulares, 2. Limpieza y desagüe, 3. Mantenimiento
  de bombas y calderas, 4. Reparaciones oportunas, 5. Actualización de sistemas obsoletos) + cierre.
  Guardar como HTML semántico (`<p>`, `<h3>`, `<ol>`/`<strong>`).
- `featured_image`: placeholder.

## Restricciones (SiteGround GoGeek)

- MySQL estándar; nada específico de otro motor. `utf8mb4` para acentos/ñ.
- Runners CLI en PHP puro (`php db/migrate.php`), sin dependencias externas.
- Añadir a `config.example.php` las claves `DB_HOST/DB_NAME/DB_USER/DB_PASS/DB_CHARSET`.

## Criterios de aceptación (DoD)

1. `php db/migrate.php` crea las 4 tablas (+ tabla de control de migraciones) sin errores y es
   idempotente (re-ejecutar no falla ni duplica).
2. `php db/seed.php` inserta los 7 servicios con su `content_json` correcto, la nota de ejemplo y el
   usuario admin; idempotente.
3. Un script/prueba simple demuestra lectura: `ServiceRepository::allPublishedOrdered()` devuelve 7
   servicios en orden; `findBySlug('plantas-de-tratamientos-de-aguas-servidas-ptas')` devuelve sus
   bloques decodificados; `PostRepository::published()` devuelve la nota.
4. Acentos y ñ se almacenan y leen correctamente (utf8mb4).
5. Consultas con sentencias preparadas; sin secretos en el repo (credenciales solo en `config.php`).

## Pasos de verificación local

```bash
# Configurar credenciales de una base MySQL local en config.php
php db/migrate.php
php db/seed.php
php -r "require 'src/bootstrap.php'; \$r=new App\Repositories\ServiceRepository();
        var_dump(count(\$r->allPublishedOrdered()));"   # espera 7
```
(Adapta el `require`/namespace al que definas. Si prefieres, agrega un pequeño `db/check.php` que
imprima el conteo de servicios y el título de la nota.)

---

## Estado y auditoría

- **Estado:** ⬜ Pendiente
- **Rama:** `feature/h1-capa-datos`
- **Notas de auditoría:** _(las completa Claude al revisar)_
