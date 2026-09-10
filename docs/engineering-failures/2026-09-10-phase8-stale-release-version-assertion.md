# Phase 8 — stale release-version assertion in Evidence Export acceptance

Status: resolved and regression-protected  
First observed: 10 September 2026  
Recurrence observed: 10 September 2026 during 1.0.1 preparation  
Affected area: CI / Playwright Evidence Export acceptance / release version transition  
Severity: release-blocking when preparing a new stable version  
Failure signature: `3d132b6e4c784f327d3417e92284441cfe19219a96a2673072f3b2b3c54a09d7`

## Symptom

The issue first appeared when Phase 8 intentionally changed the plugin from development version `0.1.0` to stable `1.0.0`. Runtime Evidence Export correctly returned `plugin_version: "1.0.0"`, while the inherited Phase 6 Playwright assertion still expected `0.1.0`.

The initial fix replaced the stale `0.1.0` literal with `1.0.0`. That made the first stable release green, but did not remove the underlying version-specific test contract.

The same failure signature therefore recurred while preparing `1.0.1`:

```text
CI: #138 / 34461359565
job: WordPress runtime acceptance
step: Run CRUD, discovery, permission, responsive and accessibility acceptance
test: tests/e2e/evidence-export.spec.js:77
assertion: tests/e2e/evidence-export.spec.js:149-152
Expected plugin_version: 1.0.0
Received plugin_version: 1.0.1
exit: 1
signature: 3d132b6e4c784f327d3417e92284441cfe19219a96a2673072f3b2b3c54a09d7
```

## Root cause

The production runtime was correct in both incidents: Evidence Export reported the real server-authoritative plugin version.

The real root cause was that the E2E contract encoded whichever version happened to be current at the time (`0.1.0`, then `1.0.0`) instead of deriving the expected version from the same validated plugin metadata that controls the running package. Updating one literal to another only postponed the failure until the next stable release.

## Durable resolution

The Evidence Export Playwright test now resolves the current semantic version directly from the `Version:` header in `ai-transparency.php` and compares exported `generator.plugin_version` against that value.

This preserves the meaningful assertion:

```text
Evidence Export must report the current packaged plugin version
```

without coupling the test implementation to a particular release number.

The stable release metadata gate remains responsible for validating consistency between plugin header, runtime constant, readme Stable Tag and release packaging.

## Prevention

- Tests that verify the **current** product version must derive that expectation from validated repository/runtime metadata; they must not hardcode the current stable number.
- Fixed version literals are allowed only when they deliberately identify immutable historical fixtures, such as the accepted `0.1.0` upgrade baseline.
- A release-version transition must not require editing behavioral assertions whose intent is simply “runtime reports the current version”.
- If runtime reports the new validated version while only a test expects the old version, classify it as a stale test contract rather than reverting production metadata.
- Failure-memory fixes must remove the class of recurrence, not just replace one stale literal with the next value.

## Verification

Initial 1.0.0 regression verification passed in **CI #122 / run `34443049878`**, but the 1.0.1 recurrence proved that the first remediation was insufficient.

The durable 1.0.1 fix is accepted only after:

```text
CI: all 8 jobs green, including WordPress runtime acceptance
Evidence Export E2E: current metadata-derived version matches exported generator version
Release Readiness: reproducible stable package + exact ZIP lifecycle green
post-merge main verification green
```

## Related evidence

```text
Initial failure:
PR: #21
Failed CI: #117 / 34442517924
Failed job: 102760391973
Expected: 0.1.0
Received: 1.0.0
Signature: 3d132b6e4c784f327d3417e92284441cfe19219a96a2673072f3b2b3c54a09d7
Initial regression CI: #122 / 34443049878 — SUCCESS

Recurrence:
PR: #24
Failed CI: #138 / 34461359565
Failed job: 102819873903 — WordPress runtime acceptance
Expected: 1.0.0
Received: 1.0.1
Signature: 3d132b6e4c784f327d3417e92284441cfe19219a96a2673072f3b2b3c54a09d7
```

---

## Español

Estado: resuelto con protección de regresión  
Primera observación: 10 de septiembre de 2026  
Recurrencia: 10 de septiembre de 2026 durante la preparación de 1.0.1  
Área afectada: CI / aceptación Playwright de Evidence Export / transición de versión  
Severidad: bloqueante para release  
Firma: `3d132b6e4c784f327d3417e92284441cfe19219a96a2673072f3b2b3c54a09d7`

### Síntoma

Al pasar de `0.1.0` a `1.0.0`, Evidence Export devolvía correctamente `1.0.0`, pero el E2E esperaba literalmente `0.1.0`. La primera corrección cambió ese literal a `1.0.0`; esto permitió publicar la primera estable, pero dejó intacta la dependencia de una versión concreta.

Al preparar `1.0.1`, el mismo fallo reapareció:

```text
CI: #138 / 34461359565
job: WordPress runtime acceptance
test: tests/e2e/evidence-export.spec.js:77
Expected plugin_version: 1.0.0
Received plugin_version: 1.0.1
exit: 1
firma: 3d132b6e4c784f327d3417e92284441cfe19219a96a2673072f3b2b3c54a09d7
```

### Causa raíz

El runtime era correcto. El problema real era que la prueba codificaba como literal la versión vigente en cada momento, en vez de obtener la expectativa de la misma metadata validada que define el paquete del plugin. Cambiar `0.1.0` por `1.0.0` solo trasladó el fallo a la siguiente release.

### Solución durable

El test E2E obtiene ahora la versión semántica actual desde el header `Version:` de `ai-transparency.php` y comprueba que `generator.plugin_version` exporta exactamente ese valor.

Así se valida el contrato correcto —“Evidence Export informa la versión actual del plugin”— sin acoplarlo a un número de release concreto.

### Prevención

- Las pruebas sobre la **versión actual** deben derivarla de metadata validada; no deben hardcodear la versión estable vigente.
- Los literales de versión solo se mantienen para fixtures históricos inmutables, como el baseline real `0.1.0` de upgrade.
- Cambiar de patch/minor/major no debe exigir editar assertions cuyo contrato es simplemente reflejar la versión runtime actual.
- La memoria de fallos debe eliminar la clase de recurrencia, no limitarse a sustituir un literal obsoleto por otro.

### Verificación

La corrección se acepta únicamente con CI completo, Evidence Export E2E, Release Readiness y verificación post-merge verdes.
