# Kairoseth AI Transparency

**EU AI Act Readiness for WordPress**

Kairoseth AI Transparency is the first WordPress-first product in the Kairoseth Extensions portfolio. It helps WordPress site owners build a reviewable technical transparency workflow around AI systems used on their websites.

> **Current status:** pre-release development. No stable WordPress.org release is claimed yet.

## Product identity

| Field | Value |
|---|---|
| Commercial name | **Kairoseth AI Transparency** |
| SEO descriptor | **EU AI Act Readiness for WordPress** |
| Technical slug | `ai-transparency` |
| WordPress text domain | `kairoseth-ai-transparency` |
| WordPress.org target slug | `kairoseth-ai-transparency` |
| Commercial model | useful Free + Custom |
| Public source license | MIT |
| Custom work | separate private repositories |

## What the Free product is being built to do

```text
WordPress site
    ↓
AI Systems Registry
    ↓
deterministic discovery of supported integrations
    ↓
evidence-backed readiness findings
    ↓
AI interaction / content disclosure workflows
    ↓
local evidence export
    ↓
optional user-initiated Custom Request
```

The Free v1 direction includes:

- a local AI Systems Registry;
- deterministic discovery for explicitly supported AI integrations;
- evidence-backed readiness findings;
- accessible AI-interaction disclosure tooling;
- explicit content/media declaration workflows where applicable and configured;
- dated local evidence/export;
- English and Spanish customer-facing UX;
- no mandatory Kairoseth account or automatic telemetry for the Free baseline.

## Important boundary

This software provides **technical readiness, workflow and evidence tooling**. It does **not** certify or guarantee legal compliance with the EU AI Act or any other law.

Kairoseth AI Transparency also intentionally does **not** attempt generic probabilistic detection of whether arbitrary text was written by AI.

## Current technical foundation

The repository bootstrap contains:

- WordPress plugin loader;
- production namespace autoloader;
- truthful initial admin screen under **Tools > AI Transparency**;
- pure PHP `AiSystem` domain model;
- deterministic `AiSystemsRegistry` foundation;
- PHPUnit tests;
- WordPress Coding Standards;
- PHP 7.4+ compatibility checks;
- PHP syntax matrix;
- official WordPress Plugin Check CI;
- security, contribution and trademark policies;
- WordPress.org-style `readme.txt`.

## Development requirements

- WordPress: **6.6+** development baseline
- Current tested-up-to target: **7.1**
- PHP: **7.4+**
- Composer 2 for development tooling

WordPress 7.1 is the current stable WordPress line at repository bootstrap. Compatibility becomes a release claim only after the corresponding acceptance evidence is complete.

## Development

```bash
composer install
composer verify
```

The public CI additionally runs syntax checks across multiple PHP versions and WordPress Plugin Check.

## Architecture

See:

- [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md)
- [`docs/ROADMAP.md`](docs/ROADMAP.md)
- [`SECURITY.md`](SECURITY.md)
- [`CONTRIBUTING.md`](CONTRIBUTING.md)
- [`TRADEMARKS.md`](TRADEMARKS.md)

The broader canonical product/commercial contracts are maintained in the Kairoseth Platform repository under `docs/AI_TRANSPARENCY_*.md` and the Kairoseth Extensions policies.

## Commercial model

The public repository remains the useful Free edition.

```text
Free public plugin
→ user finds a real need
→ contextual Custom Request
→ separate private customer repository
→ bespoke integration / remediation / workflow
```

A Custom engagement does not make this Free repository private and does not revoke rights already granted under MIT.

## WordPress.org direction

The project is being built against the current WordPress Plugin Directory expectations: GPL-compatible source, human-readable code, no trialware, no non-consensual tracking, no dashboard hijacking and no promotional links injected into the public site without permission.

MIT is GPL-compatible, but every bundled dependency and asset will be reviewed before directory submission.

## Security

Do not publish exploitable vulnerability details or real customer secrets/data in public issues. See [`SECURITY.md`](SECURITY.md).

## License and brand

Source code is licensed under the [MIT License](LICENSE).

Kairoseth brand and trademark rights are separate from the source-code license. See [`TRADEMARKS.md`](TRADEMARKS.md).

Copyright © 2026 Kairoseth / Eduardo Yauri.
