# Hito 5 — Backoffice: autenticación + shell del admin

> **Para Codex.** Prompt autocontenido. Rama **desde `develop`** (`feature/h5-admin-auth`), hasta la
> DoD, verificación local, **sin PR**. Requiere Hitos 0–1 en `develop` (idealmente 0–4).

## Objetivo

Levantar la base del **backoffice** en `/admin`: login por sesión, protección de rutas, layout de
admin y dashboard. Sienta la seguridad sobre la que se montan los CRUD de Servicios (Hito 6) y Blog
(Hito 7).

## Alcance

- **Autenticación por sesión PHP** contra `admin_users` (creado y seedeado en Hito 1: usuario
  `admin@hidrocinco.cl`).
- Rutas:
  - `GET /admin/login` → formulario de login.
  - `POST /admin/login` → valida credenciales (`password_verify`), crea sesión, redirige a
    `/admin`.
  - `GET /admin` → dashboard (protegido).
  - `POST /admin/logout` → destruye sesión, redirige a login.
- **Middleware/guard**: toda ruta bajo `/admin` (excepto login) exige sesión activa; si no, redirige
  a `/admin/login`.

## Seguridad (requisitos)

- Passwords con `password_hash`/`password_verify` (ya hasheado en seed).
- **Protección CSRF**: token en sesión, campo oculto en todos los formularios del admin (login,
  logout y los CRUD futuros); validar en cada `POST`. Crear helpers `csrf_field()` y `csrf_verify()`.
- Sesión: `session_regenerate_id(true)` tras login; cookie `HttpOnly`, `SameSite=Lax`, y `Secure` si
  HTTPS (condicional a entorno).
- **Rate limiting básico** de login (p. ej. contador en sesión + pausa tras N intentos fallidos) para
  mitigar fuerza bruta. Mensaje de error genérico ("Credenciales inválidas") sin revelar si el email
  existe.
- Cabecera `X-Frame-Options: DENY` (o CSP frame-ancestors) en el área admin.

## Layout y UI del admin

- `views/admin/layout.php`: shell propio (no el layout público). Barra lateral o superior con:
  marca "Hidrocinco Admin", enlaces **Dashboard**, **Servicios**, **Blog**, **Mensajes**
  (submissions — se llena en Hito 8), y **Cerrar sesión**. Los enlaces a secciones aún no
  implementadas pueden quedar visibles pero llevar a un placeholder "Próximamente" o deshabilitados.
- Estilo simple, limpio y funcional (no necesita replicar el front público). Reutiliza tokens de
  color para coherencia de marca (navy/verde). CSS admin en `assets/css/admin.css`.
- `views/admin/login.php`: formulario centrado (email, password, botón "Entrar", token CSRF).
- `views/admin/dashboard.php`: saludo + tarjetas de resumen (nº de servicios, nº de notas
  publicadas/borradores, nº de mensajes) usando los repositorios.

## Estructura de código

```
src/Controllers/Admin/AuthController.php     # showLogin, login, logout
src/Controllers/Admin/DashboardController.php
src/Auth.php                                 # login(), logout(), check(), user(), requireAuth()
src/Csrf.php (o en helpers)                  # token(), field(), verify()
views/admin/layout.php, login.php, dashboard.php
assets/css/admin.css
```
- Registrar rutas admin en el router. `requireAuth()` se llama al inicio de cada controlador admin
  protegido (o como paso previo en el dispatch de rutas `/admin/*` salvo login).

## Restricciones (SiteGround)
- Sesiones PHP nativas. Nada de Node ni servicios externos de auth.
- Sin dependencias externas (Composer no requerido).

## Criterios de aceptación (DoD)

1. `/admin` sin sesión redirige a `/admin/login`.
2. Login con `admin@hidrocinco.cl` / password del seed entra al dashboard; credenciales inválidas
   muestran error genérico y no crean sesión.
3. `session_regenerate_id` tras login; logout destruye la sesión y vuelve a login.
4. Todos los formularios admin incluyen token CSRF y los `POST` lo validan (un `POST` sin token o con
   token inválido es rechazado).
5. Dashboard muestra conteos reales (servicios, notas, mensajes).
6. Rate limiting básico activo tras varios intentos fallidos.
7. No hay credenciales ni secretos en el repo.

## Pasos de verificación local

```bash
php -S localhost:8000 -t public
# /admin → redirige a /admin/login
# Login OK → /admin (dashboard con conteos)
# Logout → vuelve a login; /admin vuelve a bloquear
# Enviar POST /admin/login sin csrf → rechazado
```

---

## Estado y auditoría

- **Estado:** ⬜ Pendiente
- **Rama:** `feature/h5-admin-auth`
- **Notas de auditoría:** _(las completa Claude al revisar)_
