# Phase 5 — Disclosure Tooling Acceptance

Status: **active contract — implementation not started**  
Last reviewed: 9 September 2026

## Scope

This contract applies to the first supported Phase 5 workflow: a manually placed, server-rendered AI disclosure shortcode in normal WordPress post/page content.

The intended shortcode contract is:

```text
[kairoseth_ai_disclosure system="SYSTEM_ID"]
```

Phase 5 does not infer legal obligations and does not automatically choose placement.

## Blocking gates

### Eligibility semantics

- [ ] exact system id is resolved from the current site-local registry server-side
- [ ] active + reviewed + disclosure configured + non-empty interaction context is eligible
- [ ] pending-review system is ineligible
- [ ] archived system is ineligible
- [ ] system with empty interaction context is ineligible
- [ ] system with `interaction_disclosure_required = false` is ineligible
- [ ] missing/unknown system id is ineligible
- [ ] source origin alone never grants eligibility
- [ ] same registry state produces the same eligibility result
- [ ] no Phase 5 schema migration is introduced unless implementation proves it necessary and updates this contract first

### Disclosure model/rendering

- [ ] reusable immutable disclosure representation exists
- [ ] public rendering uses only bounded disclosure data
- [ ] administrator-reviewed system name is escaped before output
- [ ] internal `interaction_context` is not rendered publicly
- [ ] system id is not rendered in public disclosure markup
- [ ] source/source-origin/review timestamps are not rendered publicly
- [ ] provider/model configuration, prompts, conversations and credentials are never read for rendering
- [ ] unknown/ineligible shortcode returns no disclosure markup
- [ ] renderer has no JavaScript dependency

### Public copy / legal boundary

- [ ] EN title is `AI transparency notice`
- [ ] ES title is `Aviso de transparencia de IA`
- [ ] EN/ES body copy ships together
- [ ] visible copy reports the configured interaction rather than inferring a legal duty
- [ ] copy does not claim compliance, non-compliance, certification or legal sufficiency
- [ ] copy contains no fabricated provider/model/workflow facts

### Placement boundary

- [ ] first accepted placement is normal singular WordPress post/page content
- [ ] WordPress Shortcode block works because it stores the same shortcode contract
- [ ] no automatic injection based solely on plugin presence/discovery
- [ ] no arbitrary page-content AI scanning
- [ ] no modification of third-party chatbot DOM
- [ ] no claim of widget/template/AJAX/feed compatibility without separate evidence

### Administrator UX

- [ ] **Tools → AI Disclosure** exists
- [ ] Disclosure admin screen requires `manage_options`
- [ ] screen is read-only with respect to registry state
- [ ] eligible system displays a usable exact shortcode
- [ ] ineligible systems display bounded deterministic reason(s)
- [ ] not-ready state links back to the registry edit workflow
- [ ] Editor/non-administrator cannot access Disclosure administration

### Security/privacy

- [ ] shortcode attribute is treated as selector only, never authority
- [ ] exact registry lookup and eligibility are server-authoritative
- [ ] output is escaped for context
- [ ] no automatic telemetry, cookies or remote request introduced
- [ ] no Kairoseth cloud dependency introduced
- [ ] disclosure configuration mutations remain protected by the existing registry capability + nonce flow
- [ ] public notice exposes only localized copy and reviewed system name

### EN/ES and package

- [ ] EN/ES runtime-string coverage = 100%
- [ ] Spanish PO includes all new disclosure strings
- [ ] compiled Spanish `.mo` exists in production package
- [ ] production package includes any Phase 5 frontend asset required by the accepted renderer

### Frontend UX/accessibility

- [ ] disclosure remains readable at 390 px viewport
- [ ] page-level horizontal overflow <= 1 px
- [ ] long system name wraps without clipping
- [ ] disclosure remains usable at 200% text zoom
- [ ] notice does not rely on color alone
- [ ] serious/critical axe violations = 0 for the disclosure component/page
- [ ] semantic notice title/label is programmatically available
- [ ] no motion/keyboard interaction is required to understand the notice

### Runtime authority

- [ ] acceptance creates or reuses a uniquely named Phase 5 runtime system
- [ ] runtime system is active
- [ ] runtime system is reviewed
- [ ] runtime system has non-empty interaction context
- [ ] runtime system has disclosure configured = true
- [ ] Tools → AI Disclosure reports the runtime system Ready
- [ ] exact generated shortcode is placed on a real public page
- [ ] anonymous visitor sees the expected disclosure
- [ ] browser output does not expose the internal interaction-context description
- [ ] browser output does not expose the system id
- [ ] disabling `interaction_disclosure_required` removes public disclosure on the same shortcode in uncached runtime
- [ ] acceptance remains retry-safe and does not assume a globally empty registry

### Cache/theme boundary

- [ ] output contains no user-specific state
- [ ] standard uncached WordPress runtime updates output immediately after registry changes
- [ ] accepted runtime theme does not break layout/accessibility
- [ ] documentation does not claim universal third-party cache invalidation
- [ ] documentation does not claim third-party theme/cache compatibility without named evidence

### Regression/release gates

- [ ] PHPUnit/WPCS/PHPCompatibility green
- [ ] PHP 7.4 / 8.1 / 8.3 / 8.5 syntax green
- [ ] WordPress Plugin Check green on `build/ai-transparency/`
- [ ] Phase 2 registry migration/CRUD/permissions remain green
- [ ] Phase 3 deterministic discovery remains green
- [ ] Phase 4 readiness findings remain green
- [ ] Multisite registry isolation remains green
- [ ] engineering failure memory updated for any material regression
- [ ] implementation PR merged to `main`
- [ ] post-merge `main` verification green
- [ ] documentation synchronized
- [ ] blockers = 0

## Explicitly deferred from the first increment

These items do not block Phase 5 v1 unless implementation expands scope to include them:

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

Phase 5 may be declared closed only when one real administrator-configured system passes the complete Registry → Disclosure Admin → shortcode → anonymous frontend workflow above, the exact merged `main` commit is green, and blockers are zero.
