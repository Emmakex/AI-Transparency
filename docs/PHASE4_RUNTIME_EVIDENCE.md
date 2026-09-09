# Phase 4 — Runtime Evidence

Status: **accepted — merged and post-merge verified**  
Last reviewed: 9 September 2026

This file is the evidence ledger for the first deterministic Readiness Findings increment.

## Runtime authority

```text
WordPress: 7.1
PHP: 8.3
production package: build/ai-transparency/
AI Engine fixture: WordPress.org 3.7.7
browser: Chromium / Playwright
```

## Accepted implementation

```text
Pull request: #9 — feat: add deterministic readiness findings
Accepted PR head: 926a154930042e53af0b082795be155389cc6916
Pre-merge CI: #75 / 34381590429
Merge commit: 836abfeca4c199930b74ba32547f9037f7fcb4de
Post-merge CI: #76 / 34382057838
Blockers: 0
```

## Pre-merge evidence — CI #75

All required jobs passed:

```text
PHP quality                                      green
EN/ES 100% coverage                             green
PHP 7.4 syntax                                  green
PHP 8.1 syntax                                  green
PHP 8.3 syntax                                  green
PHP 8.5 syntax                                  green
WordPress Plugin Check                          green
WordPress runtime acceptance                    green
```

The runtime acceptance proved:

```text
production ai-transparency package activates
AI Engine 3.7.7 installs from WordPress.org and activates
legacy registry migration remains green
administrator registry CRUD and permissions remain green
AI Engine deterministic discovery remains green
explicit Discovery → Registry acceptance remains green
AI Engine registry subject is pending review with empty interaction context
Tools → AI Readiness renders deterministic findings
exactly 2 readiness findings are asserted for the AI Engine subject
  1. registry_review_pending_v1
  2. interaction_context_missing_v1
Fact / Administrator declaration / Guidance remain separate
64-character SHA-256 evidence signatures are rendered
Editor cannot access readiness administration
390 px responsive acceptance is green
axe serious/critical accessibility acceptance is green
Multisite registry isolation remains green
```

## Post-merge evidence — CI #76

The exact `main` merge commit `836abfeca4c199930b74ba32547f9037f7fcb4de` repeated the complete required validation set and passed.

The post-merge runtime again proved:

```text
AI Engine 3.7.7 fixture install/activation       green
Discovery → Registry → Readiness browser path   green
registry migration/CRUD/permission regressions  green
responsive/accessibility acceptance             green
Multisite isolation                             green
Plugin Check on exact production package        green
EN/ES 100%                                      green
```

## Regression discovered during acceptance

CI #73 (`34380922915`) exposed stateful E2E assumptions rather than a product defect:

```text
Playwright retry reused persisted registry state
+ migration smoke left a valid legacy registry subject
+ global findings count therefore included unrelated findings
```

The acceptance was corrected to be retry-safe and subject-scoped. The failure record is maintained at:

- [`engineering-failures/2026-09-09-playwright-retry-residual-state.md`](engineering-failures/2026-09-09-playwright-retry-residual-state.md)

Final verification of that prevention is CI #75 and post-merge CI #76.

## Evidence boundary

This evidence proves the documented deterministic readiness contract only. It does not prove legal compliance, legal non-compliance, certification, a particular AI Engine workflow, which provider/model is used, or that a legal disclosure duty exists.

The configured disclosure finding remains dependent on an explicit administrator declaration and is covered deterministically by the domain/unit suite. The runtime path intentionally does not invent that declaration for the discovered AI Engine fixture.
