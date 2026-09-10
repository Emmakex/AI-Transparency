# Phase 8 — 1.0.0 Implementation Evidence

Status: **implementation candidate accepted on PR; merge/main/tag verification pending**  
Target release: **1.0.0**  
Evidence date: 10 September 2026

This record captures repository-controlled evidence for the Phase 8 implementation PR before merge. It does not claim WordPress.org submission, approval, slug assignment or publication.

## Candidate identity

```text
PR: #21 — feat: prepare stable 1.0.0 public release
Accepted release-code head: 55140a8142cb6a3e61181dc5d43adc4d14b1ad44
Base main: 42d04ab65bcb5d5727eacd9d380c705bb19ccead
Version: 1.0.0
Slug / package root: ai-transparency
ZIP: ai-transparency-1.0.0.zip
Accepted candidate SHA-256: b7fc6e0b4a80d39e0b9331faf89c3ad7c310f3d5f24795123eb9ab89299bb368
```

The SHA-256 above identifies the reproducible ZIP built by the accepted release-code head. Documentation-only commits after that head do not enter the plugin distribution package. The final tagged artifact will be rebuilt from accepted `main` and must be verified again; its checksum is authoritative for the GitHub Release.

## PR CI evidence

```text
CI: #122
Run: 34443049878
Result: SUCCESS — 8/8 jobs green
```

Passed jobs:

- PHP quality: WPCS, PHPCompatibility, PHPUnit, i18n and release metadata gate;
- EN/ES 100% coverage and compiled Spanish catalog;
- PHP 7.4 syntax;
- PHP 8.1 syntax;
- PHP 8.3 syntax;
- PHP 8.5 syntax;
- official WordPress Plugin Check;
- WordPress runtime acceptance, including browser, permissions, responsive/accessibility and Multisite isolation.

The release-version transition regression was explicitly re-proved: Evidence Export emitted `plugin_version=1.0.0` and the full runtime acceptance passed.

## Release Readiness evidence

```text
Release Readiness: #8
Run: 34443049889
Result: SUCCESS — 2/2 jobs green
```

### Reproducible 1.0.0 package

Job `102761924097` passed:

```text
release metadata = 1.0.0
slug = ai-transparency
WordPress.org short description = 121 characters
EN/ES runtime strings = 133/133 translated
compiled catalog entries = 134
ZIP built twice
byte-for-byte comparison = equal
SHA-256 = b7fc6e0b4a80d39e0b9331faf89c3ad7c310f3d5f24795123eb9ab89299bb368
```

The exact candidate contains the canonical `ai-transparency/` root and required runtime files, including `uninstall.php`, POT/PO/MO and runtime CSS, while excluding development-only content.

### Exact ZIP lifecycle acceptance

Job `102761975110` passed all lifecycle stages:

```text
build real 0.1.0 baseline from accepted pre-implementation main — PASS
install and seed 0.1.0 single-site — PASS
upgrade exact ZIP 0.1.0 → 1.0.0 — PASS
Registry preservation — PASS
deactivate 1.0.0 — PASS
Registry remains — PASS
reactivate 1.0.0 — PASS
Registry remains usable — PASS
explicit single-site uninstall — PASS
plugin-owned Registry removed — PASS
fresh exact 1.0.0 ZIP install — PASS
start Multisite — PASS
install exact 1.0.0 ZIP — PASS
network activate — PASS
seed plugin Registry on at least two sites — PASS
explicit Multisite uninstall — PASS
Registry removed from sites — PASS
unrelated site/network sentinels preserved — PASS
```

## Release engineering boundaries

The implementation adds no new product/cloud functionality. Release engineering preserves the previously accepted guarantees:

```text
local-first Registry
+ server-authoritative permissions/nonces
+ deterministic Discovery/Readiness/Disclosure/Evidence
+ no automatic sensitive upload
+ explicit bounded Kairoseth support navigation only
+ EN/ES 100%
+ responsive/accessibility acceptance
+ site-local Multisite boundaries
```

Deactivation and upgrade do not delete Registry data. `uninstall.php` is the only destructive lifecycle path and removes only `kairoseth_ai_transparency_registry`, including site-by-site Multisite cleanup.

## Failure-to-regression evidence

Two implementation-time failures were diagnosed, fixed and added to durable engineering memory:

- `docs/engineering-failures/2026-09-10-phase8-wpcli-network-activation.md`;
- `docs/engineering-failures/2026-09-10-phase8-stale-release-version-assertion.md`.

Both are regression-verified by CI #122 and Release Readiness #8.

## Remaining sequence

This evidence does not authorize skipping the remaining Phase 8 sequence:

```text
final documentation-only PR-head CI
→ mark PR #21 ready
→ merge implementation
→ main CI green
→ main Release Readiness green
→ rebuild exact 1.0.0 from accepted main
→ create tag 1.0.0
→ Stable Release workflow publishes GitHub Release
→ verify downloaded release asset byte-for-byte
```

WordPress.org remains a separate external publication dependency. Until submission/review/approval is actually verified, the repository may claim GitHub/repository release readiness but not WordPress.org availability.
