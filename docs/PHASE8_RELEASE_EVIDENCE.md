# Phase 8 — Stable Release Evidence

Status: **repository-controlled release accepted; GitHub 1.0.0 published; WordPress.org publication pending external submission/review**  
Release: **1.0.0**  
Accepted source SHA: `5d0344876eb27db798ded87888b21b11b5581af5`  
Recorded: 10 September 2026

[English](#english) · [Español](#español)

---

## English

### Outcome

Kairoseth AI Transparency **1.0.0** is the first stable repository release.

The repository-controlled Phase 8 gates are complete:

```text
reproducible production package
→ exact ZIP checksum
→ real 0.1.0 → 1.0.0 upgrade
→ deactivate/reactivate preservation
→ explicit single-site uninstall scope
→ fresh 1.0.0 installation
→ real Multisite network activation/uninstall lifecycle
→ inherited WordPress runtime/regression acceptance
→ implementation PR accepted and merged
→ final main CI accepted
→ final main Release Readiness accepted
→ stable tag created from accepted main SHA
→ GitHub Release created
→ release ZIP + checksum re-downloaded and verified
```

WordPress.org submission, review, slug assignment and public-directory availability are external gates and are **not** claimed complete by this document.

### Phase 8 implementation PR

```text
Implementation PR: #21 — feat: prepare stable 1.0.0 public release
Accepted final PR head: d4aeea5f1e5e25ebad7204b3bfa15e9c74c557d3
PR-head CI: #125 / 34443466518 — SUCCESS — 8/8 jobs green
PR-head Release Readiness: #11 / 34443466517 — SUCCESS — 2/2 jobs green
Implementation merge: 9fea609de553e10af1618d385ccb85e4ab695ffe
Post-merge CI: #126 / 34443762226 — SUCCESS — 8/8 jobs green
Post-merge Release Readiness: #12 / 34443762222 — SUCCESS — 2/2 jobs green
```

### Stable-release automation hardening

```text
Release automation PR: #22 — ci: publish stable release after accepted main readiness
Accepted final PR head: a3eee1dd61d7ea2cdf65826074019eda1772438a
PR-head CI: #128 / 34444548560 — SUCCESS — 8/8 jobs green
PR-head Release Readiness: #14 / 34444548568 — SUCCESS — 2/2 jobs green
Merge: 5d0344876eb27db798ded87888b21b11b5581af5
Final main CI: #129 / 34449448225 — SUCCESS — 8/8 jobs green
Final main Release Readiness: #15 / 34449448874 — SUCCESS — 2/2 jobs green
Stable Release: #1 / 34449679699 — SUCCESS
```

The release workflow is operationally fail-closed:

- it releases only from the current accepted `main` commit;
- it resolves the semantic version from repository release metadata;
- it requires successful `CI` and `Release Readiness` runs for the same SHA;
- it creates the version tag only when absent and refuses to move a conflicting existing tag;
- it rebuilds the exact release artifact from accepted source;
- it refuses to overwrite an existing release during publication;
- it re-downloads the ZIP and checksum and compares them to the accepted artifacts;
- a rerun can verify an existing release only when its tag resolves to the same accepted source SHA.

This is an engineering immutability policy enforced by the workflow. It is not a claim that GitHub's native release object has its optional `immutable` property enabled.

### Exact 1.0.0 artifact

```text
filename: ai-transparency-1.0.0.zip
package root: ai-transparency/
SHA-256: b7fc6e0b4a80d39e0b9331faf89c3ad7c310f3d5f24795123eb9ab89299bb368
Git tag: 1.0.0
Tag target: 5d0344876eb27db798ded87888b21b11b5581af5
GitHub Release: Kairoseth AI Transparency 1.0.0
Release state: published, non-draft, non-prerelease
```

GitHub reported the published ZIP asset with the same SHA-256 digest:

```text
sha256:b7fc6e0b4a80d39e0b9331faf89c3ad7c310f3d5f24795123eb9ab89299bb368
```

`Stable Release #1` also completed the repository's explicit **Verify release assets byte-for-byte** step successfully.

### Exact lifecycle acceptance

Release Readiness proved the release ZIP itself rather than only the repository source tree.

Single-site acceptance:

```text
real 0.1.0 baseline installed
→ representative Registry seeded
→ exact 1.0.0 ZIP installed as upgrade
→ Registry preserved
→ deactivate preserves Registry
→ reactivate preserves/uses Registry
→ explicit uninstall removes plugin-owned Registry only
→ fresh exact 1.0.0 ZIP install succeeds
```

Multisite acceptance:

```text
exact 1.0.0 ZIP installed
→ plugin activated network-wide
→ plugin-owned Registry seeded on multiple sites
→ unrelated site/network sentinel data preserved
→ network deactivate
→ explicit uninstall
→ plugin-owned Registry removed from all existing sites
→ unrelated site/network data preserved
→ no site creation/deletion by uninstall
```

### Runtime and compatibility acceptance

Final `main` CI #129 proved:

```text
PHP quality / WPCS / PHPCompatibility / PHPUnit: GREEN
EN/ES runtime gettext coverage: GREEN
PHP 7.4 syntax: GREEN
PHP 8.1 syntax: GREEN
PHP 8.3 syntax: GREEN
PHP 8.5 syntax: GREEN
official WordPress Plugin Check: GREEN
real WordPress runtime acceptance: GREEN
real Multisite isolation smoke: GREEN
```

The inherited runtime acceptance includes Registry, Discovery, Readiness, Disclosure, Evidence Export, contextual support, authorization, privacy boundaries, responsive acceptance and accessibility regression checks.

### WordPress.org external publication state

Current recorded state:

```text
accepted 1.0.0 submission artifact: READY
WordPress.org submission: PENDING / not recorded as sent
WordPress.org external review: PENDING
slug/repository assignment: NOT VERIFIED
public WordPress.org plugin page: NOT VERIFIED
public WordPress.org download: NOT VERIFIED
```

The project may claim **stable 1.0.0 released on GitHub and ready for WordPress.org submission**.

It must not claim **available on WordPress.org**, **WordPress.org approved**, or **WordPress.org slug assigned** until those states are independently verified.

### Repository-controlled blockers

```text
Repository-controlled blockers: 0
External WordPress.org publication dependency: OPEN
```

---

## Español

### Resultado

Kairoseth AI Transparency **1.0.0** es la primera versión estable publicada desde el repositorio.

Los gates controlados por el repositorio están completos:

```text
paquete reproducible
→ checksum del ZIP exacto
→ upgrade real 0.1.0 → 1.0.0
→ preservación al desactivar/reactivar
→ uninstall single-site acotado
→ instalación limpia 1.0.0
→ lifecycle Multisite real
→ regresiones/runtime WordPress heredadas
→ PR de implementación aceptado y mergeado
→ CI final de main verde
→ Release Readiness final de main verde
→ tag estable creado desde el SHA aceptado
→ GitHub Release creado
→ ZIP + checksum descargados de nuevo y verificados
```

La solicitud, revisión, asignación de slug y publicación en WordPress.org son gates externos y **no** se consideran completados en este documento.

### Evidencia de implementación

```text
PR implementación: #21
Head final aceptado: d4aeea5f1e5e25ebad7204b3bfa15e9c74c557d3
CI del PR: #125 / 34443466518 — SUCCESS — 8/8 verde
Release Readiness del PR: #11 / 34443466517 — SUCCESS — 2/2 verde
Merge: 9fea609de553e10af1618d385ccb85e4ab695ffe
CI post-merge: #126 / 34443762226 — SUCCESS — 8/8 verde
Release Readiness post-merge: #12 / 34443762222 — SUCCESS — 2/2 verde
```

### Automatización de publicación estable

```text
PR automatización: #22
Head final aceptado: a3eee1dd61d7ea2cdf65826074019eda1772438a
CI del PR: #128 / 34444548560 — SUCCESS — 8/8 verde
Release Readiness del PR: #14 / 34444548568 — SUCCESS — 2/2 verde
Merge/main aceptado: 5d0344876eb27db798ded87888b21b11b5581af5
CI final main: #129 / 34449448225 — SUCCESS — 8/8 verde
Release Readiness final main: #15 / 34449448874 — SUCCESS — 2/2 verde
Stable Release: #1 / 34449679699 — SUCCESS
```

El workflow solo publica desde `main` aceptado, exige CI + Release Readiness del mismo SHA, no mueve tags existentes, reconstruye el artefacto y vuelve a descargar/verificar ZIP + checksum después de publicar.

### Artefacto exacto 1.0.0

```text
archivo: ai-transparency-1.0.0.zip
raíz: ai-transparency/
SHA-256: b7fc6e0b4a80d39e0b9331faf89c3ad7c310f3d5f24795123eb9ab89299bb368
tag: 1.0.0
SHA del tag: 5d0344876eb27db798ded87888b21b11b5581af5
GitHub Release: Kairoseth AI Transparency 1.0.0
estado: publicado, no draft, no prerelease
```

El digest del ZIP publicado coincide con el artefacto aceptado y `Stable Release #1` completó correctamente la comparación byte a byte.

### Aceptación lifecycle y compatibilidad

Se probaron con el ZIP exacto el upgrade desde 0.1.0, preservación al desactivar/reactivar, uninstall acotado, instalación limpia y lifecycle Multisite. El CI final mantuvo verdes PHP 7.4/8.1/8.3/8.5, WPCS/PHPCompatibility/PHPUnit, EN/ES, Plugin Check oficial, runtime WordPress y aislamiento Multisite.

### Estado externo WordPress.org

```text
artefacto 1.0.0 aceptado para submission: LISTO
submission WordPress.org: PENDIENTE / no registrada como enviada
revisión externa: PENDIENTE
asignación slug/repositorio: NO VERIFICADA
página pública WordPress.org: NO VERIFICADA
descarga pública WordPress.org: NO VERIFICADA
```

Se puede afirmar **1.0.0 estable publicado en GitHub y listo para enviar a WordPress.org**.

No se puede afirmar **disponible en WordPress.org**, **aprobado por WordPress.org** o **slug asignado** hasta verificarlo externamente.

```text
Bloqueadores controlados por repositorio: 0
Dependencia externa WordPress.org: ABIERTA
```
