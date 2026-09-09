=== Kairoseth AI Transparency ===
Contributors: emmakex
Tags: ai transparency, eu ai act, ai disclosure, article 50, artificial intelligence
Requires at least: 6.6
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 0.1.0
License: MIT
License URI: https://opensource.org/license/mit/

EU AI Act transparency readiness tooling for WordPress with local AI inventory, evidence and disclosure workflows.

== Description ==

Kairoseth AI Transparency helps WordPress site owners build a technical transparency-readiness workflow for AI features used on their websites.

The Free edition is designed around local, reviewable evidence rather than opaque compliance scoring.

Initial product direction includes:

* a local AI Systems Registry;
* deterministic discovery for explicitly supported AI integrations;
* evidence-backed readiness findings;
* accessible AI-interaction disclosure tooling;
* explicit declarations for supported AI-generated or manipulated content workflows;
* local evidence/export records;
* English and Spanish customer-facing experience.

**Important:** This plugin provides technical readiness, workflow and evidence tooling. It does not certify or guarantee compliance with the EU AI Act or any other law. Legal obligations depend on the actual AI system, role, context and use case.

The plugin does not perform generic probabilistic detection of whether arbitrary text was written by AI.

== Installation ==

1. Upload the plugin directory to `/wp-content/plugins/` or install the packaged ZIP.
2. Activate **Kairoseth AI Transparency** from the Plugins screen.
3. During development, open **Tools > AI Transparency** to confirm the plugin foundation is active.

Functional discovery, registry and disclosure workflows will be enabled only after their product acceptance gates are implemented.

== Frequently Asked Questions ==

= Does this plugin make my website legally compliant? =

No. It provides technical readiness, evidence and workflow tooling. It does not provide legal certification or guarantee compliance.

= Does the plugin send my site data to Kairoseth automatically? =

No automatic Kairoseth telemetry or account connection is part of the Free v1 baseline. Any future external service must be explicit, documented and consent-aware.

= Does it detect whether any article was written by AI? =

No. Generic probabilistic AI-written-text detection is intentionally outside the product scope.

= Can Kairoseth adapt the plugin for a custom AI system? =

Yes. Custom implementations are handled separately from the public Free repository and use their own private project repositories and contracts.

== Changelog ==

= 0.1.0 =
* Repository and WordPress plugin bootstrap.
* Initial domain model and local registry foundation.
* Public CI and WordPress Plugin Check baseline prepared.

== Upgrade Notice ==

= 0.1.0 =
Initial development baseline. No stable WordPress.org release is claimed yet.
