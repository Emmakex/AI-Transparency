# Phase 6 — Evidence Export

[English](#english) · [Español](#español)

Status: **active design — implementation not started**  
Last reviewed / Última revisión: **9 September 2026 / 9 de septiembre de 2026**

---

## English

### Goal

Phase 6 creates a **dated, reviewable, administrator-generated local evidence snapshot** of the technical state already maintained or deterministically derived by Kairoseth AI Transparency.

The first increment is a JSON-only export. It is intended for internal review, audit preparation, technical handoff and evidence preservation. It is **not** a legal compliance certificate, legal opinion, regulatory filing or guarantee of completeness.

### First supported workflow

```text
WordPress administrator
→ Tools → AI Evidence Export
→ explicit Generate JSON export action
→ manage_options + nonce validation
→ current site-local registry loaded server-side
→ deterministic findings + disclosure readiness derived server-side
→ bounded JSON snapshot built
→ snapshot signature calculated
→ download returned directly to the browser
```

The plugin does not upload, email, persist or remotely transmit the generated export.

### Scope boundary

The first accepted export covers the **current WordPress site only**.

In Multisite:

```text
current blog/site only
≠ network-wide aggregation
```

The export must use the same site-local `WordPressOptionsRegistryRepository` boundary already accepted in Phase 2. A network-wide export is explicitly deferred.

### Export format

Phase 6 v1 supports **JSON only**.

Expected response contract:

```text
Content-Type: application/json; charset=UTF-8
Content-Disposition: attachment; filename="kairoseth-ai-transparency-evidence-YYYYMMDDTHHMMSSZ.json"
Cache-Control: no-store, no-cache, must-revalidate, max-age=0
```

No CSV, PDF, DOCX, ZIP or human-readable formatted report is included in the first increment.

### Export schema version

The JSON envelope uses an explicit schema version independent from the Registry schema:

```json
{
  "export_schema_version": 1,
  "generated_at": "2026-09-09T19:45:00Z",
  "snapshot_signature": "<sha256>",
  "generator": {},
  "site": {},
  "registry": {},
  "findings": [],
  "disclosure_readiness": []
}
```

`export_schema_version` is authoritative for the export document shape. It must not silently change when the Registry schema changes.

### Generator metadata

The bounded generator section is:

```json
{
  "plugin_slug": "ai-transparency",
  "plugin_name": "Kairoseth AI Transparency",
  "plugin_version": "0.1.0"
}
```

The implementation must read the real plugin version constant at generation time rather than hard-code a duplicate runtime version.

### Site metadata

The first export includes only enough site identity to make the evidence reviewable:

```json
{
  "home_url": "https://example.test/",
  "is_multisite": false,
  "blog_id": 1
}
```

No administrator username, user id, email address, IP address, authentication state, request headers or cookie data is included.

### Registry snapshot

The export includes the current Registry schema version and all current site-local records, including archived records, because the purpose is to preserve the reviewable registry state rather than only the active UI subset.

The accepted system shape is based on the current stable `AiSystem::to_array()` contract:

```json
{
  "id": "system-id",
  "name": "System name",
  "type": "assistant",
  "source": "manual",
  "source_origin": "manual",
  "status": "active",
  "review_status": "reviewed",
  "interaction_context": "Customer support assistant on the help flow.",
  "interaction_disclosure_required": true,
  "created_at": "...",
  "updated_at": "...",
  "reviewed_at": "..."
}
```

Records must be sorted by stable system id before serialization so repository iteration order cannot alter the snapshot identity.

### Confidential administrative field boundary

`interaction_context` is intentionally included because it is an administrator-authored part of the technical evidence record and is material to Phases 4 and 5.

Therefore the JSON export is an **administrative evidence artifact that may contain confidential operational context**. The UI must say this clearly before download.

This does not change the Phase 5 public disclosure boundary: `interaction_context` remains forbidden from public disclosure markup.

### Discovery evidence references

For a registry record whose persisted source encodes the accepted discovery source contract:

```text
detector:<detector_id>:<source_signature>
```

Phase 6 may emit a normalized evidence reference:

```json
{
  "system_id": "discovered-ai-engine",
  "detector_id": "ai-engine-wordpress-plugin-v1",
  "source_signature": "<sha256>"
}
```

The export must not claim that this historical source signature is a fresh observation of the currently installed plugin.

The first increment does **not** need to re-run discovery during export. Current detector re-observation can be designed separately later if it has a distinct semantic field.

### Readiness findings snapshot

The export regenerates Phase 4 findings from the same loaded Registry snapshot using the existing deterministic `FindingEngine`.

The export shape includes semantic technical evidence rather than localized presentation text:

```json
{
  "id": "...",
  "rule_id": "registry_review_pending_v1",
  "category": "registry_review",
  "priority": "review",
  "subject_system_id": "...",
  "subject_system_name": "...",
  "fact_code": "review_pending",
  "declaration_code": "",
  "guidance_code": "complete_system_review",
  "evidence_signature": "<sha256>"
}
```

The finding-level runtime `generated_at` value is not required in the serialized evidence item because the top-level export already records the generation timestamp and because Phase 6 needs stable snapshot identity across repeated exports of unchanged state.

Findings must be sorted deterministically by finding id.

### Disclosure readiness snapshot

For every Registry record, Phase 6 derives the current Phase 5 disclosure eligibility using the existing `DisclosureEngine`.

Accepted shape:

```json
{
  "system_id": "...",
  "eligible": true,
  "reason_codes": []
}
```

or:

```json
{
  "system_id": "...",
  "eligible": false,
  "reason_codes": [
    "pending_review",
    "missing_interaction_context"
  ]
}
```

Reason-code ordering must remain deterministic and must reuse the accepted Phase 5 engine rather than duplicate eligibility rules in the exporter.

### Stable snapshot signature

The export has one top-level SHA-256 `snapshot_signature`.

The signature proves identity of the bounded technical snapshot generated by the plugin; it does **not** prove legal validity, authorship, external timestamping, non-repudiation or cryptographic signing by Kairoseth.

Canonical rule:

```text
snapshot_signature = sha256(canonical JSON of stable snapshot payload)
```

The canonical signed payload includes:

```text
export_schema_version
generator metadata
site metadata
registry schema/version + deterministically ordered systems
discovery evidence references
findings without volatile generation timestamps
disclosure readiness
```

The canonical signed payload excludes:

```text
generated_at
filename
HTTP headers
current user/request/session data
localized UI strings
```

Therefore:

```text
same technical state + same plugin/export contract
→ same snapshot_signature

different generation time only
→ same snapshot_signature

meaningful exported technical state change
→ different snapshot_signature
```

Canonical JSON generation must use stable key construction/order under plugin control and must not depend on arbitrary WordPress option ordering.

### Timestamp semantics

`generated_at` is the UTC ISO-8601 time when the administrator explicitly generated the file.

It is informational export metadata and is not a trusted external timestamp authority.

The export must not claim that this timestamp proves when the underlying AI system was deployed or legally reviewed.

### Privacy and secret exclusion

The exporter must use an allow-list schema. It must **never** serialize arbitrary WordPress options, request data or plugin configuration trees.

Forbidden automatically exported data includes:

```text
WordPress salts / wp-config secrets
database credentials
API keys / provider credentials
OAuth tokens
session/auth cookies
nonces
request headers
administrator/user identities
prompts
conversations
customer content
private logs / debug logs
raw database dumps
arbitrary third-party plugin options
browser storage
```

The absence of such data is a blocking acceptance requirement.

### No persistence / no remote dependency

Phase 6 v1 is ephemeral:

```text
build snapshot in memory
→ stream/download JSON
→ request ends
```

The plugin must not automatically:

- write the export into the WordPress Media Library;
- write export history into options or a custom table;
- email the file;
- send it to Kairoseth;
- send it to an AI provider;
- create a cloud account;
- create telemetry about export usage.

Future export-history or cloud workflows require their own accepted contract.

### Administrator UX

A new read-only **Tools → AI Evidence Export** page is planned.

Access:

```text
manage_options
```

The page should explain:

- this is technical evidence, not legal certification;
- the file may contain administrator-authored confidential interaction context;
- generation is local and user-initiated;
- no file is uploaded or stored by the plugin;
- JSON is the only v1 format.

The page exposes one primary action:

```text
Generate JSON evidence export
```

Generation must be a POST/admin action protected by capability + nonce. A plain page GET must not trigger a file generation/download side effect.

### Server authority

The browser does not provide the registry payload, findings, eligibility state, signature or timestamps to be trusted.

```text
browser action
→ capability + nonce
→ server loads repository
→ server derives evidence
→ server builds canonical snapshot
→ server signs snapshot identity
→ server emits download
```

The first increment exports the complete current site-local Registry. It does not accept browser-supplied lists of system ids or evidence fields.

### Encoding and output safety

The JSON must be valid UTF-8 and parseable by a standards-compliant JSON parser.

The output must use WordPress/PHP JSON encoding with failure handling. A serialization failure must not emit a partially valid evidence document.

If generation fails, the administrator receives a bounded error and the repository actionable-diagnostics policy applies. Internal stack traces or secrets must not be sent in the download response.

### Empty state

An empty Registry is not an error.

A valid evidence export may contain:

```json
{
  "registry": {
    "schema_version": 1,
    "systems": []
  },
  "findings": [],
  "disclosure_readiness": []
}
```

The snapshot remains signed and reviewable.

### Compatibility boundary

The first acceptance target is:

```text
current supported WordPress runtime
PHP 7.4 / 8.1 / 8.3 / 8.5 syntax compatibility
single site
site-local Multisite blog context
UTF-8 JSON download
current Registry/Discovery/Readiness/Disclosure contracts
```

No claim is made for importing this JSON back into WordPress. Export and import are separate capabilities.

### Planned implementation architecture

```text
src/Export/class-evidencesnapshot.php
src/Export/class-evidencesnapshotbuilder.php
src/Export/class-jsonexporter.php
src/Admin/class-evidenceexportpage.php
```

Responsibilities:

```text
EvidenceSnapshot
  immutable bounded export-domain model

EvidenceSnapshotBuilder
  registry → systems + discovery refs + findings + disclosure readiness
  deterministic ordering
  canonical stable payload
  snapshot signature

JsonExporter
  JSON serialization / filename / response-safe bytes
  no WordPress authorization decisions

EvidenceExportPage
  Tools UI
  manage_options + nonce
  admin action/download response
```

Exact class names may be simplified during implementation, but authority and deterministic-signature boundaries are blocking.

### Validation strategy

Implementation must add unit tests for:

- deterministic system ordering;
- deterministic finding ordering;
- disclosure readiness reuse;
- discovered source-signature normalization;
- archived Registry records retained in export;
- empty Registry export;
- stable signature across different generation timestamps;
- signature change after meaningful Registry state change;
- forbidden fields absent;
- valid JSON serialization;
- JSON encoding failure handling where practical.

Real WordPress acceptance must prove:

```text
admin can open Tools → AI Evidence Export
→ admin explicitly generates export
→ download is JSON and parseable
→ schema version = 1
→ generated_at is present
→ snapshot_signature is 64-char lowercase SHA-256
→ Registry data matches current site-local state
→ Phase 4 finding signatures are present when findings exist
→ Phase 5 readiness is present
→ no forbidden secret/user/request fields exist
→ repeated export without technical state change has same snapshot_signature
→ generation timestamp differs or is allowed to differ
→ editor cannot access/generate export
→ Multisite export contains only current blog Registry state
```

Customer-facing admin UI must ship EN/ES together and pass responsive/accessibility acceptance.

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
external timestamp authority / digital signature
network-wide Multisite aggregation
current third-party plugin re-observation at export time
custom field selection
customer-defined templates
legal compliance scoring/certification
```

### Phase 6 exit

Phase 6 closes only when:

```text
JSON schema v1 accepted
+ bounded allow-list snapshot implemented
+ deterministic snapshot_signature implemented
+ Tools → AI Evidence Export accepted
+ manage_options + nonce enforced
+ local direct download only
+ same state → same signature across export times
+ secret/user/request data excluded
+ Registry + Findings + Disclosure readiness evidence accepted
+ site-local Multisite isolation green
+ EN/ES 100%
+ production package + Plugin Check green
+ PR merged
+ post-merge main verification green
+ documentation synchronized
+ blockers = 0
```

---

## Español

### Objetivo

La Fase 6 crea un **snapshot local de evidencia técnica, fechado, revisable y generado explícitamente por un administrador** a partir del estado que Kairoseth AI Transparency ya mantiene o deriva de forma determinista.

El primer incremento será exclusivamente JSON. Está pensado para revisión interna, preparación de auditorías, traspaso técnico y conservación de evidencias. **No** constituye un certificado legal de cumplimiento, una opinión jurídica, una presentación regulatoria ni una garantía de exhaustividad.

### Primer flujo soportado

```text
administrador de WordPress
→ Herramientas → AI Evidence Export
→ acción explícita Generate JSON export
→ validación manage_options + nonce
→ carga server-side del Registry local del sitio
→ derivación server-side de findings + disclosure readiness
→ construcción del snapshot JSON acotado
→ cálculo de snapshot_signature
→ descarga directa al navegador
```

El plugin no sube, envía por email, persiste ni transmite remotamente el export generado.

### Límite de alcance

El primer export cubre únicamente el **sitio WordPress actual**.

En Multisite:

```text
solo blog/sitio actual
≠ agregación de toda la red
```

Debe conservarse la misma frontera site-local de `WordPressOptionsRegistryRepository` aceptada en Fase 2.

### Formato

Fase 6 v1 soporta **solo JSON** con descarga UTF-8 y sin caché. CSV, PDF, DOCX, ZIP e informes formateados quedan fuera del primer incremento.

### Schema de export

El documento tendrá su propio:

```text
export_schema_version = 1
```

independiente del schema del Registry.

El envelope incluirá como mínimo:

```text
export_schema_version
generated_at
snapshot_signature
generator
site
registry
findings
disclosure_readiness
```

### Snapshot del Registry

El export incluye todos los registros site-local actuales, también archivados, ordenados por `id` estable. Se reutiliza la forma estable actual de `AiSystem::to_array()`.

`interaction_context` se incluye deliberadamente porque forma parte de la evidencia administrativa usada por Fases 4 y 5. Por eso el fichero debe tratarse como **artefacto administrativo potencialmente confidencial**.

Esto no modifica la frontera pública de Fase 5: `interaction_context` sigue prohibido en el disclosure frontend.

### Evidencia de Discovery

Para registros cuyo `source` persiste el contrato:

```text
detector:<detector_id>:<source_signature>
```

se puede exportar una referencia estructurada a esa evidencia histórica.

Fase 6 v1 no debe presentar esa firma histórica como si fuera una nueva observación del plugin actualmente instalado, ni necesita volver a ejecutar Discovery durante el export.

### Findings

Los findings de Fase 4 se regeneran desde el mismo snapshot del Registry mediante `FindingEngine` y se exportan como códigos semánticos y `evidence_signature`, no como texto localizado.

Se ordenan por `id` y no necesitan su `generated_at` individual porque el documento ya tiene un `generated_at` global y la identidad estable no debe cambiar solo por el reloj.

### Disclosure readiness

Para cada sistema se reutiliza `DisclosureEngine` y se exporta:

```text
system_id
eligible
reason_codes
```

Nunca se duplican las reglas de elegibilidad dentro del exporter.

### Firma estable del snapshot

`snapshot_signature` será SHA-256 del JSON canónico estable.

Incluye el estado técnico relevante y excluye datos volátiles como `generated_at`, nombre de fichero, headers HTTP, usuario/sesión y textos localizados.

Regla:

```text
mismo estado técnico + mismo contrato
→ misma snapshot_signature

solo cambia la hora de generación
→ misma snapshot_signature

cambia evidencia técnica exportada
→ cambia snapshot_signature
```

Esta firma identifica el snapshot técnico. No equivale a firma digital, sellado de tiempo externo, no repudio ni certificación legal.

### Privacidad y secretos

La serialización debe ser por allow-list. Está prohibido volcar opciones arbitrarias de WordPress.

Nunca se exportan automáticamente:

```text
salts / secretos de wp-config
credenciales de base de datos
API keys / credenciales de proveedores
tokens OAuth
cookies de sesión/autenticación
nonces
headers de request
identidades de administradores/usuarios
prompts
conversaciones
contenido de clientes
logs privados/debug
dumps de base de datos
opciones arbitrarias de plugins de terceros
browser storage
```

### Sin persistencia ni cloud

El flujo v1 será efímero:

```text
construir en memoria
→ descargar JSON
→ finalizar request
```

No se guarda en Media Library, options, tabla propia ni cloud; no se manda por correo ni a Kairoseth; no crea telemetría de uso.

### UX administrativa

Se añadirá **Herramientas → AI Evidence Export**, protegido por:

```text
manage_options
```

La pantalla debe explicar que:

- es evidencia técnica, no certificación legal;
- puede contener contexto operacional confidencial escrito por el administrador;
- la generación es local e iniciada por el usuario;
- el plugin no sube ni conserva el fichero;
- JSON es el único formato v1.

La descarga se inicia mediante POST protegido con capability + nonce. Un GET de la pantalla nunca debe producir efectos laterales.

### Autoridad server-side

El navegador no aporta como autoridad el Registry, findings, eligibility, firma ni timestamps.

El servidor carga y deriva todo el snapshot. En v1 tampoco se aceptan listas de sistemas seleccionadas por el navegador: se exporta el Registry completo del sitio actual.

### Estado vacío

Un Registry vacío produce un export válido, firmado y revisable con arrays vacíos. No es un error.

### Validación

Los tests deben demostrar orden determinista, firmas estables, cambio de firma ante cambio real de estado, ausencia de secretos, JSON válido, inclusión de archivados, findings y disclosure readiness, y soporte del Registry vacío.

La aceptación real de WordPress debe descargar un fichero como administrador, parsearlo, verificar schema/firma/estado, repetir la descarga con la misma firma, denegar Editor y comprobar aislamiento site-local en Multisite.

La UI visible al cliente debe salir EN/ES conjuntamente y pasar responsive/accesibilidad.

### Diferido

CSV, PDF, DOCX, ZIP, importación, historial de exports, programación, email, cloud, timestamp/firma digital externa, agregación Multisite de red, reobservación de terceros, selección custom de campos, templates y scoring/certificación legal quedan fuera de Fase 6 v1.

### Salida de Fase 6

Fase 6 solo puede cerrarse con JSON schema v1 aceptado, export allow-list implementado, firma determinista validada, descarga administrativa segura, aislamiento Multisite, EN/ES, paquete de producción y Plugin Check verdes, merge, verificación post-merge, documentación sincronizada y blockers = 0.
