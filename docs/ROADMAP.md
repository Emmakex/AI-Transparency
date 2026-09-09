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

| Phase | Status | Accepted outcome / target |
|---|---|---|
| 1 — Repository bootstrap | **Closed** | Safe public plugin foundation and CI |
| 2 — Persistent AI Systems Registry | **Closed** | Local versioned Registry + CRUD/review/archive |
| 3 — Deterministic discovery | **Closed** | Explainable AI Engine 3.7.7 detector |
| 4 — Readiness findings & evidence | **Closed** | Deterministic Fact / Declaration / Guidance findings |
| 5 — Disclosure tooling | **Closed** | Reviewed Registry state → explicit public shortcode disclosure |
| 6 — Evidence export | **Closed** | Deterministic site-local JSON technical evidence snapshot |
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

- versioned Registry schema (`schema_version = 1`);
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

Exit: **complete.**

## Phase 4 — Readiness findings and evidence

Status: **closed — accepted, merged and verified on `main`.**

Accepted rules:

```text
registry_review_pending_v1
interaction_context_missing_v1
configured_disclosure_review_v1
```

Closure evidence:

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

Exit: **complete.**

## Phase 5 — Disclosure tooling

Status: **closed — accepted, merged, documented and verified on `main`.**

Accepted eligibility:

```text
status = active
review_status = reviewed
interaction_disclosure_required = true
trim(interaction_context) != empty
```

Accepted flow:

```text
site-local Registry
→ DisclosureEngine
→ Tools → AI Disclosure = Ready / Not ready
→ [kairoseth_ai_disclosure system="SYSTEM_ID"]
→ server-side Registry lookup and eligibility
→ escaped EN/ES public notice
```

Implementation and closure evidence:

```text
Contract PR: #11
Contract merge: d95483e74f7b1045f2d497219fb70d7d71165faf
Contract post-merge CI: #80 / 34391946448
Implementation PR: #12
Accepted head: 37ac6f8a3adf6ae33c98910fc0b2ff816789a697
PR-head CI: #82 / 34394624556
Implementation merge: 2770c7b7982ffbbe07ba58e8cedebd12d0add14a
Implementation post-merge main CI: #83 / 34395173777
Closure docs PR: #13
Closure docs PR CI: #84 / 34396119654
Closure docs merge: 0912cf2a21956d25b8c77b1ffec3668d041def42
Final Phase 5 main CI: #85 / 34396547978
Blockers: 0
```

Exit: **complete.**

## Phase 6 — Evidence export

Status: **closed — accepted, merged and verified on `main` on 9 September 2026.**

Goal achieved: produce a dated, reviewable, privacy-safe and deterministic **site-local JSON technical evidence snapshot** without leaking secrets or turning technical evidence into legal certification.

Accepted v1 flow:

```text
WordPress administrator
→ Tools → AI Evidence Export
→ explicit POST
→ manage_options + nonce
→ current site-local Registry loaded server-side
→ RegistrySchema + persisted Discovery references
→ FindingEngine output
→ DisclosureEngine readiness
→ canonical allow-list snapshot
→ SHA-256 snapshot_signature
→ EvidenceJsonEncoder
→ direct JSON attachment download
```

Accepted boundaries:

- JSON only; `export_schema_version = 1`;
- current site/blog only in Multisite, never network-wide aggregation;
- complete current site-local Registry, including archived records;
- empty Registry is a valid signed export;
- deterministic system, Discovery-reference, finding and readiness ordering;
- normalized historical Discovery references only when persisted source shape is structurally valid;
- Phase 4 `FindingEngine` reused, not reimplemented;
- Phase 5 `DisclosureEngine` reused, not reimplemented;
- UTC `generated_at` metadata;
- stable SHA-256 `snapshot_signature` excludes volatile generation time;
- same technical state + same export contract → same signature across generation times;
- meaningful exported technical-state change → different signature;
- `interaction_context` is included only in the privileged administrative evidence artifact with a confidentiality warning;
- credentials, tokens, cookies, nonces, request headers, user identities, prompts, conversations, customer content, logs, DB dumps and arbitrary WordPress/plugin options are excluded from the allow-list;
- no Media Library persistence, export-history storage, email, telemetry, Kairoseth upload, provider call or cloud account;
- technical snapshot identity is **not** a legal/digital signature, trusted timestamp, non-repudiation proof or certification;
- customer-facing export UI ships EN/ES together and passes responsive/accessibility acceptance.

Accepted implementation classes:

```text
src/Export/class-evidencesnapshot.php
src/Export/class-evidencesnapshotbuilder.php
src/Export/class-evidencejsonencoder.php
src/Admin/class-evidenceexportpage.php
```

Implementation and verification evidence:

```text
Contract PR: #14
Implementation PR: #15
Accepted implementation head: 2b9ebe93820e98d9ce6e0abb4deb235fdeeda57c
PR-head CI: #91 / 34402108452 — SUCCESS — 8/8 jobs green
Implementation merge: bd07261751471fe7866e62049e3a66b7bd767afe
Post-merge main CI: #92 / 34402685906 — SUCCESS — 8/8 jobs green
Blockers: 0
```

Runtime acceptance proved:

```text
real administrator page/action
+ attachment JSON response
+ response headers/cache policy
+ valid schema v1
+ stable unchanged-state signature
+ changed Registry → changed signature
+ archived record retention
+ empty Registry validity
+ valid historical Discovery-reference normalization
+ malformed Discovery source rejection
+ current FindingEngine output
+ current DisclosureEngine readiness
+ forbidden sensitive/user/request fields absent
+ Editor denied
+ 390 px / 200% / axe serious+critical = 0
+ real site-local Multisite export isolation
+ inherited Phase 2–5 regressions green
```

Resolved implementation-CI incidents:

```text
CI #88 / 34401564420
PHP Quality → composer verify → exit 2
signature: 2a713bc20438829d660dc95e2e37ae7ee4f63636d066a158e0ddf13c82bcb26e
cause: WPCS short ternary / @throws formatting / assignment alignment

CI #90 / 34401789546
PHP Quality → composer verify → exit 1
signature: 6b48a8a870fbf9e3431c98705136950325a21c62cd988d4b69f3048dda33ed09
cause: Squiz @throws interpretation on EvidenceSnapshotBuilder::build()

Both fixed without behavior change and validated by CI #91 and #92.
```

References:

- [`PHASE6_EVIDENCE_EXPORT_IMPLEMENTATION.md`](PHASE6_EVIDENCE_EXPORT_IMPLEMENTATION.md)
- [`PHASE6_ACCEPTANCE.md`](PHASE6_ACCEPTANCE.md)
- [`PHASE6_JSON_SCHEMA_V1.md`](PHASE6_JSON_SCHEMA_V1.md)
- [`PHASE6_RUNTIME_EVIDENCE.md`](PHASE6_RUNTIME_EVIDENCE.md)

Exit: **complete.**

## Phase 7 — Contextual support/custom integration path

Status: **not started — unblocked after Phase 6 closure documentation is merged and verified.**

Planned contract topics:

- plugin-owned Help/About CTA;
- EN/ES copy;
- user-initiated action only;
- bounded non-sensitive context;
- no automatic telemetry/lead submission;
- no credentials, logs, prompts, conversations or arbitrary site data;
- clear boundary between local Free functionality and optional Kairoseth support/custom work.

Exit target: user-initiated support/custom path works end-to-end without weakening privacy, WordPress authority or local-first behavior.

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
