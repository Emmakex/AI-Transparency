# Phase 2 — Persistent AI Systems Registry

[English](#english) · [Español](#español)

Status: **closed — accepted, merged and verified on `main` on 9 September 2026**  
Last reviewed / Última revisión: **9 September 2026 / 9 de septiembre de 2026**

---

## English

### Goal

Phase 2 turns the bootstrap in-memory registry into a useful local WordPress inventory that an authorized administrator can maintain without sending site data to Kairoseth.

### Final implementation

Implemented across the Phase 2 workstream and closed in PR #5:

```text
WordPress site/blog
→ wp_options
→ kairoseth_ai_transparency_registry
→ schema_version = 1
→ systems[]
```

Each AI system can record:

- stable local id;
- name;
- system type;
- source identifier;
- source origin;
- lifecycle status (`active` / `archived`);
- review status (`pending` / `reviewed`);
- interaction context;
- configured interaction-disclosure requirement;
- created/updated/reviewed timestamps.

### Storage decision

Phase 2 uses the normal WordPress Options API:

```text
get_option()
update_option()
```

It intentionally does **not** use `get_site_option()` / `update_site_option()` and does not create a custom table.

Multisite state therefore follows the current WordPress blog/site context. Network-wide inventory is not introduced without a separate product contract.

### CRUD behavior

Under **Tools → AI Transparency**, an administrator can:

```text
add AI system
edit AI system
mark review state
record interaction context
configure interaction-disclosure requirement
archive AI system
```

Archive preserves the record and timestamps instead of destructively deleting evidence.

### Security boundary

Every state change requires:

```text
manage_options
+ WordPress nonce
+ server-side validation/sanitization
```

Output is escaped for its HTML context. Browser state or request payloads never grant authorization.

The real WordPress acceptance lane creates disposable runtime users with random credentials. Those values are masked before any command can echo them and are never shared with production/customer credentials.

### Privacy boundary

Registry operations make no Kairoseth telemetry/network call. Data remains local to the current WordPress site/blog.

### Schema and migration

`RegistrySchema::VERSION = 1` is the canonical persistence shape.

Legacy bootstrap arrays migrate into v1 defaults without discarding valid records. Invalid individual records are skipped rather than destroying the rest of the registry.

Future unknown schema versions fail safe instead of being interpreted as current data.

### EN/ES

The full registry UI ships in English and Spanish in the same workstream. The blocking `EN/ES 100% coverage` CI gate validates all runtime gettext strings and the compiled Spanish `.mo` in the production package.

### Validation

Unit/contract coverage includes:

- deterministic schema ordering;
- legacy migration;
- invalid-record isolation;
- future-schema fail-safe behavior;
- persistence round trip;
- simulated per-blog/site storage isolation;
- lifecycle/archive metadata preservation.

The closing runtime acceptance validates the **real production plugin package** inside the official `@wordpress/env` Docker environment:

```text
WordPress 7.1 / PHP 8.3
build/ai-transparency mounted as the plugin
single-site WordPress runtime
Playwright Chromium browser acceptance
separate Multisite runtime
```

Accepted runtime evidence:

- production package activates successfully in WordPress;
- legacy `wp_options` payload migrates and writes back `schema_version = 1`;
- administrator can add, edit, review and archive a registry record through wp-admin;
- non-administrator cannot access the registry administration surface;
- admin UI does not create document-level horizontal overflow at 390 px;
- registry table overflow region is keyboard focusable;
- axe reports no `serious` or `critical` accessibility violations in the plugin admin surface;
- Multisite creates separate blogs and proves registry state remains blog/site-local.

### Final closure evidence

```text
Functional acceptance CI: #53 / 34367111247
Accepted implementation SHA: e5927a8a2b4526f01a4649c4ba5d3a25ae3c0353
Final PR-head CI: #60 / 34368216414
Merged PR: #5
Main merge commit: c83fbb11ffcfba7816a0beb71068e228a65ece77
Post-merge main CI: #61 / 34368648895

PHP quality: success
EN/ES 100% coverage: success
PHP 7.4 syntax: success
PHP 8.1 syntax: success
PHP 8.3 syntax: success
PHP 8.5 syntax: success
WordPress Plugin Check: success
WordPress runtime acceptance: success
```

The runtime jobs passed activation, migration, CRUD/permissions/responsive/accessibility and real Multisite isolation.

### Engineering failures learned during acceptance

The Phase 2 acceptance work produced durable prevention records for:

- correct `wp-env run` command tokenization;
- inherited destructive-action contrast below WCAG AA;
- persisted WordPress state causing retry ambiguity in Playwright;
- masking dynamically generated runtime credentials before first command use.

See `docs/engineering-failures/README.md`.

### Closure checklist

```text
[x] CI green on accepted Phase 2 implementation SHA
[x] real WordPress add/edit/archive smoke
[x] unauthorized-role rejection in WordPress runtime
[x] real Multisite isolation smoke
[x] responsive admin acceptance
[x] keyboard/accessibility acceptance
[x] upgrade/migration acceptance on a real WordPress install
[x] documentation synchronized
[x] implementation/acceptance blockers = 0
[x] PR #5 merged to main
[x] post-merge main verification green
```

**Phase 2 is closed. Phase 3 is unblocked but has not started yet.**

---

## Español

### Objetivo

La Fase 2 convierte el registro en memoria del bootstrap en un inventario WordPress local y útil que un administrador autorizado puede mantener sin enviar datos del sitio a Kairoseth.

### Implementación final

Implementado durante la Fase 2 y cerrado en el PR #5:

```text
sitio/blog WordPress
→ wp_options
→ kairoseth_ai_transparency_registry
→ schema_version = 1
→ systems[]
```

Cada sistema de IA puede guardar:

- id local estable;
- nombre;
- tipo de sistema;
- identificador de fuente;
- origen de la fuente;
- estado (`active` / `archived`);
- estado de revisión (`pending` / `reviewed`);
- contexto de interacción;
- requisito configurado de aviso de interacción con IA;
- fechas de creación, actualización y revisión.

### Decisión de almacenamiento

La Fase 2 utiliza la API normal de Options de WordPress:

```text
get_option()
update_option()
```

No utiliza `get_site_option()` / `update_site_option()` ni crea tablas propias.

En Multisite, los datos siguen el contexto del blog/sitio WordPress actual. No se introduce un inventario global de red sin un contrato de producto separado.

### CRUD

En **Herramientas → Transparencia de IA**, un administrador puede:

```text
añadir sistema de IA
editar sistema de IA
marcar estado de revisión
guardar contexto de interacción
configurar necesidad de aviso de interacción
archivar sistema de IA
```

Archivar conserva el registro y su historial en lugar de eliminar evidencia de forma destructiva.

### Seguridad

Cada cambio de estado requiere:

```text
manage_options
+ nonce WordPress
+ validación/sanitización server-side
```

La salida se escapa según el contexto HTML. El navegador o el payload nunca conceden autorización.

La aceptación WordPress real crea usuarios runtime desechables con credenciales aleatorias. Los valores se enmascaran antes de que cualquier comando pueda mostrarlos y nunca se mezclan con credenciales de producción o clientes.

### Privacidad

Las operaciones del registro no realizan llamadas de telemetría/red a Kairoseth. Los datos permanecen en el sitio/blog WordPress actual.

### Schema y migración

`RegistrySchema::VERSION = 1` es la estructura canónica de persistencia.

Los arrays legacy del bootstrap se migran a valores v1 sin perder registros válidos. Un registro individual inválido se descarta sin destruir el resto del inventario.

Una versión futura desconocida del schema falla de forma segura y no se interpreta como datos actuales.

### EN/ES

Toda la UI del registro se entrega en inglés y español en el mismo workstream. El gate bloqueante `EN/ES 100% coverage` comprueba las cadenas gettext runtime y el `.mo` español compilado dentro del package de producción.

### Validación

Los tests unitarios/de contrato cubren:

- orden determinista del schema;
- migración legacy;
- aislamiento de registros inválidos;
- fail-safe ante schemas futuros;
- persistencia ida/vuelta;
- aislamiento simulado por blog/sitio;
- preservación de metadata al archivar.

La aceptación runtime de cierre valida el **package real de producción** dentro del entorno Docker oficial `@wordpress/env`:

```text
WordPress 7.1 / PHP 8.3
build/ai-transparency montado como plugin
runtime WordPress single-site
aceptación navegador Chromium con Playwright
runtime Multisite separado
```

Evidencia runtime aceptada:

- el package de producción activa correctamente en WordPress;
- un payload legacy en `wp_options` migra y se reescribe con `schema_version = 1`;
- un administrador puede añadir, editar, revisar y archivar registros desde wp-admin;
- un rol no administrador no puede acceder al panel de administración del registro;
- la UI admin no genera overflow horizontal de documento a 390 px;
- la región con overflow de la tabla es accesible por teclado;
- axe no reporta violaciones `serious` ni `critical` dentro de la superficie admin del plugin;
- Multisite crea blogs separados y demuestra que el registro permanece aislado por sitio/blog.

### Evidencia final de cierre

```text
CI aceptación funcional: #53 / 34367111247
SHA de implementación aceptado: e5927a8a2b4526f01a4649c4ba5d3a25ae3c0353
CI final del head del PR: #60 / 34368216414
PR fusionado: #5
Commit merge en main: c83fbb11ffcfba7816a0beb71068e228a65ece77
CI post-merge main: #61 / 34368648895

PHP quality: success
EN/ES 100% coverage: success
PHP 7.4 syntax: success
PHP 8.1 syntax: success
PHP 8.3 syntax: success
PHP 8.5 syntax: success
WordPress Plugin Check: success
WordPress runtime acceptance: success
```

Los jobs runtime superaron activación, migración, CRUD/permisos/responsive/accesibilidad y aislamiento Multisite real.

### Aprendizajes de ingeniería durante la aceptación

El cierre de Fase 2 generó memoria durable para prevenir:

- tokenización incorrecta de comandos con `wp-env run`;
- contraste heredado de acciones destructivas por debajo de WCAG AA;
- ambigüedad en reintentos Playwright por estado WordPress persistente;
- exposición en logs de credenciales runtime generadas dinámicamente antes de enmascararlas.

Ver `docs/engineering-failures/README.md`.

### Checklist de cierre

```text
[x] CI verde en SHA aceptado de implementación Fase 2
[x] smoke real WordPress de alta/edición/archivo
[x] rechazo runtime de roles no autorizados
[x] smoke real de aislamiento Multisite
[x] aceptación responsive del admin
[x] aceptación teclado/accesibilidad
[x] aceptación real de upgrade/migración en WordPress
[x] documentación sincronizada
[x] blockers de implementación/aceptación = 0
[x] PR #5 fusionado a main
[x] verificación post-merge verde en main
```

**La Fase 2 está cerrada. La Fase 3 está desbloqueada, pero todavía no ha comenzado.**
