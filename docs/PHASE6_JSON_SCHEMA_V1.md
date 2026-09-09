# Phase 6 — Evidence Export JSON Schema v1

Status: **accepted and implemented**  
Last reviewed: 9 September 2026

This document defines the canonical logical shape for the accepted Phase 6 JSON evidence export. It is a product contract, not a formal JSON Schema vocabulary document.

## Root object

```json
{
  "export_schema_version": 1,
  "generated_at": "2026-09-09T19:45:00Z",
  "snapshot_signature": "0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef",
  "generator": {
    "plugin_slug": "ai-transparency",
    "plugin_name": "Kairoseth AI Transparency",
    "plugin_version": "0.1.0"
  },
  "site": {
    "home_url": "https://example.test/",
    "is_multisite": false,
    "blog_id": 1
  },
  "registry": {
    "schema_version": 1,
    "systems": []
  },
  "discovery_evidence": [],
  "findings": [],
  "disclosure_readiness": []
}
```

All top-level fields above are required in schema v1.

## Field semantics

### `export_schema_version`

Integer. Exactly `1` for this contract.

This versions the exported document shape and is independent from the Registry schema version.

### `generated_at`

String. UTC ISO-8601 generation time for the explicit administrator request.

Informational only. It is **excluded** from the stable snapshot signature.

### `snapshot_signature`

String. Exactly 64 lowercase hexadecimal characters.

```text
sha256(canonical stable payload)
```

It is a technical snapshot identity, not a digital signature, legal signature, trusted timestamp or certification.

## `generator`

Required object:

```json
{
  "plugin_slug": "ai-transparency",
  "plugin_name": "Kairoseth AI Transparency",
  "plugin_version": "0.1.0"
}
```

`plugin_version` comes from the plugin runtime constant during generation.

## `site`

Required object:

```json
{
  "home_url": "https://example.test/",
  "is_multisite": false,
  "blog_id": 1
}
```

No user identity, IP address, cookies, request headers or authentication metadata is part of this object.

The accepted implementation resolves this state server-side from WordPress rather than browser input.

## `registry`

Required object:

```json
{
  "schema_version": 1,
  "systems": []
}
```

### Registry system

Every `systems[]` item uses this allow-list:

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

All keys are required in each system item, even when one of the timestamp/context strings is empty under an already accepted Registry state.

Systems are sorted ascending by `id` using byte-stable string comparison under plugin control.

Archived systems remain included.

## `discovery_evidence`

Required array. It may be empty.

Only a Registry source matching the accepted persisted source form may produce an item:

```text
detector:<detector_id>:<64-char lowercase sha256>
```

Item shape:

```json
{
  "system_id": "discovered-ai-engine",
  "detector_id": "ai-engine-wordpress-plugin-v1",
  "source_signature": "0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef"
}
```

Items are sorted ascending by `system_id`, then `detector_id` if a future contract ever permits more than one item per system.

Malformed source strings do not generate invented evidence items.

Schema v1 does not represent this as a fresh current detector observation.

## `findings`

Required array. It may be empty.

Item shape:

```json
{
  "id": "finding-id",
  "rule_id": "registry_review_pending_v1",
  "category": "registry_review",
  "priority": "review",
  "subject_system_id": "system-id",
  "subject_system_name": "System name",
  "fact_code": "review_pending",
  "declaration_code": "",
  "guidance_code": "complete_system_review",
  "evidence_signature": "0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef"
}
```

The Phase 4 `Finding::generated_at()` value is intentionally omitted from schema v1. The export has one root `generated_at`, while stable finding evidence identity remains represented by `evidence_signature`.

Findings are sorted ascending by `id`.

## `disclosure_readiness`

Required array. One item exists for every exported Registry system.

Ready example:

```json
{
  "system_id": "system-id",
  "eligible": true,
  "reason_codes": []
}
```

Not-ready example:

```json
{
  "system_id": "system-id",
  "eligible": false,
  "reason_codes": [
    "pending_review",
    "missing_interaction_context"
  ]
}
```

Items are sorted ascending by `system_id`.

`reason_codes` retain the deterministic order returned by the accepted Phase 5 `DisclosureEngine`.

## Canonical stable payload

The `snapshot_signature` is calculated from a canonical object with this logical shape:

```json
{
  "export_schema_version": 1,
  "generator": {},
  "site": {},
  "registry": {},
  "discovery_evidence": [],
  "findings": [],
  "disclosure_readiness": []
}
```

The root `generated_at` and `snapshot_signature` fields are not part of the signed payload.

Canonical construction rules:

1. Construct only allow-listed keys in the documented order.
2. Sort `registry.systems` by system id.
3. Sort `discovery_evidence` by system id.
4. Sort `findings` by finding id.
5. Sort `disclosure_readiness` by system id.
6. Preserve the accepted deterministic order of `reason_codes` returned by `DisclosureEngine`.
7. Encode booleans as JSON booleans, integers as JSON integers, and all documented text/timestamps as JSON strings.
8. Use UTF-8 JSON encoding without pretty-print whitespace as the canonical bytes used for hashing.
9. The presentation/download copy may be pretty-printed only after the canonical signature bytes have been produced and without changing signature semantics.
10. JSON encoding failure is fatal to generation; do not hash or emit partial bytes.

The accepted implementation follows this separation: `EvidenceSnapshotBuilder` calculates the stable identity first and `EvidenceJsonEncoder` produces the readable download representation afterwards.

## Determinism examples

### Same state, different generation time

```text
Export A generated_at = 2026-09-09T19:45:00Z
Export B generated_at = 2026-09-09T20:45:00Z
all stable evidence equal

snapshot_signature(A) = snapshot_signature(B)
```

### Registry change

```text
review_status pending → reviewed

snapshot_signature(before) != snapshot_signature(after)
```

### Order-only input variation

```text
repository iteration returns [system-b, system-a]
vs
repository iteration returns [system-a, system-b]

canonical systems = [system-a, system-b]
snapshot signatures equal
```

## Confidentiality boundary

This export is intentionally administrative. `interaction_context` may contain operationally confidential text written by the site administrator.

The following categories are **not members of schema v1** and therefore must not appear automatically anywhere in the document:

```text
WordPress salts / wp-config secrets
database credentials
provider/API credentials
OAuth/access/refresh tokens
auth/session cookies
nonces
request headers
administrator/user identities
prompts
conversations
customer content
private/debug logs
raw database dumps
arbitrary third-party plugin options
browser storage
```

An exporter implementation that serializes arbitrary option arrays or request state violates this schema even if the resulting JSON is syntactically valid.

## Empty Registry example

```json
{
  "export_schema_version": 1,
  "generated_at": "2026-09-09T19:45:00Z",
  "snapshot_signature": "<sha256>",
  "generator": {
    "plugin_slug": "ai-transparency",
    "plugin_name": "Kairoseth AI Transparency",
    "plugin_version": "0.1.0"
  },
  "site": {
    "home_url": "https://example.test/",
    "is_multisite": false,
    "blog_id": 1
  },
  "registry": {
    "schema_version": 1,
    "systems": []
  },
  "discovery_evidence": [],
  "findings": [],
  "disclosure_readiness": []
}
```

An empty Registry is a valid signed evidence snapshot.

## Acceptance evidence

The schema v1 contract is implemented and validated by:

```text
Implementation PR: #15
Accepted head: 2b9ebe93820e98d9ce6e0abb4deb235fdeeda57c
PR-head CI: #91 / 34402108452 — 8/8 green
Merge: bd07261751471fe7866e62049e3a66b7bd767afe
Post-merge main CI: #92 / 34402685906 — 8/8 green
```

See [`PHASE6_RUNTIME_EVIDENCE.md`](PHASE6_RUNTIME_EVIDENCE.md).
