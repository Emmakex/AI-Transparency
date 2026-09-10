=== Kairoseth AI Transparency ===
Contributors: emmakex
Tags: ai transparency, eu ai act, ai disclosure, article 50, artificial intelligence
Requires at least: 6.6
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: MIT
License URI: https://opensource.org/license/mit/

Local-first AI transparency readiness for WordPress with AI inventory, disclosure, evidence export and EU AI Act support.

== Description ==

Kairoseth AI Transparency helps WordPress site owners maintain a reviewable technical inventory of AI systems used on their websites and turn that state into bounded technical evidence.

Version 1.0.0 includes:

* a local AI Systems Registry under **Tools > AI Transparency**;
* deterministic AI integration discovery under **Tools > AI Discovery**;
* deterministic technical readiness findings under **Tools > AI Readiness**;
* explicit administrator-controlled disclosure readiness under **Tools > AI Disclosure**;
* privileged local JSON evidence export under **Tools > AI Evidence Export**;
* an optional, administrator-initiated Kairoseth support bridge under **Tools > AI Transparency Support**.

An authorized administrator can add, edit, review and archive AI system records. Registry data is stored locally in the current WordPress site's Options storage with a versioned schema.

The first validated discovery detector supports **AI Engine 3.7.7** from bounded WordPress plugin identity evidence. Discovery does not inspect AI provider credentials, provider/model settings, prompts, conversations or AI Engine internal configuration. Supported discovery results require explicit administrator acceptance and enter the Registry as pending review.

AI Readiness generates findings on demand from the current Registry and keeps three concepts separate:

* **Fact** — a technical condition observed in the current Registry;
* **Administrator declaration** — an explicit administrator state when relevant;
* **Guidance** — a technical review or completion action.

AI Disclosure renders only from server-authoritative reviewed Registry state. The public shortcode is:

`[kairoseth_ai_disclosure system="SYSTEM_ID"]`

Public output is intentionally bounded to localized disclosure copy and the reviewed system name. Internal interaction context, provider/model configuration, credentials, prompts, conversations and private logs are not automatically exposed.

AI Evidence Export creates an explicit administrator-generated JSON attachment for the current site only. The export includes the complete site-local Registry, normalized persisted Discovery references where structurally valid, current deterministic Readiness findings and Disclosure readiness.

Evidence Export v1 guarantees:

* `export_schema_version = 1`;
* a UTC `generated_at` timestamp;
* a deterministic SHA-256 `snapshot_signature` that excludes generation time;
* unchanged technical state produces the same signature across export times;
* meaningful exported Registry changes change the signature;
* archived Registry records are retained;
* an empty Registry is valid;
* site-local Multisite isolation;
* no Media Library persistence, export history, email, telemetry, Kairoseth upload or cloud-account requirement;
* no arbitrary WordPress/plugin option dump or automatic export of credentials, tokens, cookies, nonces, user identities, prompts, conversations, logs or database dumps.

The privileged export may contain administrator-authored `interaction_context`, so the file may contain confidential operational context and should be handled accordingly.

**Important:** This plugin provides technical readiness, workflow and evidence tooling. It does not certify or guarantee compliance with the EU AI Act or any other law. The evidence `snapshot_signature` is a technical snapshot identity, not a legal/digital signature, trusted timestamp, non-repudiation proof or regulatory certification.

The plugin does not perform generic probabilistic detection of whether arbitrary text was written by AI.

== External services ==

The plugin is local-first. Registry, Discovery, Readiness, Disclosure and Evidence Export do not require Kairoseth or automatically send their state to an external service.

**Kairoseth Custom Requests** is an optional support/custom-integration service used only after an administrator deliberately clicks a link on **Tools > AI Transparency Support**. Loading the WordPress support page itself makes no Kairoseth request.

The explicit browser navigation goes to `https://kairoseth.com/custom-requests` and includes only this bounded technical/product context in the URL:

* source: `extension`;
* extension slug: `ai-transparency`;
* extension name: `Kairoseth AI Transparency`;
* installed plugin version;
* host platform: `wordpress`;
* installed WordPress version;
* bounded English/Spanish locale;
* the administrator-selected bounded request type.

The plugin does **not** automatically attach or transmit the site URL, administrator identity, Registry contents, AI system names, interaction context, Discovery evidence, Readiness findings, Disclosure state, Evidence Export JSON/signature, plugin/theme inventory, server paths, credentials, prompts, conversations, logs or database contents.

After reaching Kairoseth, the administrator decides what contact, business or request information to enter and submit. Kairoseth owns the request form, its consent flow and final submission.

Service provider: Kairoseth  
Service URL: https://kairoseth.com/custom-requests  
Privacy policy: https://kairoseth.com/privacy

== Installation ==

1. Upload the plugin directory to `/wp-content/plugins/` or install the packaged ZIP.
2. Activate **Kairoseth AI Transparency** from the Plugins screen.
3. Open **Tools > AI Transparency** to maintain the local Registry.
4. Open **Tools > AI Discovery** to review supported deterministic integration evidence.
5. Open **Tools > AI Readiness** to review current technical findings.
6. Open **Tools > AI Disclosure** to review disclosure readiness and copy a shortcode for an eligible system.
7. Open **Tools > AI Evidence Export** to download the current site-local JSON evidence snapshot.
8. Optionally open **Tools > AI Transparency Support** for an explicit privacy-bounded handoff to Kairoseth support/custom requests.

== Frequently Asked Questions ==

= Does this plugin make my website legally compliant? =

No. It provides technical readiness, evidence and workflow tooling. It does not provide legal certification or guarantee compliance.

= Does the plugin automatically send Registry, Discovery, Readiness, Disclosure or Evidence Export data to an external service? =

No. The accepted local workflows remain inside the WordPress site and do not automatically send their state or generated evidence file to an external service. The optional support page only opens Kairoseth after an administrator deliberately clicks a support/custom-request link, and it sends only the bounded context documented above.

= What does AI Discovery detect? =

The first validated detector recognizes an active AI Engine 3.7.7 installation from WordPress plugin inventory evidence. It does not infer which AI provider, model, chatbot or workflow is actually configured.

= What does AI Readiness report? =

The readiness engine reports reproducible technical review conditions derived from the local AI Systems Registry. It keeps observed facts, administrator declarations and technical guidance separate and does not make legal compliance decisions.

= What is the Evidence Export snapshot signature? =

It is a SHA-256 identity of the bounded stable technical snapshot. Generation time is excluded so repeated exports of unchanged technical state keep the same signature. It is not a legal/digital signature or external timestamp.

= Is Evidence Export stored or uploaded? =

No. Evidence Export v1 is built for the explicit administrator request and returned as a direct JSON attachment. The plugin does not persist the generated file or upload it to Kairoseth.

= What happens to my Registry if I deactivate or remove the plugin? =

Deactivation and upgrades preserve the site-local Registry. An explicit WordPress uninstall removes only the Registry data owned by this plugin from the affected site or sites.

= How is Multisite handled? =

Registry and Evidence Export follow the authoritative current blog/site context. The first export does not aggregate the full network. A network uninstall removes only the plugin-owned Registry option from each existing site.

= Does it detect whether any article was written by AI? =

No. Generic probabilistic AI-written-text detection is outside the plugin scope.

== Changelog ==

= 1.0.0 =
* First stable release candidate.
* Versioned site-local AI Systems Registry with administrator CRUD/review/archive.
* Deterministic AI Engine 3.7.7 discovery with explicit administrator acceptance.
* Deterministic AI Readiness findings with Fact / Administrator declaration / Guidance separation.
* Explicit AI Disclosure readiness and public shortcode output.
* Administrator-generated deterministic JSON Evidence Export with SHA-256 snapshot identity.
* Optional administrator-initiated Kairoseth support/custom-integration handoff with a strict technical-context allow-list.
* Safe data lifecycle: upgrades and deactivation preserve Registry state; explicit uninstall removes only plugin-owned Registry data.
* Local-first privacy boundary and site-local Multisite isolation.
* Mandatory English/Spanish runtime coverage and compiled Spanish catalog.
* Public CI, real WordPress runtime acceptance and official WordPress Plugin Check baseline.

= 0.1.0 =
* Development baseline used before the first stable release.

== Upgrade Notice ==

= 1.0.0 =
First stable release. Existing Registry state from the development baseline is preserved during upgrade. Review your AI systems and disclosure readiness after updating.