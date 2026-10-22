# Changelog

All notable changes to Kairoseth AI Transparency will be documented here.

The project follows semantic versioning for public releases.

## [Unreleased]

No changes yet.

## [1.3.0] - 2026-10-22

### Added

- Operational dashboard priority state that routes administrators to the most relevant next action from current local Registry and Readiness state.
- Compact action rows for pending review, technical review findings and disclosure-ready systems.
- Compact six-module workflow map covering Registry, Discovery, Readiness, Disclosure, Evidence Export and Support.
- Dashboard version indicator, improved Registry empty state and localized WordPress-formatted update dates.

### Changed

- Reworked the plugin overview from a static module-card landing page into an operational center focused on current state, attention and next actions.
- Strengthened visual semantics for active, pending, review and disclosure-ready metrics while preserving the same underlying deterministic data.
- Recent Registry entries now use explicit visual badges for review and lifecycle state.
- Responsive behavior, keyboard focus, reduced-motion handling and Playwright accessibility coverage were expanded for the new dashboard structure.

### Privacy and authority

- No Registry schema, stored-data lifecycle, Discovery evidence boundary, Readiness logic, Disclosure eligibility, Evidence Export payload or external-service behavior changed in 1.3.0.
- Dashboard recommendations remain deterministic navigation guidance derived from local plugin state; they do not determine or certify legal compliance.

## [1.2.1] - 2026-10-21

### Fixed

- Spanish WordPress installations now have a bundled `es_ES` runtime fallback, so the plugin interface follows the WordPress locale even while the WordPress.org translation is awaiting validation, propagation or download.
- Installed WordPress.org language packs remain authoritative and take precedence over the plugin-local fallback.
- English WordPress installations continue to use the English source strings without a plugin-specific language selector.

### Validation

- Production builds now compile the maintained Spanish source catalog to `languages/kairoseth-ai-transparency-es_ES.mo`.
- CI validates that the compiled Spanish fallback is present while source `.po`/`.pot` catalogs remain outside the production ZIP.
- Real WordPress runtime acceptance switches the site to `es_ES`, verifies representative dashboard translations, then switches back to `en_US` and verifies the English fallback.

## [1.2.0] - 2026-10-10

### Added

- Extensible deterministic Discovery architecture with detector contracts and a multi-result server-authoritative selection flow.
- Validated deterministic Discovery support for **AI Puffer 2.4.97** alongside the existing **AI Engine 3.7.7** detector.
- Registry schema v2 administrator declarations for purpose, provider, model/model family, audience, human oversight and data-use context.
- Dedicated Registry form section for those declarations, with explicit wording that Discovery does not infer them from plugin identity or version.
- Deterministic Readiness rule `administrator_context_incomplete_v1` for reviewed active records with incomplete administrator context.
- Unit and browser acceptance proving Registry context create/edit/persist/re-open behavior and the new signed readiness finding.
- Evidence continuity tests proving administrator-context changes participate in the deterministic Evidence Export snapshot identity.

### Changed

- Discovery is no longer hard-wired to a single integration; result selection uses bounded `detector_id` and `source_signature` selectors and is revalidated against the current server-side WordPress plugin inventory before acceptance.
- Earlier Registry payloads migrate to schema v2 on first access while preserving existing lifecycle, review, interaction-context and disclosure state.
- New v2 declaration fields migrate as empty values instead of being inferred from detector evidence.
- Readiness defers administrator-context completeness findings until the record has been explicitly reviewed, reducing duplicate noise for newly discovered pending systems.
- Exact ZIP lifecycle acceptance now distinguishes untouched historical payload state from the intentional first-access schema migration and fingerprints the migrated payload for subsequent preservation checks.
- Spanish source coverage now includes the new Registry declaration and Readiness guidance strings.

### Privacy and authority

- Provider, model, purpose, audience, human oversight and data-use context remain explicit administrator-authored declarations.
- Discovery still reads only bounded local WordPress plugin identity/version/activation evidence and does not read credentials, prompts, conversations or private provider configuration.
- Completing Registry context does not certify or guarantee legal compliance.
- Registry, Discovery, Readiness, Disclosure and Evidence Export remain local-first with no new telemetry or remote-service dependency.

## [1.1.0] - 2026-10-10

### Added

- Dedicated top-level **Kairoseth AI Transparency** dashboard with live Registry, review, readiness and disclosure-ready metrics.
- Unified Kairoseth AI admin navigation for Registry, Discovery, Readiness, Disclosure, Evidence Export and Support while preserving legacy Tools routes for backwards compatibility.
- Dashboard Registry activity table with direct edit links to recent systems.
- Instant local Registry search and filters by system type, review status and lifecycle status without changing the persistence schema.
- Dedicated v1.1 admin experience layer with improved cards, tables, forms, findings, responsive behavior and reduced-motion handling.
- End-to-end browser acceptance covering the dashboard, unified menu, duplicate Tools-menu removal, Registry filtering, responsive behavior, accessibility and shared v1.1 styling.

### Changed

- Dashboard and internal workflow actions now stay inside the unified Kairoseth AI admin area instead of routing users back through Tools.
- Spanish source-catalog metadata and the POT template now target version 1.1.0 and the canonical `kairoseth-ai-transparency` text domain.
- WordPress.org-facing readme navigation and release metadata now describe the unified 1.1 admin experience.
- Registry persistence, Evidence Export schema and existing 1.0.x site-local data remain compatible.

## [1.0.3] - 2026-09-26

### Changed

- Clarified the public ownership relationship: Kairoseth is developed and operated by Emmake, and Kairoseth AI Transparency is an official Kairoseth product developed and maintained by Emmake.
- Plugin Author metadata now identifies `Emmake / Kairoseth` and uses `https://emmake.com/`, aligning the package with the submitting WordPress.org account's `@emmake.com` domain.
- No runtime behavior, Registry schema, privacy boundary or stored-data lifecycle changes.

## [1.0.2] - 2026-09-13

### Changed

- WordPress.org permalink and canonical text domain are aligned to the distinctive `kairoseth-ai-transparency` identity requested in response to directory review feedback.
- The submitting WordPress.org account `eduardoyauri` is now declared in `Contributors`.
- Removed the explicit `load_plugin_textdomain()` call; WordPress 6.6+ uses the WordPress.org just-in-time language-pack flow.
- Production packages no longer bundle `.po`/`.mo` catalogs; maintained Spanish source coverage remains validated in the repository for WordPress.org translation workflows.
- Release gates now fail if slug, text domain, contributor metadata or translation-loading behavior regress from the WordPress.org review contract.
- No Registry schema, privacy boundary or stored-data lifecycle changes.

## [1.0.1] - 2026-09-10

### Changed

- `Plugin URI` now points to the dedicated Kairoseth AI Transparency product page at `https://kairoseth.com/products/ai-transparency` while `Author URI` remains `https://kairoseth.com/`, satisfying the WordPress.org requirement that plugin and author URIs are not identical.
- No runtime behavior, Registry schema, privacy boundary or stored-data lifecycle changes.

## [1.0.0] - 2026-09-10

### Added

- WordPress plugin bootstrap and production namespace autoloader.
- Local versioned **AI Systems Registry** with add/edit/review/archive workflows.
- Deterministic **AI Discovery** support for the validated AI Engine 3.7.7 integration fixture.
- Deterministic **AI Readiness** findings that preserve Fact / Administrator Declaration / Guidance separation.
- Administrator-controlled **AI Disclosure** readiness and public shortcode rendering from reviewed server-side Registry state.
- Privileged deterministic **AI Evidence Export** JSON snapshot with stable `snapshot_signature` identity and strict evidence allow-list.
- Optional **AI Transparency Support** page under Tools with user-initiated navigation to the verified Kairoseth Custom Requests route.
- Server-authoritative contextual support context and exact request-type/query allow-lists.
- Fail-closed validation for non-canonical Kairoseth support destinations.
- Real WordPress responsive/accessibility and Multisite acceptance for the support flow.
- End-to-end production proof that Kairoseth Custom Requests accepts the bounded extension context and completes SMTP delivery.
- Safe explicit WordPress uninstall lifecycle limited to plugin-owned site-local Registry data.
- Reproducible versioned release packaging with SHA-256 artifact verification.
- PHPUnit baseline, WordPress Coding Standards, PHPCompatibility and PHP 7.4/8.1/8.3/8.5 syntax gates.
- EN/ES 100% runtime coverage with compiled Spanish gettext in the production package.
- Official WordPress Plugin Check gate.
- Actionable CI diagnostics and durable engineering failure/solution memory.
- MIT license, security, contribution and trademark policies.

### Privacy and authority

- Registry, Discovery, Readiness, Disclosure and Evidence Export remain local-first and do not automatically transmit their state to Kairoseth.
- The support page performs no Kairoseth request on page load.
- Contextual support automatically exposes only bounded product/platform/version/locale/request-type context after explicit user navigation.
- Site URL, user identity, Registry/evidence content, credentials, prompts, conversations, logs and arbitrary WordPress options are not automatically attached to support requests.
- Deactivation and upgrades preserve Registry state; explicit uninstall removes only plugin-owned Registry data and performs no remote request.

## [0.1.0] - Development baseline

This version identifies the initial repository/bootstrap contract. It was not claimed as a stable WordPress.org release.
