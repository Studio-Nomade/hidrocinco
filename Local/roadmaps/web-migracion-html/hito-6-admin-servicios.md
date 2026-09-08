# Hito 6 — Backoffice: CRUD de Servicios

> **Para Codex.** Prompt autocontenido. Rama **desde `develop`** (`feature/h6-admin-servicios`), hasta
> la DoD, verificación local, **sin PR**. Requiere Hitos 1, 3 y 5 en `develop`.

## Objetivo

Permitir gestionar los **servicios** desde el admin: listar, crear, editar, eliminar y reordenar. El
editor debe cubrir el modelo de bloques (`content_json`) que la plantilla del Hito 3 renderiza, más
imagen de hero e ícono. Un servicio creado aquí debe verse en el front (home + `/servicios/<slug>`)
sin tocar código.

## Rutas (protegidas por auth del Hito 5)

```
GET  /admin/servicios              -> lista
GET  /admin/servicios/nuevo        -> form crear
POST /admin/servicios              -> guardar nuevo
GET  /admin/servicios/{id}/editar  -> form editar
POST /admin/servicios/{id}         -> actualizar
POST /admin/servicios/{id}/eliminar-> eliminar (con confirmación)
POST /admin/servicios/orden        -> guardar nuevo orden (sort_order)
```
Controlador: `src/Controllers/Admin/ServiceAdminController.php`. Usa `ServiceRepository` (Hito 1).

## Pantalla de lista (`views/admin/services/index.php`)

- Tabla con: orden (drag handle o inputs numéricos), título, slug, estado (publicado/oculto),
  acciones (Editar, Eliminar).
- Reordenar: **drag & drop** con JS vanilla (o inputs de orden + botón "Guardar orden") que hace
  `POST /admin/servicios/orden` con los IDs en el nuevo orden → `ServiceRepository::updateOrder()`.
- Botón "Nuevo servicio".

## Formulario crear/editar (`views/admin/services/form.php`)

Campos:
- **Título** (requerido). **Slug** (auto-generado desde el título con JS, editable; validar único y
  formato `[a-z0-9-]`). Al editar un servicio existente, avisar que cambiar el slug rompe enlaces.
- **Ícono**: selector (lista de SVGs disponibles en `assets/img/iconos-servicios/`) o subida de SVG.
- **Imagen de hero**: subida de imagen (ver "Subida de archivos").
- **Resumen de tarjeta** (`card_summary`, opcional).
- **Estado**: publicado / oculto (`is_published`).
- **Bloques de contenido** (`content_json`): **editor de bloques** dinámico. UI para agregar/quitar/
  reordenar bloques, cada uno de un tipo:
  - `intro`: campos título, párrafos (varios, textarea repetible), imagen (subida).
  - `feature`: título, párrafos, checkbox "imagen decorativa de gota" (`decor`).
  - `list`: título, intro (opcional), items (lista repetible).
  - `richtext`: un editor de texto simple/enriquecido → guarda HTML **sanitizado**.
  - Implementar con JS vanilla: plantillas de bloque clonables, botones subir/bajar/eliminar. Al
    guardar, serializar a JSON en un input oculto y persistir en `content_json`.
- Token CSRF en el form.

## Subida de archivos (imágenes/íconos)

- Endpoint de subida (o manejo en el mismo `POST`): validar **tipo MIME real** (imágenes:
  jpg/png/webp/svg; SVG solo si se sanitiza) y **tamaño máximo** (p. ej. 3 MB). Renombrar a nombre
  seguro (hash/slug + timestamp). Guardar en `public/uploads/servicios/`.
- Guardar en DB la **ruta relativa**. `public/uploads/` está en `.gitignore` (Hito 0) salvo `.gitkeep`.
- Rechazar ejecutables y extensiones dobles. Para SVG, sanitizar (quitar `<script>`/handlers) o
  restringir a un set curado de íconos si sanitizar es riesgoso.

## Validación y seguridad

- Server-side: título y al menos un bloque requeridos; slug único y con formato válido; sanitizar
  `richtext` (allowlist de tags: p, br, strong, em, ul, ol, li, h3, a[href]); escapar todo lo demás.
- CSRF en todos los `POST`. Eliminación con confirmación (modal/JS + `POST`).
- Solo usuarios autenticados (guard del Hito 5).

## Criterios de aceptación (DoD)

1. Lista muestra los 7 servicios seed; se pueden **crear**, **editar** y **eliminar**.
2. Un servicio nuevo creado con bloques válidos aparece en el **home** (tarjeta) y en
   `/servicios/<slug>` renderizado por la plantilla del Hito 3, **sin cambios de código**.
3. Editar bloques (agregar un `list`, reordenar, cambiar textos) se refleja en el front.
4. Reordenar en el admin cambia el `sort_order` y el orden en el home.
5. Subida de imagen de hero funciona; archivo inválido (tipo/tamaño) es rechazado con mensaje.
6. Slug único validado; `richtext` sanitizado; CSRF activo; rutas protegidas.
7. Ocultar un servicio (`is_published=0`) lo saca del front pero lo mantiene en el admin.

## Pasos de verificación local

```bash
php -S localhost:8000 -t public
# Login admin → /admin/servicios
# Crear "Servicio de prueba" con un bloque intro + un list → guardar
#   → aparece en / (home) y en /servicios/servicio-de-prueba
# Editar: agregar bloque feature con decor → verificar en front
# Reordenar → verificar orden en home
# Subir imagen 5MB o .php → rechazada
# Eliminar el servicio de prueba
```

---

## Estado y auditoría

- **Estado:** ⬜ Pendiente
- **Rama:** `feature/h6-admin-servicios`
- **Notas de auditoría:** _(las completa Claude al revisar)_
