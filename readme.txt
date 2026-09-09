=== Kairoseth AI Transparency ===
Contributors: emmakex
Tags: ai transparency, eu ai act, ai disclosure, article 50, artificial intelligence
Requires at least: 6.6
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 0.1.0
License: MIT
License URI: https://opensource.org/license/mit/

AI transparency readiness tooling for WordPress with a local AI systems registry and deterministic AI integration discovery.

== Description ==

Kairoseth AI Transparency helps WordPress site owners maintain a reviewable technical inventory of AI systems used on their websites.

The current pre-release development build includes:

* a local AI Systems Registry under **Tools > AI Transparency**;
* deterministic AI integration discovery under **Tools > AI Discovery**.

An authorized administrator can add, edit, review and archive AI system records. Registry data is stored locally in the current WordPress site's normal Options storage using a versioned schema.

The first validated discovery detector supports **AI Engine 3.7.7**. It observes only the WordPress plugin basename, plugin name, version, text domain and activation state. A different AI Engine version is reported as outside the currently validated detector boundary and cannot be accepted automatically through discovery.

Discovery does not inspect AI provider credentials, provider/model settings, prompts, conversations or AI Engine internal configuration. A supported result must be explicitly added by an administrator and enters the local registry with **Pending review** status.

Current capabilities include:

* AI system name and type;
* interaction context;
* review status;
* interaction-disclosure requirement state;
* non-destructive archival;
* local versioned persistence;
* deterministic AI Engine 3.7.7 discovery evidence;
* explicit administrator acceptance of discovery results;
* English and Spanish customer-facing UI.

**Important:** This plugin provides technical readiness, workflow and evidence tooling. It does not certify or guarantee compliance with the EU AI Act or any other law. Legal obligations depend on the actual AI system, role, context and use case.

The plugin does not perform generic probabilistic detection of whether arbitrary text was written by AI.

== Installation ==

1. Upload the plugin directory to `/wp-content/plugins/` or install the packaged ZIP.
2. Activate **Kairoseth AI Transparency** from the Plugins screen.
3. Open **Tools > AI Transparency** to maintain the local registry.
4. Open **Tools > AI Discovery** to review supported deterministic integration evidence.

== Frequently Asked Questions ==

= Does this plugin make my website legally compliant? =

No. It provides technical readiness, evidence and workflow tooling. It does not provide legal certification or guarantee compliance.

= Does the plugin automatically send registry or discovery data to an external service? =

No. The current registry and discovery workflow remain local to the WordPress site and do not automatically send their data to an external service.

= What does AI Discovery detect? =

The first validated detector recognizes an active AI Engine 3.7.7 installation from WordPress plugin inventory evidence. It does not infer which AI provider, model, chatbot or workflow is actually configured.

= How is the AI Systems Registry stored? =

The current development implementation uses the normal WordPress Options API with a versioned local schema. In Multisite, the registry follows the current blog/site context rather than creating one network-wide inventory.

= Does it detect whether any article was written by AI? =

No. Generic probabilistic AI-written-text detection is outside the plugin scope.

== Changelog ==

= 0.1.0 =
* Repository and WordPress plugin bootstrap.
* Initial domain model and deterministic registry foundation.
* Mandatory English/Spanish coverage and compiled Spanish catalog.
* Versioned local AI Systems Registry persistence foundation.
* Development admin CRUD for add/edit/review/archive AI system records.
* Deterministic AI Engine 3.7.7 discovery foundation with explicit administrator acceptance.
* Public CI, WordPress runtime acceptance and WordPress Plugin Check baseline.

== Upgrade Notice ==

= 0.1.0 =
Pre-release development baseline. No stable WordPress.org release is claimed yet.
