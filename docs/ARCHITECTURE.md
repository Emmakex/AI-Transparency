# Kairoseth AI Transparency — Implementation Architecture

Status: active development — **Phases 1–5 closed; Phase 6 Evidence Export contract active, implementation not started**  
Last reviewed: 9 September 2026

## Product boundary

Kairoseth AI Transparency is a WordPress plugin for technical AI-transparency readiness. It currently provides:

```text
local AI Systems Registry
+ deterministic supported integration discovery
+ deterministic readiness findings
+ explicit administrator-controlled public disclosure tooling
```

Phase 6 is now designing a local, administrator-generated evidence snapshot on top of those accepted capabilities. The export is not implemented yet.

It is **not** a legal certification engine. It does not infer legal obligations from plugin presence, probabilistic guesses, arbitrary content or weak contextual evidence.

## Architectural principles

1. **Local-first baseline.** Core Registry, Discovery, Readiness and Disclosure workflows require no automatic external account, telemetry or off-site transfer.
2. **Evidence before conclusions.** Discovery and Readiness report only bounded technical state and explicit administrator declarations.
3. **FACT / DECLARATION / GUIDANCE separation.** Phase 4 findings preserve these concepts independently.
4. **Explicit disclosure authority.** Phase 5 disclosure renders only from reviewed administrator-configured registry state.
5. **Allow-list evidence export.** Phase 6 may serialize only explicitly contracted evidence fields; arbitrary options/request/session data are never export input.
6. **Stable evidence identity.** Export generation time is metadata, not part of the stable technical snapshot identity.
7. **Server-authoritative permissions/state.** Browser/client values never grant WordPress privileges, disclosure eligibility or export evidence authority.
8. **Browser input is a selector/action, not evidence.** Discovery re-observes WordPress server-side; Readiness loads the registry server-side; Disclosure resolves the current registry server-side; Export will build the complete current-site snapshot server-side.
9. **WordPress-native security.** Validate/sanitize input and escape/output-encode for its context.
10. **Domain separated from adapters.** Deterministic Registry/Discovery/Finding/Disclosure/Export logic stays independently testable where practical.
11. **EN/ES ships together.** Customer-facing changes require complete English and Spanish runtime catalogs in the same change.
12. **Responsive/accessibility acceptance.** Plugin-owned admin/frontend surfaces pass the relevant browser gates.
13. **Production package is release authority.** Plugin Check and runtime acceptance validate `build/ai-transparency/`.
14. **Failures become reusable knowledge.** CI/runtime failures produce actionable diagnostics; material regressions become durable failure memory.
15. **Finish before advancing.** A dependent next phase cannot begin before the current phase implementation, required gates, merge, verification and docs are complete.

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
│   │   └── class-disclosurepage.php
│   ├── Domain/
│   │   └── class-aisystem.php
│   ├── Registry/
│   │   ├── class-aisystemsregistry.php
│   │   └── class-registryschema.php
│   ├── Persistence/
│   │   └── class-wordpressoptionsregistryrepository.php
│   ├── Discovery/
│   │   ├── class-pluginobservation.php
│   │   ├── class-discoveryresult.php
│   │   ├── class-aienginedetector.php
│   │   └── class-wordpressplugininventory.php
│   ├── Evidence/
│   │   ├── class-finding.php
│   │   └── class-findingengine.php
│   ├── Disclosure/
│   │   ├── class-disclosure.php
│   │   ├── class-disclosureengine.php
│   │   └── class-disclosureshortcode.php
│   └── Export/                    Phase 6 — contract active; implementation not started
├── assets/
│   ├── admin.css
│   └── frontend.css
├── languages/
│   ├── ai-transparency.pot
│   ├── ai-transparency-es_ES.po
│   └── compiled .mo in production package
├── bin/
│   ├── build-plugin.sh
│   ├── check-i18n.php
│   ├── compile-po.php
│   └── run-with-diagnostics.sh
├── tests/
│   ├── DisclosureEngineTest.php
│   ├── e2e/
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

Development-only files are intentionally absent from the generated plugin package.

## Internationalization architecture

Source language: English. Runtime domain:

```text
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

Manual records use WordPress-generated UUIDs. Discovered records use stable detector-derived ids. Archive changes lifecycle state rather than deleting the record.

### Schema and persistence

`RegistrySchema::VERSION = 1` remains authoritative after Phase 5:

```text
{
  schema_version: 1,
  systems: [ ...deterministically ordered records... ]
}
```

Persistence:

```text
option: kairoseth_ai_transparency_registry
API: get_option() / update_option()
autoload: false on writes
scope: current WordPress blog/site
```

The repository migrates the earlier bootstrap shape, skips invalid individual records, fails safe on unknown future schema versions and preserves site-local Multisite separation.

### Registry administration

**Tools → AI Transparency** supports:

```text
list
add
edit
review state
interaction context
disclosure-required declaration
archive
```

Mutations use `admin-post.php`, `manage_options`, nonce verification and server-side validation/sanitization.

## Deterministic Discovery — Phase 3 accepted

**Tools → AI Discovery** converts supported WordPress evidence into reviewable candidates.

```text
WordPressPluginInventory
→ PluginObservation[]
→ detector
→ DiscoveryResult
   detector id
   observed version
   validated boundary
   explainable evidence
   SHA-256 signature
→ explicit administrator acceptance
→ server re-observation
→ registry candidate
```

Accepted AI Engine detector v1:

```text
plugin file: ai-engine/ai-engine.php
text domain: ai-engine
name prefix: AI Engine
active: required
validated version: 3.7.7
```

The detector proves only the supported WordPress plugin identity/version/activation state. It does not read AI Engine provider credentials, models, prompts, conversations or internal workflow configuration.

Accepted discovered candidate defaults:

```text
type: other
source_origin: discovered
review_status: pending
interaction_context: empty
interaction_disclosure_required: false
```

This prevents plugin presence from becoming evidence that a particular chatbot/workflow exists or that disclosure is required.

Implementation: [`PHASE3_DISCOVERY_IMPLEMENTATION.md`](PHASE3_DISCOVERY_IMPLEMENTATION.md).  
Runtime: [`PHASE3_RUNTIME_EVIDENCE.md`](PHASE3_RUNTIME_EVIDENCE.md).

## Readiness Findings — Phase 4 accepted

**Tools → AI Readiness** is a read-only `manage_options` surface.

```text
WordPressOptionsRegistryRepository
→ AiSystemsRegistry
→ FindingEngine
→ Finding[]
→ ReadinessPage
```

Findings are calculated on demand rather than persisted separately.

Accepted rules:

```text
registry_review_pending_v1
interaction_context_missing_v1
configured_disclosure_review_v1
```

Presentation semantics remain separate:

```text
FACT        — technically observed local state
DECLARATION — explicit administrator state
GUIDANCE    — technical review/completion action
```

The disclosure-related finding reports the administrator's declaration. It does not infer that law creates a disclosure duty.

Implementation: [`PHASE4_FINDINGS_IMPLEMENTATION.md`](PHASE4_FINDINGS_IMPLEMENTATION.md).  
Acceptance: [`PHASE4_ACCEPTANCE.md`](PHASE4_ACCEPTANCE.md).  
Runtime: [`PHASE4_RUNTIME_EVIDENCE.md`](PHASE4_RUNTIME_EVIDENCE.md).

## Disclosure Tooling — Phase 5 accepted

Phase 5 introduces the first public frontend component while preserving Registry authority.

### Eligibility architecture

```text
AiSystem
→ DisclosureEngine::reason_codes()
→ eligible only when:
   status = active
   review_status = reviewed
   interaction_disclosure_required = true
   trim(interaction_context) != empty
→ Disclosure or null
```

Deterministic ineligibility codes:

```text
archived
pending_review
missing_interaction_context
disclosure_not_configured
```

`source_origin` does not grant eligibility.

### Disclosure model

`Disclosure` is immutable and intentionally bounded:

```text
subject_system_id      internal model identity
subject_system_name    reviewed public value
copy_version           inline_v1
```

The internal id is not emitted in the disclosure markup.

### Administrator surface

**Tools → AI Disclosure**:

- requires `manage_options`;
- is read-only with respect to Registry state;
- shows `Ready` + exact shortcode for eligible systems;
- shows `Not ready` + bounded reasons for ineligible systems;
- links back to Registry editing when correction is needed.

Configuration remains in **Tools → AI Transparency**, retaining its capability + nonce mutation boundary.

### Public shortcode

Accepted contract:

```text
[kairoseth_ai_disclosure system="SYSTEM_ID"]
```

Runtime authority:

```text
attribute
→ shortcode_atts
→ sanitize + length bound
→ exact site-local registry lookup
→ server-side DisclosureEngine
→ Disclosure or empty string
→ escaped localized markup
```

A page author cannot force an ineligible system to render by changing the shortcode.

### Public output boundary

Public disclosure may expose only:

```text
localized title/body
administrator-reviewed system name
```

It does not expose automatically:

```text
interaction_context
system id in markup
source/source_origin
review timestamps
provider/model configuration
credentials/API keys
prompts/conversations
customer content/private logs
```

No automatic telemetry, cookies, remote API request or Kairoseth cloud dependency is introduced.

### Frontend presentation

Accepted semantic shape:

```html
<aside class="ai-transparency-disclosure" aria-label="AI transparency notice">
  <strong class="ai-transparency-disclosure__title">AI transparency notice</strong>
  <p class="ai-transparency-disclosure__body">…</p>
</aside>
```

`assets/frontend.css` uses only the disclosure namespace and has no JavaScript dependency. It is conditionally loaded for supported singular content that contains the shortcode.

The production build explicitly requires this stylesheet.

### Accepted runtime evidence

```text
unique reviewed/configured runtime system
→ Tools → AI Disclosure = Ready
→ shortcode on real public page
→ anonymous visitor sees disclosure
→ internal interaction_context absent
→ 390 px green
→ 200% text green
→ axe serious/critical = 0
→ disable disclosure configuration
→ system = Not ready
→ same page no longer emits disclosure
```

Implementation PR #12:

```text
Accepted head: 37ac6f8a3adf6ae33c98910fc0b2ff816789a697
CI: #82 / 34394624556
Merge: 2770c7b7982ffbbe07ba58e8cedebd12d0add14a
Post-merge CI: #83 / 34395173777
Blockers: 0
```

Implementation: [`PHASE5_DISCLOSURE_IMPLEMENTATION.md`](PHASE5_DISCLOSURE_IMPLEMENTATION.md).  
Acceptance: [`PHASE5_ACCEPTANCE.md`](PHASE5_ACCEPTANCE.md).  
Runtime: [`PHASE5_RUNTIME_EVIDENCE.md`](PHASE5_RUNTIME_EVIDENCE.md).

## Evidence Export — Phase 6 contract active

Phase 6 is now in **active design / contract**. Production implementation has not started.

### First supported architecture

```text
Tools → AI Evidence Export
→ explicit POST action
→ manage_options + nonce
→ current site-local WordPressOptionsRegistryRepository
→ EvidenceSnapshotBuilder
   ├ Registry schema + all current systems
   ├ persisted discovery evidence references where valid
   ├ FindingEngine output
   ├ DisclosureEngine readiness
   ├ deterministic ordering
   └ canonical stable payload
→ SHA-256 snapshot_signature
→ JsonExporter
→ direct browser download
```

No export file is persisted by the plugin and no remote service participates in the v1 flow.

### Proposed export classes

```text
src/Export/class-evidencesnapshot.php
src/Export/class-evidencesnapshotbuilder.php
src/Export/class-jsonexporter.php
src/Admin/class-evidenceexportpage.php
```

Names may be simplified during implementation, but responsibility boundaries are blocking.

### JSON-only v1

The accepted first format is JSON:

```text
export_schema_version = 1
```

Canonical top-level sections:

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

Phase 6 separates generation metadata from evidence identity:

```text
same technical state + same export contract
→ same snapshot_signature

different generated_at only
→ same snapshot_signature

meaningful exported technical state change
→ different snapshot_signature
```

`snapshot_signature` is SHA-256 over a plugin-built canonical allow-list payload. `generated_at`, HTTP response data, current user/request/session data and localized UI text are excluded from the hash.

This is a technical snapshot identity. It is not a digital signature, trusted timestamp, non-repudiation proof or legal certification.

### Registry evidence boundary

The privileged JSON snapshot includes all current site-local records, including archived records, sorted by stable id.

`interaction_context` is included because it is administrator-authored technical evidence used by Phases 4 and 5. Therefore the export is an administrative artifact that may contain confidential operational context.

This does not weaken Phase 5 public rendering: public disclosure still excludes `interaction_context`.

### Discovery evidence boundary

Persisted discovered records may expose a normalized historical source reference when their source matches:

```text
detector:<detector_id>:<source_signature>
```

The exporter must not call that historical signature a fresh observation. Discovery re-observation at export time is deferred.

### Derived evidence

Phase 6 reuses existing engines:

```text
FindingEngine
→ semantic finding codes + Phase 4 evidence_signature

DisclosureEngine
→ eligible + deterministic reason_codes
```

No rules are duplicated in exporter code.

### Security/privacy boundary

Serialization is allow-list only. Phase 6 v1 must not include automatically:

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

The browser never supplies the evidence payload or selected system list. The server exports the complete current site-local Registry.

### Multisite boundary

```text
current blog/site only
≠ network-wide export
```

The current server blog context is authoritative. Browser-supplied blog identifiers cannot override it.

### No persistence / no cloud

```text
build in memory
→ serialize JSON
→ direct attachment response
→ request ends
```

No Media Library file, export-history option/table, email, Kairoseth upload, telemetry or cloud account is created in v1.

### Contract references

Implementation/design: [`PHASE6_EVIDENCE_EXPORT_IMPLEMENTATION.md`](PHASE6_EVIDENCE_EXPORT_IMPLEMENTATION.md).  
Acceptance: [`PHASE6_ACCEPTANCE.md`](PHASE6_ACCEPTANCE.md).  
JSON schema v1: [`PHASE6_JSON_SCHEMA_V1.md`](PHASE6_JSON_SCHEMA_V1.md).

No `src/Export/` code may be treated as implemented until this contract is merged and the separate implementation PR satisfies the Phase 6 acceptance gates.

## WordPress compatibility baseline

- Requires WordPress: 6.6+
- Tested-up-to target: 7.1
- Requires PHP: 7.4+
- AI Engine runtime fixture: 3.7.7, requiring its compatible runtime lane

The plugin source remains PHP 7.4 compatible; CI validates PHP 7.4 / 8.1 / 8.3 / 8.5 syntax.

## Security/privacy boundary

The public plugin must never silently collect or expose:

- customer/provider secrets;
- API keys/model credentials;
- private prompts/conversations;
- customer-specific private mappings;
- private customer data fixtures;
- hidden remote execution paths;
- browser/model output capable of granting WordPress privileges.

Registry, Discovery, Readiness and Disclosure make no automatic external Kairoseth request. The Phase 6 contract preserves this local-first boundary for evidence export.

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
├ production activation
├ schema migration
├ Registry CRUD/permissions
├ AI Engine 3.7.7 Discovery
├ Discovery → Registry → Readiness
├ Phase 5 Registry → Disclosure Admin → anonymous frontend
├ responsive/accessibility
└ Multisite registry isolation
```

When Phase 6 implementation changes runtime/UI, its required contract additionally includes protected export download, deterministic signature checks, secret exclusion and site-local Multisite export isolation.

Repository-controlled failures use `bin/run-with-diagnostics.sh` and `.ci-diagnostics/` artifacts where configured.

## Phase status

- Phase 1: closed.
- Phase 2 Persistent AI Systems Registry: closed and verified on `main`.
- Phase 3 Deterministic Discovery: closed and verified on `main`.
- Phase 4 Readiness Findings & Evidence: closed and verified on `main`.
- Phase 5 Disclosure Tooling: **closed and verified on `main` via PR #12, CI #82 and post-merge CI #83; closure docs merged via PR #13**.
- Phase 6 Evidence Export: **contract active / implementation not started**.
- Phases 7–8: not started.
