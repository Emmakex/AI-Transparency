# Phase 8 — First Public Release Implementation Contract

Status: **repository-controlled implementation accepted; GitHub 1.0.0 published; WordPress.org external publication pending**  
Target release: **1.0.0**  
Target WordPress.org slug: **`ai-transparency`**  
Last reviewed: 10 September 2026

## Goal

Prepare the first stable public release of Kairoseth AI Transparency as a reproducible, installable and reviewable WordPress plugin package without weakening the local-first/privacy, authorization, EN/ES, accessibility, Multisite or deterministic-evidence guarantees accepted in Phases 1–7.

Phase 8 is release engineering. It does not silently expand product scope.

## Accepted repository outcome

The repository-controlled implementation is complete and accepted.

```text
stable version: 1.0.0
accepted main/tag SHA: 5d0344876eb27db798ded87888b21b11b5581af5
release ZIP: ai-transparency-1.0.0.zip
SHA-256: b7fc6e0b4a80d39e0b9331faf89c3ad7c310f3d5f24795123eb9ab89299bb368
Git tag: 1.0.0
GitHub Release: Kairoseth AI Transparency 1.0.0
repository-controlled blockers: 0
```

Canonical evidence is recorded in [`PHASE8_RELEASE_EVIDENCE.md`](PHASE8_RELEASE_EVIDENCE.md).

Final accepted gates:

```text
Implementation PR #21
→ PR-head CI #125 / 34443466518 — SUCCESS — 8/8
→ PR-head Release Readiness #11 / 34443466517 — SUCCESS — 2/2
→ merge 9fea609de553e10af1618d385ccb85e4ab695ffe
→ post-merge CI #126 / 34443762226 — SUCCESS — 8/8
→ post-merge Release Readiness #12 / 34443762222 — SUCCESS — 2/2

Release automation PR #22
→ PR-head CI #128 / 34444548560 — SUCCESS — 8/8
→ PR-head Release Readiness #14 / 34444548568 — SUCCESS — 2/2
→ accepted main merge 5d0344876eb27db798ded87888b21b11b5581af5
→ final main CI #129 / 34449448225 — SUCCESS — 8/8
→ final main Release Readiness #15 / 34449448874 — SUCCESS — 2/2
→ Stable Release #1 / 34449679699 — SUCCESS
```

## Version contract

The first stable public repository release is **1.0.0**.

`0.1.0` remains the historical development baseline and is not presented as the first stable public release.

The accepted release state agrees exactly on:

```text
ai-transparency.php Version: 1.0.0
KAIROSETH_AI_TRANSPARENCY_VERSION = 1.0.0
readme.txt Stable tag: 1.0.0
CHANGELOG.md release heading = 1.0.0
release ZIP filename = ai-transparency-1.0.0.zip
release package root directory = ai-transparency/
Git tag = 1.0.0
GitHub Release = 1.0.0
```

A future mismatch remains a blocking release defect.

## 8A — Reproducible release candidate

Accepted:

- production package generated only from repository-controlled inputs;
- release ZIP built deterministically from `build/ai-transparency/`;
- SHA-256 checksum generated for the exact ZIP;
- package contains runtime/distribution files only;
- development tests, CI config, `.git*`, node modules, Composer dev dependencies, debug artifacts and internal strategy documentation are excluded from the distributed plugin;
- compiled Spanish `.mo` is present and non-empty;
- package root is exactly `ai-transparency/`;
- version/readme metadata consistency is checked automatically;
- official WordPress Plugin Check validates the distribution package.

The final accepted SHA-256 is:

```text
b7fc6e0b4a80d39e0b9331faf89c3ad7c310f3d5f24795123eb9ab89299bb368
```

## 8B — Lifecycle and upgrade acceptance

The exact release ZIP passed:

```text
fresh install → activation → use
existing 0.1.0 site → replace/upgrade to 1.0.0 → existing Registry survives
1.0.0 → deactivate → data survives
1.0.0 → reactivate → data remains usable
explicit uninstall → plugin-owned local Registry removed
```

Deactivation and upgrade preserve Registry data. Uninstall is the only destructive lifecycle action and is limited to plugin-owned data.

## Uninstall contract

`uninstall.php` is guarded by `WP_UNINSTALL_PLUGIN`.

Single-site acceptance proved that uninstall deletes only `kairoseth_ai_transparency_registry` and does not remove unrelated content, users, uploads or options, and makes no remote request.

Multisite acceptance proved that explicit network uninstall iterates existing sites, removes the plugin-owned site-local Registry option, restores blog context and preserves unrelated site/network data. It does not create or delete sites and does not aggregate or transmit site data.

Future plugin-owned persistent keys may only be added to uninstall after their ownership and lifecycle are documented.

## Supported platform contract for 1.0.0

Accepted release metadata:

```text
Requires at least: WordPress 6.6
Tested up to: WordPress 7.1
Requires PHP: 7.4
```

The accepted release retains PHP 7.4/8.1/8.3/8.5 syntax gates and real WordPress runtime acceptance.

## EN/ES contract

The exact release package contains:

```text
languages/ai-transparency.pot
languages/ai-transparency-es_ES.po
languages/ai-transparency-es_ES.mo
```

Runtime English/Spanish coverage remains blocking for customer-facing changes.

## Privacy and authority regression contract

Phase 8 acceptance preserved these guarantees:

- administrator capabilities/nonces remain server-authoritative;
- Registry remains site-local;
- Discovery remains deterministic and bounded;
- Readiness remains Fact / Administrator declaration / Guidance;
- Disclosure renders only from eligible reviewed Registry state;
- Evidence Export retains its strict allow-list and deterministic signature behavior;
- Support page performs no Kairoseth request on load;
- contextual handoff remains exact allow-list only;
- no telemetry, hidden cloud dependency or automatic sensitive-data upload was introduced by release tooling.

## Stable GitHub release contract

The stable-release automation now enforces:

```text
accepted main source
→ successful CI for exact SHA
→ successful Release Readiness for exact SHA
→ stable tag absent or already pointing to same accepted source
→ exact artifact rebuilt
→ ZIP + checksum published
→ published assets downloaded again
→ byte-for-byte/checksum verification
```

The workflow refuses to move a conflicting existing version tag or overwrite an existing release during first publication. Its recovery path may verify an existing release only when its tag resolves to the same accepted source SHA.

This is a repository engineering immutability policy. It does not claim GitHub's optional native release `immutable` property is enabled.

## WordPress.org boundary

The target slug remains `ai-transparency`, but the project must not claim that the WordPress.org slug is assigned, approved or publicly available until WordPress.org confirms it.

The repository has a WordPress.org-ready `readme.txt` and the accepted 1.0.0 submission artifact. Actual submission/review/approval is an external dependency.

Current external state:

```text
accepted submission artifact: READY
WordPress.org submission: PENDING / not recorded as sent
external review: PENDING
slug/repository access: NOT VERIFIED
public WordPress.org page/download: NOT VERIFIED
```

Allowed statement:

**Stable 1.0.0 released on GitHub and ready for WordPress.org submission.**

Not allowed until externally verified:

- available on WordPress.org;
- approved by WordPress.org;
- WordPress.org slug assigned.

## Out of scope for Phase 8

- paid features or licensing;
- cloud accounts;
- new AI provider detectors unrelated to release readiness;
- new legal-classification logic;
- broad redesign of accepted admin/product workflows;
- automatic WordPress.org publication without explicit credentials/approval;
- destructive cleanup during deactivate/upgrade.

## Exit

Repository-controlled Phase 8 implementation and GitHub stable release gates are **complete**, with repository-controlled blockers at zero.

Phase 8 remains **active only for the external WordPress.org publication gate** defined in Section N of [`PHASE8_ACCEPTANCE.md`](PHASE8_ACCEPTANCE.md). It must not be marked fully closed until that external state is actually verified, unless the release-state contract is deliberately changed in a separately accepted decision.
