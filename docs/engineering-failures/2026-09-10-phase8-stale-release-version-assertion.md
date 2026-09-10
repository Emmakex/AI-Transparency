# Phase 8 — stale release-version assertion in Evidence Export acceptance

Status: resolved and regression-verified  
First observed: 10 September 2026  
Affected area: CI / Playwright Evidence Export acceptance / release version transition  
Severity: low-medium  
Failure signature: `3d132b6e4c784f327d3417e92284441cfe19219a96a2673072f3b2b3c54a09d7`

## Symptom

The Phase 8 PR intentionally changed the plugin release version to `1.0.0`. The runtime Evidence Export correctly returned:

```json
{
  "plugin_slug": "ai-transparency",
  "plugin_name": "Kairoseth AI Transparency",
  "plugin_version": "1.0.0"
}
```

but the inherited Phase 6 Playwright assertion still expected `plugin_version: '0.1.0'`.

The exact failure was in `tests/e2e/evidence-export.spec.js` around line 149:

```text
Expected plugin_version: 0.1.0
Received plugin_version: 1.0.0
```

## Root cause

The production version bump updated the server-authoritative plugin header/runtime constant and release metadata, but one historical end-to-end assertion encoded the old development version literally. The test represented a version-specific fixture rather than the current release contract.

The Evidence Export behavior itself was correct: it reported the real running plugin version.

## Resolution

The Evidence Export runtime expectation was updated to `1.0.0`, matching the accepted Phase 8 release target and the runtime value emitted by the plugin.

## Prevention

- Every intentional stable-version transition must search test/runtime fixtures for the previous version value.
- The release metadata gate remains authoritative for plugin header, runtime constant and readme Stable Tag.
- E2E tests that intentionally verify current release metadata must move with the release version in the same implementation PR.
- A mismatch where runtime reports the new accepted version and only a test expects the old development version should be classified as a stale test contract, not fixed by reverting production metadata.

## Verification

Regression verification passed in **CI #122 / run `34443049878`**. The `WordPress runtime acceptance` job `102761913086` completed successfully, including the corrected Evidence Export assertion plus the inherited Registry, Discovery, Readiness, Disclosure, Support, responsive/accessibility, permission and Multisite acceptance.

## Related evidence

```text
PR: #21
Failed CI: #117 / 34442517924
Failed job: 102760391973 — WordPress runtime acceptance
Failed test: Phase 6 Evidence Export runtime acceptance
File: tests/e2e/evidence-export.spec.js
Failure signature: 3d132b6e4c784f327d3417e92284441cfe19219a96a2673072f3b2b3c54a09d7
Regression CI: #122 / 34443049878 — SUCCESS — 8/8
Regression runtime job: 102761913086 — SUCCESS
```

---

## Español

Estado: resuelto y verificado mediante regresión  
Primera observación: 10 de septiembre de 2026  
Área afectada: CI / aceptación Playwright de Evidence Export / transición de versión  
Severidad: baja-media

### Síntoma

El plugin pasó correctamente a `1.0.0` y Evidence Export devolvió la versión real `1.0.0`, pero un assertion heredado de Fase 6 seguía esperando literalmente `0.1.0`.

### Causa raíz

El cambio de versión estable actualizó el contrato de producción, pero una prueba E2E histórica mantenía codificada la versión de desarrollo anterior. El producto se comportaba correctamente; el contrato de prueba estaba obsoleto.

### Solución

La expectativa de Evidence Export se actualizó a `1.0.0`.

### Prevención

Cada transición de versión estable debe buscar referencias a la versión anterior en tests/fixtures, manteniendo como fuente de verdad el header, la constante runtime y `Stable Tag` validados por el release gate.

### Verificación

**CI #122 / `34443049878`** quedó 8/8 verde y el job `WordPress runtime acceptance` pasó Evidence Export y todas las regresiones heredadas.
