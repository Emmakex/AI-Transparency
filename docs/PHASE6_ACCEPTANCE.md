# Phase 6 — Evidence Export Acceptance

Status: **CLOSED — accepted, merged and verified on `main`**  
Last reviewed: 9 September 2026

## Accepted scope

Phase 6 v1 provides one privileged, user-initiated JSON evidence export for the **current site-local WordPress Registry**.

It does not create legal certification, cloud synchronization, export history, scheduled exports, import capability, PDF/report output or network-wide Multisite aggregation.

## Accepted evidence

```text
Contract PR: #14
Implementation PR: #15
Accepted implementation head: 2b9ebe93820e98d9ce6e0abb4deb235fdeeda57c
PR-head CI: #91 / 34402108452 — SUCCESS — 8/8 jobs green
Implementation merge: bd07261751471fe7866e62049e3a66b7bd767afe
Post-merge main CI: #92 / 34402685906 — SUCCESS — 8/8 jobs green
Blockers: 0
```

## Blocking gate result

All Phase 6 v1 blocking gates are accepted.

### Export authority — PASS

- **Tools → AI Evidence Export** exists.
- Page/action require `manage_options`.
- Generation is an explicit POST/admin action protected by nonce.
- GET page load has no download side effect.
- Browser/request data never supplies Registry state, findings, readiness, signature or authoritative site identity.
- Server loads the complete current site-local Registry.
- Editor/non-administrator access and generation are denied.

### Local-only / persistence boundary — PASS

- Snapshot is built for the current request and downloaded directly.
- No Media Library persistence.
- No export bytes/history option.
- No custom export-history table.
- No email delivery.
- No Kairoseth/cloud/provider upload.
- No export telemetry/cookie/cloud-account requirement.

### JSON response contract — PASS

- JSON is the only Phase 6 v1 format.
- Response uses `application/json; charset=UTF-8`.
- Attachment filename begins `kairoseth-ai-transparency-evidence-`, contains UTC generation time and ends `.json`.
- No-store/no-cache response policy is applied.
- Output is valid UTF-8 and parseable JSON.
- Serialization failure handling does not emit a partial evidence document or expose stack traces/secrets.

### Export envelope — PASS

Accepted top-level contract:

```text
export_schema_version = 1
generated_at
snapshot_signature
generator
site
registry
discovery_evidence
findings
disclosure_readiness
```

`export_schema_version` is independent from Registry schema version.

### Generator metadata — PASS

- Plugin slug: `ai-transparency`.
- Product name: `Kairoseth AI Transparency`.
- Plugin version comes from the real runtime version constant.
- No contradictory exporter-specific runtime version is maintained.

### Site identity boundary — PASS

Included:

```text
home_url
is_multisite
blog_id
```

Excluded:

```text
administrator/user id or email
client IP
auth/session cookies
request headers
```

WordPress server state (`home_url()`, `is_multisite()`, `get_current_blog_id()`) is authoritative.

### Registry snapshot — PASS

- Current Registry schema version included.
- All current site-local records included.
- Archived records included.
- Empty Registry produces a valid signed export.
- Systems use the bounded stable Registry schema/`AiSystem` evidence fields.
- Systems are deterministically ordered by stable id.
- Arbitrary option/repository iteration order cannot alter snapshot identity.
- Administrator-authored `interaction_context` is included only as privileged administrative evidence.
- Export UI explicitly warns that the file may contain confidential operational context.
- Phase 5 public disclosure continues to exclude `interaction_context`.

### Discovery evidence references — PASS

- Structurally valid discovered Registry sources are normalized to system id + detector id + source signature.
- Historical persisted Discovery signatures are not described as fresh observation.
- v1 does not re-run Discovery during export.
- Malformed/non-discovery source values do not create invented Discovery evidence.

### Findings snapshot — PASS

- Existing Phase 4 `FindingEngine` is reused.
- Findings are generated from the same loaded Registry snapshot.
- Accepted Phase 4 archived-system behavior is inherited.
- Exported finding includes stable id, rule/category/priority, subject id/name, Fact/Declaration/Guidance semantic codes and Phase 4 evidence signature.
- Volatile finding generation time is excluded from the stable evidence item.
- Findings are deterministically ordered by stable id.

### Disclosure readiness snapshot — PASS

- Existing Phase 5 `DisclosureEngine` is reused.
- Every Registry system receives one readiness item.
- Each item contains `system_id`, boolean `eligible` and deterministic `reason_codes`.
- Archived/pending/missing-context/disclosure-disabled behavior and reason ordering remain inherited from Phase 5.

### Stable snapshot signature — PASS

- Algorithm: SHA-256.
- Lowercase 64-character hex output.
- Signature is calculated from a canonical plugin-built allow-list payload.
- Stable payload includes export schema version, generator, site, Registry, normalized Discovery references, findings without volatile timestamps and disclosure readiness.
- Stable payload excludes top-level `generated_at`, filename/HTTP headers, user/session/request state and localized UI strings.
- Repeated unchanged-state export at a different generation time keeps the same signature.
- Meaningful Registry mutation changes the signature.
- Ordering differences alone cannot change the signature.
- Documentation does not represent the hash as a legal/digital signature, trusted timestamp, non-repudiation proof or certification.

### Privacy / forbidden data — PASS

The allow-list excludes automatically:

```text
WordPress salts / wp-config secrets
database credentials
provider/API credentials
OAuth/access/refresh tokens
session/auth cookies
nonces
request headers
administrator/user identity
prompts
conversations
customer content
private/debug logs
raw database dumps
arbitrary third-party plugin options
browser storage
```

Real runtime acceptance explicitly checked forbidden key absence.

### Legal/product boundary — PASS

- UI states that export is technical evidence, not legal certification.
- JSON makes no legal compliance/non-compliance, regulatory approval or legal-completeness claim.
- `generated_at` does not claim external timestamp authority.
- `snapshot_signature` does not claim external cryptographic attestation.
- No automatic legal classification was introduced.

### Empty state — PASS

Empty Registry produces:

```text
registry.systems = []
findings = []
disclosure_readiness = []
valid generated_at
valid deterministic snapshot_signature
```

### Multisite isolation — PASS

- Export is current-blog/site scoped.
- Current `blog_id` is recorded.
- Another blog's Registry records never appear.
- Network-wide aggregation is absent.
- Switching blog context changes repository context server-side.
- Browser-supplied blog ids cannot override WordPress authority.

### Administrator UX / EN-ES / accessibility — PASS

- Purpose, legal boundary, confidentiality and local/no-upload behavior are explained in EN/ES.
- Primary download action is localized.
- 390 px acceptance green.
- 200% text acceptance green.
- page-level overflow acceptance green.
- axe serious/critical violations = 0.
- action remains keyboard reachable.

### Real WordPress runtime authority — PASS

Acceptance exercised a real WordPress environment and proved:

```text
administrator opens Evidence Export
→ submits protected action
→ browser receives attachment JSON
→ payload parses
→ runtime Registry system present
→ current findings/readiness present
→ interaction_context present only in privileged export when configured
→ forbidden sensitive/user/request fields absent
→ repeated unchanged export keeps signature
→ real Registry mutation changes signature
→ Editor denied
→ test retry-safe
```

### Regression / release gates — PASS

CI #91 and post-merge CI #92 verified:

- WordPress Coding Standards;
- PHPUnit;
- PHPCompatibility 7.4+;
- PHP 7.4 / 8.1 / 8.3 / 8.5 syntax;
- EN/ES runtime-string coverage = 100%;
- compiled Spanish `.mo` in exact production package;
- official WordPress Plugin Check on `build/ai-transparency/`;
- Phase 2 Registry migration/CRUD/permissions;
- Phase 3 deterministic Discovery;
- Phase 4 Readiness;
- Phase 5 Disclosure runtime;
- real site-local Multisite isolation;
- actionable diagnostics on material CI failures.

## Resolved implementation-CI incidents

### CI #88

```text
job: PHP Quality
step: Run coding standards, tests and bilingual coverage
command: composer verify
exit: 2
signature: 2a713bc20438829d660dc95e2e37ae7ee4f63636d066a158e0ddf13c82bcb26e
cause: WPCS short ternary / @throws formatting / assignment alignment
```

### CI #90

```text
job: PHP Quality
step: Run coding standards, tests and bilingual coverage
command: composer verify
exit: 1
file: src/Export/class-evidencesnapshotbuilder.php:60
signature: 6b48a8a870fbf9e3431c98705136950325a21c62cd988d4b69f3048dda33ed09
cause: Squiz @throws interpretation for direct InvalidArgumentException
```

Both incidents were non-behavioral WPCS failures, fixed without changing the Evidence Export contract and validated by CI #91 and #92.

Durable record: [`engineering-failures/2026-09-09-phase6-wpcs-docblock-formatting.md`](engineering-failures/2026-09-09-phase6-wpcs-docblock-formatting.md).

## Explicitly deferred from Phase 6 v1

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

**PASSED. Phase 6 is closed.**

The real administrator-generated JSON workflow passed current-site Registry → Findings → Disclosure readiness → canonical snapshot → deterministic signature → protected local download, the exact implementation merge `bd07261751471fe7866e62049e3a66b7bd767afe` passed post-merge `main` CI #92, and blockers are zero.
