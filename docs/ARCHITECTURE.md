# Kairoseth AI Transparency — Implementation Architecture

Status: active development — Phase 2 closed, Phase 3 deterministic discovery active  
Last reviewed: 9 September 2026

## Product boundary

Kairoseth AI Transparency is a WordPress plugin for technical AI-transparency readiness. It maintains a local, reviewable AI Systems Registry and can discover supported AI integrations only when explainable WordPress evidence exists.

It is not a legal certification engine and must not infer legal obligations from weak or probabilistic evidence.

## Architectural principles

1. **Local-first baseline.** Core registry/discovery workflows require no automatic external account, telemetry or off-site data transfer.
2. **Evidence before conclusions.** Discovery reports what was actually observed and never converts plugin presence into a legal conclusion.
3. **No probabilistic authorship detector.** The plugin does not claim to identify arbitrary AI-written text.
4. **Server-authoritative permissions.** WordPress capabilities and nonces protect privileged actions.
5. **Browser input is not discovery authority.** Discovery evidence is re-observed server-side immediately before persistence.
6. **WordPress-native security.** Validate/sanitize input and escape output for its context.
7. **Domain separated from WordPress adapters.** Pure PHP detector/registry logic remains testable without booting WordPress where practical.
8. **EN/ES 100% together.** Customer-facing functionality ships English and Spanish in the same PR/release and CI blocks incomplete Spanish coverage.
9. **Responsive/accessibility acceptance.** Plugin-owned admin/frontend surfaces pass the relevant UX/accessibility gates.
10. **Production package is release authority.** Plugin Check and runtime acceptance validate `build/ai-transparency/`.
11. **Failures become reusable knowledge.** Material failures produce structured diagnostics and durable prevention records.
12. **Finish before advancing.** A phase cannot be declared closed until required implementation, acceptance, merge and post-merge verification are complete.

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
│   │   ├── class-adminpage.php
│   │   └── class-discoverypage.php
│   ├── Domain/
│   │   └── class-aisystem.php
│   ├── Registry/
│   │   ├── class-aisystemsregistry.php
│   │   └── class-registryschema.php
│   ├── Persistence/
│   │   └── class-wordpressoptionsregistryrepository.php
│   ├── Discovery/
│   │   ├── class-pluginobservation.php
│   │   ├── class-discoveryresult.php
│   │   ├── class-aienginedetector.php
│   │   └── class-wordpressplugininventory.php
│   ├── Evidence/                  later phase
│   ├── Disclosure/                later phase
│   └── Export/                    later phase
├── assets/
│   └── admin.css
├── languages/
│   ├── ai-transparency.pot
│   └── ai-transparency-es_ES.po
├── bin/
│   ├── build-plugin.sh
│   ├── check-i18n.php
│   ├── compile-po.php
│   └── run-with-diagnostics.sh
├── tests/
│   ├── e2e/
│   └── runtime/
├── docs/
└── .github/workflows/
```

Release flow:

```text
source repository
→ bilingual coverage check
→ deterministic build
→ compile Spanish gettext catalog
→ build/ai-transparency/
→ WordPress Plugin Check
→ real WordPress runtime acceptance
→ release candidate
```

Development-only files are intentionally absent from the generated package.

## Internationalization architecture

Source language is English. Runtime strings use:

```text
text domain: ai-transparency
Spanish source: languages/ai-transparency-es_ES.po
compiled Spanish: build/ai-transparency/languages/ai-transparency-es_ES.mo
```

`bin/check-i18n.php` compares runtime gettext strings with the Spanish catalog and blocks missing/empty translations or a wrong text domain.

## Core domains

### AI Systems Registry

The registry is the canonical local inventory of AI systems known to the plugin.

Current record shape:

```text
stable local id
name
system type
source identifier
source origin
lifecycle status
review status
interaction context
configured interaction-disclosure requirement
created / updated / reviewed timestamps
```

Manual records use WordPress-generated UUIDs. Discovered records use stable detector-derived ids. Archive changes lifecycle state rather than deleting the record.

### Registry schema and persistence

`RegistrySchema::VERSION = 1` is the canonical storage shape:

```text
{
  schema_version: 1,
  systems: [ ...deterministically ordered records... ]
}
```

Persistence uses the normal WordPress Options API:

```text
option: kairoseth_ai_transparency_registry
API: get_option() / update_option()
autoload: false on writes
scope: current WordPress blog/site
```

The repository migrates the earlier bootstrap array format, skips invalid individual records, fails safe on unknown future schema versions and preserves site-local separation in Multisite.

### Registry administration

**Tools → AI Transparency** supports:

```text
list
add
edit
review state
interaction context
disclosure-required declaration
archive
```

Every mutation uses `admin-post.php`, `manage_options`, a nonce and server-side validation/sanitization.

### Deterministic discovery

**Tools → AI Discovery** converts WordPress observations into reviewable candidates.

Architecture:

```text
WordPressPluginInventory
→ PluginObservation[]
→ integration-specific detector
→ DiscoveryResult
   ├ detector id
   ├ observed version
   ├ supported boundary
   ├ explainable evidence
   └ SHA-256 source signature
→ administrator review
→ explicit Add to registry
→ server re-observes inventory
→ supported result + manage_options + nonce
→ discovered / pending-review AiSystem
```

Discovery never writes automatically.

#### AI Engine detector v1

First validated boundary:

```text
plugin file: ai-engine/ai-engine.php
text domain: ai-engine
name prefix: AI Engine
active: required
validated version: 3.7.7
```

The detector proves only that the exact WordPress plugin identity is active at an observed version. It does not read AI Engine internal options, provider credentials, model configuration, prompts, conversations or content.

A different AI Engine version can be identified but is reported as outside the validated boundary and cannot be accepted through automated discovery until explicitly validated.

The generated registry candidate intentionally uses:

```text
type: other
source_origin: discovered
review_status: pending
interaction_context: empty
interaction_disclosure_required: false
```

This prevents plugin presence from being misrepresented as evidence that a chatbot or other specific AI workflow is actually in use.

Implementation/acceptance contract: [`PHASE3_DISCOVERY_IMPLEMENTATION.md`](PHASE3_DISCOVERY_IMPLEMENTATION.md).

### Future evidence/findings

A future finding must distinguish:

```text
FACT        — what was observed
DECLARATION — what an administrator stated
GUIDANCE    — what should be reviewed or implemented
```

### Future disclosure tooling

Disclosure components will only act on explicitly configured/supported workflows. The plugin will not alter arbitrary site content based on an AI guess.

## WordPress compatibility baseline

- Requires WordPress: 6.6+
- Tested-up-to target: 7.1
- Requires PHP: 7.4+
- Phase 3 AI Engine runtime fixture: AI Engine 3.7.7, which itself requires PHP 8.1+

The plugin remains PHP 7.4 compatible; the AI Engine discovery fixture runs in the dedicated WordPress 7.1 / PHP 8.3 acceptance lane.

## Security and privacy boundary

The public plugin must never contain or silently collect:

- customer/provider secrets;
- API keys;
- model/provider credentials;
- private prompts/conversations;
- customer-specific mappings;
- private customer data fixtures;
- hidden remote execution paths;
- browser/model output capable of granting WordPress privileges.

Registry and current discovery operations make no external Kairoseth request.

## CI / release gates

Current blocking gates:

```text
PHP quality
├ WordPress Coding Standards
├ PHPCompatibility 7.4+
├ PHPUnit
└ EN/ES source coverage

EN/ES 100%
├ runtime string/catalog comparison
├ production build
└ compiled Spanish MO

PHP syntax
├ 7.4
├ 8.1
├ 8.3
└ 8.5

WordPress Plugin Check
└ exact build/ai-transparency package

WordPress runtime acceptance
├ production plugin activation
├ real schema migration
├ registry CRUD/permissions
├ responsive/accessibility browser gate
├ AI Engine 3.7.7 deterministic discovery/acceptance
└ Multisite registry isolation
```

Repository-controlled failures use `bin/run-with-diagnostics.sh` and upload `.ci-diagnostics/` evidence.

## Phase status

- Phase 1: closed.
- Phase 2 Persistent AI Systems Registry: closed and verified on `main`.
- Phase 3 Deterministic Discovery: active; first AI Engine detector under acceptance.
- Later phases: not started.
