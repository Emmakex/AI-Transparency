# Phase 6 — Evidence Export Acceptance

Status: **active contract — implementation not started**  
Last reviewed: 9 September 2026

## Scope

This contract applies to the first supported Phase 6 workflow: a privileged, user-initiated JSON evidence export for the **current site-local WordPress Registry**.

Phase 6 v1 does not create legal certification, cloud synchronization, export history, scheduled exports, import capability or human-readable PDF/report output.

## Blocking gates

### Export authority

- [ ] **Tools → AI Evidence Export** exists
- [ ] page requires `manage_options`
- [ ] export generation is an explicit POST/admin action
- [ ] export generation requires a valid nonce
- [ ] a page GET never triggers download side effects
- [ ] browser/request data never supplies Registry state, findings, readiness, signature or trusted timestamp
- [ ] server loads the current site-local Registry directly
- [ ] first increment exports the complete current site-local Registry rather than browser-selected system ids
- [ ] Editor/non-administrator cannot access or generate the export

### Local-only / persistence boundary

- [ ] export is built in memory for the current request
- [ ] JSON is downloaded directly to the requesting administrator
- [ ] plugin does not write the generated export to Media Library
- [ ] plugin does not persist export bytes/history in WordPress options
- [ ] plugin does not create a custom export-history table
- [ ] plugin does not email the export
- [ ] plugin does not send the export to Kairoseth or any remote service
- [ ] plugin introduces no export telemetry/cookies/cloud account requirement

### JSON response contract

- [ ] JSON is the only Phase 6 v1 format
- [ ] response `Content-Type` is `application/json; charset=UTF-8`
- [ ] response uses an attachment filename beginning `kairoseth-ai-transparency-evidence-`
- [ ] filename contains a UTC generation timestamp and `.json`
- [ ] response prevents caching with appropriate no-store/no-cache semantics
- [ ] payload is valid UTF-8
- [ ] payload parses as valid JSON
- [ ] serialization failure never emits a partially valid evidence document
- [ ] bounded failure does not leak stack traces or secrets

### Export envelope

- [ ] `export_schema_version = 1`
- [ ] `generated_at` exists and is UTC ISO-8601
- [ ] `snapshot_signature` exists
- [ ] `snapshot_signature` is lowercase SHA-256 hex with exactly 64 characters
- [ ] `generator` exists
- [ ] `site` exists
- [ ] `registry` exists
- [ ] `findings` exists
- [ ] `disclosure_readiness` exists
- [ ] export schema version is independent from Registry schema version

### Generator metadata

- [ ] generator identifies plugin slug `ai-transparency`
- [ ] generator identifies product name `Kairoseth AI Transparency`
- [ ] generator version comes from the real plugin runtime/version constant
- [ ] exporter does not maintain a contradictory duplicate hard-coded runtime version

### Site identity boundary

- [ ] current `home_url` is included
- [ ] current Multisite boolean is included
- [ ] current `blog_id` is included
- [ ] no administrator/user id is included
- [ ] no administrator/user email is included
- [ ] no client IP is included
- [ ] no auth/session cookie is included
- [ ] no request headers are included

### Registry snapshot

- [ ] current Registry schema version is included
- [ ] all current site-local records are included
- [ ] archived records are included
- [ ] empty Registry produces a valid export rather than an error
- [ ] exported systems use a bounded allow-list based on current stable `AiSystem` evidence fields
- [ ] systems are sorted by stable system id
- [ ] arbitrary WordPress option ordering cannot change snapshot identity
- [ ] administrator-authored `interaction_context` is included as confidential administrative evidence
- [ ] UI clearly warns that the evidence export may contain confidential operational context
- [ ] Phase 5 public disclosure continues to exclude `interaction_context`

### Discovery evidence references

- [ ] discovered Registry records preserve their persisted discovery source/signature reference where structurally valid
- [ ] normalized discovery reference includes system id, detector id and source signature
- [ ] export does not misrepresent historical persisted discovery signature as a fresh current observation
- [ ] v1 does not require detector re-observation at export time
- [ ] malformed/non-discovery `source` values do not produce invented discovery evidence

### Findings snapshot

- [ ] Phase 4 `FindingEngine` is reused rather than reimplementing rules
- [ ] findings are generated from the same loaded Registry snapshot used by the export
- [ ] archived systems remain subject to the already accepted FindingEngine behavior
- [ ] exported finding contains stable id
- [ ] exported finding contains rule id
- [ ] exported finding contains category
- [ ] exported finding contains priority
- [ ] exported finding contains subject system id/name
- [ ] exported finding contains fact/declaration/guidance semantic codes
- [ ] exported finding contains Phase 4 evidence signature
- [ ] localized Fact/Declaration/Guidance prose is not required in JSON v1
- [ ] finding-level volatile generation timestamp is excluded from the stable serialized evidence item
- [ ] findings are sorted by stable finding id

### Disclosure readiness snapshot

- [ ] Phase 5 `DisclosureEngine` is reused rather than reimplementing eligibility
- [ ] every Registry system has one disclosure-readiness item
- [ ] item contains `system_id`
- [ ] item contains boolean `eligible`
- [ ] item contains deterministic `reason_codes`
- [ ] reason-code ordering matches accepted Phase 5 engine ordering
- [ ] archived/pending/missing-context/disclosure-disabled behavior remains inherited from Phase 5

### Stable snapshot signature

- [ ] signature algorithm is SHA-256
- [ ] signature is calculated from a canonical allow-list payload built by the plugin
- [ ] canonical payload includes `export_schema_version`
- [ ] canonical payload includes generator metadata
- [ ] canonical payload includes site metadata
- [ ] canonical payload includes Registry schema/state
- [ ] canonical payload includes normalized discovery evidence references
- [ ] canonical payload includes findings without volatile per-export timestamps
- [ ] canonical payload includes disclosure readiness
- [ ] canonical payload excludes top-level `generated_at`
- [ ] canonical payload excludes filename/HTTP headers
- [ ] canonical payload excludes user/session/request data
- [ ] canonical payload excludes localized UI strings
- [ ] repeated export of unchanged technical state at a different generation time returns the same `snapshot_signature`
- [ ] meaningful exported Registry state change changes `snapshot_signature`
- [ ] ordering differences alone cannot alter `snapshot_signature`
- [ ] documentation does not call this a digital signature, legal signature, trusted timestamp, non-repudiation proof or certification

### Privacy / forbidden data

Exporter is allow-list based. The following must be absent from the generated document unless a future contract explicitly changes the schema:

- [ ] WordPress salts / wp-config secrets absent
- [ ] database credentials absent
- [ ] provider/API credentials absent
- [ ] OAuth/access/refresh tokens absent
- [ ] session/auth cookies absent
- [ ] nonces absent
- [ ] request headers absent
- [ ] administrator/user identity absent
- [ ] prompts absent
- [ ] conversations absent
- [ ] customer content absent
- [ ] private/debug logs absent
- [ ] raw database dumps absent
- [ ] arbitrary third-party plugin options absent
- [ ] browser storage absent

### Legal/product boundary

- [ ] UI states export is technical evidence, not legal certification
- [ ] JSON does not claim legal compliance/non-compliance
- [ ] JSON does not claim regulatory approval
- [ ] JSON does not claim legal completeness
- [ ] generated timestamp does not claim external timestamp authority
- [ ] snapshot signature does not claim external cryptographic attestation
- [ ] no automatic legal classification is introduced

### Empty-state behavior

- [ ] empty Registry export succeeds
- [ ] Registry systems array is empty
- [ ] findings array is empty
- [ ] disclosure readiness array is empty
- [ ] valid `generated_at` remains present
- [ ] valid deterministic `snapshot_signature` remains present

### Multisite isolation

- [ ] export is scoped to current blog/site
- [ ] current blog id is recorded
- [ ] another blog's Registry records never appear
- [ ] network-wide aggregation is not implemented in v1
- [ ] switching blogs changes the loaded repository context server-side
- [ ] no browser-supplied blog id can override the authoritative current site context

### Administrator UX / EN-ES / accessibility

- [ ] page has concise EN/ES explanation of purpose and legal boundary
- [ ] page has concise EN/ES confidentiality warning
- [ ] page states local/user-initiated/no-upload behavior
- [ ] primary action is understandable in EN/ES
- [ ] responsive layout passes at 390 px
- [ ] page-level horizontal overflow <= 1 px
- [ ] page remains usable at 200% text zoom
- [ ] serious/critical axe violations = 0
- [ ] export action is keyboard reachable
- [ ] page does not rely on color alone

### Real WordPress runtime authority

- [ ] acceptance creates or reuses unique runtime Registry evidence without assuming globally empty Registry
- [ ] administrator opens Tools → AI Evidence Export
- [ ] administrator submits the real protected export action
- [ ] browser receives an attachment JSON response
- [ ] JSON parses successfully
- [ ] exported Registry contains the unique runtime system
- [ ] exported findings match current FindingEngine behavior when applicable
- [ ] exported disclosure readiness matches current DisclosureEngine behavior
- [ ] internal `interaction_context` appears in the privileged export when configured
- [ ] forbidden secret/user/request fields are absent
- [ ] repeated export without state change has identical `snapshot_signature`
- [ ] a real Registry change causes a different `snapshot_signature`
- [ ] Editor cannot generate an export
- [ ] test remains retry-safe

### Regression/release gates

- [ ] PHPUnit/WPCS/PHPCompatibility green
- [ ] PHP 7.4 / 8.1 / 8.3 / 8.5 syntax green
- [ ] EN/ES runtime-string coverage = 100%
- [ ] compiled Spanish `.mo` exists in exact production package
- [ ] WordPress Plugin Check green on `build/ai-transparency/`
- [ ] Phase 2 Registry migration/CRUD/permissions remain green
- [ ] Phase 3 deterministic discovery remains green
- [ ] Phase 4 readiness findings remain green
- [ ] Phase 5 disclosure runtime remains green
- [ ] Multisite isolation remains green
- [ ] actionable failure diagnostics cover any material failure
- [ ] engineering failure memory updated for any material regression
- [ ] implementation PR merged to `main`
- [ ] post-merge `main` verification green
- [ ] documentation synchronized
- [ ] blockers = 0

## Explicitly deferred from Phase 6 v1

These items do not block Phase 6 unless implementation expands scope to include them:

```text
CSV
PDF / printable report
DOCX
ZIP evidence bundle
JSON import
export history/audit table
scheduled exports
email delivery
Kairoseth cloud upload
external timestamp authority
digital signing / non-repudiation
network-wide Multisite aggregation
fresh third-party plugin re-observation at export time
custom system/field selection
customer-defined templates
legal compliance scoring/certification
```

## Exit

Phase 6 may be declared closed only when one real administrator-generated JSON evidence export passes the complete current-site Registry → Findings → Disclosure readiness → canonical snapshot → deterministic signature → protected download workflow above, the exact merged `main` commit is green, and blockers are zero.
