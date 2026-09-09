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

Status: **closed — accepted, merged and verified on `main` on 9 September 2026.**

Goal: let an administrator explicitly maintain a trustworthy local inventory.

Implemented and accepted:

- [x] versioned registry schema (`schema_version = 1`)
- [x] site-local WordPress Options persistence adapter
- [x] add/edit/archive AI system records
- [x] system type taxonomy
- [x] source/origin metadata
- [x] review status and timestamps
- [x] interaction context and configured disclosure state
- [x] **100% EN/ES admin UX in the same workstream**
- [x] capability + nonce enforcement for mutations
- [x] deterministic persistence/export shape
- [x] legacy migration tests
- [x] simulated per-blog/site isolation contract test
- [x] Multisite storage decision: current blog/site scope, no network-wide registry in v1
- [x] diagnostics artifact regression fixed and recorded in failure memory
- [x] exact production package mounted into real WordPress runtime acceptance
- [x] real WordPress plugin activation
- [x] real legacy migration through `wp_options` with schema v1 write-back
- [x] real administrator add/edit/archive browser flow
- [x] real non-administrator access rejection
- [x] responsive admin acceptance at 390 px
- [x] keyboard-focusable registry table region
- [x] axe serious/critical accessibility gate
- [x] real WordPress Multisite blog isolation smoke
- [x] runtime test credentials generated ephemerally and masked before command execution
- [x] material Phase 2 CI/UX regressions recorded in engineering failure memory
- [x] PR #5 merged to `main`
- [x] post-merge `main` verification green
- [x] blockers = 0

Closure evidence:

```text
Functional acceptance CI: #53 / 34367111247
Accepted implementation SHA: e5927a8a2b4526f01a4649c4ba5d3a25ae3c0353
Final PR-head CI: #60 / 34368216414
Merged PR: #5
Main merge commit: c83fbb11ffcfba7816a0beb71068e228a65ece77
Post-merge main CI: #61 / 34368648895

PHP quality: green
EN/ES 100% coverage: green
PHP 7.4 / 8.1 / 8.3 / 8.5 syntax: green
WordPress Plugin Check: green
WordPress runtime acceptance: green
  activation: green
  migration: green
  administrator CRUD: green
  unauthorized-role rejection: green
  responsive/accessibility: green
  Multisite isolation: green
```

Implementation details and acceptance evidence: [`PHASE2_REGISTRY_IMPLEMENTATION.md`](PHASE2_REGISTRY_IMPLEMENTATION.md).

Exit: **complete.** Registry CRUD and persistence are accepted without external telemetry, EN/ES coverage is green, blockers are 0, PR #5 is merged and `main` is verified.

## Phase 3 — Deterministic discovery

Status: **not started — unblocked by Phase 2 closure.**

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
