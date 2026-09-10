# Phase 8 — First Public Release Implementation Contract

Status: **active contract — implementation not yet accepted**  
Target release: **1.0.0**  
Target WordPress.org slug: **`ai-transparency`**  
Last reviewed: 10 September 2026

## Goal

Prepare the first stable public release of Kairoseth AI Transparency as a reproducible, installable and reviewable WordPress plugin package without weakening the local-first/privacy, authorization, EN/ES, accessibility, Multisite or deterministic-evidence guarantees accepted in Phases 1–7.

Phase 8 is release engineering. It must not silently expand product scope.

## Version contract

The first stable public release is **1.0.0**.

`0.1.0` remains the historical development baseline and must not be presented as the first stable public release.

For the accepted release candidate, all release metadata must agree exactly:

```text
ai-transparency.php Version: 1.0.0
KAIROSETH_AI_TRANSPARENCY_VERSION = 1.0.0
readme.txt Stable tag: 1.0.0
CHANGELOG.md release heading = 1.0.0
release ZIP filename = ai-transparency-1.0.0.zip
release package root directory = ai-transparency/
Git tag after final acceptance = 1.0.0
GitHub Release after final acceptance = 1.0.0
```

A mismatch is a blocking release defect.

## Release stages

### 8A — Reproducible release candidate

Required:

- production package generated only from repository-controlled inputs;
- release ZIP built deterministically from `build/ai-transparency/`;
- SHA-256 checksum generated for the exact ZIP;
- package contains only runtime/distribution files;
- no development tests, CI config, `.git*`, node modules, Composer dev dependencies, debug artifacts or internal strategy documentation inside the distributed plugin;
- compiled Spanish `.mo` is present and non-empty;
- package root is exactly `ai-transparency/`;
- version/readme metadata consistency is checked automatically;
- official WordPress Plugin Check runs against the exact distribution package.

### 8B — Lifecycle and upgrade acceptance

Required lifecycle behavior:

```text
fresh install → activation → use
existing 0.1.0 site → replace/upgrade to 1.0.0 → existing Registry survives
1.0.0 → deactivate → data survives
1.0.0 → reactivate → data remains usable
explicit uninstall → plugin-owned local Registry removed
```

Deactivation must never delete Registry data.

Upgrade must never delete or reset Registry data.

Uninstall is the only destructive lifecycle action and is limited to plugin-owned data.

## Uninstall contract

The plugin must include `uninstall.php` guarded by `WP_UNINSTALL_PLUGIN`.

Single-site behavior:

- delete only `kairoseth_ai_transparency_registry` from the current site;
- do not delete posts, users, uploads, unrelated options, transients or third-party plugin data;
- do not make remote calls.

Multisite behavior:

- when WordPress uninstalls the plugin network-wide, iterate existing sites and remove the plugin-owned site-local Registry option from each site;
- restore the original blog context after iteration;
- do not create/delete sites;
- do not aggregate or transmit data;
- do not delete network options unless Phase 8 introduces an explicitly documented plugin-owned network option (none is planned).

Future plugin-owned persistent keys may only be added to uninstall after their ownership and lifecycle are documented.

## WordPress.org boundary

The target slug is `ai-transparency`, but the project must not claim that the slug is assigned, approved or publicly available until WordPress.org confirms the plugin and grants the corresponding repository access.

Before submission, the repository must provide a WordPress.org-ready `readme.txt` with:

- accurate plugin name and contributor identity;
- five or fewer relevant tags;
- current minimum WordPress and PHP requirements;
- supported/tested WordPress version based on real acceptance evidence;
- concise description within WordPress.org length guidance;
- accurate external-service disclosure for Kairoseth Custom Requests;
- stable `1.0.0` changelog/upgrade notice;
- no unsupported compliance/certification claims;
- no internal business strategy.

Actual WordPress.org submission/review/approval is an external dependency and is not considered completed merely because the repository is technically ready.

## Supported platform contract for 1.0.0

Release metadata is currently targeted as:

```text
Requires at least: WordPress 6.6
Tested up to: WordPress 7.1
Requires PHP: 7.4
```

The release candidate must retain the existing PHP 7.4/8.1/8.3/8.5 syntax gates and real WordPress 7.1 runtime acceptance. Any change to these values requires matching validation evidence.

## EN/ES contract

The existing 100% runtime English/Spanish policy remains blocking.

The exact release package must contain:

```text
languages/ai-transparency.pot
languages/ai-transparency-es_ES.po
languages/ai-transparency-es_ES.mo
```

Release validation must prove the compiled Spanish catalog belongs to the same source state as the packaged PHP/runtime strings.

## Privacy and authority regression contract

Phase 8 must prove no regression to these accepted guarantees:

- administrator capabilities/nonces remain server-authoritative;
- Registry remains site-local;
- Discovery remains deterministic and bounded;
- Readiness remains Fact / Administrator declaration / Guidance;
- Disclosure renders only from eligible reviewed Registry state;
- Evidence Export retains its strict allow-list and deterministic signature behavior;
- Support page performs no Kairoseth request on load;
- contextual handoff remains exact allow-list only;
- no telemetry, hidden cloud dependency or automatic sensitive-data upload is introduced by release tooling.

## Release artifact contract

The canonical release command will produce:

```text
build/ai-transparency/
dist/ai-transparency-1.0.0.zip
dist/ai-transparency-1.0.0.zip.sha256
```

The checksum file must contain the SHA-256 digest of the exact ZIP that is attached to the GitHub Release or submitted for external review.

A CI artifact may be used for acceptance, but a release must be reproducible from the tagged repository state.

## GitHub release contract

A tag/release must not be created until the Phase 8 implementation PR is merged and the final `main` validation is green.

Required sequence:

```text
feature branch
→ implementation PR
→ all required CI green
→ merge
→ main CI green
→ build exact 1.0.0 artifact from accepted main
→ verify checksum/package metadata
→ create immutable 1.0.0 tag/release
→ verify attached asset/checksum
```

No release workflow may bypass existing CI acceptance.

## External publication contract

WordPress.org availability is a separate final publication gate:

```text
repository release-ready
→ submit plugin to WordPress.org
→ external review
→ slug/repository access confirmed
→ publish approved package/SVN state
→ verify public directory page/download
```

Until the external review completes, the repository may state **release candidate ready for WordPress.org submission** but not **available on WordPress.org**.

## Out of scope for Phase 8

- paid features or licensing;
- cloud accounts;
- new AI provider detectors unrelated to release readiness;
- new legal-classification logic;
- broad redesign of accepted admin/product workflows;
- automatic WordPress.org publication without explicit credentials/approval;
- destructive cleanup during deactivate/upgrade.

## Exit

Phase 8 closes only when every item in `PHASE8_ACCEPTANCE.md` is satisfied, the stable artifact is reproducible, `main` is green after merge, release evidence is recorded, blockers are zero, and any claimed external publication state is actually verified.