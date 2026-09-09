# Phase 4 — Readiness Findings & Evidence

[English](#english) · [Español](#español)

Status: **active implementation — first deterministic readiness rules**  
Last reviewed / Última revisión: **9 September 2026 / 9 de septiembre de 2026**

---

## English

### Goal

Phase 4 converts current local AI Systems Registry state into reproducible technical readiness findings without turning technical evidence into automatic legal conclusions.

The first increment is intentionally registry-driven. It does not inspect arbitrary page content, infer legal duties or introduce a cloud dependency.

### Evidence classes

Every finding keeps three concepts structurally separate:

```text
FACT
what the plugin can technically observe from current local state

DECLARATION
what an administrator explicitly configured, when relevant

GUIDANCE
what should be reviewed or completed technically
```

A finding is not a certification, legal determination or compliance score.

### Architecture

```text
site-local AI Systems Registry
→ FindingEngine
→ deterministic Finding[]
→ Tools → AI Readiness
```

Findings are computed on demand. They are not persisted in a second database/table/option.

This means correcting the registry changes or removes the corresponding finding on the next evaluation without creating stale duplicate state.

### Finding model

The first immutable model contains:

```text
stable finding id
rule id
category
priority
subject system id
subject system name
fact code
declaration code
guidance code
SHA-256 evidence signature
generated_at metadata
```

`generated_at` is execution metadata only. It does not participate in the stable finding id or evidence signature.

### First deterministic rules

#### 1. Registry review pending

Condition:

```text
system.status = active
system.review_status = pending
```

Output:

```text
FACT: active registry record is pending administrator review
DECLARATION: none
GUIDANCE: complete review of actual use, type, context and transparency configuration
```

Rule id: `registry_review_pending_v1`.

#### 2. Interaction context missing

Condition:

```text
system.status = active
system.interaction_context = empty
```

Output:

```text
FACT: no interaction context is recorded
DECLARATION: none
GUIDANCE: document where/how visitors, staff or customers interact with the system
```

Rule id: `interaction_context_missing_v1`.

#### 3. Configured disclosure review

Condition:

```text
system.status = active
system.interaction_disclosure_required = true
```

Output:

```text
FACT: system is active in the local registry
DECLARATION: administrator configured the workflow as requiring AI interaction disclosure
GUIDANCE: verify that the declared disclosure has an appropriate technical implementation and placement
```

Rule id: `configured_disclosure_review_v1`.

The rule does **not** infer that law requires disclosure. It reports the administrator's explicit configuration and asks for implementation review.

### Archived records

Archived systems do not generate Phase 4 findings. The first engine evaluates only active registry state.

### Determinism

Each finding id is derived from:

```text
finding-{rule id}-{system id}
```

Each evidence signature is SHA-256 over a stable payload containing:

```text
rule id
system id
rule-relevant evidence only
```

The generation timestamp is excluded. Re-running the same rule against the same evidence must produce the same id and signature.

### WordPress administration

**Tools → AI Readiness** is read-only in this increment.

Authorization:

```text
manage_options
```

The page reads current site-local registry state server-side and renders generated findings. Browser parameters do not grant authority or provide finding evidence.

Because the page does not mutate state, no mutation nonce is introduced solely for viewing findings.

### EN/ES and UX

All customer-facing readiness strings ship English and Spanish in the same increment.

Acceptance includes:

```text
390 px viewport
no page-level horizontal overflow
Fact / Administrator declaration / Guidance visibly separated
64-character evidence signature readable/wrapped
serious/critical axe violations = 0
```

### Runtime acceptance path

The first real end-to-end path is:

```text
AI Engine 3.7.7 installed and active
→ deterministic Discovery
→ explicit Add to registry
→ discovered / pending / other / empty context
→ Tools → AI Readiness
→ exactly two initial findings for AI Engine
   - pending review
   - interaction context missing
→ Fact / Declaration / Guidance visible
→ stable 64-char SHA-256 signatures visible
→ responsive + accessibility acceptance green
```

The configured-disclosure rule is covered deterministically in the domain/unit suite and remains dependent on an explicit administrator declaration.

### Privacy and legal boundary

Phase 4 introduces no automatic external telemetry or Kairoseth cloud request.

The engine does not read:

```text
provider credentials
API keys
prompts
conversations
customer content
arbitrary page content
AI-generated-text probabilities
```

It must not emit claims such as `compliant`, `non-compliant`, `illegal`, `certified` or equivalent legal determinations from these technical rules.

### Phase 4 exit

Phase 4 may close only when:

```text
Finding model + engine implemented
+ first rules deterministic
+ EN/ES 100%
+ Tools → AI Readiness accepted
+ responsive/accessibility green
+ exact runtime Discovery → Registry → Readiness path green
+ inherited Phase 2/3 regressions green
+ WordPress Plugin Check green
+ PR merged
+ post-merge main verification green
+ documentation synchronized
+ blockers = 0
```

---

## Español

### Objetivo

La Fase 4 convierte el estado actual del AI Systems Registry local en hallazgos técnicos de readiness reproducibles sin transformar la evidencia técnica en conclusiones legales automáticas.

El primer incremento se basa deliberadamente en el registro. No inspecciona contenido arbitrario de páginas, no infiere obligaciones legales y no introduce dependencia cloud.

### Clases de evidencia

Cada hallazgo mantiene separados estructuralmente tres conceptos:

```text
HECHO
lo que el plugin puede observar técnicamente del estado local actual

DECLARACIÓN
lo que un administrador configuró explícitamente, cuando corresponda

ORIENTACIÓN
lo que debe revisarse o completarse técnicamente
```

Un hallazgo no es una certificación, una decisión legal ni una puntuación de cumplimiento.

### Arquitectura

```text
AI Systems Registry local del sitio
→ FindingEngine
→ Finding[] deterministas
→ Herramientas → AI Readiness
```

Los hallazgos se calculan bajo demanda. No se guardan en una segunda base de datos, tabla u opción.

Por ello, corregir el registro modifica o elimina el hallazgo correspondiente en la siguiente evaluación sin crear estados duplicados obsoletos.

### Modelo de Finding

El primer modelo inmutable contiene:

```text
id estable del hallazgo
id de regla
categoría
prioridad
id del sistema
nombre del sistema
código de hecho
código de declaración
código de orientación
firma SHA-256 de evidencia
generated_at como metadata
```

`generated_at` es solo metadata de ejecución. No participa en el id estable ni en la firma de evidencia.

### Primeras reglas deterministas

#### 1. Revisión del registro pendiente

Condición:

```text
system.status = active
system.review_status = pending
```

Resultado:

```text
HECHO: el registro activo sigue pendiente de revisión del administrador
DECLARACIÓN: ninguna
ORIENTACIÓN: completar la revisión del uso real, tipo, contexto y configuración de transparencia
```

Rule id: `registry_review_pending_v1`.

#### 2. Falta contexto de interacción

Condición:

```text
system.status = active
system.interaction_context = vacío
```

Resultado:

```text
HECHO: no existe contexto de interacción registrado
DECLARACIÓN: ninguna
ORIENTACIÓN: documentar dónde/cómo interactúan visitantes, personal o clientes con el sistema
```

Rule id: `interaction_context_missing_v1`.

#### 3. Revisión de disclosure configurado

Condición:

```text
system.status = active
system.interaction_disclosure_required = true
```

Resultado:

```text
HECHO: el sistema está activo en el registro local
DECLARACIÓN: el administrador configuró el flujo como requiriendo aviso de interacción con IA
ORIENTACIÓN: verificar que el aviso declarado tenga implementación y ubicación técnica apropiadas
```

Rule id: `configured_disclosure_review_v1`.

La regla **no** infiere que la ley obligue a mostrar un aviso. Informa de la configuración explícita del administrador y solicita revisar su implementación.

### Registros archivados

Los sistemas archivados no generan hallazgos de Fase 4. El primer motor evalúa únicamente el estado activo del registro.

### Determinismo

Cada id de finding se deriva de:

```text
finding-{rule id}-{system id}
```

Cada firma de evidencia es SHA-256 sobre un payload estable formado por:

```text
rule id
system id
evidencia relevante para esa regla
```

La fecha de generación queda excluida. La misma regla sobre la misma evidencia debe producir el mismo id y la misma firma.

### Administración WordPress

**Herramientas → AI Readiness** es de solo lectura en este incremento.

Autorización:

```text
manage_options
```

La pantalla lee el registro local actual del sitio en servidor y renderiza los hallazgos generados. Los parámetros del navegador no otorgan autoridad ni proporcionan la evidencia del finding.

Como la pantalla no modifica estado, no se introduce un nonce de mutación únicamente para visualizar hallazgos.

### EN/ES y UX

Todas las cadenas de readiness orientadas al usuario se entregan en inglés y español dentro del mismo incremento.

La aceptación incluye:

```text
viewport 390 px
sin overflow horizontal a nivel de página
Hecho / Declaración del administrador / Orientación visualmente separados
firma de evidencia de 64 caracteres legible y con wrap
violaciones axe serious/critical = 0
```

### Ruta de aceptación runtime

El primer recorrido end-to-end real es:

```text
AI Engine 3.7.7 instalado y activo
→ Discovery determinista
→ Add to registry explícito
→ discovered / pending / other / contexto vacío
→ Herramientas → AI Readiness
→ exactamente dos hallazgos iniciales para AI Engine
   - revisión pendiente
   - contexto de interacción ausente
→ Hecho / Declaración / Orientación visibles
→ firmas SHA-256 estables de 64 caracteres visibles
→ responsive + accesibilidad verdes
```

La regla de disclosure configurado se cubre de forma determinista en los tests de dominio/unidad y depende siempre de una declaración explícita del administrador.

### Límite de privacidad y legal

La Fase 4 no introduce telemetría externa automática ni solicitudes al cloud de Kairoseth.

El motor no lee:

```text
credenciales de proveedores
API keys
prompts
conversaciones
contenido de clientes
contenido arbitrario de páginas
probabilidades de texto generado por IA
```

No debe emitir afirmaciones como `compliant`, `non-compliant`, `illegal`, `certified` ni decisiones legales equivalentes a partir de estas reglas técnicas.

### Cierre de Fase 4

La Fase 4 solo puede cerrarse cuando:

```text
modelo Finding + motor implementados
+ primeras reglas deterministas
+ EN/ES 100%
+ Herramientas → AI Readiness aceptado
+ responsive/accesibilidad verdes
+ recorrido real Discovery → Registry → Readiness verde
+ regresiones heredadas de Fases 2/3 verdes
+ WordPress Plugin Check verde
+ PR fusionado
+ verificación post-merge de main verde
+ documentación sincronizada
+ blockers = 0
```
