# Engineering Failure & Solution Memory

[English](#english) · [Español](#español)

This directory is the durable failure/solution memory for Kairoseth AI Transparency.

---

## English

Before changing CI, packaging, WordPress compatibility, translations, authorization, Multisite behavior or release tooling, search this directory for related records.

A material failure record must include:

```text
status
first observed date
affected area
severity
symptom
root cause
resolution
prevention
verification
related evidence
```

Root cause must not repeat the symptom. If evidence is incomplete, mark it `probable` or `unconfirmed` rather than inventing certainty.

### Current records

- [`2026-09-09-wpcs-class-file-conventions.md`](2026-09-09-wpcs-class-file-conventions.md) — initial PSR-style class filenames/method naming conflicted with the repository's WordPress Coding Standards gate.
- [`2026-09-09-plugin-check-production-package-scope.md`](2026-09-09-plugin-check-production-package-scope.md) — Plugin Check must validate the distributable WordPress package, not the development repository root.

---

## Español

Antes de modificar CI, packaging, compatibilidad WordPress, traducciones, autorización, comportamiento Multisite o tooling de release, se debe buscar aquí si existe un fallo relacionado.

Un registro material debe incluir:

```text
estado
fecha inicial
área afectada
severidad
síntoma
causa raíz
solución
prevención
verificación
evidencia relacionada
```

La causa raíz no puede limitarse a repetir el síntoma. Si no existe evidencia suficiente, se marca como `probable` o `unconfirmed` en lugar de inventar certeza.

### Registros actuales

- [`2026-09-09-wpcs-class-file-conventions.md`](2026-09-09-wpcs-class-file-conventions.md) — los nombres de archivo/método estilo PSR iniciales chocaron con el gate WordPress Coding Standards del repositorio.
- [`2026-09-09-plugin-check-production-package-scope.md`](2026-09-09-plugin-check-production-package-scope.md) — Plugin Check debe validar el paquete WordPress distribuible y no la raíz del repositorio de desarrollo.
