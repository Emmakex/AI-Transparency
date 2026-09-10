# Kairoseth AI Transparency — Implementation Architecture

Status: active development — **Phases 1–7 closed; Phase 8 not started**  
Last reviewed: 10 September 2026

## Product boundary

Kairoseth AI Transparency is a local-first WordPress plugin for technical AI-transparency readiness. The accepted architecture provides:

```text
local AI Systems Registry
+ deterministic supported integration Discovery
+ deterministic Readiness findings
+ explicit administrator-controlled public Disclosure tooling
+ privileged deterministic JSON Evidence Export
+ optional user-initiated contextual Support / Custom Requests navigation
```

The support/custom path does not make any accepted local workflow dependent on Kairoseth.

The plugin is **not** a legal certification engine and does not infer legal obligations from plugin presence, probabilistic guesses, arbitrary content or weak contextual evidence.

## Architectural principles

1. **Local-first baseline.** Registry, Discovery, Readiness, Disclosure and Evidence Export require no automatic external account, telemetry or off-site transfer.
2. **Evidence before conclusions.** Discovery and Readiness report bounded technical state and explicit administrator declarations.
3. **FACT / DECLARATION / GUIDANCE separation.** Phase 4 findings preserve these concepts independently.
4. **Explicit disclosure authority.** Phase 5 renders only from reviewed administrator-configured Registry state.
5. **Allow-list evidence export.** Phase 6 serializes only contracted evidence fields; arbitrary option/request/session data never become export input.
6. **Stable evidence identity.** `generated_at` is metadata and is excluded from the Phase 6 stable `snapshot_signature`.
7. **Server-authoritative permissions/state.** Browser/client values never grant WordPress privileges, disclosure eligibility, export evidence authority or Kairoseth destination authority.
8. **Browser input is an action/selector, not evidence.** Server-side state remains authoritative across Discovery, Readiness, Disclosure, Export and support-context construction.
9. **User-initiated external navigation.** Phase 7 performs no background Kairoseth request on page load; only an explicit CTA click may leave WordPress.
10. **Strict contextual allow-list.** Phase 7 exposes only bounded product/version/platform/locale/request-type context; Registry/evidence/personal/site data remain excluded.
11. **Fail-closed destination validation.** Only the canonical HTTPS `kairoseth.com/custom-requests` destination is accepted.
12. **WordPress-native security.** Capability checks, nonces where mutations exist, validation/sanitization and context-correct output encoding remain mandatory.
13. **Domain separated from adapters.** Deterministic Registry/Discovery/Finding/Disclosure/Export/Support logic stays independently testable where practical.
14. **EN/ES ships together.** Customer-facing changes require complete English and Spanish runtime catalogs in the same change.
15. **Responsive/accessibility acceptance.** Plugin-owned admin/frontend surfaces pass relevant browser gates.
16. **Production package is release authority.** Plugin Check and runtime acceptance validate `build/ai-transparency/`.
17. **Failures become reusable knowledge.** Material CI/runtime failures produce actionable diagnostics and durable failure-memory records.
18. **Finish before advancing.** A dependent next phase cannot begin before implementation, required gates, merge, verification and documentation are complete.

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
│   │   ├── class-evidenceexportpage.php
│   │   └── class-supportpage.php
│   ├── Domain/
│   ├── Registry/
│   ├── Persistence/
│   ├── Discovery/
│   ├── Evidence/
│   ├── Disclosure/
│   ├── Export/
│   │   ├── class-evidencesnapshot.php
│   │   ├── class-evidencesnapshotbuilder.php
│   │   └── class-evidencejsonencoder.php
│   └── Support/
│       ├── class-supportcontext.php
│       └── class-supporturlbuilder.php
├── assets/
├── languages/
├── bin/
├── tests/
├── docs/
└── .github/workflows/
```

## Release flow

```text
source repository
→ EN/ES source coverage
→ deterministic build
→ compiled Spanish gettext catalog
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

## Registry / Discovery / Readiness / Disclosure

The Registry remains the canonical site-local inventory. Persistence uses the versioned `kairoseth_ai_transparency_registry` WordPress option and follows current blog/site context in Multisite.

Discovery first validates AI Engine 3.7.7 from bounded WordPress plugin identity evidence and never infers provider/model/workflow configuration or reads provider credentials.

Readiness derives deterministic findings on demand and preserves:

```text
FACT
ADMINISTRATOR DECLARATION
GUIDANCE
```

Disclosure eligibility remains server-authoritative:

```text
status = active
review_status = reviewed
interaction_disclosure_required = true
trim(interaction_context) != empty
```

Public shortcode:

```text
[kairoseth_ai_disclosure system="SYSTEM_ID"]
```

Public output remains limited to localized disclosure copy plus the reviewed system name.

## Evidence Export — Phase 6 accepted

Accepted authority:

```text
Tools → AI Evidence Export
→ POST + manage_options + nonce
→ current site-local Registry
→ RegistrySchema::encode()
→ persisted valid Discovery references
→ FindingEngine
→ DisclosureEngine
→ canonical allow-list payload
→ SHA-256 snapshot_signature
→ EvidenceJsonEncoder
→ direct JSON attachment
```

Key boundaries:

- complete current site-local Registry, including archived records;
- empty Registry valid;
- deterministic ordering;
- `generated_at` excluded from stable signature identity;
- `interaction_context` only in privileged administrative export;
- credentials/tokens/cookies/nonces/user identities/prompts/conversations/logs/DB dumps/arbitrary options excluded;
- no export persistence, email, telemetry, Kairoseth upload or cloud account;
- current authoritative blog/site only in Multisite.

Accepted evidence:

```text
Implementation PR: #15
Accepted head: 2b9ebe93820e98d9ce6e0abb4deb235fdeeda57c
PR-head CI: #91 / 34402108452 — 8/8 green
Implementation merge: bd07261751471fe7866e62049e3a66b7bd767afe
Post-merge main CI: #92 / 34402685906 — 8/8 green
Closure docs PR: #16
Closure docs merge: baaeb200c7aa0a7625923515f8f1637e973797d9
Final Phase 6 main CI: #94 / 34404252257 — 8/8 green
Blockers: 0
```

References:

- [`PHASE6_EVIDENCE_EXPORT_IMPLEMENTATION.md`](PHASE6_EVIDENCE_EXPORT_IMPLEMENTATION.md)
- [`PHASE6_ACCEPTANCE.md`](PHASE6_ACCEPTANCE.md)
- [`PHASE6_JSON_SCHEMA_V1.md`](PHASE6_JSON_SCHEMA_V1.md)
- [`PHASE6_RUNTIME_EVIDENCE.md`](PHASE6_RUNTIME_EVIDENCE.md)

## Contextual Support / Custom Integration — Phase 7 accepted

### WordPress surface

```text
Tools → AI Transparency Support
capability: manage_options
```

The page is read-only with respect to Registry state and exposes two explicit navigation actions:

```text
Get support
→ implementation_support

Request custom integration
→ third_party_integration
```

### Context construction

Server-side automatic context is limited to:

```text
source=extension
extensionSlug=ai-transparency
extensionName=Kairoseth AI Transparency
extensionVersion=<runtime plugin version>
hostPlatform=wordpress
hostPlatformVersion=<runtime WordPress version>
locale=<WordPress locale normalized to en|es>
requestType=<accepted enum>
```

Accepted `requestType` values:

```text
implementation_support
third_party_integration
business_customization
automation
additional_feature
other
```

### Forbidden automatic context

The plugin does not automatically transmit:

```text
site/home URL
administrator/customer/user identity
Registry contents or AI system names
interaction_context
Discovery evidence
Readiness findings
Disclosure state
Evidence Export JSON or snapshot_signature
plugin/theme inventory
server paths / IP
cookies / nonces / session data
credentials / API / OAuth tokens
prompts / conversations / customer content
logs / database contents / arbitrary options
```

### Navigation / network boundary

```text
GET WordPress support page
→ zero Kairoseth request

explicit CTA click
→ browser navigation only
→ https://kairoseth.com/custom-requests
```

The WordPress plugin does not submit the final lead/request. Kairoseth owns the destination form, privacy notice, consent, backend validation, rate limiting and final SMTP delivery.

### Destination authority

`SupportUrlBuilder` accepts only:

```text
scheme = https
host = kairoseth.com
path = /custom-requests
no userinfo/password
no custom port
no preloaded query
no fragment
```

Unknown request types and unsafe destinations fail closed.

### Local independence

A broken/unavailable support destination cannot break:

```text
Registry
Discovery
Readiness
Disclosure
Evidence Export
```

### Production backend authority

The Kairoseth backend re-normalizes extension context and resolves the destination mailbox only server-side:

```text
CUSTOM_REQUESTS_TO
→ otherwise SMTP_USER
→ otherwise fail closed
```

The browser, WordPress context and user-entered form fields cannot select the mailbox.

### Accepted evidence

```text
Contract PR: #17
Contract CI: #95 / 34405157557 — SUCCESS
Contract merge: cbc04eea07b20af60f3ec4b3621a9aa89c92ae84
Contract post-merge CI: #96 / 34405183831 — SUCCESS
Implementation PR: #18
Accepted head: e49721eb00b85b0a4cfbf72d53e876d80dc96f44
PR-head CI: #101 / 34436069862 — 8/8 green
Implementation merge: f225646808f604b5758bbc960417451af8c31738
Post-merge main CI: #102 / 34436374187 — 8/8 green
Kairoseth fallback merge: 5c01adfd40151da6392c8d780203230c315c19fb
Kairoseth post-merge CI #897 / 34437075381 — SUCCESS
Kairoseth Production Smoke #119 / 34437075355 — SUCCESS
Final synthetic SMTP E2E #4 / 34437244753 — SUCCESS
Blockers: 0
```

References:

- [`PHASE7_CONTEXTUAL_SUPPORT_IMPLEMENTATION.md`](PHASE7_CONTEXTUAL_SUPPORT_IMPLEMENTATION.md)
- [`PHASE7_ACCEPTANCE.md`](PHASE7_ACCEPTANCE.md)
- [`PHASE7_RUNTIME_EVIDENCE.md`](PHASE7_RUNTIME_EVIDENCE.md)

## WordPress compatibility baseline

- Requires WordPress: 6.6+
- Tested-up-to target: 7.1
- Requires PHP: 7.4+
- AI Engine runtime fixture: 3.7.7

CI validates PHP 7.4 / 8.1 / 8.3 / 8.5 syntax.

## CI / release gates

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

PHP syntax 7.4 / 8.1 / 8.3 / 8.5

WordPress Plugin Check
└ exact build/ai-transparency package

WordPress runtime acceptance
├ activation + migration
├ Registry CRUD/permissions
├ Discovery
├ Readiness
├ Disclosure
├ Evidence Export
├ contextual Support privacy/URL behavior
├ responsive/accessibility
└ Multisite isolation
```

Repository-controlled failures use `bin/run-with-diagnostics.sh` and `.ci-diagnostics/` artifacts where configured.

## Phase status

- Phase 1: closed.
- Phase 2 Persistent AI Systems Registry: closed and verified on `main`.
- Phase 3 Deterministic Discovery: closed and verified on `main`.
- Phase 4 Readiness Findings & Evidence: closed and verified on `main`.
- Phase 5 Disclosure Tooling: closed and verified on `main`.
- Phase 6 Evidence Export: closed and verified on `main`.
- Phase 7 Contextual Support / Custom Integration: **closed and verified end-to-end**.
- Phase 8 First public release: **not started**.
