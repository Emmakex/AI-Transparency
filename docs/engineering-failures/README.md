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
- [`2026-09-09-phase6-wpcs-docblock-formatting.md`](2026-09-09-phase6-wpcs-docblock-formatting.md) — Phase 6 CI #88/#90 exposed repository WPCS requirements for explicit ternaries, alignment and method-local `@throws` documentation before PHPUnit can be considered reached.
- [`2026-09-10-phase7-wpcs-exception-and-eof.md`](2026-09-10-phase7-wpcs-exception-and-eof.md) — Phase 7 CI #97 captured the POSIX final-newline requirement and WPCS treatment of dynamic exception messages as output.
- [`2026-09-10-wp-env-alpine-tls-bootstrap.md`](2026-09-10-wp-env-alpine-tls-bootstrap.md) — `wp-env` Docker bootstrap can fail before plugin execution when the Alpine package mirror/index request has a transient TLS failure; diagnose the earliest network error before treating the later `no such package` message as a product regression.
- [`2026-09-10-phase8-wpcli-network-activation.md`](2026-09-10-phase8-wpcli-network-activation.md) — Phase 8 Release Readiness must install the ZIP first and then run `wp plugin activate ai-transparency --network`; `wp plugin install` does not accept `--network-activate`.
- [`2026-09-10-phase8-stale-release-version-assertion.md`](2026-09-10-phase8-stale-release-version-assertion.md) — the Evidence Export version assertion recurred at 1.0.1 after an initial 0.1.0→1.0.0 literal update; it now derives the expected current version from validated plugin metadata instead of hardcoding a stable release number.
- [`2026-09-10-release-readiness-version-hardcode.md`](2026-09-10-release-readiness-version-hardcode.md) — the first 1.0.1 candidate exposed stable-version literals in Release Readiness; release packaging and exact-ZIP lifecycle now resolve and propagate the validated repository version dynamically.

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
- [`2026-09-09-phase6-wpcs-docblock-formatting.md`](2026-09-09-phase6-wpcs-docblock-formatting.md) — los CI #88/#90 de Fase 6 documentan las reglas WPCS del repositorio para ternarios explícitos, alineación y `@throws` por método antes de considerar que PHPUnit llegó a ejecutarse.
- [`2026-09-10-phase7-wpcs-exception-and-eof.md`](2026-09-10-phase7-wpcs-exception-and-eof.md) — el CI #97 de Fase 7 registra el requisito de salto de línea POSIX y cómo WPCS trata los mensajes dinámicos de excepción como salida.
- [`2026-09-10-wp-env-alpine-tls-bootstrap.md`](2026-09-10-wp-env-alpine-tls-bootstrap.md) — el bootstrap Docker de `wp-env` puede fallar antes de ejecutar el plugin si el mirror/índice de paquetes Alpine sufre un error TLS transitorio; hay que diagnosticar primero el fallo de red y no asumir que el posterior `no such package` es una regresión del producto.
- [`2026-09-10-phase8-wpcli-network-activation.md`](2026-09-10-phase8-wpcli-network-activation.md) — Release Readiness de Fase 8 debe instalar primero el ZIP y después ejecutar `wp plugin activate ai-transparency --network`; `wp plugin install` no admite `--network-activate`.
- [`2026-09-10-phase8-stale-release-version-assertion.md`](2026-09-10-phase8-stale-release-version-assertion.md) — la assertion de versión de Evidence Export reapareció en 1.0.1 tras una corrección literal 0.1.0→1.0.0; ahora obtiene la versión actual desde la metadata validada del plugin y deja de hardcodear releases estables.
- [`2026-09-10-release-readiness-version-hardcode.md`](2026-09-10-release-readiness-version-hardcode.md) — el primer candidato 1.0.1 reveló literales de versión estable en Release Readiness; packaging y lifecycle del ZIP exacto ahora resuelven y propagan dinámicamente la versión validada del repositorio.
