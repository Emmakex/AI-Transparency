# Release Readiness stable-version hardcode

[English](#english) · [Español](#español)

---

## English

**Status:** resolved  
**First observed:** 2026-09-10  
**Affected area:** GitHub Actions / stable release packaging and exact-ZIP lifecycle  
**Severity:** release-blocking

### Symptom

The first 1.0.1 release candidate passed ordinary version consistency but `Release Readiness` failed before producing its exact release artifact.

```text
workflow: Release Readiness
job: Reproducible 1.0.0 package
step: Build and verify exact release package twice
command: bash bin/run-with-diagnostics.sh "Phase 8 reproducible release package" -- bash bin/verify-release-package.sh
exit: 1
message: Release version must be 1.0.0 for this build; found 1.0.1.
signature: 97938adad425c77118aee7a81ba0b795a63eae0f0c2c833391cb8ce8fdbf0439
```

### Root cause

Phase 8 originally contracted and validated the first stable release, so `release-readiness.yml`, `verify-release-package.sh`, and the exact-ZIP lifecycle assertions encoded `1.0.0` as the target stable version. That was correct for the first publication but accidentally made the reusable release pipeline version-specific. The first patch release therefore conflicted with CI even though repository metadata consistently declared 1.0.1.

### Resolution

- resolve the canonical stable version from `bin/check-release.php --print-version`;
- pass that version through the release-package job output and lifecycle job environment;
- name/download/checksum/install artifacts using the resolved version;
- parameterize single-site upgrade/fresh-install and Multisite seed assertions with the resolved stable version;
- keep only the historical `0.1.0` upgrade fixture fixed, because that value identifies the accepted pre-stable baseline;
- make `verify-release-package.sh` derive the repository version when no explicit expected version is provided.

### Prevention

Release automation must never hardcode the current stable version unless it is intentionally identifying a historical immutable fixture. Current release identity comes from the repository's validated release metadata and is propagated into packaging and lifecycle gates.

A future patch/minor/major release must therefore pass the same reusable pipeline without editing version literals inside workflow or runtime acceptance logic.

### Verification

Required verification for the fix:

```text
CI: all required jobs green
Release Readiness: reproducible package green
Exact ZIP lifecycle: single-site upgrade/deactivate/uninstall/fresh install green
Exact ZIP lifecycle: Multisite network install/uninstall green
post-merge main CI + Release Readiness green
stable release automation publishes only after both accepted-main gates pass
```

### Related evidence

- PR #24 — WordPress.org Plugin URI metadata patch and release-pipeline generalization.
- Release Readiness run #16 / 34460848625 — original failing run.
- Failed job: 102818061204.

---

## Español

**Estado:** resuelto  
**Primera observación:** 2026-09-10  
**Área afectada:** GitHub Actions / packaging de release estable y lifecycle del ZIP exacto  
**Severidad:** bloqueante para release

### Síntoma

El primer candidato 1.0.1 superó la consistencia normal de versión, pero `Release Readiness` falló antes de producir el artefacto exacto.

```text
workflow: Release Readiness
job: Reproducible 1.0.0 package
step: Build and verify exact release package twice
command: bash bin/run-with-diagnostics.sh "Phase 8 reproducible release package" -- bash bin/verify-release-package.sh
exit: 1
mensaje: Release version must be 1.0.0 for this build; found 1.0.1.
firma: 97938adad425c77118aee7a81ba0b795a63eae0f0c2c833391cb8ce8fdbf0439
```

### Causa raíz

La Fase 8 se diseñó inicialmente para validar la primera release estable, por lo que `release-readiness.yml`, `verify-release-package.sh` y las assertions de lifecycle codificaban `1.0.0` como versión estable objetivo. Era correcto para la primera publicación, pero convirtió accidentalmente el pipeline reusable en uno específico de esa versión. El primer patch release chocó así con CI aunque toda la metadata del repositorio declaraba 1.0.1 de forma consistente.

### Solución

- resolver la versión estable canónica desde `bin/check-release.php --print-version`;
- propagarla mediante outputs/env entre packaging y lifecycle;
- nombrar, descargar, verificar e instalar artefactos con esa versión;
- parametrizar las assertions de upgrade/fresh install y Multisite;
- mantener fijo solo el fixture histórico `0.1.0`, porque identifica el baseline pre-stable aceptado;
- hacer que `verify-release-package.sh` derive la versión del repositorio cuando no recibe una versión esperada explícita.

### Prevención

La automatización de release no debe hardcodear la versión estable actual salvo cuando identifica deliberadamente un fixture histórico inmutable. La identidad de cada nueva release se obtiene de la metadata validada del repositorio y se propaga a los gates de packaging y lifecycle.

Un futuro patch/minor/major debe poder pasar el mismo pipeline sin editar literales de versión en workflows o assertions runtime.

### Verificación

```text
CI: todos los jobs requeridos verdes
Release Readiness: package reproducible verde
Lifecycle ZIP exacto: upgrade/deactivate/uninstall/fresh install single-site verde
Lifecycle ZIP exacto: install/uninstall Multisite verde
CI + Release Readiness post-merge en main verdes
Stable Release solo publica después de ambos gates del SHA aceptado
```

### Evidencia relacionada

- PR #24 — corrección Plugin URI de WordPress.org y generalización del pipeline.
- Release Readiness #16 / 34460848625 — ejecución que detectó el fallo.
- Job fallido: 102818061204.
