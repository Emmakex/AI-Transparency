# Phase 8 — stale release-version assertion in Evidence Export acceptance

Status: resolved pending final green regression verification  
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

Final verification is the next full CI run on the corrected PR head. The WordPress runtime acceptance job must pass the complete Evidence Export test together with inherited Registry, Discovery, Readiness, Disclosure, Support, responsive/accessibility and permission acceptance.

## Related evidence

```text
PR: #21
CI: #117
Run: 34442517924
Job: 102760391973 — WordPress runtime acceptance
Failed test: Phase 6 Evidence Export runtime acceptance
File: tests/e2e/evidence-export.spec.js
Failure signature: 3d132b6e4c784f327d3417e92284441cfe19219a96a2673072f3b2b3c54a09d7
```

---

## Español

Estado: resuelto, pendiente de la verificación de regresión verde final  
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

El siguiente CI completo del head corregido debe pasar WordPress runtime acceptance sin alterar el comportamiento funcional de Evidence Export.
