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
- [`2026-09-09-hidden-ci-diagnostics-artifacts.md`](2026-09-09-hidden-ci-diagnostics-artifacts.md) — `upload-artifact` excluded the hidden `.ci-diagnostics/` directory until hidden-file upload was explicitly enabled.
- [`2026-09-09-wp-env-cli-tokenization.md`](2026-09-09-wp-env-cli-tokenization.md) — `wp-env run` must receive WP-CLI executable and arguments as separate tokens instead of one quoted command string.
- [`2026-09-09-admin-archive-contrast.md`](2026-09-09-admin-archive-contrast.md) — real browser acceptance found insufficient WCAG AA contrast in the inherited WordPress destructive action styling.
- [`2026-09-09-playwright-retry-residual-state.md`](2026-09-09-playwright-retry-residual-state.md) — persisted WordPress state made a fixed-name CRUD test ambiguous on Playwright retry; runtime records now use unique identifiers.
- [`2026-09-09-runtime-credential-log-masking.md`](2026-09-09-runtime-credential-log-masking.md) — dynamically generated disposable WordPress passwords must be masked before any CI command can echo them.

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
- [`2026-09-09-hidden-ci-diagnostics-artifacts.md`](2026-09-09-hidden-ci-diagnostics-artifacts.md) — `upload-artifact` excluía el directorio oculto `.ci-diagnostics/` hasta habilitar explícitamente la subida de archivos ocultos.
- [`2026-09-09-wp-env-cli-tokenization.md`](2026-09-09-wp-env-cli-tokenization.md) — `wp-env run` debe recibir WP-CLI y sus argumentos como tokens separados y no como un único string entrecomillado.
- [`2026-09-09-admin-archive-contrast.md`](2026-09-09-admin-archive-contrast.md) — la aceptación real en navegador detectó contraste WCAG AA insuficiente en el estilo destructivo heredado de WordPress.
- [`2026-09-09-playwright-retry-residual-state.md`](2026-09-09-playwright-retry-residual-state.md) — el estado persistente de WordPress hacía ambiguo un test CRUD con nombre fijo al reintentarse; ahora los registros runtime usan identificadores únicos.
- [`2026-09-09-runtime-credential-log-masking.md`](2026-09-09-runtime-credential-log-masking.md) — las contraseñas WordPress desechables generadas en runtime deben enmascararse antes de que cualquier comando CI pueda mostrarlas.
