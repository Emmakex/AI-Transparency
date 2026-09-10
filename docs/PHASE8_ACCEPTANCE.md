# Phase 8 — First Public Release Acceptance

Status: **repository-controlled acceptance complete; GitHub 1.0.0 published; WordPress.org external publication pending**  
Target stable release: **1.0.0**  
Last reviewed: 10 September 2026

This document is the blocking acceptance matrix for the first stable public release. Repository-controlled gates A–M are accepted. Section N remains the external WordPress.org publication gate.

## A. Version and metadata

- [x] `ai-transparency.php` header version is `1.0.0`.
- [x] `KAIROSETH_AI_TRANSPARENCY_VERSION` is `1.0.0`.
- [x] `readme.txt` Stable tag is `1.0.0`.
- [x] `CHANGELOG.md` contains the accepted `1.0.0` release entry.
- [x] package filename is `ai-transparency-1.0.0.zip`.
- [x] package root directory is exactly `ai-transparency/`.
- [x] automated validation fails on any version mismatch.

## B. WordPress.org readme readiness

- [x] short description is within WordPress.org length guidance.
- [x] maximum five relevant tags.
- [x] `Requires at least`, `Tested up to` and `Requires PHP` match real validation evidence.
- [x] external-service section accurately describes the explicit Kairoseth Custom Requests handoff.
- [x] no unsupported legal-compliance/certification claim.
- [x] no internal business strategy or commercial planning appears in `readme.txt`.
- [x] installation/FAQ/changelog/upgrade notice describe the stable release rather than a pre-release build.

## C. Exact distribution package

- [x] `bin/build-plugin.sh` creates the canonical runtime tree.
- [x] release tooling creates `dist/ai-transparency-1.0.0.zip`.
- [x] `dist/ai-transparency-1.0.0.zip.sha256` contains the digest of the exact ZIP.
- [x] build is reproducible from the accepted source/tag state.
- [x] ZIP contains required PHP, assets, license, readme and language files only.
- [x] ZIP excludes tests, CI configuration, `.git*`, node modules, development dependencies, build diagnostics and internal-only docs.
- [x] Spanish `.mo` exists and is non-empty inside the exact ZIP.
- [x] official WordPress Plugin Check validates the distribution package.

## D. Fresh install and activation

- [x] fresh WordPress installation accepts the 1.0.0 ZIP.
- [x] plugin activates without PHP warning/fatal.
- [x] all six accepted user workflows remain available after activation.
- [x] administrator-only pages retain `manage_options` authority.
- [x] Editor/non-authorized user remains denied.

## E. Upgrade preservation

- [x] representative 0.1.0 Registry state is created in the release lifecycle fixture.
- [x] exact 1.0.0 ZIP upgrades/replaces the 0.1.0 plugin.
- [x] Registry records survive unchanged except valid schema migration.
- [x] reviewed/archive state survives.
- [x] Disclosure eligibility/readiness remains consistent.
- [x] Evidence Export remains deterministic for equivalent technical state.
- [x] no upgrade step transmits local state externally.

## F. Deactivation/reactivation

- [x] deactivate 1.0.0.
- [x] Registry option remains present.
- [x] reactivate 1.0.0.
- [x] Registry remains readable and functional.
- [x] deactivation performs no destructive cleanup and no remote request.

## G. Uninstall — single site

- [x] `uninstall.php` is guarded by `WP_UNINSTALL_PLUGIN`.
- [x] uninstall deletes only the plugin-owned site-local Registry option.
- [x] unrelated options/content/users/uploads remain untouched.
- [x] uninstall performs no remote request.

## H. Uninstall — Multisite

- [x] plugin-owned Registry exists on at least two sites in acceptance.
- [x] network uninstall removes the Registry from every existing site.
- [x] original blog context is restored after iteration.
- [x] unrelated site/network data remains untouched.
- [x] no sites are created/deleted by uninstall.
- [x] no remote request occurs.

## I. EN/ES release package

- [x] runtime gettext coverage is 100% EN/ES.
- [x] POT and Spanish PO are synchronized.
- [x] compiled Spanish MO is generated from the accepted source state.
- [x] exact package passes Spanish/runtime acceptance.
- [x] customer-facing runtime release text is not partially untranslated.

## J. Responsive/accessibility regressions

- [x] existing 390 px acceptance remains green.
- [x] 200% text acceptance remains green.
- [x] axe serious/critical violations remain zero on accepted plugin pages.
- [x] keyboard/focus behavior remains accepted.

## K. Privacy/security regressions

- [x] Registry remains site-local.
- [x] no telemetry/background cloud request is introduced.
- [x] Discovery does not inspect provider credentials/prompts/conversations.
- [x] Evidence Export allow-list and signature semantics remain accepted.
- [x] Support page performs no Kairoseth request on load.
- [x] contextual support URL contains only the accepted bounded keys.
- [x] no credentials/secrets are packaged or logged.

## L. Compatibility gates

- [x] WordPress 7.1 real runtime acceptance green.
- [x] PHP 7.4 syntax green.
- [x] PHP 8.1 syntax green.
- [x] PHP 8.3 syntax green.
- [x] PHP 8.5 syntax green.
- [x] WPCS/PHPCompatibility/PHPUnit green.
- [x] official WordPress Plugin Check green.
- [x] real Multisite isolation/lifecycle smoke green.

## M. GitHub release gate

- [x] implementation PR green.
- [x] implementation PR merged.
- [x] post-merge `main` CI green.
- [x] exact 1.0.0 release artifact built from accepted `main`.
- [x] SHA-256 verified: `b7fc6e0b4a80d39e0b9331faf89c3ad7c310f3d5f24795123eb9ab89299bb368`.
- [x] Git tag `1.0.0` created only after final `main` acceptance.
- [x] tag `1.0.0` points to `5d0344876eb27db798ded87888b21b11b5581af5`.
- [x] GitHub Release `1.0.0` contains the exact accepted ZIP and checksum.
- [x] published release ZIP/checksum were re-downloaded and verified after creation.

Evidence: [`PHASE8_RELEASE_EVIDENCE.md`](PHASE8_RELEASE_EVIDENCE.md).

## N. WordPress.org publication gate

- [x] submission package is the accepted 1.0.0 artifact or its byte-equivalent approved packaging state.
- [ ] WordPress.org submission actually sent.
- [ ] external review status recorded factually.
- [ ] slug/repository access confirmed by WordPress.org before claiming assignment.
- [ ] public plugin page/download verified before claiming availability.

These are external dependencies. Repository-controlled release readiness is complete, but the project must not claim WordPress.org approval or availability until the corresponding external state is verified.

## Current closure state

```text
Repository-controlled Phase 8 gates: COMPLETE
Repository-controlled blockers: 0
GitHub stable release 1.0.0: PUBLISHED + VERIFIED
WordPress.org submission/review/publication: PENDING EXTERNAL GATE
```

Phase 8 remains **active only for the WordPress.org external-publication gate**. It must not be marked fully closed until Section N is complete or the project explicitly changes the release-state contract in a separately accepted decision.
