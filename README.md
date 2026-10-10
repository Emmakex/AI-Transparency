# Kairoseth AI Transparency for WordPress

**Plugin:** Kairoseth AI Transparency  
**Current release candidate:** `1.2.0`  
**Current public stable:** `1.1.0`  
**Canonical slug:** `kairoseth-ai-transparency`  
**WordPress text domain:** `kairoseth-ai-transparency`  
**Author:** Emmake / Kairoseth  
**License:** MIT

[English](#english) · [Español](#español)

---

## English

Kairoseth AI Transparency is a local-first WordPress plugin for maintaining a reviewable inventory of AI systems, deriving deterministic technical readiness evidence, placing explicit AI interaction disclosures, exporting a bounded technical evidence snapshot, and optionally opening a privacy-bounded Kairoseth support/custom-request flow.

### v1.2 product scope

Version 1.2 builds on the unified v1.1 admin experience and expands the product in three areas:

- **extensible deterministic Discovery** with multiple independently validated detectors;
- **Registry schema v2** with explicit administrator-authored context;
- **richer deterministic Readiness** for incomplete reviewed Registry context.

The visible workflow remains:

- **Kairoseth AI Transparency** — overview dashboard and live summary.
- **AI Systems Registry** — add, edit, review and archive systems, with instant local search and filters.
- **AI Discovery** — deterministic supported-integration evidence.
- **AI Readiness** — deterministic technical findings and readiness evidence.
- **AI Disclosure** — administrator-controlled disclosure readiness and public shortcode.
- **AI Evidence Export** — privileged, user-initiated JSON technical evidence download.
- **AI Transparency Support** — optional user-initiated navigation to Kairoseth support/custom requests.

Legacy 1.0.x Tools URLs remain registered for backwards compatibility while the visible workflow stays grouped under the dedicated Kairoseth AI Transparency menu.

### Requirements and identity

| Field | Value |
|---|---|
| Plugin name | **Kairoseth AI Transparency** |
| Canonical slug | `kairoseth-ai-transparency` |
| WordPress text domain | `kairoseth-ai-transparency` |
| Release candidate | `1.2.0` |
| Requires WordPress | 6.6+ |
| Tested up to | 7.1 |
| Requires PHP | 7.4+ |
| Languages | English + Spanish source coverage |
| License | MIT |

### AI Systems Registry v2

The Registry stores the authoritative site-local technical inventory in WordPress Options using a versioned persistence schema. An authorized administrator can add, edit, review and archive AI system records.

Version 1.2 introduces Registry schema v2 with six optional administrator-authored context fields:

- purpose;
- provider;
- model or model family;
- audience;
- human oversight;
- data-use context.

Existing Registry state migrates on first access. Existing lifecycle, review, interaction-context and disclosure state is preserved. The six new fields default to empty values and are never inferred automatically from Discovery evidence.

### AI Discovery

Discovery now uses an extensible detector contract and a server-authoritative multi-result engine.

The validated v1.2 detector boundaries are:

- **AI Engine 3.7.7**;
- **AI Puffer 2.4.97** (`gpt3-ai-content-generator/gpt3-ai-content-generator.php`).

Discovery reads bounded WordPress plugin identity, version and activation state. It does not inspect provider credentials, prompts, conversations or private provider configuration. Browser selectors are revalidated against freshly generated server-side detector output before a result can be accepted into the Registry.

A supported result requires explicit administrator acceptance and enters the Registry as pending review.

### AI Readiness

Readiness findings are derived from the current Registry and keep three concepts separate:

- **Fact** — a technical condition observed in current local state.
- **Administrator declaration** — an explicit administrator state when relevant.
- **Guidance** — a technical review or completion action.

Version 1.2 adds the deterministic rule `administrator_context_incomplete_v1`. It is emitted only for active records that have already been reviewed and still have one or more of the six administrator-context fields undocumented. Pending discovered records are not given this extra completeness finding, which avoids duplicate review noise.

The plugin does not turn these findings into legal-compliance claims.

### AI Disclosure

Public disclosure renders only from server-authoritative reviewed Registry state.

```text
[kairoseth_ai_disclosure system="SYSTEM_ID"]
```

A disclosure can render only when the selected system is active, reviewed, configured as requiring interaction disclosure and has non-empty interaction context. The shortcode value is a lookup selector; it does not grant eligibility.

Public output is intentionally bounded to localized disclosure copy and the reviewed system name. Internal context, credentials, prompts, conversations and private logs are not automatically exposed.

### AI Evidence Export

Evidence Export creates an explicit administrator-generated JSON attachment for the current site only. The export includes the complete site-local Registry, normalized persisted Discovery references where structurally valid, current deterministic Readiness findings and Disclosure readiness.

Evidence Export keeps `export_schema_version = 1`, while the nested Registry payload is versioned independently and is now schema v2.

The deterministic SHA-256 `snapshot_signature` excludes generation time. Equivalent technical state produces the same signature, while meaningful Registry changes — including administrator-context changes — change the snapshot identity.

The generated file is not persisted, emailed or uploaded automatically. Because it can contain administrator-authored operational context, it should be handled as potentially confidential business information.

### Local-first and privacy boundary

Registry, Discovery, Readiness, Disclosure and Evidence Export operate locally and do not automatically upload their state to Kairoseth or another provider.

The optional support page performs no Kairoseth request when the WordPress page loads. Network interaction begins only after an administrator explicitly follows a support/custom-request action to:

```text
https://kairoseth.com/custom-requests
```

The handoff contains only bounded product/platform context. It does **not** automatically attach the site URL, administrator identity, Registry contents, AI-system names, administrator declarations, evidence, credentials, prompts, conversations, logs or database contents.

### Legal boundary

Kairoseth AI Transparency provides technical readiness, workflow, disclosure and evidence tooling. It does **not** certify or guarantee compliance with the EU AI Act or any other law.

The Evidence Export `snapshot_signature` is a technical snapshot identity, not a legal/digital signature, trusted timestamp, non-repudiation proof or regulatory certification.

The plugin does not perform generic probabilistic detection of whether arbitrary content was written by AI.

### Development and validation

```bash
composer install
composer verify
bash bin/build-plugin.sh
bash bin/build-release.sh
```

The release matrix blocks on WordPress Coding Standards, PHPUnit, PHPCompatibility, PHP 7.4/8.1/8.3/8.5 syntax, EN/ES source coverage, official WordPress Plugin Check, real WordPress runtime acceptance, browser CRUD/discovery/permissions/responsive/accessibility checks, Multisite isolation, Registry migration, upgrade/lifecycle acceptance and reproducible release packaging.

Version 1.2 additionally covers multi-detector selection/tamper resistance, real AI Engine + AI Puffer fixtures, Registry schema v2 migration, administrator-context persistence, readiness completeness guidance and exact-ZIP preservation across upgrade/deactivation/reactivation/uninstall/fresh-install flows.

### Documentation

- [`CHANGELOG.md`](CHANGELOG.md)
- [`docs/ROADMAP.md`](docs/ROADMAP.md)
- [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md)
- [`docs/BILINGUAL_EN_ES_POLICY.md`](docs/BILINGUAL_EN_ES_POLICY.md)
- [`SECURITY.md`](SECURITY.md)
- [`CONTRIBUTING.md`](CONTRIBUTING.md)

---

## Español

Kairoseth AI Transparency es un plugin local-first para WordPress orientado a mantener un inventario revisable de sistemas de IA, derivar evidencia técnica determinista, colocar avisos explícitos de interacción con IA, exportar un snapshot técnico acotado y, opcionalmente, abrir un flujo de soporte/solicitud personalizada de Kairoseth con contexto mínimo.

### Alcance de v1.2

La versión 1.2 mantiene la experiencia de administración unificada de v1.1 y amplía el producto en tres áreas:

- **Discovery determinista extensible**, preparado para varios detectores validados de forma independiente;
- **Registry schema v2**, con contexto declarado explícitamente por el administrador;
- **Readiness ampliado**, capaz de detectar contexto administrativo incompleto en registros ya revisados.

El flujo visible sigue agrupado bajo **Kairoseth AI Transparency**: Dashboard, AI Systems Registry, AI Discovery, AI Readiness, AI Disclosure, AI Evidence Export y AI Transparency Support.

### Requisitos e identidad

| Campo | Valor |
|---|---|
| Nombre | **Kairoseth AI Transparency** |
| Slug canónico | `kairoseth-ai-transparency` |
| Text domain | `kairoseth-ai-transparency` |
| Candidato de release | `1.2.0` |
| Requiere WordPress | 6.6+ |
| Probado hasta | 7.1 |
| Requiere PHP | 7.4+ |
| Idiomas | cobertura fuente inglés + español |
| Licencia | MIT |

### AI Systems Registry v2

El Registry conserva el inventario técnico autoritativo del sitio mediante WordPress Options y un esquema de persistencia versionado.

La versión 1.2 añade seis campos opcionales escritos por el administrador:

- finalidad;
- proveedor;
- modelo o familia de modelos;
- audiencia;
- supervisión humana;
- contexto de uso de datos.

Los registros anteriores migran automáticamente al esquema v2 cuando el repositorio los carga. Se mantienen estado, revisión, contexto de interacción y configuración de disclosure. Los nuevos campos empiezan vacíos y **Discovery no los rellena ni los infiere**.

### AI Discovery

Discovery utiliza ahora una arquitectura extensible de detectores y un motor multiresultado validado en servidor.

Los detectores validados en v1.2 son:

- **AI Engine 3.7.7**;
- **AI Puffer 2.4.97** (`gpt3-ai-content-generator/gpt3-ai-content-generator.php`).

Discovery solo utiliza evidencia acotada de identidad, versión y estado de activación del plugin. No inspecciona credenciales, prompts, conversaciones ni configuración privada del proveedor. Cualquier selección enviada por el navegador se revalida contra el inventario actual y la salida recién regenerada de los detectores.

### AI Readiness

Readiness mantiene separados:

- **Hecho** — condición técnica observada.
- **Declaración del administrador** — estado explícito cuando corresponde.
- **Orientación** — acción técnica de revisión o completado.

La versión 1.2 añade la regla determinista `administrator_context_incomplete_v1`. Solo se aplica a registros activos ya revisados que mantienen uno o más campos de contexto administrativo sin documentar. No se usa para convertir la ausencia de datos en una conclusión legal ni para inferir declaraciones.

### AI Disclosure

El aviso público solo se genera desde estado autoritativo server-side del Registry.

```text
[kairoseth_ai_disclosure system="SYSTEM_ID"]
```

El sistema debe estar activo, revisado, configurado como requiriendo aviso de interacción y disponer de contexto de interacción.

### AI Evidence Export

Evidence Export mantiene `export_schema_version = 1`, pero el Registry anidado usa ahora schema v2.

La `snapshot_signature` SHA-256 sigue siendo determinista y excluye la hora de generación. Si cambia de forma significativa el Registry —incluido el contexto administrativo— cambia también la identidad del snapshot.

El archivo se genera solo tras una acción explícita del administrador y no se persiste, envía por correo ni sube automáticamente. Puede contener contexto operativo confidencial y debe tratarse en consecuencia.

### Local-first y privacidad

Registry, Discovery, Readiness, Disclosure y Evidence Export funcionan localmente y no suben automáticamente su estado a Kairoseth ni a otro proveedor.

La página de soporte tampoco contacta Kairoseth al cargarse. La interacción de red empieza únicamente cuando un administrador decide abrir deliberadamente una acción hacia `https://kairoseth.com/custom-requests`.

### Límite legal

Kairoseth AI Transparency proporciona herramientas técnicas de preparación, flujo, disclosure y evidencia. **No certifica ni garantiza cumplimiento legal** del Reglamento de IA de la UE ni de otra norma.

La `snapshot_signature` identifica técnicamente un snapshot; no es una firma legal/digital, sello de tiempo confiable, prueba de no repudio ni certificación regulatoria.

### Desarrollo y validación

La matriz bloqueante cubre estándares WordPress, PHPUnit, PHPCompatibility, PHP 7.4/8.1/8.3/8.5, cobertura EN/ES, WordPress Plugin Check oficial, WordPress real, CRUD/Discovery/permisos/responsive/accesibilidad, aislamiento Multisite, migración de Registry, lifecycle del ZIP y empaquetado reproducible.

Para v1.2 se añaden específicamente pruebas de multi-detector, resistencia a manipulación de selectores, fixtures reales de AI Engine + AI Puffer, persistencia del contexto del administrador, migración schema v2 y Readiness por contexto incompleto.

### Documentación

- [`CHANGELOG.md`](CHANGELOG.md)
- [`docs/ROADMAP.md`](docs/ROADMAP.md)
- [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md)
- [`docs/BILINGUAL_EN_ES_POLICY.md`](docs/BILINGUAL_EN_ES_POLICY.md)
- [`SECURITY.md`](SECURITY.md)
- [`CONTRIBUTING.md`](CONTRIBUTING.md)
