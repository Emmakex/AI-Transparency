# AI Transparency for WordPress

**Plugin:** Kairoseth AI Transparency  
**Stable release:** `1.0.1`  
**Technical slug:** `ai-transparency`  
**WordPress text domain:** `ai-transparency`  
**WordPress.org target slug:** `ai-transparency`  
**Status:** stable `1.0.1` released on GitHub · WordPress.org review response pending

[English](#english) · [Español](#español)

---

## English

Kairoseth AI Transparency is a local-first WordPress plugin for maintaining a reviewable inventory of AI systems, deriving deterministic technical readiness evidence, placing explicit AI interaction disclosures, exporting a bounded technical evidence snapshot, and optionally opening a privacy-bounded Kairoseth support/custom-request flow.

### Requirements and identity

| Field | Value |
|---|---|
| Plugin name | **Kairoseth AI Transparency** |
| Technical slug | `ai-transparency` |
| WordPress text domain | `ai-transparency` |
| WordPress.org target slug | `ai-transparency` |
| Stable version | `1.0.1` |
| Requires WordPress | 6.6+ |
| Tested up to | 7.1 |
| Requires PHP | 7.4+ |
| Languages | English + Spanish |
| License | MIT |

### Stable release 1.0.1

```text
Git tag: 1.0.1
Accepted source SHA: 2b6e8906f96d28cff216e3f4ede52bc13eaa1a1d
Release ZIP: ai-transparency-1.0.1.zip
SHA-256: b87aefa0cf389592abab0602d0579c2cf3f3d29c67a9bb3e0fd1975883ef8f0e
```

The `1.0.1` release changes the WordPress plugin metadata so `Plugin URI` points to the dedicated AI Transparency product page while `Author URI` remains separate. Release-version handling in packaging and lifecycle gates is also version-agnostic to prevent stale hardcoded-version failures.

The release package passed the repository's reproducible ZIP, checksum, compatibility, runtime, Multisite, privacy, EN/ES, WordPress Plugin Check, upgrade and release-readiness gates. Published release assets were verified against the accepted artifact.

**WordPress.org publication is an external review process.** The current `1.0.1` correction package is ready for the ongoing review thread; public directory availability is not claimed until WordPress.org independently approves and publishes it.

### What the plugin provides

- **AI Systems Registry** — maintain AI-system inventory under **Tools → AI Transparency**.
- **AI Discovery** — deterministic supported-integration evidence.
- **AI Readiness** — deterministic technical findings and readiness evidence.
- **AI Disclosure** — administrator-controlled disclosure readiness and public shortcode.
- **AI Evidence Export** — privileged, user-initiated JSON technical evidence download.
- **AI Transparency Support** — optional user-initiated navigation to Kairoseth support/custom requests.

Public disclosure shortcode:

```text
[kairoseth_ai_disclosure system="SYSTEM_ID"]
```

A disclosure can render only from current server-side Registry state that is active, reviewed, marked as requiring interaction disclosure, and has non-empty interaction context. The shortcode value is a lookup selector; it does not grant eligibility.

### Local-first and privacy boundary

Registry data is stored site-locally through the WordPress Options API. In Multisite, each blog/site remains authoritative for its own Registry.

Registry, Discovery, Readiness, Disclosure, and Evidence Export do not automatically upload their state to Kairoseth or another provider. Evidence Export is generated only after an authorized administrator action and is downloaded directly as JSON.

The optional support flow remains local on page load. Network interaction begins only after an explicit administrator CTA to:

```text
https://kairoseth.com/custom-requests
```

The support link adds only bounded non-sensitive product/platform context:

```text
source
extensionSlug
extensionName
extensionVersion
hostPlatform
hostPlatformVersion
locale
requestType
```

It does **not** automatically attach the site URL, administrator/customer identity, Registry contents, AI-system names, interaction context, Discovery evidence, Readiness findings, Disclosure state, Evidence Export data/signature, plugin/theme inventory, credentials, prompts, conversations, logs, database contents, or arbitrary WordPress options.

### Evidence and legal boundary

Evidence Export produces a deterministic technical snapshot with `export_schema_version = 1` and a stable SHA-256 `snapshot_signature` for equivalent exported technical state.

That signature is a technical snapshot identity. It is **not** a legal/digital signature, trusted timestamp, non-repudiation proof, or compliance certification.

The plugin provides technical readiness, workflow, disclosure, and evidence tooling. It does **not** certify or guarantee legal compliance with the EU AI Act or any other law and does not attempt generic probabilistic detection of whether arbitrary content was written by AI.

### Installation

1. Install the stable plugin ZIP or plugin directory.
2. Activate **Kairoseth AI Transparency**.
3. Open **Tools → AI Transparency** to maintain the AI Systems Registry.
4. Use **AI Discovery**, **AI Readiness**, **AI Disclosure**, and **AI Evidence Export** as needed.
5. Optionally open **AI Transparency Support** to deliberately navigate to Kairoseth support/custom requests.

### Development and validation

```bash
composer install
composer verify
bash bin/build-plugin.sh
bash bin/build-release.sh
```

Canonical stable outputs:

```text
build/ai-transparency/
dist/ai-transparency-1.0.1.zip
dist/ai-transparency-1.0.1.zip.sha256
```

Blocking validation includes WordPress Coding Standards, PHPUnit, PHPCompatibility, supported PHP syntax checks, EN/ES runtime coverage, compiled Spanish gettext, official WordPress Plugin Check, real WordPress runtime acceptance, responsive/accessibility acceptance, deterministic Registry/Discovery/Readiness/Disclosure/Evidence behavior, contextual-support privacy acceptance, upgrade testing, and real Multisite isolation/lifecycle testing.

### Roadmap status

```text
Phase 1 Repository bootstrap                 CLOSED
Phase 2 Persistent AI Systems Registry       CLOSED
Phase 3 Deterministic Discovery              CLOSED
Phase 4 Readiness Findings & Evidence        CLOSED
Phase 5 Disclosure Tooling                   CLOSED
Phase 6 Evidence Export                      CLOSED
Phase 7 Contextual support/custom path       CLOSED
Phase 8 Repository/GitHub release            COMPLETE
Phase 8 WordPress.org external publication   PENDING REVIEW
```

### Documentation

- [`docs/ROADMAP.md`](docs/ROADMAP.md)
- [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md)
- [`docs/PHASE6_EVIDENCE_EXPORT_IMPLEMENTATION.md`](docs/PHASE6_EVIDENCE_EXPORT_IMPLEMENTATION.md)
- [`docs/PHASE6_RUNTIME_EVIDENCE.md`](docs/PHASE6_RUNTIME_EVIDENCE.md)
- [`docs/PHASE7_CONTEXTUAL_SUPPORT_IMPLEMENTATION.md`](docs/PHASE7_CONTEXTUAL_SUPPORT_IMPLEMENTATION.md)
- [`docs/PHASE7_RUNTIME_EVIDENCE.md`](docs/PHASE7_RUNTIME_EVIDENCE.md)
- [`docs/PHASE8_PUBLIC_RELEASE_IMPLEMENTATION.md`](docs/PHASE8_PUBLIC_RELEASE_IMPLEMENTATION.md)
- [`docs/PHASE8_ACCEPTANCE.md`](docs/PHASE8_ACCEPTANCE.md)
- [`docs/PHASE8_RELEASE_EVIDENCE.md`](docs/PHASE8_RELEASE_EVIDENCE.md)
- [`docs/BILINGUAL_EN_ES_POLICY.md`](docs/BILINGUAL_EN_ES_POLICY.md)
- [`SECURITY.md`](SECURITY.md)
- [`CONTRIBUTING.md`](CONTRIBUTING.md)

---

## Español

Kairoseth AI Transparency es un plugin local-first para WordPress orientado a mantener un inventario revisable de sistemas de IA, derivar evidencia técnica determinista, colocar avisos explícitos de interacción con IA, exportar un snapshot técnico acotado y, opcionalmente, abrir un flujo de soporte/solicitud personalizada de Kairoseth con contexto mínimo.

### Requisitos e identidad

| Campo | Valor |
|---|---|
| Nombre | **Kairoseth AI Transparency** |
| Slug técnico | `ai-transparency` |
| Text domain | `ai-transparency` |
| Slug objetivo WordPress.org | `ai-transparency` |
| Versión estable | `1.0.1` |
| Requiere WordPress | 6.6+ |
| Probado hasta | 7.1 |
| Requiere PHP | 7.4+ |
| Idiomas | inglés + español |
| Licencia | MIT |

### Release estable 1.0.1

```text
Tag Git: 1.0.1
SHA fuente aceptado: 2b6e8906f96d28cff216e3f4ede52bc13eaa1a1d
ZIP: ai-transparency-1.0.1.zip
SHA-256: b87aefa0cf389592abab0602d0579c2cf3f3d29c67a9bb3e0fd1975883ef8f0e
```

La versión `1.0.1` corrige los metadatos del plugin para que `Plugin URI` apunte a la página específica de AI Transparency y `Author URI` permanezca separado. También generaliza la gestión de versiones en los gates de empaquetado y lifecycle para evitar fallos por versiones antiguas hardcodeadas.

El paquete superó los gates de ZIP reproducible, checksum, compatibilidad, runtime, Multisite, privacidad, EN/ES, WordPress Plugin Check, upgrade y release readiness. Los assets publicados se verificaron contra el artefacto aceptado.

**La publicación en WordPress.org es un proceso externo de revisión.** El paquete de corrección `1.0.1` está listo para continuar el hilo de revisión existente; no se considera publicada la extensión en el directorio hasta que WordPress.org la apruebe y la haga pública.

### Funcionalidad

- **AI Systems Registry** — inventario de sistemas IA en **Herramientas → AI Transparency**.
- **AI Discovery** — evidencia determinista de integraciones soportadas.
- **AI Readiness** — hallazgos técnicos y evidencia de readiness.
- **AI Disclosure** — preparación de avisos controlada por el administrador y shortcode público.
- **AI Evidence Export** — descarga JSON privilegiada e iniciada explícitamente por el usuario.
- **AI Transparency Support** — navegación opcional iniciada por el usuario hacia soporte/solicitudes personalizadas.

Shortcode público:

```text
[kairoseth_ai_disclosure system="SYSTEM_ID"]
```

El aviso público solo puede renderizarse desde estado server-side del Registry que esté activo, revisado, marcado para disclosure y con contexto de interacción no vacío. El parámetro del shortcode solo selecciona el registro; no concede elegibilidad.

### Local-first y privacidad

El Registry se almacena localmente mediante WordPress Options. En Multisite, cada blog/sitio mantiene su propio estado autoritativo.

Registry, Discovery, Readiness, Disclosure y Evidence Export no suben automáticamente su estado a Kairoseth ni a otro proveedor. Evidence Export solo se genera tras una acción autorizada del administrador y se descarga directamente como JSON.

La página de soporte no contacta Kairoseth al cargarse. La interacción de red comienza únicamente tras un CTA explícito hacia:

```text
https://kairoseth.com/custom-requests
```

El soporte añade únicamente estas claves técnicas acotadas:

```text
source
extensionSlug
extensionName
extensionVersion
hostPlatform
hostPlatformVersion
locale
requestType
```

No adjunta automáticamente URL del sitio, identidad de administrador/cliente, Registry, nombres de sistemas IA, contexto de interacción, Discovery, findings, Disclosure, Evidence Export/firma, inventario de plugins/temas, credenciales, prompts, conversaciones, logs, contenido de base de datos ni options arbitrarias.

### Evidencia y límite legal

Evidence Export genera un snapshot técnico determinista con `export_schema_version = 1` y una `snapshot_signature` SHA-256 estable para el mismo estado técnico exportado.

Esa firma identifica técnicamente el snapshot. **No** es una firma legal/digital, sello de tiempo confiable, prueba de no repudio ni certificación de cumplimiento.

El plugin proporciona herramientas técnicas de readiness, flujo, disclosure y evidencia. **No certifica ni garantiza cumplimiento legal** del Reglamento de IA de la UE ni de otra norma, ni intenta detectar probabilísticamente si contenido arbitrario fue escrito por IA.

### Instalación

1. Instala el ZIP estable o el directorio del plugin.
2. Activa **Kairoseth AI Transparency**.
3. Usa **Herramientas → AI Transparency** para mantener el Registry.
4. Usa **AI Discovery**, **AI Readiness**, **AI Disclosure** y **AI Evidence Export** según sea necesario.
5. Opcionalmente usa **AI Transparency Support** para abrir deliberadamente soporte/solicitudes personalizadas de Kairoseth.

### Desarrollo y validación

```bash
composer install
composer verify
bash bin/build-plugin.sh
bash bin/build-release.sh
```

Artefactos estables canónicos:

```text
build/ai-transparency/
dist/ai-transparency-1.0.1.zip
dist/ai-transparency-1.0.1.zip.sha256
```

La validación bloqueante incluye WordPress Coding Standards, PHPUnit, PHPCompatibility, comprobaciones de sintaxis PHP soportada, cobertura runtime EN/ES, gettext español compilado, WordPress Plugin Check oficial, runtime WordPress real, responsive/accesibilidad, comportamiento determinista de Registry/Discovery/Readiness/Disclosure/Evidence, privacidad del soporte contextual, pruebas de upgrade y aislamiento/lifecycle Multisite real.

### Estado del roadmap

```text
Fase 1 Repository bootstrap                 CERRADA
Fase 2 Persistent AI Systems Registry       CERRADA
Fase 3 Deterministic Discovery              CERRADA
Fase 4 Readiness Findings & Evidence        CERRADA
Fase 5 Disclosure Tooling                   CERRADA
Fase 6 Evidence Export                      CERRADA
Fase 7 Contextual support/custom path       CERRADA
Fase 8 Release repositorio/GitHub           COMPLETA
Fase 8 Publicación externa WordPress.org    PENDIENTE DE REVISIÓN
```

### Documentación

- [`docs/ROADMAP.md`](docs/ROADMAP.md)
- [`docs/ARCHITECTURE.md`](docs/ARCHITECTURE.md)
- [`docs/PHASE6_EVIDENCE_EXPORT_IMPLEMENTATION.md`](docs/PHASE6_EVIDENCE_EXPORT_IMPLEMENTATION.md)
- [`docs/PHASE6_RUNTIME_EVIDENCE.md`](docs/PHASE6_RUNTIME_EVIDENCE.md)
- [`docs/PHASE7_CONTEXTUAL_SUPPORT_IMPLEMENTATION.md`](docs/PHASE7_CONTEXTUAL_SUPPORT_IMPLEMENTATION.md)
- [`docs/PHASE7_RUNTIME_EVIDENCE.md`](docs/PHASE7_RUNTIME_EVIDENCE.md)
- [`docs/PHASE8_PUBLIC_RELEASE_IMPLEMENTATION.md`](docs/PHASE8_PUBLIC_RELEASE_IMPLEMENTATION.md)
- [`docs/PHASE8_ACCEPTANCE.md`](docs/PHASE8_ACCEPTANCE.md)
- [`docs/PHASE8_RELEASE_EVIDENCE.md`](docs/PHASE8_RELEASE_EVIDENCE.md)
- [`docs/BILINGUAL_EN_ES_POLICY.md`](docs/BILINGUAL_EN_ES_POLICY.md)
- [`SECURITY.md`](SECURITY.md)
- [`CONTRIBUTING.md`](CONTRIBUTING.md)
