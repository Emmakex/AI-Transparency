# Kairoseth AI Transparency — Implementation Architecture

Status: active development — **Phases 1–6 closed and verified on `main`; Phase 7 not started**  
Last reviewed: 9 September 2026

## Product boundary

Kairoseth AI Transparency is a local-first WordPress plugin for technical AI-transparency readiness. The accepted architecture now provides:

```text
local AI Systems Registry
+ deterministic supported integration Discovery
+ deterministic Readiness findings
+ explicit administrator-controlled public Disclosure tooling
+ privileged deterministic JSON Evidence Export
```

It is **not** a legal certification engine and does not infer legal obligations from plugin presence, probabilistic guesses, arbitrary content or weak contextual evidence.

## Architectural principles

1. **Local-first baseline.** Registry, Discovery, Readiness, Disclosure and Evidence Export require no automatic external account, telemetry or off-site transfer.
2. **Evidence before conclusions.** Discovery and Readiness report bounded technical state and explicit administrator declarations.
3. **FACT / DECLARATION / GUIDANCE separation.** Phase 4 findings preserve these concepts independently.
4. **Explicit disclosure authority.** Phase 5 renders only from reviewed administrator-configured Registry state.
5. **Allow-list evidence export.** Phase 6 serializes only contracted evidence fields; arbitrary option/request/session data never become export input.
6. **Stable evidence identity.** `generated_at` is metadata and is excluded from the Phase 6 stable `snapshot_signature`.
7. **Server-authoritative permissions/state.** Browser/client values never grant WordPress privileges, disclosure eligibility or export evidence authority.
8. **Browser input is an action/selector, not evidence.** Discovery re-observes WordPress server-side; Readiness, Disclosure and Export load authoritative Registry state server-side.
9. **WordPress-native security.** Capability checks, nonces, validation/sanitization and context-correct output encoding remain mandatory.
10. **Domain separated from adapters.** Deterministic Registry/Discovery/Finding/Disclosure/Export logic stays independently testable where practical.
11. **EN/ES ships together.** Customer-facing changes require complete English and Spanish runtime catalogs in the same change.
12. **Responsive/accessibility acceptance.** Plugin-owned admin/frontend surfaces pass relevant browser gates.
13. **Production package is release authority.** Plugin Check and runtime acceptance validate `build/ai-transparency/`.
14. **Failures become reusable knowledge.** Material CI/runtime failures produce actionable diagnostics and durable failure-memory records.
15. **Finish before advancing.** A dependent next phase cannot begin before implementation, required gates, merge, verification and documentation are complete.

Canonical policies:

- `docs/ENGINEERING_RULES.md`
- `docs/BILINGUAL_EN_ES_POLICY.md`
- `docs/CI_VALIDATION_POLICY.md`
- `docs/IMPLEMENTATION_COMPLETION_POLICY.md`
- `docs/CI_FAILURE_DIAGNOSTICS_POLICY.md`
- `docs/engineering-failures/README.md`

## Current source/release structure

```text
AI-Transparency/
├── ai-transparency.php
├── src/
│   ├── class-autoloader.php
│   ├── class-plugin.php
│   ├── Admin/
│   │   ├── class-adminpage.php
│   │   ├── class-discoverypage.php
│   │   ├── class-readinesspage.php
│   │   ├── class-disclosurepage.php
│   │   └── class-evidenceexportpage.php
│   ├── Domain/
│   │   └── class-aisystem.php
│   ├── Registry/
│   │   ├── class-aisystemsregistry.php
│   │   └── class-registryschema.php
│   ├── Persistence/
│   │   └── class-wordpressoptionsregistryrepository.php
│   ├── Discovery/
│   ├── Evidence/
│   │   ├── class-finding.php
│   │   └── class-findingengine.php
│   ├── Disclosure/
│   │   ├── class-disclosure.php
│   │   ├── class-disclosureengine.php
│   │   └── class-disclosureshortcode.php
│   └── Export/
│       ├── class-evidencesnapshot.php
│       ├── class-evidencesnapshotbuilder.php
│       └── class-evidencejsonencoder.php
├── assets/
├── languages/
├── bin/
├── tests/
│   ├── EvidenceSnapshotBuilderTest.php
│   ├── e2e/evidence-export.spec.js
│   └── runtime/
├── docs/
└── .github/workflows/
```

Release flow:

```text
source repository
→ EN/ES source coverage
→ deterministic build
→ compile Spanish gettext catalog
→ build/ai-transparency/
→ WordPress Plugin Check
→ real WordPress runtime acceptance
→ release candidate
```

Development-only files remain absent from the generated plugin package.

## Internationalization architecture

```text
source language: English
text domain: ai-transparency
Spanish source: languages/ai-transparency-es_ES.po
compiled Spanish: build/ai-transparency/languages/ai-transparency-es_ES.mo
```

`bin/check-i18n.php` blocks missing/empty Spanish runtime translations or incorrect text-domain usage.

## Core domain — AI Systems Registry

The Registry is the canonical local inventory of AI systems known to the plugin.

Current record shape:

```text
stable local id
name
system type
source identifier
source origin
lifecycle status
review status
interaction context
configured interaction-disclosure requirement
created / updated / reviewed timestamps
```

Persistence:

```text
schema_version: 1
option: kairoseth_ai_transparency_registry
API: get_option() / update_option()
autoload: false on writes
scope: current WordPress blog/site
```

Archive changes lifecycle state rather than deleting the record. The repository migrates the earlier bootstrap shape, skips invalid individual records, fails safe on unknown future schema versions and preserves site-local Multisite separation.

## Deterministic Discovery — Phase 3 accepted

```text
WordPressPluginInventory
→ PluginObservation[]
→ detector
→ DiscoveryResult
→ explicit administrator acceptance
→ server re-observation
→ Registry candidate
```

First validated detector:

```text
plugin file: ai-engine/ai-engine.php
text domain: ai-engine
name prefix: AI Engine
active: required
validated version: 3.7.7
```

Discovery proves only the supported WordPress plugin identity/version/activation state. It does not infer provider, model, chatbot, prompt or workflow configuration and does not inspect provider credentials.

## Readiness Findings — Phase 4 accepted

```text
WordPressOptionsRegistryRepository
→ AiSystemsRegistry
→ FindingEngine
→ Finding[]
→ Tools → AI Readiness
```

Findings are calculated on demand rather than persisted separately.

Accepted rules:

```text
registry_review_pending_v1
interaction_context_missing_v1
configured_disclosure_review_v1
```

Presentation semantics remain distinct:

```text
FACT
ADMINISTRATOR DECLARATION
GUIDANCE
```

## Disclosure Tooling — Phase 5 accepted

Eligibility remains server-authoritative:

```text
status = active
review_status = reviewed
interaction_disclosure_required = true
trim(interaction_context) != empty
```

Accepted public contract:

```text
[kairoseth_ai_disclosure system="SYSTEM_ID"]
```

A shortcode attribute is only a lookup selector. It cannot force an ineligible record to render.

Public output may expose only localized disclosure copy and the reviewed system name. It does not automatically expose Registry ids, `interaction_context`, source metadata, timestamps, provider/model configuration, credentials, prompts, conversations or private logs.

## Evidence Export — Phase 6 accepted

Phase 6 is **implemented, merged and verified on `main`**.

### Runtime authority

```text
Tools → AI Evidence Export
→ explicit POST
→ manage_options + nonce
→ current site-local WordPressOptionsRegistryRepository
→ EvidenceSnapshotBuilder
   ├ RegistrySchema::encode()
   ├ persisted Discovery source references where structurally valid
   ├ FindingEngine
   ├ DisclosureEngine
   ├ deterministic ordering
   └ canonical allow-list payload
→ SHA-256 snapshot_signature
→ EvidenceJsonEncoder
→ direct JSON attachment download
```

The browser supplies no Registry payload, findings, readiness values, signature, site identity or trusted timestamp. WordPress server state resolves the current site with `home_url()`, `is_multisite()` and `get_current_blog_id()`.

### Accepted classes

```text
src/Export/class-evidencesnapshot.php
src/Export/class-evidencesnapshotbuilder.php
src/Export/class-evidencejsonencoder.php
src/Admin/class-evidenceexportpage.php
```

### JSON v1

```text
export_schema_version
generated_at
snapshot_signature
generator
site
registry
discovery_evidence
findings
disclosure_readiness
```

Canonical schema: [`PHASE6_JSON_SCHEMA_V1.md`](PHASE6_JSON_SCHEMA_V1.md).

### Stable snapshot identity

```text
same technical state + same export contract
→ same snapshot_signature

different generated_at only
→ same snapshot_signature

meaningful exported technical-state change
→ different snapshot_signature
```

The signature is SHA-256 over the stable plugin-built allow-list payload. `generated_at`, filename/HTTP headers, current user/request/session state and localized UI prose are excluded.

This is a technical snapshot identity, not a digital/legal signature, trusted timestamp, non-repudiation proof or regulatory certification.

### Registry / Discovery / derived evidence

The export includes all current site-local Registry records, including archived records, sorted deterministically. `interaction_context` is present because the privileged file is administrative evidence; the UI warns that the downloaded file may contain confidential operational context.

Persisted discovered records may expose a normalized historical reference only when their source matches the accepted structural form:

```text
detector:<detector_id>:<source_signature>
```

This is not represented as a fresh observation.

Phase 6 reuses existing engines:

```text
FindingEngine
→ semantic finding codes + Phase 4 evidence_signature

DisclosureEngine
→ eligible + deterministic reason_codes
```

No Readiness or Disclosure rule is duplicated inside export code.

### Security/privacy boundary

Serialization is allow-list only and excludes automatically:

```text
wp-config/salts/database secrets
provider/API/OAuth credentials
cookies/nonces/request headers
administrator/user identities
prompts/conversations/customer content
private/debug logs
raw database dumps
arbitrary third-party options
browser storage
```

### No persistence / no cloud

```text
build in memory
→ serialize JSON
→ direct attachment response
→ request ends
```

No Media Library file, export-history option/table, email, telemetry, Kairoseth upload, provider call or cloud account is created in v1.

### Multisite boundary

```text
current authoritative blog/site only
≠ network-wide export
```

Browser-supplied blog ids cannot override the server context.

### Accepted evidence

```text
Contract PR: #14
Implementation PR: #15
Accepted head: 2b9ebe93820e98d9ce6e0abb4deb235fdeeda57c
PR-head CI: #91 / 34402108452 — 8/8 green
Implementation merge: bd07261751471fe7866e62049e3a66b7bd767afe
Post-merge main CI: #92 / 34402685906 — 8/8 green
Blockers: 0
```

Validated runtime evidence includes real browser attachment download, JSON/header checks, repeated unchanged-state signature stability, signature change after Registry mutation, forbidden-field absence, Editor denial, 390 px, 200% text, axe serious/critical = 0 and site-local Multisite export isolation.

References:

- [`PHASE6_EVIDENCE_EXPORT_IMPLEMENTATION.md`](PHASE6_EVIDENCE_EXPORT_IMPLEMENTATION.md)
- [`PHASE6_ACCEPTANCE.md`](PHASE6_ACCEPTANCE.md)
- [`PHASE6_JSON_SCHEMA_V1.md`](PHASE6_JSON_SCHEMA_V1.md)
- [`PHASE6_RUNTIME_EVIDENCE.md`](PHASE6_RUNTIME_EVIDENCE.md)

## WordPress compatibility baseline

- Requires WordPress: 6.6+
- Tested-up-to target: 7.1
- Requires PHP: 7.4+
- AI Engine runtime fixture: 3.7.7

CI validates PHP 7.4 / 8.1 / 8.3 / 8.5 syntax.

## CI / release gates

Current blocking gates for changed contracts:

```text
PHP Quality
├ WordPress Coding Standards
├ PHPCompatibility 7.4+
├ PHPUnit
└ EN/ES source coverage

EN/ES 100%
├ runtime string/catalog comparison
├ production build
└ compiled Spanish MO

PHP syntax
├ 7.4
├ 8.1
├ 8.3
└ 8.5

WordPress Plugin Check
└ exact build/ai-transparency package

WordPress runtime acceptance
├ activation + migration
├ Registry CRUD/permissions
├ AI Engine 3.7.7 Discovery
├ Readiness
├ Disclosure Admin → anonymous frontend
├ Evidence Export protected download + deterministic signature
├ responsive/accessibility
└ Multisite Registry + Evidence Export isolation
```

Repository-controlled failures use `bin/run-with-diagnostics.sh` and `.ci-diagnostics/` artifacts where configured.

## Phase status

- Phase 1: closed.
- Phase 2 Persistent AI Systems Registry: closed and verified on `main`.
- Phase 3 Deterministic Discovery: closed and verified on `main`.
- Phase 4 Readiness Findings & Evidence: closed and verified on `main`.
- Phase 5 Disclosure Tooling: closed and verified on `main`.
- Phase 6 Evidence Export: **closed and verified on `main` via PR #15, CI #91 and post-merge CI #92**.
- Phase 7 Contextual support/custom integration: not started.
- Phase 8 First public release: not started.
