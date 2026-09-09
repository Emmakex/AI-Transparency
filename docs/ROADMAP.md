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

Status: **closed.**

Goal: establish a safe, reviewable WordPress plugin foundation with public CI.

Key accepted outputs:

- [x] public repository and MIT license
- [x] WordPress plugin bootstrap and namespace/autoloader
- [x] PHPUnit + WordPress Coding Standards + PHP compatibility matrix
- [x] deterministic production package at `build/ai-transparency/`
- [x] official WordPress Plugin Check
- [x] actionable CI diagnostics and engineering failure memory
- [x] mandatory EN/ES 100% runtime coverage
- [x] compiled Spanish `.mo` in the production package
- [x] bilingual public README/readme

Exit: complete.

## Phase 2 — Persistent AI Systems Registry

Status: **closed — accepted, merged and verified on `main` on 9 September 2026.**

Goal: let an administrator explicitly maintain a trustworthy local inventory.

Accepted:

- [x] versioned registry schema (`schema_version = 1`)
- [x] site-local WordPress Options persistence
- [x] add/edit/review/archive records
- [x] system type, source/origin, interaction context and disclosure-required declaration
- [x] EN/ES 100% admin UX
- [x] capability + nonce enforcement
- [x] legacy migration and fail-safe schema behavior
- [x] real WordPress activation/migration/CRUD/permission acceptance
- [x] responsive + accessibility browser acceptance
- [x] real Multisite isolation
- [x] PR #5 merged and post-merge `main` CI green
- [x] blockers = 0

Closure evidence:

```text
Functional acceptance CI: #53 / 34367111247
Final PR-head CI: #60 / 34368216414
Merged PR: #5
Main merge commit: c83fbb11ffcfba7816a0beb71068e228a65ece77
Post-merge main CI: #61 / 34368648895
Final Phase 2 documentation main CI: #63 / 34369796882
```

Implementation details: [`PHASE2_REGISTRY_IMPLEMENTATION.md`](PHASE2_REGISTRY_IMPLEMENTATION.md).

Exit: complete.

## Phase 3 — Deterministic discovery

Status: **closed — AI Engine 3.7.7 detector accepted, merged and verified on `main`.**

Goal: discover only integrations for which the plugin can produce explainable and reproducible evidence.

Accepted detector contract:

```text
detector id
supported integration/version boundary
observed WordPress evidence
stable source signature
discovered candidate
explicit administrator review
server-side re-observation
no legal conclusion
```

### First accepted detector — AI Engine

Validated boundary:

```text
WordPress.org slug: ai-engine
plugin file: ai-engine/ai-engine.php
text domain: ai-engine
version: 3.7.7
active plugin required: yes
```

Accepted:

- [x] immutable WordPress `PluginObservation`
- [x] deterministic `DiscoveryResult` with SHA-256 evidence signature
- [x] exact AI Engine 3.7.7 supported boundary
- [x] other versions reported outside validated boundary
- [x] unrelated/inactive plugins ignored
- [x] WordPress plugin inventory adapter
- [x] **Tools → AI Discovery** administrator review surface
- [x] browser values are not discovery authority
- [x] server re-observes WordPress before persistence
- [x] explicit `manage_options` + nonce acceptance action
- [x] candidate enters registry as `other + discovered + pending review`
- [x] EN/ES 100% discovery UI/catalog
- [x] deterministic unit tests
- [x] exact WordPress.org AI Engine 3.7.7 runtime fixture
- [x] Playwright detection → evidence → Add to registry → registry verification
- [x] responsive/accessibility acceptance
- [x] inherited Phase 2 migration/CRUD/permissions regressions green
- [x] Multisite isolation green
- [x] WordPress Plugin Check green
- [x] PR #7 merged to `main`
- [x] post-merge `main` CI green
- [x] final documentation PR #8 merged and verified on `main`
- [x] blockers = 0

Closure evidence:

```text
Accepted PR head: af44fe2156bde2947519e78112ea1d7a39abb0ff
Pre-merge CI: #68 / 34373934127
Implementation PR: #7
Implementation merge commit: d595819a7a8f7d292bb23a7c919bec22f6138381
Implementation post-merge CI: #69 / 34377130702
Closure documentation PR: #8
Closure documentation merge commit: fbb1eaac71a2b0a026c7633549bc0704bbd1d42f
Final Phase 3 main CI: #71 / 34378441934
```

Implementation details: [`PHASE3_DISCOVERY_IMPLEMENTATION.md`](PHASE3_DISCOVERY_IMPLEMENTATION.md).  
Runtime evidence: [`PHASE3_RUNTIME_EVIDENCE.md`](PHASE3_RUNTIME_EVIDENCE.md).

Exit: **complete.**

## Phase 4 — Readiness findings and evidence

Status: **active implementation — first deterministic registry-driven findings.**

Goal: convert registry/discovery state into evidence-backed technical findings while preserving the boundary between observed facts, administrator declarations and guidance.

Current contract:

```text
Finding
├ stable finding id
├ stable rule id
├ category
├ priority
├ subject system id/name
├ FACT — observed technical state
├ DECLARATION — explicit administrator state when relevant
├ GUIDANCE — technical review/completion action
├ SHA-256 evidence signature
└ generated_at metadata
```

Current workstream:

- [x] immutable `Finding` domain model
- [x] pure-PHP deterministic `FindingEngine`
- [x] findings computed on demand rather than persisted separately
- [x] stable finding identity independent of generation time
- [x] stable SHA-256 signature from rule-relevant evidence only
- [x] archived registry records ignored
- [x] `registry_review_pending_v1`
- [x] `interaction_context_missing_v1`
- [x] `configured_disclosure_review_v1`
- [x] disclosure finding preserves administrator declaration instead of inferring a legal obligation
- [x] **Tools → AI Readiness** read-only administrator surface
- [x] `manage_options` capability boundary
- [x] EN/ES customer-facing readiness strings
- [x] responsive readiness card layout and signature wrapping
- [x] PHPUnit coverage for rule semantics, determinism and correction/removal behavior
- [x] Playwright Discovery → Registry → Readiness path
- [x] Editor access denial coverage
- [ ] final PR-head CI green
- [ ] WordPress Plugin Check green on exact package
- [ ] runtime Readiness acceptance green
- [ ] inherited Phase 2/3 regression suite green
- [ ] PR merged to `main`
- [ ] post-merge `main` verification green
- [ ] blockers = 0

First real runtime expectation:

```text
AI Engine 3.7.7
→ deterministic Discovery
→ explicit Add to registry
→ discovered + pending review + empty interaction context
→ Tools → AI Readiness
→ exactly two initial technical findings
   1. pending administrator review
   2. missing interaction context
```

Rules:

- findings must be deterministic and reproducible;
- correcting registry state must remove obsolete findings on the next evaluation;
- observed facts, administrator declarations and guidance must never be conflated;
- findings are technical review signals, not legal decisions or certification;
- customer-facing findings ship EN/ES together;
- no automatic external telemetry is introduced.

Implementation details: [`PHASE4_FINDINGS_IMPLEMENTATION.md`](PHASE4_FINDINGS_IMPLEMENTATION.md).  
Acceptance checklist: [`PHASE4_ACCEPTANCE.md`](PHASE4_ACCEPTANCE.md).

Exit: at least one real registry/discovery state produces reproducible, bilingual findings with explicit evidence, merged code, green post-merge verification and no legal overclaim.

## Phase 5 — Disclosure tooling

Status: **not started — blocked by Phase 4 closure.**

Goal: provide accessible, explicit disclosure components for supported configured contexts.

Planned:

- reusable disclosure model;
- admin configuration;
- block/shortcode or integration-specific renderer only where justified;
- accessible markup;
- locale-aware EN/ES copy with 100% coverage;
- deterministic placement tests;
- cache/theme compatibility acceptance.

Exit: one real supported interaction-disclosure workflow passes bilingual, accessibility and frontend acceptance.

## Phase 6 — Evidence export

Goal: produce a dated, reviewable local evidence record.

Planned:

- JSON export first;
- human-readable report/export where useful;
- EN/ES customer-readable output when human-facing;
- schema/version metadata;
- source/evidence signatures;
- no secrets/private credentials;
- explicit generation timestamp;
- clear non-certification disclaimer.

Exit: export is deterministic, documented, bilingual where customer-facing and privacy-safe.

## Phase 7 — Contextual support/custom integration path

Goal: provide a non-intrusive plugin-owned Help/About path for users who need unsupported integrations or implementation help.

Planned:

- plugin-owned Help/About CTA;
- EN/ES copy;
- user-initiated action only;
- bounded non-sensitive context;
- no automatic telemetry/lead submission.

Exit: the user-initiated path works end-to-end without weakening privacy or WordPress UX.

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
