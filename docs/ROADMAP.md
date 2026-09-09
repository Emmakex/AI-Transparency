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

Status: **active — first detector implementation: AI Engine 3.7.7.**

Goal: discover only integrations for which the plugin can produce explainable and reproducible evidence.

Detector contract:

```text
detector id
supported integration/version boundary
observed WordPress evidence
source signature
discovered candidate
explicit administrator review
no legal conclusion
```

### First detector — AI Engine

Validated boundary:

```text
WordPress.org slug: ai-engine
plugin file: ai-engine/ai-engine.php
text domain: ai-engine
version: 3.7.7
active plugin required: yes
```

Current workstream:

- [x] immutable WordPress `PluginObservation`
- [x] deterministic `DiscoveryResult` with SHA-256 evidence signature
- [x] AI Engine detector with exact 3.7.7 supported boundary
- [x] unsupported-version result that cannot be auto-accepted
- [x] WordPress plugin inventory adapter
- [x] **Tools → AI Discovery** admin review surface
- [x] browser values are not accepted as discovery authority
- [x] server re-observes WordPress before persistence
- [x] explicit `manage_options` + nonce acceptance action
- [x] discovered candidate enters registry as `other + discovered + pending review`
- [x] EN/ES 100% discovery UI/catalog source
- [x] unit tests for identity, active state, version boundary, signature and candidate semantics
- [x] runtime CI fixture installs exact AI Engine 3.7.7 from WordPress.org
- [x] Playwright path covers detection → evidence → explicit acceptance → registry
- [ ] final PR-head CI green
- [ ] WordPress Plugin Check green on exact package
- [ ] runtime discovery acceptance green
- [ ] PR merged to `main`
- [ ] post-merge `main` verification green
- [ ] blockers = 0

Implementation/acceptance details: [`PHASE3_DISCOVERY_IMPLEMENTATION.md`](PHASE3_DISCOVERY_IMPLEMENTATION.md).

Exit: at least one real supported AI integration can be discovered, reviewed and added to the registry with deterministic tests, EN/ES coverage, merged code and green post-merge verification.

## Phase 4 — Readiness findings and evidence

Status: **not started — blocked by Phase 3 closure.**

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

Exit: findings are reproducible, fully EN/ES customer-facing and never represented as automatic legal certification.

## Phase 5 — Disclosure tooling

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
