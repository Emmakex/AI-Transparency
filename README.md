# Kairoseth AI Transparency

**EU AI Act Readiness for WordPress**

[English](#english) · [Español](#español)

> **Status / Estado:** pre-release development / desarrollo pre-release. No stable WordPress.org release is claimed yet / todavía no se afirma una versión estable en WordPress.org.

---

## English

Kairoseth AI Transparency is the first WordPress-first product in the Kairoseth Extensions portfolio. It helps WordPress site owners build a reviewable technical transparency workflow around AI systems used on their websites.

### Product identity

| Field | Value |
|---|---|
| Commercial name | **Kairoseth AI Transparency** |
| SEO descriptor | **EU AI Act Readiness for WordPress** |
| Technical slug | `ai-transparency` |
| WordPress text domain | `kairoseth-ai-transparency` |
| WordPress.org target slug | `kairoseth-ai-transparency` |
| Commercial model | useful Free + Custom |
| Public source license | MIT |
| Custom work | separate private repositories |
| Mandatory languages | English + Spanish, 100% customer-facing coverage |

### Free product direction

```text
WordPress site
    ↓
AI Systems Registry
    ↓
deterministic discovery of supported integrations
    ↓
evidence-backed readiness findings
    ↓
AI interaction / content disclosure workflows
    ↓
local evidence export
    ↓
optional user-initiated Custom Request
```

The Free v1 direction includes:

- a local AI Systems Registry;
- deterministic discovery for explicitly supported AI integrations;
- evidence-backed readiness findings;
- accessible AI-interaction disclosure tooling;
- explicit content/media declaration workflows where applicable and configured;
- dated local evidence/export;
- **100% English and Spanish customer-facing UX in every functional release**;
- no mandatory Kairoseth account or automatic telemetry for the Free baseline.

### Important boundary

This software provides **technical readiness, workflow and evidence tooling**. It does **not** certify or guarantee legal compliance with the EU AI Act or any other law.

Kairoseth AI Transparency intentionally does **not** attempt generic probabilistic detection of whether arbitrary text was written by AI.

### Engineering baseline

The repository contains:

- WordPress plugin loader and production autoloader;
- initial admin screen under **Tools > AI Transparency**;
- pure PHP `AiSystem` domain model and deterministic `AiSystemsRegistry` foundation;
- PHPUnit, WordPress Coding Standards and PHP 7.4+ compatibility gates;
- PHP syntax matrix;
- official WordPress Plugin Check against the generated production package;
- mandatory **EN/ES 100% coverage** CI gate;
- bundled Spanish gettext catalog compiled into the production package;
- actionable CI diagnostic runner and failure artifacts;
- durable engineering failure/solution memory;
- security, contribution, engineering, bilingual and trademark policies;
- WordPress.org-style `readme.txt`.

### Development requirements

- WordPress: **6.6+** development baseline
- Current tested-up-to target: **7.1**
- PHP: **7.4+**
- Composer 2 for development tooling

Compatibility becomes a release claim only after the corresponding acceptance evidence is complete.

### Development

```bash
composer install
composer verify
bash bin/build-plugin.sh
```

`composer verify` includes coding standards, tests and the bilingual coverage gate. The build compiles the Spanish catalog and produces `build/kairoseth-ai-transparency/`, which is the release-validation authority.

### Mandatory engineering policies

- [`docs/ENGINEERING_RULES.md`](docs/ENGINEERING_RULES.md)
- [`docs/BILINGUAL_EN_ES_POLICY.md`](docs/BILINGUAL_EN_ES_POLICY.md)
- [`docs/engineering-failures/README.md`](docs/engineering-failures/README.md)
- [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md)
- [`docs/ROADMAP.md`](docs/ROADMAP.md)
- [`SECURITY.md`](SECURITY.md)
- [`CONTRIBUTING.md`](CONTRIBUTING.md)
- [`TRADEMARKS.md`](TRADEMARKS.md)

The broader product/commercial contracts remain in `Emmakex/kairoseth-platform` under the Kairoseth Extensions and AI Transparency documentation.

### Commercial model

```text
Free public plugin
→ user finds a real need
→ contextual Custom Request
→ separate private customer repository
→ bespoke integration / remediation / workflow
```

A Custom engagement does not make this Free repository private and does not revoke rights already granted under MIT.

### WordPress.org direction

The project is built toward WordPress Plugin Directory expectations: GPL-compatible source, human-readable code, no trialware, no non-consensual tracking, no dashboard hijacking and no promotional links injected into the public site without permission.

MIT is GPL-compatible, but every bundled dependency and asset will be reviewed before directory submission.

### Security, license and brand

Do not publish exploitable vulnerability details or real customer secrets/data in public issues. See [`SECURITY.md`](SECURITY.md).

Source code is licensed under the [MIT License](LICENSE). Kairoseth brand and trademark rights are separate from the source-code license; see [`TRADEMARKS.md`](TRADEMARKS.md).

---

## Español

Kairoseth AI Transparency es el primer producto WordPress-first del catálogo Kairoseth Extensions. Ayuda a propietarios y administradores de sitios WordPress a construir un flujo técnico revisable de transparencia alrededor de los sistemas de IA utilizados en sus webs.

### Identidad del producto

| Campo | Valor |
|---|---|
| Nombre comercial | **Kairoseth AI Transparency** |
| Descriptor SEO | **EU AI Act Readiness for WordPress** |
| Slug técnico | `ai-transparency` |
| Text domain WordPress | `kairoseth-ai-transparency` |
| Slug objetivo WordPress.org | `kairoseth-ai-transparency` |
| Modelo comercial | Free útil + Custom |
| Licencia pública | MIT |
| Trabajo Custom | repositorios privados separados |
| Idiomas obligatorios | inglés + español, cobertura customer-facing 100% |

### Dirección del producto Free

```text
sitio WordPress
    ↓
AI Systems Registry
    ↓
descubrimiento determinista de integraciones soportadas
    ↓
findings de readiness respaldados por evidencia
    ↓
flujos de transparencia de interacción/contenido IA
    ↓
export local de evidencia
    ↓
Custom Request opcional iniciado por el usuario
```

La dirección Free v1 incluye:

- AI Systems Registry local;
- descubrimiento determinista de integraciones IA soportadas explícitamente;
- findings de readiness respaldados por evidencia;
- tooling accesible de avisos de interacción con IA;
- flujos explícitos de declaración de contenido/media cuando apliquen y estén configurados;
- evidencia/export local fechado;
- **UX customer-facing 100% en inglés y español en cada release funcional**;
- sin cuenta Kairoseth obligatoria ni telemetría automática en el baseline Free.

### Límite importante

Este software proporciona **readiness técnico, workflow y herramientas de evidencia**. **No certifica ni garantiza cumplimiento legal** del Reglamento de IA de la UE ni de ninguna otra norma.

Kairoseth AI Transparency tampoco intenta detectar probabilísticamente y de forma genérica si cualquier texto fue escrito por IA.

### Baseline de ingeniería

El repositorio contiene:

- loader del plugin WordPress y autoloader de producción;
- pantalla inicial en **Herramientas > AI Transparency**;
- modelo PHP puro `AiSystem` y base determinista `AiSystemsRegistry`;
- PHPUnit, WordPress Coding Standards y gates de compatibilidad PHP 7.4+;
- matriz de sintaxis PHP;
- WordPress Plugin Check oficial sobre el paquete generado;
- gate CI obligatorio de **cobertura EN/ES 100%**;
- catálogo gettext español compilado dentro del paquete de producción;
- runner de diagnósticos CI accionables y artefactos de fallo;
- memoria duradera de fallos/soluciones de ingeniería;
- políticas de seguridad, contribución, ingeniería, bilingüismo y marca;
- `readme.txt` estilo WordPress.org.

### Requisitos de desarrollo

- WordPress: baseline **6.6+**
- Target actual tested-up-to: **7.1**
- PHP: **7.4+**
- Composer 2 para tooling de desarrollo

La compatibilidad solo se convierte en claim de release cuando existe la evidencia de aceptación correspondiente.

### Desarrollo

```bash
composer install
composer verify
bash bin/build-plugin.sh
```

`composer verify` incluye coding standards, tests y cobertura bilingüe. El build compila el catálogo español y genera `build/kairoseth-ai-transparency/`, que es la autoridad para la validación de release.

### Políticas obligatorias de ingeniería

- [`docs/ENGINEERING_RULES.md`](docs/ENGINEERING_RULES.md)
- [`docs/BILINGUAL_EN_ES_POLICY.md`](docs/BILINGUAL_EN_ES_POLICY.md)
- [`docs/engineering-failures/README.md`](docs/engineering-failures/README.md)
- [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md)
- [`docs/ROADMAP.md`](docs/ROADMAP.md)
- [`SECURITY.md`](SECURITY.md)
- [`CONTRIBUTING.md`](CONTRIBUTING.md)
- [`TRADEMARKS.md`](TRADEMARKS.md)

Los contratos globales de producto/comercial permanecen en `Emmakex/kairoseth-platform`, dentro de la documentación de Kairoseth Extensions y AI Transparency.

### Modelo comercial

```text
plugin Free público
→ el usuario encuentra una necesidad real
→ Custom Request contextual
→ repositorio privado separado del cliente
→ integración / remediación / workflow a medida
```

Un proyecto Custom no convierte este repositorio Free en privado ni revoca los derechos ya concedidos bajo MIT.

### Dirección WordPress.org

El proyecto se construye para las expectativas del directorio WordPress: código GPL-compatible, legible, sin trialware, sin tracking no consentido, sin secuestro del dashboard y sin enlaces promocionales insertados en la web pública sin permiso.

MIT es compatible con GPL, pero cada dependencia y asset se revisará antes del envío al directorio.

### Seguridad, licencia y marca

No publiques vulnerabilidades explotables ni secretos/datos reales de clientes en issues públicos. Consulta [`SECURITY.md`](SECURITY.md).

El código fuente usa [licencia MIT](LICENSE). Los derechos de marca Kairoseth son independientes de la licencia del código; consulta [`TRADEMARKS.md`](TRADEMARKS.md).

Copyright © 2026 Kairoseth / Eduardo Yauri.
