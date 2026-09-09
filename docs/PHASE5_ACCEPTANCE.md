# Phase 5 — Disclosure Tooling Acceptance

Status: **closed — accepted, merged and verified on `main`**  
Last reviewed: 9 September 2026

## Closure evidence

```text
Contract PR: #11
Contract merge: d95483e74f7b1045f2d497219fb70d7d71165faf
Contract post-merge CI: #80 / 34391946448

Implementation PR: #12
Accepted implementation head: 37ac6f8a3adf6ae33c98910fc0b2ff816789a697
Final PR-head CI: #82 / 34394624556
Implementation merge commit: 2770c7b7982ffbbe07ba58e8cedebd12d0add14a
Post-merge main CI: #83 / 34395173777
Blockers: 0
```

## Scope

This acceptance applies to the first supported Phase 5 workflow: a manually placed, server-rendered AI disclosure shortcode in normal singular WordPress post/page content.

```text
[kairoseth_ai_disclosure system="SYSTEM_ID"]
```

The shortcode selector never grants authority. Current site-local registry state is resolved and evaluated server-side on every render.

## Blocking gates

### Eligibility semantics

- [x] exact system id is resolved from the current site-local registry server-side
- [x] active + reviewed + disclosure configured + non-empty interaction context is eligible
- [x] pending-review system is ineligible
- [x] archived system is ineligible
- [x] system with empty interaction context is ineligible
- [x] system with `interaction_disclosure_required = false` is ineligible
- [x] missing/unknown system id is ineligible
- [x] source origin alone never grants eligibility
- [x] same registry state produces the same eligibility result
- [x] Registry schema v1 remains unchanged; no Phase 5 migration was required

### Disclosure model/rendering

- [x] immutable bounded `Disclosure` representation exists
- [x] pure deterministic `DisclosureEngine` exists
- [x] public rendering uses only bounded disclosure data
- [x] administrator-reviewed system name is escaped before output
- [x] internal `interaction_context` is not rendered publicly
- [x] system id is not rendered in public disclosure markup
- [x] source/source-origin/review timestamps are not rendered publicly
- [x] provider/model configuration, prompts, conversations and credentials are never read for rendering
- [x] unknown/ineligible shortcode returns no disclosure markup
- [x] renderer has no JavaScript dependency

### Public copy / legal boundary

- [x] EN title is `AI transparency notice`
- [x] ES title is `Aviso de transparencia de IA`
- [x] EN/ES body copy ships together
- [x] visible copy reports the configured interaction rather than inferring a legal duty
- [x] copy does not claim compliance, non-compliance, certification or legal sufficiency
- [x] copy contains no fabricated provider/model/workflow facts

### Placement boundary

- [x] first accepted placement is normal singular WordPress post/page content
- [x] WordPress Shortcode block is compatible because it stores the same shortcode contract
- [x] no automatic injection based solely on plugin presence/discovery
- [x] no arbitrary page-content AI scanning
- [x] no modification of third-party chatbot DOM
- [x] no widget/template/AJAX/feed compatibility claim without separate evidence

### Administrator UX

- [x] **Tools → AI Disclosure** exists
- [x] Disclosure admin screen requires `manage_options`
- [x] screen is read-only with respect to registry state
- [x] eligible system displays a usable exact shortcode
- [x] ineligible systems display bounded deterministic reason(s)
- [x] not-ready state links back to the registry edit workflow
- [x] Editor/non-administrator cannot access Disclosure administration

### Security/privacy

- [x] shortcode attribute is treated as selector only, never authority
- [x] exact registry lookup and eligibility are server-authoritative
- [x] output is escaped for context
- [x] no automatic telemetry, cookies or remote request introduced
- [x] no Kairoseth cloud dependency introduced
- [x] disclosure configuration mutations remain protected by the existing registry capability + nonce flow
- [x] public notice exposes only localized copy and reviewed system name

### EN/ES and package

- [x] EN/ES runtime-string coverage = 100%
- [x] Spanish PO includes all disclosure strings
- [x] compiled Spanish `.mo` exists in production package
- [x] production package includes `assets/frontend.css`
- [x] build fails if the required disclosure frontend stylesheet is missing

### Frontend UX/accessibility

- [x] disclosure remains readable at 390 px viewport
- [x] horizontal overflow acceptance <= 1 px
- [x] long content can wrap without fixed-height clipping
- [x] disclosure remains usable at 200% text size
- [x] notice does not rely on color alone
- [x] serious/critical axe violations = 0 for the disclosure component
- [x] localized semantic notice title/label is programmatically available
- [x] no motion/keyboard interaction is required to understand the notice

### Runtime authority

- [x] acceptance creates a uniquely named Phase 5 runtime system
- [x] runtime system is active
- [x] runtime system is reviewed
- [x] runtime system has non-empty interaction context
- [x] runtime system has disclosure configured = true
- [x] Tools → AI Disclosure reports the runtime system Ready
- [x] exact generated shortcode is placed on a real public page
- [x] anonymous visitor sees the expected disclosure
- [x] browser output does not expose the internal interaction-context description
- [x] browser output does not expose the system id in disclosure markup
- [x] required frontend stylesheet is present on the supported page
- [x] disabling `interaction_disclosure_required` removes public disclosure on the same shortcode in uncached runtime
- [x] acceptance is retry-safe and does not assume a globally empty registry

### Cache/theme boundary

- [x] output contains no user-specific state
- [x] standard uncached WordPress runtime updates output immediately after registry changes
- [x] accepted runtime theme does not break layout/accessibility
- [x] documentation does not claim universal third-party cache invalidation
- [x] documentation does not claim third-party theme/cache compatibility without named evidence

### Regression/release gates

- [x] PHPUnit/WPCS/PHPCompatibility green
- [x] PHP 7.4 / 8.1 / 8.3 / 8.5 syntax green
- [x] WordPress Plugin Check green on `build/ai-transparency/`
- [x] Phase 2 registry migration/CRUD/permissions remain green
- [x] Phase 3 deterministic discovery remains green
- [x] Phase 4 readiness findings remain green
- [x] Multisite registry isolation remains green
- [x] no material regression required a new engineering-failure-memory entry
- [x] implementation PR #12 merged to `main`
- [x] post-merge `main` verification #83 green
- [x] closure documentation synchronized in the Phase 5 closure workstream
- [x] blockers = 0

## CI incident resolved before acceptance

```text
CI #81 / 34394493780
Job: PHP Quality
Command: composer verify
Exit: 2
File: src/Admin/class-disclosurepage.php
Lines: 114–116
Finding: 0 errors, 3 WPCS alignment warnings
Signature: 073cfa47b6645c467d89e94ad0e5ebe5441cf298acde8c998c47322bda2b00f8
Root cause: assignment alignment only; no behavioral defect
Fix: align $reasons / $is_ready / $edit_url / $shortcode assignments
Validation: CI #82 PHP Quality green and complete suite 8/8 green
```

The incident was formatting-only and did not represent a material runtime regression, so no durable engineering-failure-memory entry was required.

## Explicitly deferred from the first increment

These items are not part of the accepted Phase 5 v1 boundary:

```text
native Gutenberg custom block
widget/template automatic placement
AI Engine DOM-specific injection
integration-specific JavaScript adapters
third-party cache purge integrations
universal theme compatibility claims
custom per-system arbitrary HTML disclosure copy
network-wide Multisite disclosure registry
automatic legal classification
```

## Exit

**Complete.** Phase 5 is accepted at implementation merge `2770c7b7982ffbbe07ba58e8cedebd12d0add14a`, with PR-head CI #82 and post-merge `main` CI #83 green and blockers at zero.
