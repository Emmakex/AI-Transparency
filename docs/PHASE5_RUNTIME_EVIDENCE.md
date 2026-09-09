# Phase 5 — Disclosure Tooling Runtime Evidence

Status: **accepted**  
Last reviewed: 9 September 2026

## Authoritative implementation evidence

```text
Contract PR: #11
Contract merge: d95483e74f7b1045f2d497219fb70d7d71165faf
Contract post-merge CI: #80 / 34391946448

Implementation PR: #12
Accepted PR head: 37ac6f8a3adf6ae33c98910fc0b2ff816789a697
Final PR-head CI: #82 / 34394624556
Implementation merge: 2770c7b7982ffbbe07ba58e8cedebd12d0add14a
Post-merge main CI: #83 / 34395173777
Blockers: 0
```

## Accepted real WordPress path

The Phase 5 browser acceptance runs against the generated production package, not a source-only substitute.

```text
build/ai-transparency/
→ activate in real WordPress runtime
→ configure unique Phase 5 AI system
   status = active
   review_status = reviewed
   interaction_context = non-empty
   interaction_disclosure_required = true
→ Tools → AI Disclosure
→ system reports Ready
→ exact shortcode generated
   [kairoseth_ai_disclosure system="SYSTEM_ID"]
→ authenticated administrator publishes a real WordPress page containing shortcode
→ anonymous browser opens public page
→ localized disclosure component is visible
→ reviewed system name is visible
→ private interaction_context is absent
→ disclosure markup does not expose system id
→ frontend stylesheet is loaded
→ viewport 390 px accepted
→ 200% text-size acceptance green
→ serious/critical axe violations = 0
→ administrator disables interaction_disclosure_required
→ Tools → AI Disclosure reports Not ready
→ same public page no longer renders disclosure
```

## Server-authority evidence

The public shortcode is not trusted as configuration authority.

```text
shortcode system selector
→ sanitize and bound
→ current site-local registry lookup
→ DisclosureEngine eligibility evaluation
→ eligible Disclosure model or empty output
→ escaped public markup
```

Eligibility requires all of:

```text
status = active
review_status = reviewed
interaction_disclosure_required = true
trim(interaction_context) != empty
```

The deterministic unit suite verifies that pending, archived, missing-context and disclosure-disabled systems cannot create a disclosure.

## Privacy evidence

Accepted public output is intentionally bounded to:

```text
localized disclosure title/body
administrator-reviewed system name
```

The accepted runtime verifies that the private interaction-context description is not present in the public page output.

The renderer does not require provider/model configuration, credentials, prompts, conversations, telemetry, cookies or a remote Kairoseth service.

## Accessibility and responsive evidence

The real anonymous disclosure component passed:

```text
390 px viewport
200% text-size check
component horizontal overflow <= 1 px
serious axe violations = 0
critical axe violations = 0
semantic localized title / aria-label
no JavaScript interaction requirement
```

## Package evidence

The generated production package includes:

```text
src/Disclosure/
src/Admin/class-disclosurepage.php
assets/frontend.css
complete EN/ES PO/POT assets
compiled Spanish .mo
```

`bin/build-plugin.sh` explicitly fails if `assets/frontend.css` is absent from the production package.

WordPress Plugin Check passed on the exact package in PR-head CI #82 and again in post-merge CI #83.

## Regression evidence

The same accepted runs also kept the inherited contracts green:

```text
Phase 2 registry migration / CRUD / permissions
Phase 3 AI Engine 3.7.7 deterministic discovery
Phase 4 Discovery → Registry → Readiness findings
Editor access denial
Multisite registry isolation
PHP 7.4 / 8.1 / 8.3 / 8.5 syntax
PHP Quality / WPCS / PHPUnit / PHPCompatibility
EN/ES 100% runtime coverage
```

## Resolved CI incident

The first Phase 5 implementation run, CI #81 / `34394493780`, failed only in PHP Quality because four adjacent assignments in `class-disclosurepage.php` were not aligned to WPCS formatting expectations.

Structured diagnosis:

```text
pipeline: CI #81
job: PHP Quality
step: Run coding standards, tests and bilingual coverage
command: composer verify
exit code: 2
file: src/Admin/class-disclosurepage.php
lines: 114–116
message: 0 errors and 3 alignment warnings
signature: 073cfa47b6645c467d89e94ad0e5ebe5441cf298acde8c998c47322bda2b00f8
root cause: confirmed formatting-only WPCS alignment
fix: assignment alignment corrected
validation: CI #82 = 8/8 green; CI #83 = 8/8 green
```

No behavioral defect was identified by this incident.

## Accepted boundary

Phase 5 does **not** provide evidence for:

- automatic placement inside third-party chatbot DOM;
- arbitrary AI-content detection;
- universal third-party cache invalidation;
- universal theme/widget/template compatibility;
- legal compliance or legal sufficiency;
- automatic legal classification.

Those capabilities remain outside the accepted Phase 5 v1 contract until separately designed and evidenced.
