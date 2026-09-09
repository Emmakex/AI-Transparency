# Kairoseth AI Transparency — Implementation Architecture

Status: development baseline  
Last reviewed: 9 September 2026

## Product boundary

Kairoseth AI Transparency is a WordPress plugin for technical AI-transparency readiness. It helps a site owner maintain evidence about AI systems used on the site and implement supported disclosure workflows.

It is not a legal certification engine and must not infer legal obligations from weak evidence.

## Architectural principles

1. **Local-first Free baseline.** No automatic Kairoseth account, telemetry or off-site data transfer is required for core Free workflows.
2. **Evidence before conclusions.** A finding records why it exists and whether the evidence came from deterministic discovery, site configuration or an explicit user declaration.
3. **No probabilistic authorship detector.** v1 does not claim to identify arbitrary AI-written text.
4. **Server-authoritative permissions.** WordPress capabilities govern privileged operations.
5. **WordPress-native security.** Nonces for state changes, validation/sanitization on input and context-aware escaping on output.
6. **Domain separated from WordPress adapters.** Registry/rules/evidence models should remain testable as pure PHP where practical.
7. **EN/ES together.** Customer-facing features ship English and Spanish together.
8. **Custom work stays separate.** Customer-specific integrations live in independent private repositories.

## Initial package structure

```text
AI-Transparency/
├── ai-transparency.php           WordPress bootstrap
├── src/
│   ├── Autoloader.php
│   ├── Plugin.php                lifecycle coordinator
│   ├── Admin/                    WordPress admin adapters
│   ├── Domain/                   pure product/domain models
│   ├── Registry/                 local AI system registry domain
│   ├── Discovery/                deterministic integration detectors (next)
│   ├── Evidence/                 evidence/finding models (next)
│   ├── Disclosure/               disclosure rules/rendering (next)
│   ├── Persistence/              WordPress storage adapters (next)
│   └── Export/                   evidence export adapters (next)
├── tests/
├── docs/
└── .github/workflows/
```

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

The first bootstrap model intentionally contains only the stable minimum. Persistence/versioning fields are added when their storage contract is implemented.

### Discovery

Discovery is deterministic and adapter-based.

Examples:

```text
supported plugin installed/active
known block/widget configuration
known shortcode/configuration
explicit administrator declaration
```

Discovery must not convert a weak heuristic into a legal conclusion.

Every detector returns evidence, confidence/source type and a stable detector signature.

### Evidence / findings

A future finding should distinguish:

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

Disclosure tooling will support explicit, accessible notices for integrations/workflows where the administrator has configured a disclosure requirement.

It will not automatically alter arbitrary site content based solely on an AI guess.

### Persistence

The first persistent implementation should prefer WordPress-native storage and versioned schemas. No custom table is justified until the data volume/query contract requires one.

Likely v1 primitives:

```text
options / site options
post/user metadata only when the feature contract naturally belongs there
```

Multisite behavior must explicitly distinguish site-local and network-level state.

## WordPress compatibility baseline

Development target at repository bootstrap:

- Requires WordPress: 6.6+
- Tested-up-to target: 7.1
- Requires PHP: 7.4+
- Modern supported PHP is recommended for production.

Compatibility claims become release claims only after the corresponding acceptance evidence exists.

## Security boundary

The public plugin must never contain:

- client secrets;
- customer-specific API credentials;
- proprietary customer mappings;
- private customer data fixtures;
- hidden remote execution paths;
- model output capable of granting WordPress roles/capabilities.

If optional external Kairoseth services are introduced later, the integration must have an explicit product contract covering data categories, consent, purpose, retention and failure behavior.

## Custom Requests boundary

The Free plugin will eventually expose a contextual, non-intrusive Custom Request CTA in plugin-owned admin/help surfaces.

The public frontend does not receive promotional Kairoseth credits/links by default.

Custom implementations use a new private repository based on a referenced Free commit/release. The public Free repository remains public.

## CI / release gates

Current bootstrap CI:

```text
WordPress Coding Standards
PHPCompatibility 7.4+
PHPUnit domain tests
PHP syntax matrix
official WordPress Plugin Check
```

Later phases add:

```text
WordPress integration tests
Multisite tests
accessibility tests
packaging/release checks
install/activate/deactivate/uninstall acceptance
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

This repository owns the implementation/release truth for the WordPress Free plugin. If implementation forces a product-contract change, update both repositories in the same workstream before declaring the phase complete.
