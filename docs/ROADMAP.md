# Kairoseth AI Transparency — Technical Roadmap

Status: active development  
Last reviewed: 9 September 2026

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
- [x] `readme.txt`
- [x] security/contribution/trademark policies
- [ ] CI green on bootstrap PR
- [ ] merge to `main`

Exit: bootstrap PR merged with required public CI evidence.

## Phase 2 — Persistent AI Systems Registry

Goal: let an administrator explicitly maintain a trustworthy local inventory.

Planned:

- versioned registry schema;
- WordPress persistence adapter;
- add/edit/archive AI system records;
- system type taxonomy;
- source/origin metadata;
- review status and timestamps;
- EN/ES admin UX;
- capability + nonce enforcement;
- deterministic export shape;
- migration tests;
- Multisite storage decision.

Exit: registry CRUD and persistence accepted without external telemetry.

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

Exit: at least one real supported AI integration can be discovered, reviewed and added to the registry with deterministic tests.

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

Exit: findings are reproducible and never represented as automatic legal certification.

## Phase 5 — Disclosure tooling

Goal: provide accessible, explicit disclosure components for supported configured contexts.

Planned:

- reusable disclosure model;
- admin configuration;
- block/shortcode or integration-specific renderer only where justified;
- accessible markup;
- locale-aware EN/ES copy;
- no public promotional link by default;
- deterministic placement tests;
- cache/theme compatibility acceptance.

Exit: one real supported interaction-disclosure workflow passes accessibility and frontend acceptance.

## Phase 6 — Evidence export

Goal: produce a dated, reviewable local evidence record.

Planned:

- JSON export first;
- human-readable report/export where useful;
- schema/version metadata;
- source/evidence signatures;
- no secrets/private credentials;
- explicit generation timestamp;
- clear non-certification disclaimer.

Exit: export is deterministic, documented and privacy-safe.

## Phase 7 — Custom Requests integration

Goal: connect plugin users who need unsupported/custom workflows with Kairoseth without degrading the WordPress experience.

Planned:

- plugin-owned Help/About CTA;
- context prepared locally;
- user explicitly initiates contact;
- only documented fields transmitted;
- no automatic lead/telemetry submission;
- Kairoseth Platform Custom Requests endpoint when implemented;
- graceful fallback to canonical contact route.

Exit: user-initiated CTA reaches the authorized Custom Requests workflow end-to-end.

## Phase 8 — First public release

Required release evidence:

```text
functional Free value
+ EN/ES
+ supported WordPress/PHP compatibility evidence
+ security/privacy review
+ accessibility acceptance
+ Plugin Check repo category green
+ install/activation/deactivation/uninstall tests
+ release ZIP
+ README/readme/version consistency
+ canonical Kairoseth product page
+ share
+ Custom Request CTA
+ documentation synchronized
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
