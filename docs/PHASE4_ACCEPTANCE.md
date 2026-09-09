# Phase 4 — Readiness Findings Acceptance

Status: active acceptance  
Last reviewed: 9 September 2026

## Scope

This contract applies to the first deterministic Readiness Findings increment.

## Blocking gates

### Finding semantics

- [ ] every finding exposes a stable finding id
- [ ] every finding exposes a stable rule id
- [ ] `FACT`, `DECLARATION` and `GUIDANCE` remain separate concepts
- [ ] generated timestamp does not affect finding id or evidence signature
- [ ] same evidence produces same SHA-256 signature
- [ ] archived records do not generate findings
- [ ] correcting registry state removes obsolete findings on next evaluation

### First deterministic rules

- [ ] active + pending review produces `registry_review_pending_v1`
- [ ] active + empty interaction context produces `interaction_context_missing_v1`
- [ ] active + administrator-configured disclosure produces `configured_disclosure_review_v1`
- [ ] disclosure rule reports administrator declaration rather than inferring a legal duty

### Security/privacy

- [ ] readiness page requires `manage_options`
- [ ] browser parameters do not provide finding authority/evidence
- [ ] readiness remains read-only in this increment
- [ ] no automatic telemetry or external Kairoseth request introduced
- [ ] no provider credentials/API keys/prompts/conversations/customer content are read
- [ ] no automatic legal classification/certification is emitted

### EN/ES and UX

- [ ] EN/ES 100% runtime-string coverage green
- [ ] compiled Spanish `.mo` exists in production package
- [ ] Tools → AI Readiness responsive at 390 px
- [ ] page-level horizontal overflow <= 1 px
- [ ] serious/critical axe violations = 0
- [ ] Fact / Administrator declaration / Guidance headings are visible
- [ ] evidence signatures remain readable/wrapped on narrow viewports

### Runtime authority

- [ ] CI installs exact WordPress.org AI Engine 3.7.7 fixture
- [ ] Discovery explicitly adds AI Engine to registry
- [ ] resulting discovered system is pending review with empty interaction context
- [ ] readiness page generates exactly two initial AI Engine findings
- [ ] each runtime finding displays a 64-character SHA-256 signature
- [ ] Editor cannot access readiness administration surface

### Regression/release gates

- [ ] PHPUnit/WPCS/PHPCompatibility green
- [ ] PHP 7.4 / 8.1 / 8.3 / 8.5 syntax green
- [ ] WordPress Plugin Check green on `build/ai-transparency/`
- [ ] existing registry migration/CRUD/permissions acceptance remains green
- [ ] deterministic AI Engine discovery acceptance remains green
- [ ] Multisite registry isolation remains green
- [ ] PR merged to `main`
- [ ] post-merge `main` verification green
- [ ] engineering failure memory synchronized if material regression occurs
- [ ] documentation synchronized
- [ ] blockers = 0

## Exit

Phase 4 may be declared closed only when the exact merged `main` commit passes the complete required validation set above.
