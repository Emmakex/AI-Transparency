# AI Transparency for WordPress

**Plugin:** Kairoseth AI Transparency  
**Status:** pre-release development

[English](#english) · [Español](#español)

---

## English

Kairoseth AI Transparency is a WordPress plugin for maintaining a reviewable technical inventory of AI systems used on a website and, in later phases, supporting evidence-backed transparency and disclosure workflows.

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

The current development line includes a local **AI Systems Registry** under **Tools > AI Transparency**.

An authorized administrator can:

- add AI systems;
- edit AI systems;
- record the system type and interaction context;
- set review status;
- configure whether the recorded workflow requires an AI interaction disclosure;
- archive records without deleting their history.

Registry data is stored locally using the WordPress Options API with a versioned schema. In Multisite, storage follows the current site/blog context rather than creating a network-wide registry.

### Privacy and data handling

The current registry does not automatically send its data to any external service. Core registry state stays inside the WordPress installation unless a future feature explicitly documents and requires an external connection.

### Important limitation

This plugin provides technical readiness, workflow and evidence tooling. It does **not** certify or guarantee legal compliance with the EU AI Act or any other law.

It also does not attempt generic probabilistic detection of whether arbitrary text was written by AI.

### Installation

1. Upload the plugin directory to `/wp-content/plugins/` or install the packaged ZIP.
2. Activate **Kairoseth AI Transparency** from the WordPress Plugins screen.
3. Open **Tools > AI Transparency**.
4. Add and maintain the site's AI systems in the local registry.

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
- official WordPress Plugin Check against the generated production package.

### Project documentation

- [`docs/ROADMAP.md`](docs/ROADMAP.md)
- [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md)
- [`docs/PHASE2_REGISTRY_IMPLEMENTATION.md`](docs/PHASE2_REGISTRY_IMPLEMENTATION.md)
- [`docs/BILINGUAL_EN_ES_POLICY.md`](docs/BILINGUAL_EN_ES_POLICY.md)
- [`SECURITY.md`](SECURITY.md)
- [`CONTRIBUTING.md`](CONTRIBUTING.md)

---

## Español

Kairoseth AI Transparency es un plugin para WordPress orientado a mantener un inventario técnico y revisable de los sistemas de IA utilizados en una web y, en fases posteriores, facilitar flujos de transparencia y evidencia respaldados por datos observables.

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

La línea actual de desarrollo incluye un **AI Systems Registry** local en **Herramientas > AI Transparency**.

Un administrador autorizado puede:

- añadir sistemas de IA;
- editar sistemas de IA;
- registrar el tipo de sistema y el contexto de interacción;
- establecer el estado de revisión;
- indicar si el flujo registrado requiere un aviso de interacción con IA;
- archivar registros sin eliminar su historial.

Los datos del registro se guardan localmente mediante la API Options de WordPress con un schema versionado. En Multisite, el almacenamiento sigue el contexto del sitio/blog actual y no crea un registro global de red.

### Privacidad y tratamiento de datos

El registro actual no envía automáticamente sus datos a ningún servicio externo. El estado principal del registro permanece dentro de la instalación WordPress salvo que una función futura documente y requiera explícitamente una conexión externa.

### Límite importante

Este plugin proporciona readiness técnico, workflow y herramientas de evidencia. **No certifica ni garantiza cumplimiento legal** del Reglamento de IA de la UE ni de ninguna otra norma.

Tampoco intenta detectar probabilísticamente y de forma genérica si cualquier texto fue escrito por IA.

### Instalación

1. Sube el directorio del plugin a `/wp-content/plugins/` o instala el ZIP generado.
2. Activa **Kairoseth AI Transparency** desde la pantalla de Plugins de WordPress.
3. Abre **Herramientas > AI Transparency**.
4. Añade y mantén los sistemas de IA del sitio en el registro local.

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
- WordPress Plugin Check oficial sobre el paquete de producción generado.

### Documentación del proyecto

- [`docs/ROADMAP.md`](docs/ROADMAP.md)
- [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md)
- [`docs/PHASE2_REGISTRY_IMPLEMENTATION.md`](docs/PHASE2_REGISTRY_IMPLEMENTATION.md)
- [`docs/BILINGUAL_EN_ES_POLICY.md`](docs/BILINGUAL_EN_ES_POLICY.md)
- [`SECURITY.md`](SECURITY.md)
- [`CONTRIBUTING.md`](CONTRIBUTING.md)
