# Phase 6 — Evidence Export Runtime Evidence

Status: **accepted**  
Date: 9 September 2026

## Scope

This document records the runtime evidence used to accept and close **Phase 6 — Evidence Export**.

The target was one real WordPress administrator-generated JSON export proving the complete server-authoritative flow:

```text
Registry
→ persisted Discovery references
→ Findings
→ Disclosure readiness
→ canonical stable snapshot
→ SHA-256 snapshot_signature
→ protected local JSON download
```

## Implementation evidence

```text
Contract PR: #14
Implementation PR: #15
Accepted head: 2b9ebe93820e98d9ce6e0abb4deb235fdeeda57c
Implementation merge: bd07261751471fe7866e62049e3a66b7bd767afe
```

## Accepted PR-head CI

```text
CI: #91
Run id: 34402108452
Head: 2b9ebe93820e98d9ce6e0abb4deb235fdeeda57c
Conclusion: SUCCESS
Jobs: 8/8 green
```

Green jobs:

```text
PHP quality
PHP 7.4 syntax
PHP 8.1 syntax
PHP 8.3 syntax
PHP 8.5 syntax
EN/ES 100% coverage
WordPress Plugin Check
WordPress runtime acceptance
```

## Post-merge `main` verification

```text
CI: #92
Run id: 34402685906
Branch: main
Head: bd07261751471fe7866e62049e3a66b7bd767afe
Conclusion: SUCCESS
Jobs: 8/8 green
```

This confirms the exact merged implementation commit rather than only the PR merge preview.

## Unit / deterministic evidence

`EvidenceSnapshotBuilderTest` covers the Phase 6 deterministic contract, including:

- stable signature across different `generated_at` values when technical state is unchanged;
- signature change after meaningful exported Registry state changes;
- deterministic ordering independent of insertion order;
- empty Registry export;
- archived Registry record retention;
- structurally valid persisted Discovery reference normalization;
- malformed Discovery source rejection;
- finding-level volatile timestamp exclusion from stable snapshot identity;
- reuse of current deterministic Finding/Disclosure semantics.

The accepted PHP Quality job proves WordPress Coding Standards, PHPUnit, PHPCompatibility and bilingual source coverage on the accepted head and on post-merge `main`.

## Real WordPress browser acceptance

The runtime job exercised the production plugin package in a real WordPress environment.

Accepted Evidence Export path:

```text
administrator login
→ Tools → AI Evidence Export
→ explicit protected POST
→ real attachment download
→ JSON parsed
```

Verified runtime properties:

- `Content-Type: application/json; charset=UTF-8`;
- attachment filename follows `kairoseth-ai-transparency-evidence-*.json`;
- no-store/no-cache response behavior;
- `export_schema_version = 1`;
- `generated_at` present;
- lowercase 64-character SHA-256 `snapshot_signature` present;
- real Registry evidence appears in the document;
- privileged `interaction_context` appears when configured;
- current Findings are present when applicable;
- current Disclosure readiness is present;
- forbidden sensitive/user/request keys are absent;
- repeated unchanged-state export keeps the same signature;
- real Registry mutation changes the signature;
- Editor cannot access/generate the export.

## Responsive and accessibility evidence

The real administrator page passed:

```text
390 px viewport
200% text scaling
horizontal overflow acceptance
keyboard-reachable action
axe serious/critical violations = 0
```

The UI contains EN/ES purpose, local/no-upload explanation, legal-boundary copy and confidentiality warning.

## Production package evidence

The exact generated `build/ai-transparency/` package passed official WordPress Plugin Check in both accepted CI #91 and post-merge CI #92.

The build includes the accepted Phase 6 production classes and localized catalogs.

## PHP compatibility evidence

Syntax gates passed independently on:

```text
PHP 7.4
PHP 8.1
PHP 8.3
PHP 8.5
```

Both accepted PR-head and post-merge runs were green.

## EN/ES evidence

The EN/ES gate passed 100% runtime-string/catalog coverage and verified the production build plus compiled Spanish `.mo`.

## Multisite runtime evidence

The runtime job started a real Multisite environment and executed the site-isolation smoke.

Phase 6-specific evidence proved:

```text
blog/site A Registry → export A
blog/site B Registry → export B
```

with:

- correct authoritative `blog_id` per site;
- each export containing only that site's Registry state;
- different site-local state producing site-specific snapshot identity;
- no network-wide aggregation;
- no browser-supplied blog id authority.

The Multisite smoke passed in CI #91 and again in post-merge CI #92.

## Privacy evidence

The generated document follows an explicit allow-list. Runtime acceptance checked absence of forbidden categories such as:

```text
credentials / API keys / OAuth tokens
cookies / nonces / request headers
administrator/user identity
prompts / conversations / customer content
private/debug logs
raw database dumps
arbitrary third-party options
browser storage
```

The plugin creates no export-history persistence, Media Library artifact, email, telemetry, Kairoseth upload, external provider request or cloud account.

## Resolved CI incidents during implementation

### CI #88 / 34401564420

```text
pipeline: CI
job: PHP Quality
step: Run coding standards, tests and bilingual coverage
command: composer verify
exit: 2
files:
  src/Export/class-evidencesnapshotbuilder.php
  src/Admin/class-evidenceexportpage.php
signature: 2a713bc20438829d660dc95e2e37ae7ee4f63636d066a158e0ddf13c82bcb26e
```

Confirmed root cause: repository WPCS rejected short ternary syntax, the initial `@throws` formatting and one assignment-alignment warning.

Fix: explicit ternaries plus WPCS-compatible formatting. No Evidence Export behavior changed.

### CI #90 / 34401789546

```text
pipeline: CI
job: PHP Quality
step: Run coding standards, tests and bilingual coverage
command: composer verify
exit: 1
file: src/Export/class-evidencesnapshotbuilder.php:60
signature: 6b48a8a870fbf9e3431c98705136950325a21c62cd988d4b69f3048dda33ed09
```

Confirmed root cause: the Squiz function-comment rule did not recognize the attempted combined exception declaration as satisfying the directly thrown `InvalidArgumentException` requirement.

Fix: document the direct `InvalidArgumentException` on `build()` and keep the canonical JSON `RuntimeException` contract on the method that directly throws it.

Validation: both incidents are resolved by CI #91 and post-merge CI #92.

Durable failure memory: [`engineering-failures/2026-09-09-phase6-wpcs-docblock-formatting.md`](engineering-failures/2026-09-09-phase6-wpcs-docblock-formatting.md).

## Acceptance conclusion

```text
Implementation merged: yes
Accepted PR-head CI: green
Exact post-merge main CI: green
Production Plugin Check: green
Real browser download: green
Signature determinism: green
Meaningful-change signature mutation: green
Privacy allow-list: green
Editor denial: green
EN/ES: green
390 px / 200% / axe: green
Multisite export isolation: green
Inherited Phase 2–5 regressions: green
Blockers: 0
```

**Phase 6 runtime acceptance is complete.**
