# Kairoseth AI Transparency for WordPress

**Plugin:** Kairoseth AI Transparency  
**Current release candidate:** `1.1.0`  
**Public WordPress.org stable before this candidate:** `1.0.3`  
**Canonical slug:** `kairoseth-ai-transparency`  
**WordPress text domain:** `kairoseth-ai-transparency`  
**Author:** Emmake / Kairoseth  
**License:** MIT

[English](#english) · [Español](#español)

---

## English

Kairoseth AI Transparency is a local-first WordPress plugin for maintaining a reviewable inventory of AI systems, deriving deterministic technical readiness evidence, placing explicit AI interaction disclosures, exporting a bounded technical evidence snapshot, and optionally opening a privacy-bounded Kairoseth support/custom-request flow.

### v1.1 admin experience

Version 1.1 groups the complete workflow under a dedicated **Kairoseth AI Transparency** WordPress admin menu instead of scattering visible entries under Tools.

The dashboard provides live summary metrics from the current site-local Registry, Readiness and Disclosure state, workflow cards for the six product areas, and recent Registry activity with direct edit actions.

The visible workflow is:

- **Kairoseth AI Transparency** — overview dashboard and live summary.
- **AI Systems Registry** — add, edit, review and archive systems, with instant local search and filters by type, review state and lifecycle status.
- **AI Discovery** — deterministic supported-integration evidence.
- **AI Readiness** — deterministic technical findings and readiness evidence.
- **AI Disclosure** — administrator-controlled disclosure readiness and public shortcode.
- **AI Evidence Export** — privileged, user-initiated JSON technical evidence download.
- **AI Transparency Support** — optional user-initiated navigation to Kairoseth support/custom requests.

Legacy 1.0.x Tools URLs remain registered for backwards compatibility, but duplicate visible Tools menu entries are removed in 1.1.

### Requirements and identity

| Field | Value |
|---|---|
| Plugin name | **Kairoseth AI Transparency** |
| Canonical slug | `kairoseth-ai-transparency` |
| WordPress text domain | `kairoseth-ai-transparency` |
| Release candidate | `1.1.0` |
| Requires WordPress | 6.6+ |
| Tested up to | 7.1 |
| Requires PHP | 7.4+ |
| Languages | English + Spanish source coverage |
| License | MIT |

### AI Systems Registry

The Registry stores the authoritative site-local technical inventory in WordPress Options using a versioned persistence schema. An authorized administrator can add, edit, review and archive AI system records.

The 1.1 search and filter controls are local presentation helpers. They do not change Registry persistence, perform remote searches or introduce a new data schema.

### AI Discovery

The first validated deterministic detector recognizes **AI Engine 3.7.7** from bounded WordPress plugin identity evidence.

Discovery does not inspect AI provider credentials, provider/model settings, prompts, conversations or AI Engine internal configuration. Supported discovery results require explicit administrator acceptance and enter the Registry as pending review.

### AI Readiness

Readiness findings are derived from the current Registry and keep three concepts separate:

- **Fact** — a technical condition observed in current local state.
- **Administrator declaration** — an explicit administrator state when relevant.
- **Guidance** — a technical review or completion action.

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

Evidence Export v1 guarantees:

- `export_schema_version = 1`;
- UTC `generated_at`;
- a deterministic SHA-256 `snapshot_signature` excluding generation time;
- unchanged technical state produces the same signature across export times;
- meaningful exported Registry changes change the signature;
- archived Registry records are retained;
- an empty Registry is valid;
- site-local Multisite isolation;
- no Media Library persistence, export history, email, telemetry, Kairoseth upload or cloud-account requirement;
- no arbitrary WordPress/plugin option dump or automatic export of credentials, tokens, cookies, nonces, user identities, prompts, conversations, logs or database dumps.

The privileged export may contain administrator-authored `interaction_context`, so downloaded evidence can include confidential operational context and should be handled accordingly.

### Local-first and privacy boundary

Registry, Discovery, Readiness, Disclosure and Evidence Export operate locally and do not automatically upload their state to Kairoseth or another provider.

The optional support page performs no Kairoseth request when the WordPress page loads. Network interaction begins only after an administrator explicitly follows a support/custom-request action to:

```text
https://kairoseth.com/custom-requests
```

The handoff contains only bounded product/platform context such as plugin version, WordPress version, locale and administrator-selected request type. It does **not** automatically attach the site URL, administrator identity, Registry contents, AI-system names, interaction context, evidence, credentials, prompts, conversations, logs or database contents.

### Legal boundary

Kairoseth AI Transparency provides technical readiness, workflow, disclosure and evidence tooling. It does **not** certify or guarantee compliance with the EU AI Act or any other law.

The Evidence Export `snapshot_signature` is a technical snapshot identity, not a legal/digital signature, trusted timestamp, non-repudiation proof or regulatory certification.

The plugin does not perform generic probabilistic detection of whether arbitrary content was written by AI.

### Installation

1. Install the packaged plugin ZIP or plugin directory.
2. Activate **Kairoseth AI Transparency**.
3. Open **Kairoseth AI Transparency** for the dashboard.
4. Use **AI Systems Registry** to maintain, search and filter the local inventory.
5. Use **AI Discovery**, **AI Readiness**, **AI Disclosure** and **AI Evidence Export** as needed.
6. Optionally use **AI Transparency Support** for an explicit privacy-bounded handoff to Kairoseth.

### Development and validation

```bash
composer install
composer verify
bash bin/build-plugin.sh
bash bin/build-release.sh
```

The release matrix blocks on WordPress Coding Standards, PHPUnit, PHPCompatibility, PHP 7.4/8.1/8.3/8.5 syntax, EN/ES source coverage, official WordPress Plugin Check, real WordPress runtime acceptance, browser CRUD/discovery/permissions/responsive/accessibility checks, Multisite isolation, upgrade/lifecycle acceptance and reproducible release packaging.

The v1.1 branch additionally covers the dedicated dashboard, unified menu, duplicate Tools-menu removal, Registry search/filter UX, shared responsive styling and accessibility behavior in browser acceptance.

### Roadmap

The 1.1 release is intentionally a low-risk UX/i18n release built on the stable 1.0.x data model.

Planned 1.2 work remains separate and includes broader detector architecture, richer structured Registry evidence, expanded deterministic readiness, disclosure templates/preview, Evidence Export v2 and validated Registry migration/import-export.

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

### Experiencia de administración v1.1

La versión 1.1 agrupa todo el flujo bajo un menú propio **Kairoseth AI Transparency** en WordPress, en lugar de dispersar las entradas visibles dentro de Herramientas.

El dashboard muestra métricas reales del Registry local, Readiness y Disclosure, accesos a las seis áreas del producto y actividad reciente del Registry con acceso directo a edición.

El flujo visible queda así:

- **Kairoseth AI Transparency** — dashboard y resumen técnico actual.
- **AI Systems Registry** — alta, edición, revisión y archivo, con búsqueda local instantánea y filtros por tipo, revisión y estado.
- **AI Discovery** — evidencia determinista de integraciones soportadas.
- **AI Readiness** — hallazgos técnicos y evidencia de preparación.
- **AI Disclosure** — preparación de avisos controlada por el administrador y shortcode público.
- **AI Evidence Export** — descarga JSON privilegiada e iniciada explícitamente por el usuario.
- **AI Transparency Support** — navegación opcional hacia soporte/solicitudes personalizadas de Kairoseth.

Las URLs heredadas de Herramientas de la línea 1.0.x siguen registradas por compatibilidad, pero la versión 1.1 elimina sus entradas visibles duplicadas.

### Requisitos e identidad

| Campo | Valor |
|---|---|
| Nombre | **Kairoseth AI Transparency** |
| Slug canónico | `kairoseth-ai-transparency` |
| Text domain | `kairoseth-ai-transparency` |
| Candidato de release | `1.1.0` |
| Requiere WordPress | 6.6+ |
| Probado hasta | 7.1 |
| Requiere PHP | 7.4+ |
| Idiomas | cobertura fuente inglés + español |
| Licencia | MIT |

### AI Systems Registry

El Registry conserva el inventario técnico autoritativo del sitio mediante WordPress Options y un esquema de persistencia versionado. Un administrador autorizado puede añadir, editar, revisar y archivar sistemas de IA.

La búsqueda y los filtros de 1.1 son ayudas locales de presentación: no cambian la persistencia, no realizan búsquedas remotas y no introducen un nuevo esquema de datos.

### AI Discovery

El primer detector determinista validado reconoce **AI Engine 3.7.7** mediante evidencia acotada del inventario de plugins de WordPress.

Discovery no inspecciona credenciales de proveedores IA, configuración de proveedor/modelo, prompts, conversaciones ni configuración interna de AI Engine. Los resultados soportados requieren aceptación explícita del administrador y entran al Registry como pendientes de revisión.

### AI Readiness

Readiness deriva hallazgos del Registry actual y mantiene separados:

- **Hecho** — condición técnica observada en el estado local.
- **Declaración del administrador** — estado explícito del administrador cuando corresponda.
- **Orientación** — acción técnica de revisión o completado.

El plugin no convierte esos hallazgos en afirmaciones de cumplimiento legal.

### AI Disclosure

El aviso público solo se genera desde estado autoritativo server-side del Registry.

```text
[kairoseth_ai_disclosure system="SYSTEM_ID"]
```

El sistema debe estar activo, revisado, configurado como requiriendo aviso de interacción y disponer de contexto de interacción. El parámetro del shortcode solo selecciona el registro; no concede elegibilidad.

### AI Evidence Export

Evidence Export genera, tras una acción explícita del administrador, un JSON del sitio actual con Registry, referencias Discovery persistidas válidas, findings Readiness y preparación Disclosure.

`export_schema_version = 1` se mantiene en 1.1, al igual que la firma técnica determinista `snapshot_signature`. La actualización 1.1 no modifica el esquema de Evidence Export ni el esquema de persistencia del Registry.

El archivo puede contener `interaction_context` escrito por el administrador y debe tratarse como posible contexto operativo confidencial.

### Local-first y privacidad

Registry, Discovery, Readiness, Disclosure y Evidence Export funcionan localmente y no suben automáticamente su estado a Kairoseth ni a otro proveedor.

La página de soporte tampoco contacta Kairoseth al cargarse. La interacción de red comienza únicamente cuando un administrador activa deliberadamente una acción hacia:

```text
https://kairoseth.com/custom-requests
```

El handoff contiene únicamente contexto acotado de producto/plataforma. No adjunta automáticamente URL del sitio, identidad del administrador, Registry, nombres de sistemas IA, contexto, evidencia, credenciales, prompts, conversaciones, logs ni contenido de base de datos.

### Límite legal

Kairoseth AI Transparency proporciona herramientas técnicas de preparación, flujo, disclosure y evidencia. **No certifica ni garantiza cumplimiento legal** del Reglamento de IA de la UE ni de otra norma.

La `snapshot_signature` de Evidence Export identifica técnicamente un snapshot; no es una firma legal/digital, sello de tiempo confiable, prueba de no repudio ni certificación regulatoria.

El plugin tampoco intenta detectar probabilísticamente si contenido arbitrario fue escrito por IA.

### Instalación

1. Instala el ZIP del plugin o su directorio.
2. Activa **Kairoseth AI Transparency**.
3. Abre **Kairoseth AI Transparency** para ver el dashboard.
4. Usa **AI Systems Registry** para mantener, buscar y filtrar el inventario local.
5. Usa **AI Discovery**, **AI Readiness**, **AI Disclosure** y **AI Evidence Export** según sea necesario.
6. Opcionalmente usa **AI Transparency Support** para abrir deliberadamente el soporte de Kairoseth.

### Desarrollo y validación

```bash
composer install
composer verify
bash bin/build-plugin.sh
bash bin/build-release.sh
```

La matriz bloqueante cubre WordPress Coding Standards, PHPUnit, PHPCompatibility, sintaxis PHP 7.4/8.1/8.3/8.5, cobertura fuente EN/ES, WordPress Plugin Check oficial, runtime WordPress real, CRUD/discovery/permisos/responsive/accesibilidad en navegador, aislamiento Multisite, upgrades/lifecycle y empaquetado reproducible.

La rama 1.1 añade aceptación específica para dashboard, menú unificado, eliminación de duplicados en Herramientas, búsqueda/filtros del Registry, responsive y accesibilidad.

### Roadmap

1.1 se mantiene deliberadamente como una actualización UX/i18n de bajo riesgo sobre el modelo de datos estable de 1.0.x.

Para 1.2 quedan separados: más detectores, evidencia estructurada más rica en Registry, Readiness ampliado, plantillas/preview de Disclosure, Evidence Export v2 e importación/exportación validada del Registry.

### Documentación

- [`CHANGELOG.md`](CHANGELOG.md)
- [`docs/ROADMAP.md`](docs/ROADMAP.md)
- [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md)
- [`docs/BILINGUAL_EN_ES_POLICY.md`](docs/BILINGUAL_EN_ES_POLICY.md)
- [`SECURITY.md`](SECURITY.md)
- [`CONTRIBUTING.md`](CONTRIBUTING.md)
