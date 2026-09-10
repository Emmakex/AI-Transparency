# Phase 8 — First Public Release Acceptance

Status: **contract active**  
Target stable release: **1.0.0**  
Last reviewed: 10 September 2026

This document is the blocking acceptance matrix for the first stable public release.

## A. Version and metadata

- [ ] `ai-transparency.php` header version is `1.0.0`.
- [ ] `KAIROSETH_AI_TRANSPARENCY_VERSION` is `1.0.0`.
- [ ] `readme.txt` Stable tag is `1.0.0`.
- [ ] `CHANGELOG.md` contains the accepted `1.0.0` release entry.
- [ ] package filename is `ai-transparency-1.0.0.zip`.
- [ ] package root directory is exactly `ai-transparency/`.
- [ ] automated validation fails on any version mismatch.

## B. WordPress.org readme readiness

- [ ] short description is within WordPress.org length guidance.
- [ ] maximum five relevant tags.
- [ ] `Requires at least`, `Tested up to` and `Requires PHP` match real validation evidence.
- [ ] external-service section accurately describes the explicit Kairoseth Custom Requests handoff.
- [ ] no unsupported legal-compliance/certification claim.
- [ ] no internal business strategy or commercial planning appears in `readme.txt`.
- [ ] installation/FAQ/changelog/upgrade notice describe the stable release rather than a pre-release build.

## C. Exact distribution package

- [ ] `bin/build-plugin.sh` creates the canonical runtime tree.
- [ ] a release command creates `dist/ai-transparency-1.0.0.zip`.
- [ ] `dist/ai-transparency-1.0.0.zip.sha256` contains the digest of the exact ZIP.
- [ ] build is reproducible from the accepted tagged/source state.
- [ ] ZIP contains required PHP, assets, license, readme and language files only.
- [ ] ZIP excludes tests, CI configuration, `.git*`, node modules, development dependencies, build diagnostics and internal-only docs.
- [ ] Spanish `.mo` exists and is non-empty inside the exact ZIP.
- [ ] official WordPress Plugin Check validates the exact distribution tree/package.

## D. Fresh install and activation

- [ ] fresh WordPress installation accepts the 1.0.0 ZIP.
- [ ] plugin activates without PHP warning/fatal.
- [ ] all six accepted user workflows remain available after activation.
- [ ] administrator-only pages retain `manage_options` authority.
- [ ] Editor/non-authorized user remains denied.

## E. Upgrade preservation

- [ ] create representative 0.1.0 Registry state.
- [ ] upgrade/replace plugin with 1.0.0.
- [ ] Registry records survive unchanged except valid schema migration.
- [ ] reviewed/archive state survives.
- [ ] Disclosure eligibility/readiness remains consistent.
- [ ] Evidence Export remains deterministic for equivalent technical state.
- [ ] no upgrade step transmits local state externally.

## F. Deactivation/reactivation

- [ ] deactivate 1.0.0.
- [ ] Registry option remains present.
- [ ] reactivate 1.0.0.
- [ ] Registry remains readable and functional.
- [ ] deactivation performs no destructive cleanup and no remote request.

## G. Uninstall — single site

- [ ] `uninstall.php` is guarded by `WP_UNINSTALL_PLUGIN`.
- [ ] uninstall deletes only the plugin-owned site-local Registry option.
- [ ] unrelated options/content/users/uploads remain untouched.
- [ ] uninstall performs no remote request.

## H. Uninstall — Multisite

- [ ] plugin-owned Registry exists on at least two sites.
- [ ] network uninstall removes the Registry from every existing site.
- [ ] original blog context is restored after iteration.
- [ ] unrelated site/network data remains untouched.
- [ ] no sites are created/deleted.
- [ ] no remote request occurs.

## I. EN/ES release package

- [ ] runtime gettext coverage is 100% EN/ES.
- [ ] POT and Spanish PO are synchronized.
- [ ] compiled Spanish MO is generated from the accepted source state.
- [ ] exact package passes Spanish runtime smoke.
- [ ] customer-facing release/readme text is not partially untranslated where runtime translation is expected.

## J. Responsive/accessibility regressions

- [ ] existing 390 px acceptance remains green.
- [ ] 200% text acceptance remains green.
- [ ] axe serious/critical violations remain zero on accepted plugin pages.
- [ ] keyboard/focus behavior remains accepted.

## K. Privacy/security regressions

- [ ] Registry remains site-local.
- [ ] no telemetry/background cloud request is introduced.
- [ ] Discovery does not inspect provider credentials/prompts/conversations.
- [ ] Evidence Export allow-list and signature semantics remain unchanged unless separately versioned/documented.
- [ ] Support page performs no Kairoseth request on load.
- [ ] contextual support URL contains only the accepted bounded keys.
- [ ] no credentials/secrets are packaged or logged.

## L. Compatibility gates

- [ ] WordPress 7.1 real runtime acceptance green.
- [ ] PHP 7.4 syntax green.
- [ ] PHP 8.1 syntax green.
- [ ] PHP 8.3 syntax green.
- [ ] PHP 8.5 syntax green.
- [ ] WPCS/PHPCompatibility/PHPUnit green.
- [ ] official WordPress Plugin Check green.
- [ ] real Multisite isolation/lifecycle smoke green.

## M. GitHub release gate

- [ ] implementation PR green.
- [ ] implementation PR merged.
- [ ] post-merge `main` CI green.
- [ ] exact 1.0.0 release artifact built from accepted `main`.
- [ ] SHA-256 verified.
- [ ] Git tag `1.0.0` created only after final `main` acceptance.
- [ ] GitHub Release `1.0.0` contains the exact accepted ZIP and checksum.
- [ ] release asset/checksum verified after creation.

## N. WordPress.org publication gate

- [ ] submission package is the accepted 1.0.0 artifact or byte-equivalent approved packaging state.
- [ ] WordPress.org submission actually sent.
- [ ] external review status recorded factually.
- [ ] slug/repository access confirmed by WordPress.org before claiming assignment.
- [ ] public plugin page/download verified before claiming availability.

Items in this section are external dependencies. Technical repository readiness may be complete while these remain pending, but Phase 8 must not claim WordPress.org publication until they are actually complete.

## Closure rule

Phase 8 may be marked **Closed** only when all repository-controlled gates required for the chosen release state are green, blockers are zero, evidence is recorded, and any external publication claim matches the externally verified state.