# Kairoseth AI Transparency — Implementation Architecture

Status: active development — Phase 4 closed; Phase 5 disclosure contract active, production implementation not started  
Last reviewed: 9 September 2026

## Product boundary

Kairoseth AI Transparency is a WordPress plugin for technical AI-transparency readiness. It maintains a local, reviewable AI Systems Registry, can discover supported AI integrations only when explainable WordPress evidence exists, can generate deterministic technical readiness findings from current registry state, and is now defining an explicit administrator-controlled disclosure renderer for reviewed workflows.

It is not a legal certification engine and must not infer legal obligations from weak, probabilistic or merely contextual evidence.

## Architectural principles

1. **Local-first baseline.** Core registry/discovery/readiness/disclosure workflows require no automatic external account, telemetry or off-site data transfer.
2. **Evidence before conclusions.** Discovery and readiness report what is technically supported by evidence and never convert plugin presence or registry state into a legal conclusion.
3. **FACT / DECLARATION / GUIDANCE separation.** Technical observations, administrator assertions and guidance remain distinct in every finding.
4. **Explicit placement for disclosure.** Phase 5 v1 may render only from an administrator-reviewed eligible registry record and deliberate shortcode placement; plugin presence alone never injects frontend output.
5. **No probabilistic authorship detector.** The plugin does not claim to identify arbitrary AI-written text.
6. **Server-authoritative permissions.** WordPress capabilities and nonces protect privileged mutations; read-only privileged surfaces still require server-side capabilities.
7. **Browser/content input is not evidence authority.** Discovery evidence is re-observed server-side before persistence, readiness findings are generated from server-loaded registry state, and a shortcode system id is only a selector for a server-side eligibility lookup.
8. **WordPress-native security.** Validate/sanitize input and escape output for its context.
9. **Domain separated from WordPress adapters.** Pure PHP detector/registry/finding/disclosure logic remains testable without booting WordPress where practical.
10. **EN/ES 100% together.** Customer-facing functionality ships English and Spanish in the same PR/release and CI blocks incomplete Spanish coverage.
11. **Responsive/accessibility acceptance.** Plugin-owned admin/frontend surfaces pass the relevant UX/accessibility gates.
12. **Production package is release authority.** Plugin Check and runtime acceptance validate `build/ai-transparency/`.
13. **Failures become reusable knowledge.** Material failures produce structured diagnostics and durable prevention records.
14. **Finish before advancing.** A phase cannot be declared closed until required implementation, acceptance, merge and post-merge verification are complete.

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
│   │   ├── class-discoverypage.php
│   │   ├── class-readinesspage.php
│   │   └── class-disclosurepage.php       Phase 5 planned
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
│   ├── Evidence/
│   │   ├── class-finding.php
│   │   └── class-findingengine.php
│   ├── Disclosure/                        Phase 5 planned
│   │   ├── class-disclosure.php
│   │   ├── class-disclosureengine.php
│   │   └── class-disclosureshortcode.php
│   └── Export/                            later phase
├── assets/
│   ├── admin.css
│   └── disclosure.css                    Phase 5 optional/planned
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

The Phase 5 file split is a design target, not shipped code. Implementation may simplify the split if the same authority/security contracts remain explicit.

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

Phase 5 v1 is explicitly designed to reuse this schema. A new disclosure table/option/schema field is not justified by the first supported workflow.

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

This remains the only Phase 5 v1 configuration authority. The planned Disclosure screen is read-only.

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

Accepted boundary:

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

Accepted implementation/acceptance contract: [`PHASE3_DISCOVERY_IMPLEMENTATION.md`](PHASE3_DISCOVERY_IMPLEMENTATION.md).  
Runtime evidence: [`PHASE3_RUNTIME_EVIDENCE.md`](PHASE3_RUNTIME_EVIDENCE.md).

### Readiness findings / evidence — Phase 4 accepted

**Tools → AI Readiness** renders technical findings generated from the current site-local registry.

Architecture:

```text
WordPressOptionsRegistryRepository
→ AiSystemsRegistry
→ FindingEngine
→ Finding[]
→ ReadinessPage
```

The accepted first increment intentionally does **not** persist findings separately. The registry remains the source of truth and the engine recalculates findings on every evaluation.

#### Finding model

```text
stable finding id
stable rule id
category
priority
subject system id/name
fact code
declaration code
guidance code
SHA-256 evidence signature
generated_at metadata
```

The model keeps presentation semantics separate:

```text
FACT        — technically observed local state
DECLARATION — explicit administrator state when relevant
GUIDANCE    — technical review/completion action
```

`generated_at` does not participate in stable identity/signature. The same rule against the same relevant evidence produces the same finding id and SHA-256 signature.

#### Accepted finding rules

```text
registry_review_pending_v1
  active + pending review
  → FACT: review still pending
  → DECLARATION: none
  → GUIDANCE: complete system review

interaction_context_missing_v1
  active + empty interaction context
  → FACT: context absent
  → DECLARATION: none
  → GUIDANCE: document actual interaction context

configured_disclosure_review_v1
  active + interaction_disclosure_required=true
  → FACT: system active
  → DECLARATION: administrator marked disclosure required
  → GUIDANCE: verify implementation/placement
```

The disclosure rule does not infer that a legal duty exists. It reports the administrator's explicit configuration.

Archived systems are ignored by the accepted readiness engine.

#### Readiness authority boundary

Readiness is read-only in the accepted Phase 4 increment and requires `manage_options`.

Browser query/form values do not supply finding evidence. Registry state is loaded server-side and findings are generated server-side. Because viewing findings does not mutate state, there is no mutation nonce solely for the read-only page.

Implementation contract: [`PHASE4_FINDINGS_IMPLEMENTATION.md`](PHASE4_FINDINGS_IMPLEMENTATION.md).  
Acceptance checklist: [`PHASE4_ACCEPTANCE.md`](PHASE4_ACCEPTANCE.md).  
Runtime evidence: [`PHASE4_RUNTIME_EVIDENCE.md`](PHASE4_RUNTIME_EVIDENCE.md).

Accepted Phase 4 implementation evidence:

```text
PR: #9
Accepted head: 926a154930042e53af0b082795be155389cc6916
Pre-merge CI: #75 / 34381590429
Merge: 836abfeca4c199930b74ba32547f9037f7fcb4de
Post-merge CI: #76 / 34382057838
Closure PR: #10
Final closure merge: 40d7aee73015c2e7185bde156349d106e72a4e18
Final Phase 4 main CI: #78 / 34390407476
Blockers: 0
```

### Disclosure tooling — Phase 5 contract active

Phase 5 production implementation has not started. The design/acceptance contract defines one deliberately narrow first workflow: a manually placed inline shortcode in normal singular WordPress post/page content.

#### Eligibility authority

The existing `AiSystem` is sufficient for v1. Eligibility is server-authoritative and requires all four conditions:

```text
status = active
review_status = reviewed
interaction_disclosure_required = true
interaction_context != empty
```

`source_origin` alone never grants eligibility.

This creates an intentional progression:

```text
Discovery candidate
→ administrator review
→ interaction context documented
→ disclosure declaration configured
→ eligible Phase 5 public component
```

An unreviewed AI Engine discovery result therefore cannot automatically become public disclosure output.

#### Planned disclosure domain

```text
AiSystem
→ DisclosureEngine
   ├ eligible? boolean
   ├ deterministic ineligibility reason codes
   └ Disclosure when eligible
→ WordPress adapters
```

The first `Disclosure` model contains only bounded rendering context. Internal interaction-context descriptions, source metadata and timestamps stay outside public markup.

#### Planned administrator surface

**Tools → AI Disclosure** is read-only and requires `manage_options`.

It will present:

```text
Ready
→ exact shortcode

Not ready
→ bounded reason codes/messages
→ link to Tools → AI Transparency edit workflow
```

No mutation nonce is needed merely to view the Disclosure screen. Registry changes continue through the existing protected Registry form.

#### Planned public adapter

The first shortcode contract is:

```text
[kairoseth_ai_disclosure system="SYSTEM_ID"]
```

Processing:

```text
shortcode attribute
→ bounded selector
→ exact site-local registry lookup
→ eligibility re-evaluated server-side
→ no disclosure when missing/ineligible
→ escaped localized notice when eligible
```

The system id in post content is not authority. A page author cannot force an unreviewed/inactive/unconfigured system to render by supplying an id.

The public output intentionally contains only localized notice copy and the administrator-reviewed system name. It does not expose the internal interaction-context description or source/review metadata.

#### No automatic placement

Phase 5 v1 does not:

```text
scan arbitrary content for AI authorship
inject because AI Engine is present
modify third-party chatbot DOM
infer which page contains the interaction
automatically convert a Phase 4 finding into frontend output
```

The administrator chooses the placement explicitly.

#### Cache/theme boundary

The planned output is server-rendered and user-independent, making it suitable for normal page caching. The project does not claim universal third-party cache invalidation or theme compatibility without named acceptance evidence.

Canonical contract: [`PHASE5_DISCLOSURE_IMPLEMENTATION.md`](PHASE5_DISCLOSURE_IMPLEMENTATION.md).  
Acceptance gates: [`PHASE5_ACCEPTANCE.md`](PHASE5_ACCEPTANCE.md).

## WordPress compatibility baseline

- Requires WordPress: 6.6+
- Tested-up-to target: 7.1
- Requires PHP: 7.4+
- Accepted Phase 3/4 AI Engine runtime fixture: AI Engine 3.7.7, which itself requires PHP 8.1+

The plugin remains PHP 7.4 compatible; the AI Engine discovery/readiness runtime fixture runs in the dedicated WordPress 7.1 / PHP 8.3 acceptance lane.

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

Registry, discovery and readiness operations make no automatic external Kairoseth request. Phase 5 preserves this local-first boundary and adds no automatic telemetry, cookies or remote dependency.

Readiness rules operate only on bounded local registry fields. Phase 5 eligibility also operates only on bounded registry fields and must not crawl arbitrary content or inspect AI provider configuration.

## CI / release gates

Current blocking gates for changed contracts remain:

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
├ Discovery → Registry → Readiness findings acceptance
├ Phase 5 Registry → Disclosure Admin → shortcode → anonymous frontend acceptance when implemented
└ Multisite registry isolation
```

Repository-controlled failures use `bin/run-with-diagnostics.sh` and upload `.ci-diagnostics/` evidence.

## Phase status

- Phase 1: closed.
- Phase 2 Persistent AI Systems Registry: closed and verified on `main`.
- Phase 3 Deterministic Discovery: closed and verified on `main`.
- Phase 4 Readiness Findings & Evidence: closed, documented and finally verified on `main` through CI #78.
- Phase 5 Disclosure Tooling: design/acceptance contract active; production implementation not started.
- Later phases: not started.
