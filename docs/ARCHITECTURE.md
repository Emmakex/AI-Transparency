# Kairoseth AI Transparency — Implementation Architecture

Status: development baseline  
Last reviewed: 9 September 2026

## Product boundary

Kairoseth AI Transparency is a WordPress plugin for technical AI-transparency readiness. It helps a site owner maintain evidence about AI systems used on the site and implement supported disclosure workflows.

It is not a legal certification engine and must not infer legal obligations from weak evidence.

## Architectural principles

1. **Local-first Free baseline.** No automatic Kairoseth account, telemetry or off-site data transfer is required for core Free workflows.
2. **Evidence before conclusions.** A finding records why it exists and whether evidence came from deterministic discovery, site configuration or an explicit user declaration.
3. **No probabilistic authorship detector.** v1 does not claim to identify arbitrary AI-written text.
4. **Server-authoritative permissions.** WordPress capabilities govern privileged operations; state changes use nonces where applicable.
5. **WordPress-native security.** Validate/sanitize input and escape output for its context.
6. **Domain separated from WordPress adapters.** Registry/rules/evidence models remain testable as pure PHP where practical.
7. **EN/ES 100% together.** Customer-facing functionality ships English and Spanish in the same PR/release and CI blocks incomplete Spanish coverage.
8. **Responsive/accessibility acceptance.** Customer-facing admin/frontend surfaces must meet the relevant UX/accessibility contract.
9. **Custom work stays separate.** Customer-specific integrations live in independent private repositories.
10. **Production package is release authority.** WordPress.org/release gates validate the generated package, not the engineering repository root.
11. **Failures become reusable knowledge.** Material failures produce structured diagnostics and durable records when the lesson is reusable.

Canonical engineering policies:

- `docs/ENGINEERING_RULES.md`
- `docs/BILINGUAL_EN_ES_POLICY.md`
- `docs/CI_VALIDATION_POLICY.md`
- `docs/IMPLEMENTATION_COMPLETION_POLICY.md`
- `docs/CI_FAILURE_DIAGNOSTICS_POLICY.md`
- `docs/engineering-failures/README.md`

## Source and release structure

```text
AI-Transparency/
├── ai-transparency.php
├── src/
│   ├── class-autoloader.php
│   ├── class-plugin.php
│   ├── Admin/
│   │   └── class-adminpage.php
│   ├── Domain/
│   │   └── class-aisystem.php
│   ├── Registry/
│   │   └── class-aisystemsregistry.php
│   ├── Discovery/                 next
│   ├── Evidence/                  next
│   ├── Disclosure/                next
│   ├── Persistence/               next
│   └── Export/                    next
├── languages/
│   ├── kairoseth-ai-transparency.pot
│   └── kairoseth-ai-transparency-es_ES.po
├── bin/
│   ├── build-plugin.sh
│   ├── check-i18n.php
│   ├── compile-po.php
│   └── run-with-diagnostics.sh
├── tests/
├── docs/
└── .github/workflows/
```

Release flow:

```text
source repository
→ bilingual coverage check
→ deterministic build
→ compile Spanish gettext catalog
→ build/kairoseth-ai-transparency/
→ official WordPress Plugin Check
→ release ZIP / WordPress.org candidate
```

Development-only files are intentionally absent from the generated package.

## Internationalization architecture

Source language is English. Runtime strings use the text domain:

```text
kairoseth-ai-transparency
```

Spanish source translations live in:

```text
languages/kairoseth-ai-transparency-es_ES.po
```

`bin/check-i18n.php` compares runtime gettext strings with the Spanish catalog and fails on missing/empty translations or the wrong text domain.

`bin/build-plugin.sh` compiles the PO catalog into:

```text
build/kairoseth-ai-transparency/languages/kairoseth-ai-transparency-es_ES.mo
```

The runtime loads bundled translations from `/languages` at WordPress `init`.

The business contract is stricter than normal fallback behavior: **English and Spanish must both be complete for every customer-facing release.**

## Core domains

### AI Systems Registry

The registry is the canonical local inventory of AI systems known to the plugin.

A system eventually records at least:

```text
stable local id
name
system type
source / evidence origin
status
interaction context
configured disclosure state
review timestamp
```

The bootstrap model intentionally contains only the stable minimum. Persistence/versioning fields are added when their storage contract is implemented.

### Discovery

Discovery is deterministic and adapter-based.

Examples:

```text
supported plugin installed/active
known block/widget configuration
known shortcode/configuration
explicit administrator declaration
```

Discovery must not convert a weak heuristic into a legal conclusion. Every detector returns evidence, source type and a stable detector signature.

### Evidence / findings

A future finding distinguishes:

```text
FACT
what was observed

DECLARATION
what the administrator explicitly stated

GUIDANCE
what Kairoseth recommends reviewing/implementing
```

This distinction is required for honest product claims and future exports.

### Disclosure

Disclosure tooling supports explicit, accessible notices for integrations/workflows where the administrator has configured a disclosure requirement.

It will not automatically alter arbitrary site content based solely on an AI guess.

### Persistence

The first persistent implementation should prefer WordPress-native storage and versioned schemas. No custom table is justified until the data volume/query contract requires one.

Likely v1 primitives:

```text
options / site options
post/user metadata only when the feature contract naturally belongs there
```

Multisite behavior must explicitly distinguish site-local and network-level state and authorization.

## WordPress compatibility baseline

Development target:

- Requires WordPress: 6.6+
- Tested-up-to target: 7.1
- Requires PHP: 7.4+
- Modern supported PHP is recommended for production.

Compatibility claims become release claims only after corresponding acceptance evidence exists.

## Security and privacy boundary

The public plugin must never contain:

- client secrets;
- customer-specific API credentials;
- proprietary customer mappings;
- private customer data fixtures;
- hidden remote execution paths;
- model output capable of granting WordPress roles/capabilities.

If optional external Kairoseth services are introduced later, the integration requires an explicit product contract covering data categories, consent/action, purpose, retention, authorization and failure behavior.

## Custom Requests boundary

The Free plugin will eventually expose a contextual, non-intrusive Custom Request CTA in plugin-owned admin/help surfaces.

The CTA and its context are EN/ES customer-facing surfaces and therefore subject to the 100% bilingual gate.

The public frontend does not receive promotional Kairoseth credits/links by default.

Custom implementations use a new private repository based on a referenced Free commit/release. The public Free repository remains public.

## CI / release gates

Current baseline:

```text
PHP quality
├── WordPress Coding Standards
├── PHPCompatibility 7.4+
├── PHPUnit
└── EN/ES coverage checker

EN/ES 100% coverage
├── source/catalog comparison
├── deterministic production build
└── compiled Spanish MO exists

PHP syntax
├── 7.4
├── 8.1
├── 8.3
└── 8.5

WordPress Plugin Check
└── exact build/kairoseth-ai-transparency package
```

Repository-controlled failing commands use `bin/run-with-diagnostics.sh` and upload bounded `.ci-diagnostics/` evidence. Plugin Check keeps its own structured findings/results artifact.

Later phases add:

```text
WordPress integration tests
Multisite tests
responsive/browser acceptance
accessibility tests
install/activate/deactivate/uninstall acceptance
release ZIP checks
WordPress.org readme/version consistency
```

## Canonical cross-repository documents

The broader product/commercial truth remains in `Emmakex/kairoseth-platform/docs/`:

- `AI_TRANSPARENCY_PRODUCT_V1.md`
- `AI_TRANSPARENCY_NAMING_SEO.md`
- `AI_TRANSPARENCY_ARCHITECTURE.md`
- `AI_TRANSPARENCY_ROADMAP.md`
- `AI_TRANSPARENCY_ACCEPTANCE.md`
- `EXTENSIONS_REPOSITORY_AND_CUSTOM_POLICY.md`

This repository owns implementation/release truth for the WordPress Free plugin. If implementation forces a product-contract change, both repositories are updated in the same workstream before the phase is declared complete.
