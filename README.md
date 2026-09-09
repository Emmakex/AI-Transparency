# AI Transparency for WordPress

**Plugin:** Kairoseth AI Transparency  
**Status:** pre-release development

[English](#english) · [Español](#español)

---

## English

Kairoseth AI Transparency is a WordPress plugin for maintaining a reviewable technical inventory of AI systems used on a website and supporting evidence-backed transparency workflows.

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

### Current functionality

The current development line includes four accepted workflows:

- local **AI Systems Registry** under **Tools > AI Transparency**;
- deterministic **AI Discovery** under **Tools > AI Discovery**;
- deterministic technical **AI Readiness** findings under **Tools > AI Readiness**;
- explicit administrator-controlled **AI Disclosure** tooling under **Tools > AI Disclosure**.

An authorized administrator can:

- add, edit, review and archive AI system records;
- record system type and interaction context;
- configure whether a reviewed workflow requires an AI interaction disclosure;
- detect a supported active AI integration from explainable WordPress evidence;
- explicitly add a supported discovery result to the Registry for manual review;
- review reproducible technical Readiness findings generated from current Registry state;
- see whether a Registry system is technically ready for disclosure placement;
- place an accepted public disclosure manually with the shortcode:

```text
[kairoseth_ai_disclosure system="SYSTEM_ID"]
```

### Registry and disclosure authority

Registry data is stored locally using the WordPress Options API with a versioned schema. In Multisite, storage follows the current site/blog context rather than creating a network-wide registry.

A system can render the accepted disclosure only when current server-side Registry state satisfies all of these conditions:

```text
status = active
review_status = reviewed
interaction_disclosure_required = true
interaction_context is not empty
```

The shortcode `system` value is only a lookup selector. It cannot make an ineligible record render a public notice.

Pending, archived, missing-context and disclosure-disabled systems produce no public disclosure markup.

### Public disclosure boundary

The accepted frontend notice is server-rendered, accessible and has no JavaScript dependency.

Public output is intentionally limited to:

- localized disclosure copy;
- the administrator-reviewed AI system name.

It does **not** automatically expose the internal interaction context, Registry id, source metadata, timestamps, provider/model configuration, credentials, prompts, conversations or private logs.

The first accepted placement is normal singular WordPress post/page content. The plugin does not automatically inject notices into third-party chatbot DOM or arbitrary templates.

### Deterministic discovery

The first validated discovery detector supports **AI Engine 3.7.7**.

It observes only supported WordPress plugin identity evidence such as plugin basename, name, version, text domain and activation state. A different AI Engine version is reported outside the currently validated detector boundary and is not automatically accepted.

Discovery does not infer which provider, model, chatbot, prompt or workflow is being used and does not read AI provider credentials or AI Engine internal configuration.

### Readiness findings

Readiness keeps three concepts separate:

- **Fact** — a technical condition observed in current Registry state;
- **Administrator declaration** — explicit administrator state, when relevant;
- **Guidance** — what should be reviewed or completed technically.

Accepted first rules cover active systems pending administrator review, active systems with no interaction context, and workflows explicitly configured by an administrator as requiring disclosure review.

Findings are generated on demand and are not persisted separately from the Registry.

### Privacy and data handling

Registry, Discovery, Readiness and Disclosure do not automatically send their state to an external service. Core state remains inside the WordPress installation.

### Important limitation

This plugin provides technical readiness, workflow and evidence tooling. It does **not** certify or guarantee legal compliance with the EU AI Act or any other law.

Readiness findings are technical review signals, not legal decisions. Disclosure tooling renders explicit administrator configuration; it does not decide whether a legal disclosure obligation exists or whether a particular notice is legally sufficient.

The plugin also does not attempt generic probabilistic detection of whether arbitrary text was written by AI.

### Installation

1. Upload the plugin directory to `/wp-content/plugins/` or install the generated ZIP.
2. Activate **Kairoseth AI Transparency** from the WordPress Plugins screen.
3. Open **Tools > AI Transparency** to maintain the Registry.
4. Open **Tools > AI Discovery** to review supported deterministic integration evidence.
5. Open **Tools > AI Readiness** to review current technical findings.
6. Open **Tools > AI Disclosure** to review disclosure readiness and copy a shortcode for a ready system.

### Development

```bash
composer install
composer verify
bash bin/build-plugin.sh
```

The production package is generated at:

```text
build/ai-transparency/
```

### Validation

The repository currently validates:

- WordPress Coding Standards;
- PHPUnit tests;
- PHP 7.4+ compatibility;
- PHP syntax on PHP 7.4 / 8.1 / 8.3 / 8.5;
- 100% EN/ES runtime-string coverage;
- compiled Spanish gettext catalog;
- official WordPress Plugin Check against the generated production package;
- real WordPress activation, Registry migration, CRUD and permission checks;
- responsive/accessibility browser acceptance;
- Multisite Registry isolation;
- deterministic AI Engine 3.7.7 discovery;
- deterministic Readiness finding rules;
- Phase 5 Disclosure eligibility tests;
- real Registry → Disclosure Admin → public anonymous frontend acceptance;
- disclosure removal after administrator configuration changes.

### Current roadmap state

```text
Phase 1 Repository bootstrap                 CLOSED
Phase 2 Persistent AI Systems Registry       CLOSED
Phase 3 Deterministic Discovery              CLOSED
Phase 4 Readiness Findings & Evidence        CLOSED
Phase 5 Disclosure Tooling                   CLOSED
Phase 6 Evidence Export                      UNBLOCKED / NOT STARTED
Phase 7 Contextual support/custom path       NOT STARTED
Phase 8 First public release                 NOT STARTED
```

Phase 5 implementation was accepted in PR #12 at merge `2770c7b7982ffbbe07ba58e8cedebd12d0add14a`; PR-head CI #82 and post-merge `main` CI #83 passed the complete required suite.

### Project documentation

- [`docs/ROADMAP.md`](docs/ROADMAP.md)
- [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md)
- [`docs/PHASE2_REGISTRY_IMPLEMENTATION.md`](docs/PHASE2_REGISTRY_IMPLEMENTATION.md)
- [`docs/PHASE3_DISCOVERY_IMPLEMENTATION.md`](docs/PHASE3_DISCOVERY_IMPLEMENTATION.md)
- [`docs/PHASE4_FINDINGS_IMPLEMENTATION.md`](docs/PHASE4_FINDINGS_IMPLEMENTATION.md)
- [`docs/PHASE4_RUNTIME_EVIDENCE.md`](docs/PHASE4_RUNTIME_EVIDENCE.md)
- [`docs/PHASE5_DISCLOSURE_IMPLEMENTATION.md`](docs/PHASE5_DISCLOSURE_IMPLEMENTATION.md)
- [`docs/PHASE5_ACCEPTANCE.md`](docs/PHASE5_ACCEPTANCE.md)
- [`docs/PHASE5_RUNTIME_EVIDENCE.md`](docs/PHASE5_RUNTIME_EVIDENCE.md)
- [`docs/BILINGUAL_EN_ES_POLICY.md`](docs/BILINGUAL_EN_ES_POLICY.md)
- [`SECURITY.md`](SECURITY.md)
- [`CONTRIBUTING.md`](CONTRIBUTING.md)

---

## Español

Kairoseth AI Transparency es un plugin para WordPress orientado a mantener un inventario técnico y revisable de los sistemas de IA utilizados en una web y facilitar flujos de transparencia respaldados por evidencia.

### Identidad del plugin

| Campo | Valor |
|---|---|
| Nombre del plugin | **Kairoseth AI Transparency** |
| Slug técnico | `ai-transparency` |
| Text domain WordPress | `ai-transparency` |
| Slug objetivo WordPress.org | `ai-transparency` |
| Requiere WordPress | 6.6+ |
| Target tested-up-to | 7.1 |
| Requiere PHP | 7.4+ |
| Idiomas | inglés + español |
| Licencia | MIT |

### Funcionalidad actual

La línea actual de desarrollo incluye cuatro flujos aceptados:

- **AI Systems Registry** local en **Herramientas > AI Transparency**;
- **AI Discovery** determinista en **Herramientas > AI Discovery**;
- hallazgos técnicos deterministas de **AI Readiness** en **Herramientas > AI Readiness**;
- tooling explícito de **AI Disclosure** controlado por el administrador en **Herramientas > AI Disclosure**.

Un administrador autorizado puede:

- añadir, editar, revisar y archivar registros de sistemas de IA;
- registrar tipo de sistema y contexto de interacción;
- indicar si un flujo revisado requiere un aviso de interacción con IA;
- detectar una integración compatible mediante evidencia WordPress explicable;
- añadir explícitamente un resultado compatible al Registro para revisión manual;
- revisar hallazgos técnicos reproducibles generados desde el Registro actual;
- ver si un sistema está técnicamente listo para colocar un aviso;
- colocar manualmente el aviso público aceptado mediante:

```text
[kairoseth_ai_disclosure system="SYSTEM_ID"]
```

### Autoridad del Registro y Disclosure

Los datos del Registro se guardan localmente mediante la API Options de WordPress con schema versionado. En Multisite, el almacenamiento sigue el sitio/blog actual y no crea un registro global de red.

Un sistema solo puede renderizar el aviso aceptado cuando el estado actual del Registro en servidor cumple todas estas condiciones:

```text
status = active
review_status = reviewed
interaction_disclosure_required = true
interaction_context no está vacío
```

El valor `system` del shortcode es únicamente un selector de lookup. No puede hacer que un registro no elegible publique un aviso.

Los sistemas pendientes, archivados, sin contexto o con disclosure desactivado no generan markup público del aviso.

### Límite del aviso público

El aviso frontend aceptado se renderiza en servidor, es accesible y no depende de JavaScript.

La salida pública se limita intencionadamente a:

- copy localizado del aviso;
- nombre del sistema de IA revisado por el administrador.

No expone automáticamente el contexto de interacción interno, id del Registro, metadata de origen, timestamps, configuración de proveedor/modelo, credenciales, prompts, conversaciones ni logs privados.

La primera ubicación aceptada es contenido normal de entradas/páginas singulares de WordPress. El plugin no inyecta automáticamente avisos en DOM de chatbots de terceros ni en templates arbitrarios.

### Discovery determinista

El primer detector validado soporta **AI Engine 3.7.7**.

Observa únicamente evidencia compatible de identidad WordPress como basename, nombre, versión, text domain y activación. Otras versiones se muestran fuera del alcance actualmente validado y no se aceptan automáticamente.

Discovery no infiere proveedor, modelo, chatbot, prompt o flujo, ni lee credenciales de IA o configuración interna de AI Engine.

### Hallazgos de Readiness

Readiness mantiene separados:

- **Hecho** — condición técnica observada en el Registro;
- **Declaración del administrador** — estado explícito configurado por un administrador;
- **Orientación** — lo que debe revisarse o completarse técnicamente.

Las reglas aceptadas cubren registros activos pendientes de revisión, sistemas activos sin contexto de interacción y flujos configurados explícitamente por un administrador como requiriendo revisión del disclosure.

Los hallazgos se calculan bajo demanda y no se guardan separadamente del Registro.

### Privacidad y tratamiento de datos

Registry, Discovery, Readiness y Disclosure no envían automáticamente su estado a servicios externos. El estado principal permanece dentro de la instalación WordPress.

### Límite importante

Este plugin proporciona readiness técnico, workflow y herramientas de evidencia. **No certifica ni garantiza cumplimiento legal** del Reglamento de IA de la UE ni de ninguna otra norma.

Los hallazgos de Readiness son señales técnicas, no decisiones legales. Disclosure renderiza una configuración explícita del administrador; no decide si existe obligación legal de informar ni si un aviso concreto es jurídicamente suficiente.

Tampoco intenta detectar probabilísticamente si cualquier texto arbitrario fue escrito por IA.

### Instalación

1. Sube el plugin a `/wp-content/plugins/` o instala el ZIP generado.
2. Activa **Kairoseth AI Transparency**.
3. Abre **Herramientas > AI Transparency** para mantener el Registro.
4. Abre **Herramientas > AI Discovery** para revisar evidencia determinista.
5. Abre **Herramientas > AI Readiness** para revisar hallazgos técnicos.
6. Abre **Herramientas > AI Disclosure** para revisar readiness de disclosure y copiar el shortcode de un sistema listo.

### Desarrollo

```bash
composer install
composer verify
bash bin/build-plugin.sh
```

El paquete de producción se genera en:

```text
build/ai-transparency/
```

### Validación

El repositorio valida actualmente:

- WordPress Coding Standards;
- tests PHPUnit;
- compatibilidad PHP 7.4+;
- sintaxis PHP 7.4 / 8.1 / 8.3 / 8.5;
- cobertura 100% EN/ES de cadenas runtime;
- catálogo gettext español compilado;
- WordPress Plugin Check sobre el paquete exacto;
- activación WordPress real, migración del Registro, CRUD y permisos;
- aceptación responsive/accesibilidad con navegador;
- aislamiento Multisite;
- discovery determinista de AI Engine 3.7.7;
- reglas deterministas de Readiness;
- tests de elegibilidad de Disclosure;
- aceptación real Registry → Disclosure Admin → frontend público anónimo;
- retirada del aviso después de cambiar la configuración del administrador.

### Estado actual del roadmap

```text
Fase 1 Repository bootstrap                 CLOSED
Fase 2 Persistent AI Systems Registry       CLOSED
Fase 3 Deterministic Discovery              CLOSED
Fase 4 Readiness Findings & Evidence        CLOSED
Fase 5 Disclosure Tooling                   CLOSED
Fase 6 Evidence Export                      DESBLOQUEADA / NO INICIADA
Fase 7 Contextual support/custom path       NO INICIADA
Fase 8 First public release                 NO INICIADA
```

La implementación de Fase 5 fue aceptada en PR #12 con merge `2770c7b7982ffbbe07ba58e8cedebd12d0add14a`; CI #82 del head y CI #83 post-merge en `main` pasaron toda la suite requerida.

### Documentación del proyecto

- [`docs/ROADMAP.md`](docs/ROADMAP.md)
- [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md)
- [`docs/PHASE2_REGISTRY_IMPLEMENTATION.md`](docs/PHASE2_REGISTRY_IMPLEMENTATION.md)
- [`docs/PHASE3_DISCOVERY_IMPLEMENTATION.md`](docs/PHASE3_DISCOVERY_IMPLEMENTATION.md)
- [`docs/PHASE4_FINDINGS_IMPLEMENTATION.md`](docs/PHASE4_FINDINGS_IMPLEMENTATION.md)
- [`docs/PHASE4_RUNTIME_EVIDENCE.md`](docs/PHASE4_RUNTIME_EVIDENCE.md)
- [`docs/PHASE5_DISCLOSURE_IMPLEMENTATION.md`](docs/PHASE5_DISCLOSURE_IMPLEMENTATION.md)
- [`docs/PHASE5_ACCEPTANCE.md`](docs/PHASE5_ACCEPTANCE.md)
- [`docs/PHASE5_RUNTIME_EVIDENCE.md`](docs/PHASE5_RUNTIME_EVIDENCE.md)
- [`docs/BILINGUAL_EN_ES_POLICY.md`](docs/BILINGUAL_EN_ES_POLICY.md)
- [`SECURITY.md`](SECURITY.md)
- [`CONTRIBUTING.md`](CONTRIBUTING.md)
