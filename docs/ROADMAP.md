# Kairoseth AI Transparency — Technical Roadmap

Status: active development  
Last reviewed: 10 September 2026

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
| 7 — Contextual support/custom integration | **Closed** | User-initiated bounded WordPress → Kairoseth Custom Requests flow with production SMTP proof |
| 8 — First public release | **Not started** | Stable public release after all release gates |

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
- Phase 4 `FindingEngine` and Phase 5 `DisclosureEngine` reused, not reimplemented;
- UTC `generated_at` metadata;
- stable SHA-256 `snapshot_signature` excludes volatile generation time;
- same technical state + same export contract → same signature across generation times;
- meaningful exported technical-state change → different signature;
- `interaction_context` is included only in the privileged administrative evidence artifact with a confidentiality warning;
- credentials, tokens, cookies, nonces, request headers, user identities, prompts, conversations, customer content, logs, DB dumps and arbitrary WordPress/plugin options are excluded from the allow-list;
- no Media Library persistence, export-history storage, email, telemetry, Kairoseth upload, provider call or cloud account;
- technical snapshot identity is **not** a legal/digital signature, trusted timestamp, non-repudiation proof or certification;
- customer-facing export UI ships EN/ES together and passes responsive/accessibility acceptance.

Implementation and verification evidence:

```text
Contract PR: #14
Implementation PR: #15
Accepted implementation head: 2b9ebe93820e98d9ce6e0abb4deb235fdeeda57c
PR-head CI: #91 / 34402108452 — SUCCESS — 8/8 jobs green
Implementation merge: bd07261751471fe7866e62049e3a66b7bd767afe
Post-merge main CI: #92 / 34402685906 — SUCCESS — 8/8 jobs green
Closure docs PR: #16
Closure docs merge: baaeb200c7aa0a7625923515f8f1637e973797d9
Final Phase 6 main CI: #94 / 34404252257 — SUCCESS — 8/8 jobs green
Blockers: 0
```

References:

- [`PHASE6_EVIDENCE_EXPORT_IMPLEMENTATION.md`](PHASE6_EVIDENCE_EXPORT_IMPLEMENTATION.md)
- [`PHASE6_ACCEPTANCE.md`](PHASE6_ACCEPTANCE.md)
- [`PHASE6_JSON_SCHEMA_V1.md`](PHASE6_JSON_SCHEMA_V1.md)
- [`PHASE6_RUNTIME_EVIDENCE.md`](PHASE6_RUNTIME_EVIDENCE.md)

Exit: **complete.**

## Phase 7 — Contextual support/custom integration path

Status: **closed — accepted, merged and verified end-to-end on 10 September 2026.**

Goal achieved: provide an optional, user-initiated route from the WordPress admin to Kairoseth support/custom work while preserving the useful local Free product and strict privacy/data-minimization boundaries.

Accepted flow:

```text
administrator
→ Tools → AI Transparency Support
→ page load stays local
→ explicit Get support / Request custom integration click
→ plugin builds bounded contextual URL server-side
→ https://kairoseth.com/custom-requests
→ Kairoseth shows bounded product/platform context
→ user decides what personal/business information to enter
→ user explicitly consents and submits
→ Kairoseth backend validates, rate-limits and sends through SMTP
```

Allowed automatic context is exactly:

```text
source=extension
extensionSlug=ai-transparency
extensionName=Kairoseth AI Transparency
extensionVersion=<real plugin version>
hostPlatform=wordpress
hostPlatformVersion=<real WordPress version>
locale=<current locale>
requestType=<bounded enum>
```

Accepted request types:

```text
implementation_support
third_party_integration
business_customization
automation
additional_feature
other
```

The plugin does not automatically transmit site/home URL, user identity, Registry contents, AI-system names, `interaction_context`, Discovery evidence, Readiness findings, Disclosure state, Evidence Export data/signature, plugin inventory, credentials, prompts, conversations, logs, database content or arbitrary options.

Phase 7 introduces no automatic network request on page load, no background lead submission, no telemetry, no remote entitlement and no cloud dependency for existing local workflows.

Implemented classes:

```text
src/Support/class-supportcontext.php
src/Support/class-supporturlbuilder.php
src/Admin/class-supportpage.php
```

WordPress implementation evidence:

```text
Contract PR: #17
Contract head: 703e04cd073e2c7572ec65b367ef5cbfd5b9c78a
Contract PR CI: #95 / 34405157557 — SUCCESS
Contract merge: cbc04eea07b20af60f3ec4b3621a9aa89c92ae84
Contract post-merge CI: #96 / 34405183831 — SUCCESS
Implementation PR: #18
Accepted implementation head: e49721eb00b85b0a4cfbf72d53e876d80dc96f44
PR-head CI: #101 / 34436069862 — SUCCESS — 8/8 jobs green
Implementation merge: f225646808f604b5758bbc960417451af8c31738
Post-merge main CI: #102 / 34436374187 — SUCCESS — 8/8 jobs green
```

Kairoseth production evidence:

```text
Custom Requests implementation PR: kairoseth-platform #211
Implementation merge: 6855dfacd3ce6616c2f58d254e058ad3df59416c
Permanent production-proof PR: kairoseth-platform #212
Production-proof merge: 7d8752634e9b5e186a794080cb557c7b8cc6f349
Production Smoke #116 / 34434998950 — SUCCESS
SMTP recipient fallback PR: kairoseth-platform #213
Fallback merge: 5c01adfd40151da6392c8d780203230c315c19fb
Post-merge CI #897 / 34437075381 — SUCCESS
Post-merge Production Smoke #119 / 34437075355 — SUCCESS
Final synthetic SMTP proof #4 / 34437244753 — SUCCESS
Blockers: 0
```

Runtime acceptance proved:

```text
real administrator Tools page
+ manage_options authority
+ Editor denied
+ no Kairoseth request on page load
+ exact 8-key bounded context
+ canonical HTTPS kairoseth.com/custom-requests destination
+ no forbidden private/sensitive automatic context
+ 390 px / 200% / axe serious+critical = 0
+ inherited Registry/Discovery/Readiness/Disclosure/Evidence Export regressions green
+ real Multisite isolation green
+ production form route green
+ production backend validation/rate-limit green
+ SMTP delivery green
```

References:

- [`PHASE7_CONTEXTUAL_SUPPORT_IMPLEMENTATION.md`](PHASE7_CONTEXTUAL_SUPPORT_IMPLEMENTATION.md)
- [`PHASE7_ACCEPTANCE.md`](PHASE7_ACCEPTANCE.md)
- [`PHASE7_RUNTIME_EVIDENCE.md`](PHASE7_RUNTIME_EVIDENCE.md)

Exit: **complete.**

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
