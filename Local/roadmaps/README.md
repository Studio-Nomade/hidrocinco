# Roadmaps — Hidrocinco (local, no versionado)

Carpeta **solo local** para ordenar los roadmaps de implementación de Hidrocinco. Vive dentro de
`Local/` (hub de carpetas locales) y no se sube al repo (todo `Local/` está ignorado vía
`.git/info/exclude`, sin tocar el `.gitignore` versionado).

## Cómo se organiza

- **Un subfolder por módulo / iniciativa** (nombre general que agrupa sus fases/hitos).
- Dentro de cada módulo:
  - `README.md` — contexto, decisión estratégica, metodología, **índice de hitos con estado** y pauta
    de auditoría.
  - `hito-<x>-<slug>.md` — **un archivo por hito**, con el **prompt completo y autocontenido para
    Codex** (para ir pasándolos de a uno) + bloque de estado y notas de auditoría.

## Metodología (resumen)

- **Claude = PM Senior / arquitecto + auditor.** Emite un prompt completo por hito. No escribe el
  código de los hitos.
- **Codex = ejecutor.** Corta **rama nueva por feature SIEMPRE desde `develop`**, implementa hasta la
  DoD, verifica en preview, y **NO abre PR**.
- **Ciclo:** Codex termina → Sebastián devuelve la rama a Claude → Claude audita → si OK, PR a
  `develop` → siguiente hito.

## Módulos

| Módulo | Carpeta | Estado |
|---|---|---|
| Migración Web Hidrocinco (WP/Elementor → HTML + PHP) | [`web-migracion-html/`](web-migracion-html/README.md) | 🟨 En curso (11 hitos definidos, 0 ejecutados) |



> Para agregar un módulo nuevo: crea `roadmaps/<modulo>/` con su `README.md` (índice) y un archivo por
> hito siguiendo el mismo formato.
