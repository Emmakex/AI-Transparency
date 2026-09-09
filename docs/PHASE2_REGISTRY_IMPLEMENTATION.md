# Phase 2 — Persistent AI Systems Registry

[English](#english) · [Español](#español)

Status: **active implementation**  
Last reviewed / Última revisión: **9 September 2026 / 9 de septiembre de 2026**

---

## English

### Goal

Phase 2 turns the bootstrap in-memory registry into a useful local WordPress inventory that an authorized administrator can maintain without sending site data to Kairoseth.

### Current implementation

Implemented in PR #3:

```text
WordPress site/blog
→ wp_options
→ kairoseth_ai_transparency_registry
→ schema_version = 1
→ systems[]
```

Each AI system can currently record:

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

This means Multisite state follows the current WordPress blog/site context by default. Network-wide inventory is not introduced without a separate product contract.

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

### Privacy boundary

Registry operations make no Kairoseth telemetry/network call. Data remains local to the current WordPress site/blog.

### Schema and migration

`RegistrySchema::VERSION = 1` is the canonical persistence shape.

Legacy bootstrap arrays are migrated into v1 defaults without discarding valid records. Invalid individual records are skipped rather than destroying the rest of the registry.

Future unknown schema versions fail safe instead of being interpreted as current data.

### EN/ES

The full registry UI ships in English and Spanish in the same workstream. The existing blocking `EN/ES 100% coverage` CI gate validates all runtime gettext strings and the compiled Spanish `.mo` in the production package.

### Validation added

Unit/contract coverage includes:

- deterministic schema ordering;
- legacy migration;
- invalid-record isolation;
- future-schema fail-safe behavior;
- persistence round trip;
- simulated per-blog/site storage isolation;
- lifecycle/archive metadata preservation.

### Remaining before Phase 2 can close

Phase 2 remains active until the following are accepted:

```text
[ ] CI green on final Phase 2 SHA
[ ] real WordPress add/edit/archive smoke
[ ] unauthorized-role mutation rejection in WordPress runtime
[ ] real Multisite isolation smoke
[ ] responsive admin acceptance
[ ] keyboard/accessibility acceptance
[ ] upgrade/migration acceptance on a real WordPress install
[ ] documentation synchronized
[ ] blockers = 0
```

Phase 3 must not begin before those closure gates are complete.

---

## Español

### Objetivo

La Fase 2 convierte el registro en memoria del bootstrap en un inventario WordPress local y útil que un administrador autorizado puede mantener sin enviar datos del sitio a Kairoseth.

### Implementación actual

Implementado en el PR #3:

```text
sitio/blog WordPress
→ wp_options
→ kairoseth_ai_transparency_registry
→ schema_version = 1
→ systems[]
```

Cada sistema de IA puede guardar actualmente:

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

En Multisite, los datos siguen por defecto el contexto del blog/sitio WordPress actual. No se introduce un inventario global de red sin un contrato de producto separado.

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

### Privacidad

Las operaciones del registro no realizan llamadas de telemetría/red a Kairoseth. Los datos permanecen en el sitio/blog WordPress actual.

### Schema y migración

`RegistrySchema::VERSION = 1` es la estructura canónica de persistencia.

Los arrays legacy del bootstrap se migran a valores v1 sin perder registros válidos. Un registro individual inválido se descarta sin destruir el resto del inventario.

Una versión futura desconocida del schema falla de forma segura y no se interpreta como datos actuales.

### EN/ES

Toda la UI del registro se entrega en inglés y español en el mismo workstream. El gate bloqueante `EN/ES 100% coverage` comprueba las cadenas gettext runtime y el `.mo` español compilado dentro del package de producción.

### Validación añadida

Los tests cubren:

- orden determinista del schema;
- migración legacy;
- aislamiento de registros inválidos;
- fail-safe ante schemas futuros;
- persistencia ida/vuelta;
- aislamiento simulado por blog/sitio;
- preservación de metadata al archivar.

### Pendiente antes de cerrar Fase 2

La Fase 2 sigue activa hasta completar:

```text
[ ] CI verde en SHA final de Fase 2
[ ] smoke real WordPress de alta/edición/archivo
[ ] rechazo runtime de mutaciones por roles no autorizados
[ ] smoke real de aislamiento Multisite
[ ] aceptación responsive del admin
[ ] aceptación teclado/accesibilidad
[ ] aceptación real de upgrade/migración en WordPress
[ ] documentación sincronizada
[ ] blockers = 0
```

La Fase 3 no puede comenzar antes de cerrar estos gates.
