# AI Transparency for WordPress

**Plugin:** Kairoseth AI Transparency  
**Status:** pre-release development

[English](#english) · [Español](#español)

---

## English

Kairoseth AI Transparency is a local-first WordPress plugin for maintaining a reviewable inventory of AI systems, deriving deterministic technical readiness evidence, placing explicit AI interaction disclosures and exporting a bounded technical evidence snapshot.

### Plugin identity

| Field | Value |
|---|---|
| Plugin name | **Kairoseth AI Transparency** |
| Technical slug | `ai-transparency` |
| WordPress text domain | `ai-transparency` |
| WordPress.org target slug | `ai-transparency` |
| Requires WordPress | 6.6+ |
| Tested-up-to target | 7.1 |
| Requires PHP | 7.4+ |
| Languages | English + Spanish |
| License | MIT |

### Current accepted functionality

The current development line contains five accepted workflows:

- **AI Systems Registry** — **Tools → AI Transparency**;
- **AI Discovery** — deterministic supported integration evidence under **Tools → AI Discovery**;
- **AI Readiness** — deterministic technical findings under **Tools → AI Readiness**;
- **AI Disclosure** — administrator-controlled disclosure readiness plus the public shortcode;
- **AI Evidence Export** — privileged, user-initiated JSON technical evidence download under **Tools → AI Evidence Export**.

An authorized administrator can add, edit, review and archive AI system records; review supported discovery evidence; inspect deterministic readiness findings; configure disclosure state; copy a public disclosure shortcode for eligible systems; and download the complete current site-local evidence snapshot.

Public disclosure uses:

```text
[kairoseth_ai_disclosure system="SYSTEM_ID"]
```

### Evidence Export — Phase 6 accepted

Phase 6 is **closed and verified on `main`**.

Accepted runtime flow:

```text
WordPress administrator
→ Tools → AI Evidence Export
→ explicit POST
→ manage_options + nonce
→ current site-local Registry loaded server-side
→ RegistrySchema + persisted Discovery references
→ FindingEngine
→ DisclosureEngine
→ canonical allow-list payload
→ SHA-256 snapshot_signature
→ readable JSON
→ direct attachment download
```

Key guarantees:

- `export_schema_version = 1`;
- complete current site-local Registry is exported, including archived records;
- empty Registry remains a valid signed export;
- `generated_at` is UTC metadata and is excluded from stable snapshot identity;
- unchanged technical state produces the same `snapshot_signature` even when generated later;
- meaningful exported Registry changes produce a different signature;
- Registry systems, Discovery references, findings and disclosure-readiness entries use deterministic ordering;
- Phase 4 `FindingEngine` and Phase 5 `DisclosureEngine` are reused rather than duplicated;
- `interaction_context` is included only in the privileged administrative artifact and the UI warns that the file may contain confidential operational context;
- credentials, tokens, cookies, nonces, user identity, prompts, conversations, logs, database dumps and arbitrary plugin options are outside the export allow-list;
- the plugin does not persist the generated file, create export history, email it, upload it to Kairoseth, create telemetry or require a cloud account;
- Multisite export is scoped to the authoritative current blog/site only;
- `snapshot_signature` is a technical snapshot identity, **not** a legal/digital signature, trusted timestamp, non-repudiation proof or compliance certification.

### Registry and disclosure authority

Registry data is stored locally using the WordPress Options API with a versioned schema. In Multisite, storage follows the current blog/site context rather than creating a network-wide inventory.

A public disclosure can render only when current server-side Registry state satisfies:

```text
status = active
review_status = reviewed
interaction_disclosure_required = true
interaction_context is not empty
```

The shortcode `system` value is only a lookup selector; it cannot grant eligibility. Public output is limited to localized copy and the reviewed system name. Internal Registry context, source metadata, credentials, prompts, conversations and private logs are not automatically exposed.

### Deterministic Discovery and Readiness

The first validated discovery detector supports **AI Engine 3.7.7** from bounded WordPress plugin identity evidence. It does not infer provider, model, chatbot, prompt or workflow configuration and does not inspect provider credentials.

Readiness preserves:

```text
FACT
ADMINISTRATOR DECLARATION
GUIDANCE
```

Findings are derived on demand from current Registry state and are technical review signals, not legal decisions.

### Privacy and legal boundary

Registry, Discovery, Readiness, Disclosure and Evidence Export do not automatically send their state to an external service. Core state remains inside the WordPress installation.

This plugin provides technical readiness, workflow and evidence tooling. It does **not** certify or guarantee legal compliance with the EU AI Act or any other law, and it does not attempt generic probabilistic detection of whether arbitrary text was written by AI.

### Installation

1. Upload the plugin directory to `/wp-content/plugins/` or install the generated ZIP.
2. Activate **Kairoseth AI Transparency**.
3. Open **Tools → AI Transparency** for the Registry.
4. Open **Tools → AI Discovery** for supported deterministic discovery evidence.
5. Open **Tools → AI Readiness** for technical findings.
6. Open **Tools → AI Disclosure** for disclosure readiness and shortcode placement.
7. Open **Tools → AI Evidence Export** to download the current site-local JSON evidence snapshot.

### Development and validation

```bash
composer install
composer verify
bash bin/build-plugin.sh
```

The production package is generated at `build/ai-transparency/`.

Blocking validation includes WordPress Coding Standards, PHPUnit, PHPCompatibility 7.4+, PHP 7.4/8.1/8.3/8.5 syntax, EN/ES 100%, compiled Spanish gettext, official WordPress Plugin Check, real WordPress activation/migration/CRUD/permissions, responsive/accessibility browser acceptance, deterministic Discovery/Readiness/Disclosure behavior and real Multisite isolation.

Phase 6 implementation evidence:

```text
Contract PR: #14
Implementation PR: #15
Accepted PR head: 2b9ebe93820e98d9ce6e0abb4deb235fdeeda57c
PR-head CI: #91 / 34402108452 — 8/8 green
Implementation merge: bd07261751471fe7866e62049e3a66b7bd767afe
Post-merge main CI: #92 / 34402685906 — 8/8 green
Blockers: 0
```

### Current roadmap state

```text
Phase 1 Repository bootstrap                 CLOSED
Phase 2 Persistent AI Systems Registry       CLOSED
Phase 3 Deterministic Discovery              CLOSED
Phase 4 Readiness Findings & Evidence        CLOSED
Phase 5 Disclosure Tooling                   CLOSED
Phase 6 Evidence Export                      CLOSED
Phase 7 Contextual support/custom path       NOT STARTED
Phase 8 First public release                 NOT STARTED
```

### Project documentation

- [`docs/ROADMAP.md`](docs/ROADMAP.md)
- [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md)
- [`docs/PHASE5_DISCLOSURE_IMPLEMENTATION.md`](docs/PHASE5_DISCLOSURE_IMPLEMENTATION.md)
- [`docs/PHASE5_ACCEPTANCE.md`](docs/PHASE5_ACCEPTANCE.md)
- [`docs/PHASE5_RUNTIME_EVIDENCE.md`](docs/PHASE5_RUNTIME_EVIDENCE.md)
- [`docs/PHASE6_EVIDENCE_EXPORT_IMPLEMENTATION.md`](docs/PHASE6_EVIDENCE_EXPORT_IMPLEMENTATION.md)
- [`docs/PHASE6_ACCEPTANCE.md`](docs/PHASE6_ACCEPTANCE.md)
- [`docs/PHASE6_JSON_SCHEMA_V1.md`](docs/PHASE6_JSON_SCHEMA_V1.md)
- [`docs/PHASE6_RUNTIME_EVIDENCE.md`](docs/PHASE6_RUNTIME_EVIDENCE.md)
- [`docs/BILINGUAL_EN_ES_POLICY.md`](docs/BILINGUAL_EN_ES_POLICY.md)
- [`SECURITY.md`](SECURITY.md)
- [`CONTRIBUTING.md`](CONTRIBUTING.md)

---

## Español

Kairoseth AI Transparency es un plugin local-first para WordPress orientado a mantener un inventario revisable de sistemas de IA, derivar evidencia técnica determinista, colocar avisos explícitos de interacción con IA y exportar un snapshot técnico acotado.

### Identidad del plugin

| Campo | Valor |
|---|---|
| Nombre | **Kairoseth AI Transparency** |
| Slug técnico | `ai-transparency` |
| Text domain | `ai-transparency` |
| Slug objetivo WordPress.org | `ai-transparency` |
| Requiere WordPress | 6.6+ |
| Tested-up-to target | 7.1 |
| Requiere PHP | 7.4+ |
| Idiomas | inglés + español |
| Licencia | MIT |

### Funcionalidad aceptada actual

La línea actual incluye cinco flujos aceptados:

- **AI Systems Registry** — **Herramientas → AI Transparency**;
- **AI Discovery** — evidencia determinista de integraciones soportadas;
- **AI Readiness** — hallazgos técnicos deterministas;
- **AI Disclosure** — readiness controlado por administrador y shortcode público;
- **AI Evidence Export** — descarga JSON privilegiada e iniciada explícitamente por el usuario.

El shortcode público aceptado es:

```text
[kairoseth_ai_disclosure system="SYSTEM_ID"]
```

### Evidence Export — Fase 6 aceptada

La Fase 6 está **cerrada y verificada en `main`**.

Flujo aceptado:

```text
administrador WordPress
→ Herramientas → AI Evidence Export
→ POST explícito
→ manage_options + nonce
→ Registry local del sitio cargado server-side
→ RegistrySchema + referencias Discovery persistidas
→ FindingEngine
→ DisclosureEngine
→ payload canónico por allow-list
→ snapshot_signature SHA-256
→ JSON legible
→ descarga directa
```

Garantías principales:

- `export_schema_version = 1`;
- export del Registry completo del sitio actual, incluidos archivados;
- Registry vacío sigue generando un export válido y firmado;
- `generated_at` es metadata UTC y no forma parte de la identidad estable;
- mismo estado técnico produce la misma `snapshot_signature` aunque se genere después;
- un cambio técnico relevante modifica la firma;
- orden determinista de sistemas, referencias Discovery, findings y readiness;
- reutilización de `FindingEngine` y `DisclosureEngine`;
- `interaction_context` solo aparece en el artefacto administrativo privilegiado y se advierte que puede contener contexto operativo confidencial;
- credenciales, tokens, cookies, nonces, identidad de usuario, prompts, conversaciones, logs, dumps de BD y options arbitrarias quedan fuera de la allow-list;
- sin persistencia del fichero, historial, email, telemetría, upload a Kairoseth ni cuenta cloud;
- aislamiento Multisite por blog/sitio actual;
- la firma es identidad técnica del snapshot, **no** firma legal/digital, sellado de tiempo ni certificación.

### Autoridad, privacidad y límites

El Registry usa WordPress Options con schema versionado y alcance site-local. Un aviso público solo puede renderizarse desde estado server-side revisado y elegible; el navegador no concede permisos ni elegibilidad.

Discovery soporta inicialmente **AI Engine 3.7.7** mediante evidencia acotada de identidad WordPress. Readiness mantiene separados Hecho, Declaración del administrador y Orientación.

Registry, Discovery, Readiness, Disclosure y Evidence Export no envían automáticamente su estado a servicios externos. El plugin ofrece tooling técnico y evidencia; **no certifica cumplimiento legal** del Reglamento de IA de la UE ni de ninguna otra norma.

### Instalación

1. Instala el directorio o ZIP del plugin.
2. Activa **Kairoseth AI Transparency**.
3. Usa **Herramientas → AI Transparency** para el Registry.
4. Usa **Herramientas → AI Discovery** para Discovery.
5. Usa **Herramientas → AI Readiness** para findings.
6. Usa **Herramientas → AI Disclosure** para disclosure.
7. Usa **Herramientas → AI Evidence Export** para descargar el snapshot JSON local.

### Evidencia de Fase 6

```text
PR de contrato: #14
PR de implementación: #15
Head aceptado: 2b9ebe93820e98d9ce6e0abb4deb235fdeeda57c
CI del PR: #91 / 34402108452 — 8/8 verde
Merge: bd07261751471fe7866e62049e3a66b7bd767afe
CI post-merge main: #92 / 34402685906 — 8/8 verde
Bloqueadores: 0
```

### Estado del roadmap

```text
Fase 1 Repository bootstrap                 CLOSED
Fase 2 Persistent AI Systems Registry       CLOSED
Fase 3 Deterministic Discovery              CLOSED
Fase 4 Readiness Findings & Evidence        CLOSED
Fase 5 Disclosure Tooling                   CLOSED
Fase 6 Evidence Export                      CLOSED
Fase 7 Contextual support/custom path       NO INICIADA
Fase 8 First public release                 NO INICIADA
```

### Documentación

- [`docs/ROADMAP.md`](docs/ROADMAP.md)
- [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md)
- [`docs/PHASE6_EVIDENCE_EXPORT_IMPLEMENTATION.md`](docs/PHASE6_EVIDENCE_EXPORT_IMPLEMENTATION.md)
- [`docs/PHASE6_ACCEPTANCE.md`](docs/PHASE6_ACCEPTANCE.md)
- [`docs/PHASE6_JSON_SCHEMA_V1.md`](docs/PHASE6_JSON_SCHEMA_V1.md)
- [`docs/PHASE6_RUNTIME_EVIDENCE.md`](docs/PHASE6_RUNTIME_EVIDENCE.md)
- [`SECURITY.md`](SECURITY.md)
- [`CONTRIBUTING.md`](CONTRIBUTING.md)
