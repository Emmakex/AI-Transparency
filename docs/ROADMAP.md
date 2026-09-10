# Kairoseth AI Transparency — Technical Roadmap

Status: **stable 1.0.0 released on GitHub; WordPress.org external publication pending**  
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
| 8 — First public release | **Active — GitHub 1.0.0 released; WordPress.org external gate pending** | Repository release complete; external directory submission/review remains |

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

Accepted detector boundary:

```text
WordPress.org slug: ai-engine
plugin file: ai-engine/ai-engine.php
text domain: ai-engine
validated version: 3.7.7
active required: yes
```

Closure evidence:

```text
Implementation PR: #7
Accepted PR head: af44fe2156bde2947519e78112ea1d7a39abb0ff
Pre-merge CI: #68 / 34373934127
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
Implementation PR: #9
Accepted PR head: 926a154930042e53af0b082795be155389cc6916
Pre-merge CI: #75 / 34381590429
Implementation merge: 836abfeca4c199930b74ba32547f9037f7fcb4de
Implementation post-merge CI: #76 / 34382057838
Closure docs PR: #10
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

Closure evidence:

```text
Contract PR: #11
Implementation PR: #12
Accepted implementation head: 37ac6f8a3adf6ae33c98910fc0b2ff816789a697
PR-head CI: #82 / 34394624556
Implementation merge: 2770c7b7982ffbbe07ba58e8cedebd12d0add14a
Closure docs PR: #13
Closure docs merge: 0912cf2a21956d25b8c77b1ffec3668d041def42
Final Phase 5 main CI: #85 / 34396547978
Blockers: 0
```

Exit: **complete.**

## Phase 6 — Evidence export

Status: **closed — accepted, merged and verified on `main`.**

Accepted outcome:

- JSON-only deterministic site-local evidence snapshot;
- complete current Registry including archived records;
- stable SHA-256 `snapshot_signature` for equivalent technical state;
- deterministic Discovery/findings/disclosure-readiness ordering;
- privileged `manage_options` + nonce export;
- explicit confidentiality boundary for `interaction_context`;
- credentials, tokens, user identities, prompts, conversations, logs, DB dumps and arbitrary options excluded;
- no persistence, telemetry, email, provider call or Kairoseth upload;
- current-blog isolation in Multisite;
- technical evidence is not represented as legal certification.

Closure evidence:

```text
Contract PR: #14
Implementation PR: #15
Accepted implementation head: 2b9ebe93820e98d9ce6e0abb4deb235fdeeda57c
PR-head CI: #91 / 34402108452 — SUCCESS — 8/8
Implementation merge: bd07261751471fe7866e62049e3a66b7bd767afe
Closure docs PR: #16
Closure docs merge: baaeb200c7aa0a7625923515f8f1637e973797d9
Final Phase 6 main CI: #94 / 34404252257 — SUCCESS — 8/8
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

Accepted flow:

```text
administrator
→ Tools → AI Transparency Support
→ page load stays local
→ explicit support/custom CTA
→ server-built bounded contextual HTTPS URL
→ https://kairoseth.com/custom-requests
→ user decides what personal/business data to enter
→ explicit consent + submit
→ Kairoseth validates, rate-limits and sends through SMTP
```

The plugin automatically generates only the accepted eight context keys and does not automatically transmit site URL, user identity, Registry content, AI-system names, evidence, credentials, prompts, conversations, logs or arbitrary WordPress data.

Closure evidence:

```text
Contract PR: #17
Implementation PR: #18
Accepted implementation head: e49721eb00b85b0a4cfbf72d53e876d80dc96f44
PR-head CI: #101 / 34436069862 — SUCCESS — 8/8
Implementation merge: f225646808f604b5758bbc960417451af8c31738
Post-merge main CI: #102 / 34436374187 — SUCCESS — 8/8
Kairoseth production delivery proof: #4 / 34437244753 — SUCCESS
Blockers: 0
```

References:

- [`PHASE7_CONTEXTUAL_SUPPORT_IMPLEMENTATION.md`](PHASE7_CONTEXTUAL_SUPPORT_IMPLEMENTATION.md)
- [`PHASE7_ACCEPTANCE.md`](PHASE7_ACCEPTANCE.md)
- [`PHASE7_RUNTIME_EVIDENCE.md`](PHASE7_RUNTIME_EVIDENCE.md)

Exit: **complete.**

## Phase 8 — First public release

Status: **repository-controlled release complete — GitHub 1.0.0 published and verified; WordPress.org external gate pending.**

Accepted stable release:

```text
version: 1.0.0
technical slug: ai-transparency
WordPress.org target slug: ai-transparency
accepted main/tag SHA: 5d0344876eb27db798ded87888b21b11b5581af5
release ZIP: ai-transparency-1.0.0.zip
SHA-256: b7fc6e0b4a80d39e0b9331faf89c3ad7c310f3d5f24795123eb9ab89299bb368
Git tag: 1.0.0
GitHub Release: Kairoseth AI Transparency 1.0.0
```

Repository-controlled gates completed:

```text
8A reproducible exact package
+ version/readme/changelog consistency
+ EN/ES 100% + compiled Spanish catalog
+ official WordPress Plugin Check
+ PHP 7.4/8.1/8.3/8.5
+ WordPress runtime acceptance
+ responsive/accessibility regressions
+ privacy/authority regressions

8B exact ZIP lifecycle
+ real 0.1.0 → 1.0.0 Registry-preserving upgrade
+ deactivate/reactivate preservation
+ explicit single-site uninstall isolation
+ fresh install
+ Multisite network activation + uninstall isolation

8C repository/GitHub release
+ implementation PR accepted and merged
+ release automation PR accepted and merged
+ final main CI green
+ final main Release Readiness green
+ stable tag created from accepted SHA
+ GitHub Release published
+ ZIP/checksum re-downloaded and verified byte-for-byte
```

Final evidence:

```text
Implementation PR: #21
Implementation merge: 9fea609de553e10af1618d385ccb85e4ab695ffe
Release automation PR: #22
Accepted main/tag SHA: 5d0344876eb27db798ded87888b21b11b5581af5
Final main CI: #129 / 34449448225 — SUCCESS — 8/8
Final main Release Readiness: #15 / 34449448874 — SUCCESS — 2/2
Stable Release: #1 / 34449679699 — SUCCESS
Repository-controlled blockers: 0
```

External WordPress.org state:

```text
accepted 1.0.0 submission artifact: READY
WordPress.org submission: PENDING / not recorded as sent
external review: PENDING
slug/repository assignment: NOT VERIFIED
public directory page/download: NOT VERIFIED
```

The project may state **stable 1.0.0 released on GitHub and ready for WordPress.org submission**. It must not state that the plugin is approved or available on WordPress.org until that is actually verified.

References:

- [`PHASE8_PUBLIC_RELEASE_IMPLEMENTATION.md`](PHASE8_PUBLIC_RELEASE_IMPLEMENTATION.md)
- [`PHASE8_ACCEPTANCE.md`](PHASE8_ACCEPTANCE.md)
- [`PHASE8_RELEASE_EVIDENCE.md`](PHASE8_RELEASE_EVIDENCE.md)

Exit: **repository-controlled exit complete; overall Phase 8 remains open only for the external WordPress.org publication gate.**

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
