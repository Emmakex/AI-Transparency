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

The current development line includes:

- a local **AI Systems Registry** under **Tools > AI Transparency**;
- deterministic **AI Discovery** under **Tools > AI Discovery**.

An authorized administrator can:

- add, edit, review and archive AI system records;
- record system type and interaction context;
- configure whether the recorded workflow requires an AI interaction disclosure;
- detect a supported active AI integration from explainable WordPress evidence;
- explicitly add a supported discovery result to the registry for manual review.

The first validated detector supports **AI Engine 3.7.7**. It observes only the WordPress plugin basename, plugin name, version, text domain and activation state. A different AI Engine version is reported as outside the currently validated detector boundary and is not automatically accepted.

Discovery does not infer which provider, model, chatbot, prompt or workflow is being used. It does not read AI provider credentials or AI Engine internal configuration.

Registry data is stored locally using the WordPress Options API with a versioned schema. In Multisite, storage follows the current site/blog context rather than creating a network-wide registry.

### Privacy and data handling

The current registry and discovery workflow do not automatically send their data to any external service. Core state stays inside the WordPress installation.

### Important limitation

This plugin provides technical readiness, workflow and evidence tooling. It does **not** certify or guarantee legal compliance with the EU AI Act or any other law.

It also does not attempt generic probabilistic detection of whether arbitrary text was written by AI.

### Installation

1. Upload the plugin directory to `/wp-content/plugins/` or install the packaged ZIP.
2. Activate **Kairoseth AI Transparency** from the WordPress Plugins screen.
3. Open **Tools > AI Transparency** to maintain the local registry.
4. Open **Tools > AI Discovery** to review supported deterministic integration evidence.

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
- PHP syntax across the configured version matrix;
- 100% EN/ES runtime-string coverage;
- compiled Spanish gettext catalog;
- official WordPress Plugin Check against the generated production package;
- real WordPress runtime activation, registry migration, CRUD and permission checks;
- responsive/accessibility browser acceptance;
- Multisite registry isolation;
- deterministic discovery against an exact AI Engine runtime fixture.

### Project documentation

- [`docs/ROADMAP.md`](docs/ROADMAP.md)
- [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md)
- [`docs/PHASE2_REGISTRY_IMPLEMENTATION.md`](docs/PHASE2_REGISTRY_IMPLEMENTATION.md)
- [`docs/PHASE3_DISCOVERY_IMPLEMENTATION.md`](docs/PHASE3_DISCOVERY_IMPLEMENTATION.md)
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

La línea actual de desarrollo incluye:

- un **AI Systems Registry** local en **Herramientas > AI Transparency**;
- **AI Discovery** determinista en **Herramientas > AI Discovery**.

Un administrador autorizado puede:

- añadir, editar, revisar y archivar registros de sistemas de IA;
- registrar el tipo de sistema y el contexto de interacción;
- indicar si el flujo registrado requiere un aviso de interacción con IA;
- detectar una integración de IA compatible y activa a partir de evidencia WordPress explicable;
- añadir explícitamente un resultado compatible al registro para revisión manual.

El primer detector validado soporta **AI Engine 3.7.7**. Observa únicamente el basename del plugin, nombre, versión, text domain y estado de activación. Otra versión de AI Engine se muestra como fuera del alcance actualmente validado y no se acepta automáticamente.

Discovery no infiere qué proveedor, modelo, chatbot, prompt o flujo se está utilizando. Tampoco lee credenciales de proveedores de IA ni configuración interna de AI Engine.

Los datos del registro se guardan localmente mediante la API Options de WordPress con un schema versionado. En Multisite, el almacenamiento sigue el contexto del sitio/blog actual y no crea un registro global de red.

### Privacidad y tratamiento de datos

El registro y el flujo de discovery actuales no envían automáticamente sus datos a ningún servicio externo. El estado principal permanece dentro de la instalación WordPress.

### Límite importante

Este plugin proporciona readiness técnico, workflow y herramientas de evidencia. **No certifica ni garantiza cumplimiento legal** del Reglamento de IA de la UE ni de ninguna otra norma.

Tampoco intenta detectar probabilísticamente y de forma genérica si cualquier texto fue escrito por IA.

### Instalación

1. Sube el directorio del plugin a `/wp-content/plugins/` o instala el ZIP generado.
2. Activa **Kairoseth AI Transparency** desde la pantalla de Plugins de WordPress.
3. Abre **Herramientas > AI Transparency** para mantener el registro local.
4. Abre **Herramientas > AI Discovery** para revisar evidencia determinista de integraciones compatibles.

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
- sintaxis PHP en la matriz de versiones configurada;
- cobertura 100% EN/ES de las cadenas runtime;
- catálogo gettext español compilado;
- WordPress Plugin Check oficial sobre el paquete de producción generado;
- activación WordPress real, migración del registro, CRUD y permisos;
- aceptación responsive/accesibilidad con navegador;
- aislamiento Multisite del registro;
- discovery determinista contra un fixture runtime exacto de AI Engine.

### Documentación del proyecto

- [`docs/ROADMAP.md`](docs/ROADMAP.md)
- [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md)
- [`docs/PHASE2_REGISTRY_IMPLEMENTATION.md`](docs/PHASE2_REGISTRY_IMPLEMENTATION.md)
- [`docs/PHASE3_DISCOVERY_IMPLEMENTATION.md`](docs/PHASE3_DISCOVERY_IMPLEMENTATION.md)
- [`docs/BILINGUAL_EN_ES_POLICY.md`](docs/BILINGUAL_EN_ES_POLICY.md)
- [`SECURITY.md`](SECURITY.md)
- [`CONTRIBUTING.md`](CONTRIBUTING.md)
