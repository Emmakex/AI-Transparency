# Phase 8 — WP-CLI Multisite network activation flag

Status: resolved and regression-verified  
First observed: 10 September 2026  
Affected area: Phase 8 Release Readiness / exact ZIP Multisite lifecycle  
Severity: medium

## Symptom

`Release Readiness #3` reached the Multisite lifecycle after the entire exact-ZIP single-site lifecycle had passed, then failed while attempting to install and network-activate the 1.0.0 ZIP:

```text
Error: Parameter errors:
 unknown --network-activate parameter
```

Affected command:

```text
wp plugin install <ai-transparency-1.0.0.zip> --network-activate --force
```

## Root cause

The Release Readiness workflow incorrectly assumed that `wp plugin install` accepts a `--network-activate` option. WP-CLI separates plugin installation from network activation: installation accepts the package and install options, while network activation is performed through `wp plugin activate <plugin> --network`.

This was a CI lifecycle-command contract error. The plugin ZIP, single-site installation, upgrade, deactivation/reactivation and uninstall behavior had already passed in the same run before this command was reached.

## Resolution

The Multisite setup is now explicit and ordered:

```text
wp plugin install <ai-transparency-1.0.0.zip> --force
wp plugin activate ai-transparency --network
wp eval-file .../release-multisite-lifecycle.php seed
```

## Prevention

- Treat WP-CLI install and activation as separate lifecycle operations in Multisite acceptance.
- Do not infer that single-site convenience flags have a network-activation equivalent on another WP-CLI command.
- Keep the exact ZIP Multisite lifecycle gate blocking for every release-relevant change.
- When a WP-CLI command fails with `unknown ... parameter`, diagnose command grammar before treating it as a plugin regression.

## Verification

Regression verification passed on `Release Readiness #8` / run `34443049889`:

```text
Reproducible 1.0.0 package — SUCCESS
Exact ZIP lifecycle acceptance — SUCCESS
Multisite install + explicit network activation — SUCCESS
Multisite seed across two sites — SUCCESS
Multisite uninstall isolation — SUCCESS
```

## Related evidence

```text
PR: #21
Failed Release Readiness: #3 / 34442517930
Failed job: 102760414027 — Exact ZIP lifecycle acceptance
Failure step: Install exact 1.0.0 ZIP network-wide and seed two sites
Single-site lifecycle before failure: PASS
Regression Release Readiness: #8 / 34443049889 — SUCCESS
Regression lifecycle job: 102761975110 — SUCCESS
```

---

## Español

Estado: resuelto y verificado mediante regresión  
Primera observación: 10 de septiembre de 2026  
Área afectada: Release Readiness de Fase 8 / lifecycle Multisite del ZIP exacto  
Severidad: media

### Síntoma

`Release Readiness #3` completó correctamente todo el lifecycle single-site del ZIP real y falló al iniciar la instalación/activación Multisite con:

```text
Error: Parameter errors:
 unknown --network-activate parameter
```

### Causa raíz

El workflow asumía incorrectamente que `wp plugin install` admitía `--network-activate`. WP-CLI separa la instalación de la activación de red: primero se instala el paquete y después se ejecuta `wp plugin activate ai-transparency --network`.

No era una regresión del plugin: instalación, upgrade `0.1.0 → 1.0.0`, desactivación/reactivación, uninstall y fresh install single-site ya habían pasado en el mismo run.

### Solución

Se cambió el flujo Multisite a:

```text
instalar ZIP con --force
→ activar ai-transparency con --network
→ ejecutar fixture Multisite
```

### Prevención

Los lifecycle tests de Multisite deben modelar instalación y activación como operaciones separadas y los errores `unknown ... parameter` deben diagnosticarse como gramática WP-CLI antes de atribuirse al producto.

### Verificación

`Release Readiness #8` / `34443049889` pasó los dos jobs, incluido instalación + network activation, seed en dos sites y uninstall Multisite aislado.
