# Phase 6 — Evidence Export

[English](#english) · [Español](#español)

Status: **CLOSED — implemented, merged and verified on `main`**  
Last reviewed / Última revisión: **9 September 2026 / 9 de septiembre de 2026**

---

## English

### Goal achieved

Phase 6 provides a **dated, reviewable, administrator-generated local JSON evidence snapshot** of the technical state already maintained or deterministically derived by Kairoseth AI Transparency.

The export is intended for internal review, audit preparation, technical handoff and evidence preservation. It is **not** a legal compliance certificate, legal opinion, regulatory filing, external timestamp or guarantee of completeness.

### Accepted workflow

```text
WordPress administrator
→ Tools → AI Evidence Export
→ explicit POST action
→ manage_options + nonce
→ current site-local Registry loaded server-side
→ EvidenceSnapshotBuilder
   ├ RegistrySchema::encode()
   ├ persisted Discovery source references
   ├ FindingEngine
   └ DisclosureEngine
→ canonical allow-list stable payload
→ SHA-256 snapshot_signature
→ EvidenceJsonEncoder
→ direct JSON attachment download
```

No evidence payload, trusted timestamp, blog id, finding state, readiness state or signature is accepted from browser input.

### Accepted implementation

```text
src/Export/class-evidencesnapshot.php
src/Export/class-evidencesnapshotbuilder.php
src/Export/class-evidencejsonencoder.php
src/Admin/class-evidenceexportpage.php
```

Responsibilities:

```text
EvidenceSnapshot
  bounded immutable export-domain result

EvidenceSnapshotBuilder
  Registry → systems + Discovery refs + findings + Disclosure readiness
  deterministic ordering
  canonical stable payload
  snapshot_signature

EvidenceJsonEncoder
  readable JSON serialization after signing
  no authorization or site-context authority

EvidenceExportPage
  Tools UI
  manage_options + nonce
  authoritative WordPress site context
  protected download response
```

### JSON v1 contract

```text
export_schema_version = 1
```

Accepted top-level sections:

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

### Generator and site authority

Generator metadata identifies:

```text
plugin_slug = ai-transparency
plugin_name = Kairoseth AI Transparency
plugin_version = real runtime plugin constant
```

Site identity is resolved only from WordPress server state:

```text
home_url()
is_multisite()
get_current_blog_id()
```

No user id/email, IP, session/auth cookie or request-header data is included.

### Registry snapshot

The export includes the complete current site-local Registry, including archived records, using the accepted stable Registry schema. Systems are deterministically ordered by stable id.

An empty Registry is valid and still produces a signed evidence document.

`interaction_context` is intentionally included because it is administrator-authored technical evidence used by Readiness and Disclosure. The download page therefore warns that the privileged JSON file may contain confidential operational context.

This does not weaken the public Phase 5 disclosure boundary: `interaction_context` remains absent from public disclosure markup.

### Discovery references

When a discovered Registry record has a structurally valid persisted source:

```text
detector:<detector_id>:<source_signature>
```

Phase 6 normalizes it to:

```text
system_id
detector_id
source_signature
```

This is explicitly historical persisted evidence, not a claim of fresh observation at export time. Malformed or unrelated `source` values do not create invented Discovery evidence.

### Derived evidence

Phase 6 reuses existing accepted engines:

```text
FindingEngine
→ current deterministic findings
→ stable semantic codes + Phase 4 evidence_signature

DisclosureEngine
→ current eligibility
→ deterministic reason_codes
```

Exporter code does not duplicate Phase 4 or Phase 5 rules.

### Stable snapshot signature

```text
snapshot_signature = sha256(canonical JSON of stable allow-list payload)
```

Included in stable identity:

```text
export_schema_version
generator
site
Registry schema/state
normalized Discovery references
findings without volatile generation timestamps
disclosure readiness
```

Excluded:

```text
generated_at
filename
HTTP headers
current user/session/request state
localized UI strings
```

Therefore:

```text
same technical state + same contract
→ same snapshot_signature

different generation time only
→ same snapshot_signature

meaningful exported technical-state change
→ different snapshot_signature
```

The hash identifies the bounded technical snapshot. It is **not** a legal/digital signature, trusted timestamp, non-repudiation proof or regulatory certification.

### Privacy / secret exclusion

The serializer is allow-list based and excludes automatically:

```text
WordPress salts / wp-config secrets
database credentials
API/provider/OAuth credentials
session/auth cookies
nonces
request headers
administrator/user identities
prompts
conversations
customer content
private/debug logs
raw database dumps
arbitrary third-party options
browser storage
```

Runtime acceptance checks forbidden-key absence in the generated JSON.

### Local-only behavior

```text
build snapshot in memory
→ encode JSON
→ attachment response
→ request ends
```

Phase 6 v1 creates no Media Library file, export-history option/table, email, Kairoseth upload, provider call, telemetry or cloud account.

### Multisite boundary

```text
current authoritative blog/site only
≠ network-wide aggregation
```

The repository follows WordPress blog context server-side. Browser-supplied blog identifiers cannot override it.

### Accepted UX

**Tools → AI Evidence Export**:

- requires `manage_options`;
- explains the technical/legal boundary;
- warns about potentially confidential `interaction_context`;
- states that generation is local, explicit and not uploaded/persisted;
- exposes one localized primary JSON-download action;
- generation uses POST + nonce;
- GET never triggers download side effects.

EN/ES, 390 px, 200% text and axe serious/critical acceptance are green.

### Accepted validation evidence

```text
Contract PR: #14
Implementation PR: #15
Accepted implementation head: 2b9ebe93820e98d9ce6e0abb4deb235fdeeda57c
PR-head CI: #91 / 34402108452 — SUCCESS — 8/8 green
Implementation merge: bd07261751471fe7866e62049e3a66b7bd767afe
Post-merge main CI: #92 / 34402685906 — SUCCESS — 8/8 green
Blockers: 0
```

Validated:

```text
deterministic unit tests
empty Registry
archived record retention
malformed Discovery source rejection
finding timestamp exclusion
real browser JSON attachment
headers/cache policy
unchanged-state signature stability
Registry mutation changes signature
forbidden sensitive-key absence
Editor denial
390 px / 200% / axe
inherited Registry / Discovery / Readiness / Disclosure
real site-local Multisite export isolation
production-package Plugin Check
PHP 7.4 / 8.1 / 8.3 / 8.5 syntax
EN/ES 100%
```

Runtime evidence: [`PHASE6_RUNTIME_EVIDENCE.md`](PHASE6_RUNTIME_EVIDENCE.md).  
Acceptance: [`PHASE6_ACCEPTANCE.md`](PHASE6_ACCEPTANCE.md).

### Explicitly deferred

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
external timestamp authority / digital signing
network-wide Multisite aggregation
fresh third-party plugin re-observation during export
custom field/system selection
customer-defined templates
legal compliance scoring/certification
```

### Exit

**Phase 6 exit passed.** The complete Registry → Findings → Disclosure readiness → canonical snapshot → deterministic signature → protected local JSON download workflow is implemented and verified on the exact merged `main` commit, with blockers = 0.

---

## Español

### Objetivo alcanzado

La Fase 6 proporciona un **snapshot JSON local, fechado, revisable y generado explícitamente por un administrador** a partir del estado técnico que Kairoseth AI Transparency mantiene o deriva de forma determinista.

Está orientado a revisión interna, preparación de auditorías, traspaso técnico y conservación de evidencia. **No** es certificado legal, opinión jurídica, presentación regulatoria, sellado de tiempo externo ni garantía de exhaustividad.

### Flujo aceptado

```text
administrador WordPress
→ Herramientas → AI Evidence Export
→ POST explícito
→ manage_options + nonce
→ Registry local del sitio cargado server-side
→ EvidenceSnapshotBuilder
   ├ RegistrySchema::encode()
   ├ referencias Discovery persistidas
   ├ FindingEngine
   └ DisclosureEngine
→ payload estable canónico por allow-list
→ snapshot_signature SHA-256
→ EvidenceJsonEncoder
→ descarga JSON directa
```

El navegador no aporta Registry, findings, readiness, firma, identidad de sitio ni timestamp que el servidor deba confiar.

### Implementación aceptada

```text
src/Export/class-evidencesnapshot.php
src/Export/class-evidencesnapshotbuilder.php
src/Export/class-evidencejsonencoder.php
src/Admin/class-evidenceexportpage.php
```

La autoridad queda separada: el builder deriva evidencia determinista; el encoder serializa; la página WordPress controla permisos, nonce, sitio actual y respuesta de descarga.

### Contrato JSON v1

```text
export_schema_version = 1
```

Secciones aceptadas:

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

### Registry, Discovery y evidencia derivada

Se exporta el Registry completo del sitio actual, incluidos archivados, con orden determinista. Registry vacío es válido.

`interaction_context` aparece solo en este artefacto administrativo privilegiado y la UI advierte que el fichero puede contener contexto operativo confidencial. El disclosure público de Fase 5 sigue sin exponerlo.

Las referencias Discovery solo se normalizan cuando el `source` persistido cumple la estructura aceptada y se describen como evidencia histórica, no observación fresca.

Se reutilizan `FindingEngine` y `DisclosureEngine`; el exporter no duplica reglas de Readiness o Disclosure.

### Firma estable

`generated_at` queda fuera de la identidad estable. Por tanto:

```text
mismo estado técnico
→ misma snapshot_signature aunque cambie la hora

cambio técnico exportado relevante
→ snapshot_signature distinta
```

La firma SHA-256 identifica el snapshot técnico acotado. **No** es firma legal/digital, timestamp confiable, prueba de no repudio ni certificación regulatoria.

### Privacidad y local-first

La allow-list excluye credenciales, tokens, cookies, nonces, headers, identidad de usuario, prompts, conversaciones, contenido de clientes, logs, dumps de BD y options arbitrarias.

```text
construir en memoria
→ serializar JSON
→ responder attachment
→ termina request
```

No se crea Media Library, historial, email, upload a Kairoseth, llamada a proveedor, telemetría ni cuenta cloud.

En Multisite el servidor exporta únicamente el blog/sitio actual; no existe agregación de red en v1.

### Evidencia aceptada

```text
PR contrato: #14
PR implementación: #15
Head aceptado: 2b9ebe93820e98d9ce6e0abb4deb235fdeeda57c
CI PR: #91 / 34402108452 — 8/8 verde
Merge: bd07261751471fe7866e62049e3a66b7bd767afe
CI post-merge main: #92 / 34402685906 — 8/8 verde
Bloqueadores: 0
```

La aceptación real cubre descarga JSON, headers, firma estable, cambio de firma tras mutación del Registry, privacidad, Editor bloqueado, EN/ES, 390 px, 200%, axe y Multisite site-local.

### Cierre

**La salida de Fase 6 está superada.** El flujo completo está implementado y verificado en el commit exacto fusionado a `main`, con bloqueadores = 0.
