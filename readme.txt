=== Kairoseth AI Transparency ===
Contributors: eduardoyauri
Tags: ai transparency, eu ai act, ai disclosure, article 50, artificial intelligence
Requires at least: 6.6
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.3.0
License: MIT
License URI: https://opensource.org/license/mit/

Local-first AI transparency readiness for WordPress with AI inventory, disclosure, evidence export and EU AI Act support.

== Description ==

Kairoseth AI Transparency is an official Kairoseth product developed and maintained by Emmake. Kairoseth is developed and operated by Emmake. The plugin helps WordPress site owners maintain a reviewable technical inventory of AI systems used on their websites and turn that state into bounded technical evidence.

The stable release includes:

* a dedicated **Kairoseth AI Transparency** operational dashboard with live Registry, review, readiness and disclosure-ready metrics plus deterministic next-action guidance;
* a local AI Systems Registry under **Kairoseth AI Transparency > AI Systems Registry**, including instant local search and filters by type, review and lifecycle status;
* administrator-authored context for purpose, provider, model/model family, audience, human oversight and data-use context;
* deterministic AI integration discovery under **Kairoseth AI Transparency > AI Discovery**;
* deterministic technical readiness findings under **Kairoseth AI Transparency > AI Readiness**;
* explicit administrator-controlled disclosure readiness under **Kairoseth AI Transparency > AI Disclosure**;
* privileged local JSON evidence export under **Kairoseth AI Transparency > AI Evidence Export**;
* an optional, administrator-initiated Kairoseth support bridge under **Kairoseth AI Transparency > AI Transparency Support**.

The 1.3 dashboard turns the plugin overview into an operational center. It highlights the highest-priority next action from the current site-local state, keeps pending review, technical review findings and disclosure-ready counts visible, provides direct access to the complete six-module workflow and shows recent Registry state with review/lifecycle badges. These dashboard signals are navigation guidance only; they do not determine or certify legal compliance.

Legacy Tools URLs from the 1.0.x line remain registered for backwards compatibility, while the visible workflow is grouped under the dedicated Kairoseth AI Transparency menu.

An authorized administrator can add, edit, review and archive AI system records. Registry data is stored locally in the current WordPress site's Options storage with a versioned schema. Version 1.2 upgraded Registry persistence to schema v2 and migrates earlier records without inventing provider, model, purpose or other administrator declarations. Version 1.3 does not change the Registry schema.

AI Discovery uses an extensible deterministic detector architecture. The validated detectors support:

* **AI Engine 3.7.7**;
* **AI Puffer 2.4.97** (`gpt3-ai-content-generator/gpt3-ai-content-generator.php`).

Discovery evaluates bounded WordPress plugin identity, version and activation-state evidence. It does not inspect AI provider credentials, prompts, conversations or private provider configuration, and it does not infer purpose, provider, model, audience, oversight or data-use declarations. Supported discovery results require explicit administrator acceptance and enter the Registry as pending review.

AI Readiness generates findings on demand from the current Registry and keeps three concepts separate:

* **Fact** — a technical condition observed in the current Registry;
* **Administrator declaration** — an explicit administrator state when relevant;
* **Guidance** — a technical review or completion action.

A reviewed active record can be flagged when one or more administrator-context fields remain undocumented. This is a bounded completeness signal, not a legal-compliance decision.

AI Disclosure renders only from server-authoritative reviewed Registry state. The public shortcode is:

`[kairoseth_ai_disclosure system="SYSTEM_ID"]`

Public output is intentionally bounded to localized disclosure copy and the reviewed system name. Internal interaction context, provider/model configuration, credentials, prompts, conversations and private logs are not automatically exposed.

AI Evidence Export creates an explicit administrator-generated JSON attachment for the current site only. The export includes the complete site-local Registry, normalized persisted Discovery references where structurally valid, current deterministic Readiness findings and Disclosure readiness.

Evidence Export v1 guarantees:

* `export_schema_version = 1`;
* a UTC `generated_at` timestamp;
* a deterministic SHA-256 `snapshot_signature` that excludes generation time;
* unchanged technical state produces the same signature across export times;
* meaningful exported Registry changes, including administrator-authored context changes, change the signature;
* archived Registry records are retained;
* an empty Registry is valid;
* site-local Multisite isolation;
* no Media Library persistence, export history, email, telemetry, Kairoseth upload or cloud-account requirement;
* no arbitrary WordPress/plugin option dump or automatic export of credentials, tokens, cookies, nonces, user identities, prompts, conversations, logs or database dumps.

The privileged export may contain administrator-authored operational context from the Registry, including interaction context and administrator context fields, so the file may contain confidential business information and should be handled accordingly.

**Important:** This plugin provides technical readiness, workflow and evidence tooling. It does not certify or guarantee compliance with the EU AI Act or any other law. The evidence `snapshot_signature` is a technical snapshot identity, not a legal/digital signature, trusted timestamp, non-repudiation proof or regulatory certification.

The plugin does not perform generic probabilistic detection of whether arbitrary text was written by AI.

== External services ==

The plugin is local-first. Registry, Discovery, Readiness, Disclosure and Evidence Export do not require Kairoseth or automatically send their state to an external service.

**Kairoseth Custom Requests** is an optional support/custom-integration service used only after an administrator deliberately clicks a link on **Kairoseth AI Transparency > AI Transparency Support**. Loading the WordPress support page itself makes no Kairoseth request.

The explicit browser navigation goes to `https://kairoseth.com/custom-requests` and includes only this bounded technical/product context in the URL:

* source: `extension`;
* extension slug: `kairoseth-ai-transparency`;
* extension name: `Kairoseth AI Transparency`;
* installed plugin version;
* host platform: `wordpress`;
* installed WordPress version;
* bounded English/Spanish locale;
* the administrator-selected bounded request type.

The plugin does **not** automatically attach or transmit the site URL, administrator identity, Registry contents, AI system names, interaction context, administrator context fields, Discovery evidence, Readiness findings, Disclosure state, Evidence Export JSON/signature, plugin/theme inventory, server paths, credentials, prompts, conversations, logs or database contents.

After reaching Kairoseth, the administrator decides what contact, business or request information to enter and submit. Kairoseth owns the request form, its consent flow and final submission.

Service provider: Kairoseth  
Service URL: https://kairoseth.com/custom-requests  
Privacy policy: https://kairoseth.com/privacy

== Installation ==

1. Upload the plugin directory to `/wp-content/plugins/` or install the packaged ZIP.
2. Activate **Kairoseth AI Transparency** from the Plugins screen.
3. Open **Kairoseth AI Transparency** for the operational dashboard and current technical status.
4. Open **Kairoseth AI Transparency > AI Systems Registry** to maintain, search and filter the local Registry.
5. Open **Kairoseth AI Transparency > AI Discovery** to review supported deterministic integration evidence.
6. Open **Kairoseth AI Transparency > AI Readiness** to review current technical findings.
7. Open **Kairoseth AI Transparency > AI Disclosure** to review disclosure readiness and copy a shortcode for an eligible system.
8. Open **Kairoseth AI Transparency > AI Evidence Export** to download the current site-local JSON evidence snapshot.
9. Optionally open **Kairoseth AI Transparency > AI Transparency Support** for an explicit privacy-bounded handoff to Kairoseth support/custom requests.

== Frequently Asked Questions ==

= Does this plugin make my website legally compliant? =

No. It provides technical readiness, evidence and workflow tooling. It does not provide legal certification or guarantee compliance.

= Does the plugin automatically send Registry, Discovery, Readiness, Disclosure or Evidence Export data to an external service? =

No. The accepted local workflows remain inside the WordPress site and do not automatically send their state or generated evidence file to an external service. The optional support page only opens Kairoseth after an administrator deliberately clicks a support/custom-request link, and it sends only the bounded context documented above.

= What does the 1.3 dashboard decide? =

It does not make legal decisions. It derives navigation guidance from current local plugin state so an administrator can quickly see whether to review the Registry, inspect readiness findings, review disclosure-ready systems or run Discovery.

= What does AI Discovery detect? =

The validated deterministic detectors cover AI Engine 3.7.7 and AI Puffer 2.4.97. Discovery identifies only supported plugin identity/version/activation evidence and does not infer which provider, model, purpose, audience or workflow is actually configured.

= What information can I add to the Registry? =

In addition to the technical record, an administrator can document purpose, provider, model/model family, audience, human oversight and data-use context. These values remain explicit administrator declarations and are not filled automatically by Discovery.

= What does AI Readiness report? =

The readiness engine reports reproducible technical review conditions derived from the local AI Systems Registry. It keeps observed facts, administrator declarations and technical guidance separate. It can also highlight reviewed active records with incomplete administrator context. It does not make legal compliance decisions.

= What is the Evidence Export snapshot signature? =

It is a SHA-256 identity of the bounded stable technical snapshot. Generation time is excluded so repeated exports of unchanged technical state keep the same signature. Meaningful Registry changes, including administrator-context changes, change the signature. It is not a legal/digital signature or external timestamp.

= Is Evidence Export stored or uploaded? =

No. Evidence Export v1 is built for the explicit administrator request and returned as a direct JSON attachment. The plugin does not persist the generated file or upload it to Kairoseth.

= What happens to my Registry if I update from an earlier version? =

Version 1.2 migrated earlier Registry records to schema v2 on first access while preserving existing lifecycle, review, disclosure and interaction-context state. Version 1.3 changes the dashboard experience only and does not introduce a new Registry migration.

= What happens to my Registry if I deactivate or remove the plugin? =

Deactivation and upgrades preserve the site-local Registry. An explicit WordPress uninstall removes only the Registry data owned by this plugin from the affected site or sites.

= How is Multisite handled? =

Registry and Evidence Export follow the authoritative current blog/site context. The export does not aggregate the full network. A network uninstall removes only the plugin-owned Registry option from each existing site.

= Does it detect whether any article was written by AI? =

No. Generic probabilistic AI-written-text detection is outside the plugin scope.

== Changelog ==

= 1.3.0 =
* Turns the plugin overview into an operational dashboard center with deterministic priority guidance from current local state.
* Adds direct Registry and Discovery hero actions, stronger live metric semantics and a visible installed-version indicator.
* Adds compact action rows for pending review, technical review findings and disclosure-ready systems.
* Adds a compact six-module workflow map for Registry, Discovery, Readiness, Disclosure, Evidence Export and Support.
* Improves recent Registry presentation with review/lifecycle badges, WordPress-formatted dates and an actionable empty state.
* Expands responsive, keyboard-focus, reduced-motion and accessibility acceptance for the dashboard.
* Does not change the Registry schema, stored data, privacy boundary or evidence/disclosure logic.

= 1.2.1 =
* Makes the plugin follow the WordPress locale automatically with a bundled Spanish (`es_ES`) runtime fallback.
* Keeps installed WordPress.org language packs authoritative when they are available.
* Preserves English as the source/fallback language and does not add a plugin-specific language selector.
* Adds production-package and real WordPress runtime checks for Spanish and English locale behavior.

= 1.2.0 =
* Adds an extensible multi-detector Discovery engine while preserving server-authoritative, deterministic evidence selection.
* Adds validated deterministic Discovery support for AI Puffer 2.4.97 alongside AI Engine 3.7.7.
* Upgrades the site-local Registry to schema v2 with administrator-authored purpose, provider, model/model family, audience, human oversight and data-use context.
* Migrates earlier Registry records safely with the new declaration fields empty instead of inferring values from plugin identity or Discovery evidence.
* Adds Registry form controls for all six administrator-context fields with explicit local-first and declaration boundaries.
* Extends deterministic Evidence Export snapshots so administrator-context changes participate in the stable snapshot identity.
* Adds a bounded Readiness finding for reviewed active systems whose administrator context remains incomplete.
* Preserves Fact / Administrator declaration / Guidance separation and does not convert completeness findings into legal-compliance claims.
* Extends real WordPress, Multisite, accessibility, migration, exact-ZIP lifecycle and EN/ES validation for the 1.2 data model.

= 1.1.0 =
* Adds a dedicated top-level Kairoseth AI Transparency dashboard with live Registry, review, readiness and disclosure-ready metrics.
* Groups Registry, Discovery, Readiness, Disclosure, Evidence Export and Support under one visible plugin menu while preserving legacy 1.0.x Tools URLs for backwards compatibility.
* Adds a recent Registry activity table with direct edit actions from the dashboard.
* Adds instant local Registry search and filters by system type, review status and lifecycle status without changing the persistence schema.
* Improves admin cards, tables, forms, findings, responsive behavior, keyboard focus handling and reduced-motion behavior.
* Aligns Spanish source-catalog metadata and the translation template with version 1.1.0 and the canonical `kairoseth-ai-transparency` text domain.
* Keeps Registry storage, Evidence Export schema and existing 1.0.x site-local data compatible with the 1.0.x line.

= 1.0.3 =
* Clarifies that Kairoseth is developed and operated by Emmake and that Kairoseth AI Transparency is an official Kairoseth product developed and maintained by Emmake.
* Aligns the plugin Author metadata with the submitting `@emmake.com` ownership identity used for WordPress.org review.
* No runtime behavior or stored-data schema changes.

= 1.0.2 =
* Aligns the WordPress.org permalink, plugin text domain and product slug with the distinctive Kairoseth AI Transparency identity.
* Uses WordPress.org just-in-time language packs instead of manually calling `load_plugin_textdomain()`.
* Lists the submitting WordPress.org account `eduardoyauri` as the plugin contributor.
* No stored-data schema changes.

= 1.0.1 =
* Uses the dedicated Kairoseth AI Transparency product page as Plugin URI so plugin and author metadata remain distinct for WordPress.org validation.
* No runtime behavior or stored-data schema changes.

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
* Internationalized customer-facing strings with maintained English/Spanish source coverage.
* Public CI, real WordPress runtime acceptance and official WordPress Plugin Check baseline.

= 0.1.0 =
* Development baseline used before the first stable release.

== Upgrade Notice ==

= 1.3.0 =
Upgrades the plugin overview into an operational center with deterministic next-action guidance, compact workflow navigation and improved Registry state presentation. No Registry schema migration is required.

= 1.2.1 =
Adds automatic locale-aware Spanish rendering for `es_ES` WordPress sites while keeping official WordPress.org language packs authoritative and English as the fallback for unsupported locales.

= 1.2.0 =
Adds multi-detector Discovery, AI Puffer support and Registry schema v2 with explicit administrator context. Existing Registry records are preserved and migrated safely with new declaration fields empty until reviewed.

= 1.1.0 =
Adds the unified Kairoseth AI Transparency dashboard, admin navigation and Registry search/filter UX while preserving the existing site-local Registry schema and 1.0.x data.

= 1.0.3 =
Ownership-metadata clarification for the ongoing WordPress.org review. No runtime behavior or stored-data schema changes.

= 1.0.2 =
WordPress.org review update aligning the plugin slug, text domain, contributor metadata and translation loading with directory requirements.

= 1.0.1 =
Metadata-only patch for WordPress.org submission readiness. Runtime behavior and Registry data remain unchanged.

= 1.0.0 =
First stable release. Existing Registry state from the development baseline is preserved during upgrade. Review your AI systems and disclosure readiness after updating.
