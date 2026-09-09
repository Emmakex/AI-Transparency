# Kairoseth AI Transparency — Technical Roadmap

Status: active development  
Last reviewed: 9 September 2026

## Cross-cutting release invariants

These rules remain blocking whenever relevant:

```text
English + Spanish 100% customer-facing coverage
+ WordPress server-authoritative capabilities/nonces
+ local-first/privacy boundaries
+ responsive/accessibility UX acceptance
+ minimum sufficient validation
+ actionable failure diagnostics
+ engineering failure-memory consultation/update
+ feature branch → PR → CI → merge → verification
+ finish current phase before advancing
```

Canonical policies:

- `docs/ENGINEERING_RULES.md`
- `docs/BILINGUAL_EN_ES_POLICY.md`
- `docs/CI_VALIDATION_POLICY.md`
- `docs/IMPLEMENTATION_COMPLETION_POLICY.md`
- `docs/CI_FAILURE_DIAGNOSTICS_POLICY.md`
- `docs/engineering-failures/README.md`

## Phase status summary

| Phase | Status | Accepted outcome |
|---|---|---|
| 1 — Repository bootstrap | **Closed** | Safe public plugin foundation and CI |
| 2 — Persistent AI Systems Registry | **Closed** | Local versioned registry + CRUD/review/archive |
| 3 — Deterministic discovery | **Closed** | Explainable AI Engine 3.7.7 detector |
| 4 — Readiness findings & evidence | **Closed** | Deterministic Fact / Declaration / Guidance findings |
| 5 — Disclosure tooling | **Closed** | Reviewed registry state → explicit public shortcode disclosure |
| 6 — Evidence export | **Unblocked / not started** | Dated reviewable local evidence export |
| 7 — Contextual support/custom integration | Not started | User-initiated support/custom path |
| 8 — First public release | Not started | Stable public release after all release gates |

---

## Phase 1 — Repository bootstrap

Status: **closed.**

Accepted:

- public repository and MIT license;
- WordPress plugin bootstrap and namespace/autoloader;
- PHPUnit + WordPress Coding Standards + PHP compatibility matrix;
- deterministic production package at `build/ai-transparency/`;
- official WordPress Plugin Check;
- actionable CI diagnostics and engineering failure memory;
- mandatory EN/ES runtime coverage and compiled Spanish `.mo`;
- bilingual public README/readme.

Exit: **complete.**

## Phase 2 — Persistent AI Systems Registry

Status: **closed — accepted, merged and verified on `main`.**

Accepted:

- versioned registry schema (`schema_version = 1`);
- site-local WordPress Options persistence;
- add/edit/review/archive records;
- system type, source/origin, interaction context and disclosure-required declaration;
- capability + nonce enforcement;
- legacy migration and fail-safe future-schema handling;
- responsive/accessibility browser acceptance;
- real Multisite isolation.

Closure evidence:

```text
Functional acceptance CI: #53 / 34367111247
Final PR-head CI: #60 / 34368216414
Merged PR: #5
Main merge: c83fbb11ffcfba7816a0beb71068e228a65ece77
Post-merge CI: #61 / 34368648895
Final Phase 2 docs main CI: #63 / 34369796882
```

Implementation: [`PHASE2_REGISTRY_IMPLEMENTATION.md`](PHASE2_REGISTRY_IMPLEMENTATION.md).

Exit: **complete.**

## Phase 3 — Deterministic discovery

Status: **closed — accepted, merged and verified on `main`.**

First accepted detector boundary:

```text
WordPress.org slug: ai-engine
plugin file: ai-engine/ai-engine.php
text domain: ai-engine
validated version: 3.7.7
active required: yes
```

Accepted:

- immutable WordPress `PluginObservation`;
- deterministic `DiscoveryResult` + SHA-256 evidence signature;
- exact version boundary and unsupported-version state;
- **Tools → AI Discovery** administrator review surface;
- server re-observation before persistence;
- explicit `manage_options` + nonce acceptance;
- candidate enters registry as discovered + pending review;
- real WordPress fixture and Playwright evidence;
- inherited registry + Multisite regression coverage.

Closure evidence:

```text
Accepted PR head: af44fe2156bde2947519e78112ea1d7a39abb0ff
Pre-merge CI: #68 / 34373934127
Implementation PR: #7
Implementation merge: d595819a7a8f7d292bb23a7c919bec22f6138381
Post-merge CI: #69 / 34377130702
Closure docs PR: #8
Closure docs merge: fbb1eaac71a2b0a026c7633549bc0704bbd1d42f
Final Phase 3 main CI: #71 / 34378441934
```

Implementation: [`PHASE3_DISCOVERY_IMPLEMENTATION.md`](PHASE3_DISCOVERY_IMPLEMENTATION.md).  
Runtime evidence: [`PHASE3_RUNTIME_EVIDENCE.md`](PHASE3_RUNTIME_EVIDENCE.md).

Exit: **complete.**

## Phase 4 — Readiness findings and evidence

Status: **closed — accepted, merged and verified on `main`.**

Accepted:

- immutable `Finding` model;
- pure deterministic `FindingEngine`;
- findings generated on demand from current registry state;
- stable ids and SHA-256 evidence signatures;
- archived systems ignored;
- `registry_review_pending_v1`;
- `interaction_context_missing_v1`;
- `configured_disclosure_review_v1`;
- Fact / Administrator declaration / Guidance kept distinct;
- **Tools → AI Readiness** read-only administrator surface;
- no automatic legal classification or certification.

Implementation evidence:

```text
Accepted PR head: 926a154930042e53af0b082795be155389cc6916
Pre-merge CI: #75 / 34381590429
Implementation PR: #9
Implementation merge: 836abfeca4c199930b74ba32547f9037f7fcb4de
Implementation post-merge CI: #76 / 34382057838
Closure docs PR: #10
Closure docs PR CI: #77 / 34390042410
Closure docs merge: 40d7aee73015c2e7185bde156349d106e72a4e18
Final Phase 4 main CI: #78 / 34390407476
Blockers: 0
```

Implementation: [`PHASE4_FINDINGS_IMPLEMENTATION.md`](PHASE4_FINDINGS_IMPLEMENTATION.md).  
Acceptance: [`PHASE4_ACCEPTANCE.md`](PHASE4_ACCEPTANCE.md).  
Runtime evidence: [`PHASE4_RUNTIME_EVIDENCE.md`](PHASE4_RUNTIME_EVIDENCE.md).

Exit: **complete.**

## Phase 5 — Disclosure tooling

Status: **closed — accepted, merged and verified on `main` on 9 September 2026.**

Goal achieved: turn explicit reviewed registry configuration into a deliberately placed public disclosure without allowing browser content or plugin presence to become disclosure authority.

Accepted eligibility:

```text
status = active
review_status = reviewed
interaction_disclosure_required = true
trim(interaction_context) != empty
```

Accepted flow:

```text
site-local registry
→ DisclosureEngine
→ Tools → AI Disclosure = Ready / Not ready
→ [kairoseth_ai_disclosure system="SYSTEM_ID"]
→ server-side registry lookup and eligibility
→ escaped EN/ES public notice
```

Accepted:

- immutable bounded `Disclosure` model;
- deterministic `DisclosureEngine` reason codes;
- no Phase 5 registry migration;
- read-only **Tools → AI Disclosure** protected by `manage_options`;
- exact shortcode for ready systems;
- ineligible records render no public disclosure;
- server-authoritative registry lookup on every render;
- public output limited to localized copy + reviewed system name;
- no JS dependency;
- scoped `assets/frontend.css` with production-package build gate;
- EN/ES 100% runtime coverage and compiled Spanish `.mo`;
- real anonymous frontend E2E;
- 390 px + 200% text + axe accessibility acceptance;
- disabling disclosure removes output in uncached runtime;
- inherited Registry / Discovery / Readiness / Multisite gates green;
- no telemetry, cookies, cloud dependency or legal compliance claim.

Implementation evidence:

```text
Contract PR: #11
Contract merge: d95483e74f7b1045f2d497219fb70d7d71165faf
Contract post-merge CI: #80 / 34391946448
Implementation PR: #12
Accepted head: 37ac6f8a3adf6ae33c98910fc0b2ff816789a697
PR-head CI: #82 / 34394624556
Implementation merge: 2770c7b7982ffbbe07ba58e8cedebd12d0add14a
Post-merge main CI: #83 / 34395173777
Blockers: 0
```

Resolved non-behavioral CI incident:

```text
CI #81 / 34394493780
PHP Quality → composer verify → exit 2
src/Admin/class-disclosurepage.php lines 114–116
0 errors / 3 WPCS alignment warnings
signature 073cfa47b6645c467d89e94ad0e5ebe5441cf298acde8c998c47322bda2b00f8
fixed by assignment alignment
validated by CI #82 and #83
```

Implementation: [`PHASE5_DISCLOSURE_IMPLEMENTATION.md`](PHASE5_DISCLOSURE_IMPLEMENTATION.md).  
Acceptance: [`PHASE5_ACCEPTANCE.md`](PHASE5_ACCEPTANCE.md).  
Runtime evidence: [`PHASE5_RUNTIME_EVIDENCE.md`](PHASE5_RUNTIME_EVIDENCE.md).

Exit: **complete.**

## Phase 6 — Evidence export

Status: **unblocked / not started.**

Goal: produce a dated, reviewable local evidence record without leaking secrets or turning technical evidence into legal certification.

Planned first contract:

- JSON export first;
- schema/version metadata;
- registry state relevant to the exported evidence;
- discovery/finding evidence signatures where applicable;
- explicit generation timestamp;
- no secrets/provider credentials/prompts/conversations;
- human-readable output only when separately designed and accepted;
- EN/ES together for any customer-facing export UX or human-readable report;
- deterministic output contract where timestamps are excluded from stable evidence identity as appropriate.

Phase 6 implementation must not start until its design/acceptance contract is defined on a separate branch/PR.

Exit target: one real administrator-generated export is deterministic, reviewable, privacy-safe, documented and validated against the production package.

## Phase 7 — Contextual support/custom integration path

Status: **not started.**

Planned:

- plugin-owned Help/About CTA;
- EN/ES copy;
- user-initiated action only;
- bounded non-sensitive context;
- no automatic telemetry/lead submission.

Exit target: user-initiated support/custom path works end-to-end without weakening privacy or WordPress UX.

## Phase 8 — First public release

Status: **not started.**

Required release evidence:

```text
functional Free value
+ EN/ES 100% customer-facing coverage
+ compiled Spanish catalog in exact release package
+ supported WordPress/PHP compatibility evidence
+ security/privacy review
+ accessibility/responsive acceptance
+ Plugin Check green
+ install/activation/deactivation/uninstall tests
+ release ZIP
+ README/readme/version consistency
+ documentation synchronized
+ blockers = 0
```

Only then may the project claim a stable public release or WordPress.org availability.

## Deferred until justified

- paid local feature locks / trialware;
- generic AI authorship detection;
- mandatory cloud account;
- universal AI provider integration;
- automatic legal classification;
- broad GRC platform;
- custom database tables without measured need;
- WooCommerce-specific features unrelated to AI transparency;
- automatic third-party chatbot DOM injection;
- universal cache/theme compatibility claims.
