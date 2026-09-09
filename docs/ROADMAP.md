# Kairoseth AI Transparency — Technical Roadmap

Status: active development  
Last reviewed: 9 September 2026

## Cross-cutting release invariants

These rules apply to every phase and are blocking when relevant:

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

## Phase 1 — Repository bootstrap

Goal: establish a safe, reviewable WordPress plugin foundation with public CI.

- [x] public repository
- [x] MIT license
- [x] WordPress plugin bootstrap
- [x] namespace/autoloader
- [x] initial admin surface
- [x] AI system domain model
- [x] in-memory registry foundation
- [x] PHPUnit baseline
- [x] WordPress Coding Standards
- [x] PHP 7.4+ compatibility gate
- [x] PHP syntax matrix
- [x] official WordPress Plugin Check workflow
- [x] deterministic production package at `build/kairoseth-ai-transparency`
- [x] canonical extension engineering policies adapted from Kairoseth Platform
- [x] actionable CI diagnostic runner + bounded failure artifacts
- [x] durable engineering failure/solution memory
- [x] mandatory EN/ES 100% coverage checker
- [x] bundled Spanish gettext source catalog
- [x] release build compiles Spanish `.mo` catalog
- [x] public README EN/ES
- [x] `readme.txt`
- [x] security/contribution/trademark policies
- [x] required bootstrap PR CI green

Exit: bootstrap foundation merged with required public CI evidence. Engineering/i18n policy hardening is an extension of the Phase 1 foundation and must be green before Phase 2 implementation begins.

## Phase 2 — Persistent AI Systems Registry

Status: **active — first functional increment implemented in PR #3; acceptance still pending.**

Goal: let an administrator explicitly maintain a trustworthy local inventory.

Implemented in the current Phase 2 workstream:

- [x] versioned registry schema (`schema_version = 1`)
- [x] site-local WordPress Options persistence adapter
- [x] add/edit/archive AI system records
- [x] system type taxonomy
- [x] source/origin metadata
- [x] review status and timestamps
- [x] interaction context and configured disclosure state
- [x] **100% EN/ES admin UX in the same PR**
- [x] capability + nonce enforcement for mutations
- [x] deterministic persistence/export shape
- [x] legacy migration tests
- [x] simulated per-blog/site isolation contract test
- [x] Multisite storage decision: current blog/site scope, no network-wide registry in v1
- [x] diagnostics artifact regression fixed and recorded in failure memory

Required before Phase 2 closure:

- [ ] final CI green on the closing Phase 2 SHA
- [ ] real WordPress add/edit/archive smoke
- [ ] unauthorized-role mutation rejection in WordPress runtime
- [ ] real Multisite isolation smoke
- [ ] responsive admin acceptance
- [ ] keyboard/accessibility acceptance
- [ ] real upgrade/migration acceptance on WordPress
- [ ] documentation/acceptance evidence synchronized
- [ ] blockers = 0

Implementation details and acceptance status: [`PHASE2_REGISTRY_IMPLEMENTATION.md`](PHASE2_REGISTRY_IMPLEMENTATION.md).

Exit: registry CRUD and persistence accepted without external telemetry, with EN/ES coverage gate green and blockers 0.

## Phase 3 — Deterministic discovery

Goal: discover only integrations for which we can produce explainable evidence.

Initial detector contract:

```text
detector id
supported integration/version boundary
observed evidence
discovered system candidate
source signature
no legal conclusion
```

Candidate integrations are selected from actual WordPress market usage and maintainable APIs/hooks, one at a time.

Exit: at least one real supported AI integration can be discovered, reviewed and added to the registry with deterministic tests and bilingual user-facing findings.

## Phase 4 — Readiness findings and evidence

Goal: convert registry/discovery state into evidence-backed technical findings.

Planned categories:

- interaction disclosure missing/review needed;
- system record incomplete;
- unsupported/unknown AI integration requiring manual review;
- content/media declaration workflow missing where explicitly configured;
- stale review/evidence.

Every finding must separate:

```text
observed fact
administrator declaration
guidance
```

Exit: findings are reproducible, fully EN/ES customer-facing, and never represented as automatic legal certification.

## Phase 5 — Disclosure tooling

Goal: provide accessible, explicit disclosure components for supported configured contexts.

Planned:

- reusable disclosure model;
- admin configuration;
- block/shortcode or integration-specific renderer only where justified;
- accessible markup;
- locale-aware EN/ES copy with 100% coverage;
- no public promotional link by default;
- deterministic placement tests;
- cache/theme compatibility acceptance.

Exit: one real supported interaction-disclosure workflow passes bilingual, accessibility and frontend acceptance.

## Phase 6 — Evidence export

Goal: produce a dated, reviewable local evidence record.

Planned:

- JSON export first;
- human-readable report/export where useful;
- EN/ES customer-readable output when a human-facing export exists;
- schema/version metadata;
- source/evidence signatures;
- no secrets/private credentials;
- explicit generation timestamp;
- clear non-certification disclaimer.

Exit: export is deterministic, documented, bilingual where customer-facing and privacy-safe.

## Phase 7 — Custom Requests integration

Goal: connect plugin users who need unsupported/custom workflows with Kairoseth without degrading the WordPress experience.

Planned:

- plugin-owned Help/About CTA;
- **EN/ES CTA and context copy**;
- context prepared locally;
- user explicitly initiates contact;
- only documented fields transmitted;
- no automatic lead/telemetry submission;
- Kairoseth Platform Custom Requests endpoint when implemented;
- graceful fallback to canonical contact route.

Exit: user-initiated CTA reaches the authorized Custom Requests workflow end-to-end in EN/ES.

## Phase 8 — First public release

Required release evidence:

```text
functional Free value
+ EN/ES 100% customer-facing coverage
+ compiled Spanish catalog in release package
+ supported WordPress/PHP compatibility evidence
+ security/privacy review
+ accessibility/responsive acceptance
+ Plugin Check green on exact production package
+ install/activation/deactivation/uninstall tests
+ release ZIP
+ README/readme/version consistency
+ canonical Kairoseth product page EN/ES
+ share
+ Custom Request CTA EN/ES
+ engineering failure memory synchronized
+ documentation synchronized
+ blockers 0
```

Only then does the project claim a stable public release / WordPress.org availability.

## Deferred until justified

- paid local feature locks / trialware;
- generic AI authorship detection;
- mandatory cloud account;
- universal AI provider integration;
- automatic legal classification;
- broad GRC platform;
- custom database tables without measured need;
- WooCommerce-specific features unrelated to AI transparency.
