# Phase 5 — Disclosure Tooling

[English](#english) · [Español](#español)

Status: **closed — accepted, merged and verified on `main`**  
Last reviewed / Última revisión: **9 September 2026 / 9 de septiembre de 2026**

```text
Implementation PR: #12
Accepted head: 37ac6f8a3adf6ae33c98910fc0b2ff816789a697
PR-head CI: #82 / 34394624556
Merge: 2770c7b7982ffbbe07ba58e8cedebd12d0add14a
Post-merge CI: #83 / 34395173777
Blockers: 0
```

---

## English

### Goal achieved

Phase 5 converts explicitly reviewed registry state into an accessible frontend AI transparency notice for a deliberately supported WordPress placement.

It does **not** decide whether law requires a notice. It renders only from administrator-configured state that satisfies the accepted technical eligibility contract.

### Accepted workflow

```text
active AiSystem
+ review_status = reviewed
+ non-empty interaction_context
+ interaction_disclosure_required = true
→ Tools → AI Disclosure = Ready
→ administrator places
   [kairoseth_ai_disclosure system="SYSTEM_ID"]
→ server resolves current site-local registry state
→ DisclosureEngine evaluates eligibility
→ localized accessible frontend notice
```

The first accepted placement is normal singular WordPress post/page content. The normal WordPress Shortcode block is compatible because it stores the same shortcode contract.

### Implemented architecture

```text
site-local AI Systems Registry
→ exact AiSystem lookup
→ DisclosureEngine
   ├ deterministic eligibility
   ├ deterministic reason codes
   └ Disclosure model or no disclosure
→ DisclosureShortcode
→ escaped semantic frontend markup
```

Implemented files:

```text
src/Disclosure/class-disclosure.php
src/Disclosure/class-disclosureengine.php
src/Disclosure/class-disclosureshortcode.php
src/Admin/class-disclosurepage.php
assets/frontend.css
tests/DisclosureEngineTest.php
tests/e2e/admin.spec.js
```

No Phase 5 registry migration was needed. Registry schema v1 remains authoritative.

### Eligibility contract

A system is eligible only when all four conditions are true:

```text
status = active
review_status = reviewed
interaction_disclosure_required = true
trim(interaction_context) != empty
```

The deterministic engine exposes bounded reason codes for ineligible records:

```text
archived
pending_review
missing_interaction_context
disclosure_not_configured
```

`source_origin` does not grant eligibility. The shortcode system id is only a selector: it cannot override current server-side registry state.

Missing, unknown or ineligible systems return no disclosure markup.

### Bounded Disclosure model

The immutable public `Disclosure` model contains only:

```text
internal subject system id
administrator-reviewed system name
stable copy version = inline_v1
```

The internal id is used for the bounded model/lookup relationship but is not emitted in public disclosure markup.

### Public copy

Accepted localized contract:

```text
EN title:
AI transparency notice

EN body:
This interaction uses the AI system “%s”. Review important information or outcomes before relying on them.

ES title:
Aviso de transparencia de IA

ES body:
Esta interacción utiliza el sistema de IA «%s». Revisa la información o los resultados importantes antes de confiar en ellos.
```

`%s` is the escaped administrator-reviewed system name.

The copy makes no claim of legal duty, certification, compliance, non-compliance or legal sufficiency.

### Frontend renderer

The accepted renderer produces simple semantic server-rendered markup:

```html
<aside class="ai-transparency-disclosure" aria-label="AI transparency notice">
  <strong class="ai-transparency-disclosure__title">AI transparency notice</strong>
  <p class="ai-transparency-disclosure__body">…</p>
</aside>
```

Properties:

- no JavaScript dependency;
- localized visible title and `aria-label`;
- narrowly scoped `.ai-transparency-disclosure` CSS namespace;
- stylesheet loaded only for supported singular content containing the shortcode;
- production build fails if `assets/frontend.css` is absent.

### Administrator UX

**Tools → AI Disclosure** is a read-only `manage_options` surface.

For each registry system it displays:

```text
Ready
→ exact shortcode

Not ready
→ one or more deterministic reason labels
→ link back to registry edit workflow
```

All configuration still occurs through **Tools → AI Transparency**, preserving the existing capability + nonce mutation boundary.

Editors/non-administrators cannot access the Disclosure administration screen.

### Security and privacy boundary

The public shortcode flow is:

```text
attribute selector
→ sanitize + bound
→ exact site-local registry lookup
→ server-side eligibility
→ bounded Disclosure
→ escaped output
```

Public rendering may expose only:

```text
localized disclosure copy
reviewed system name
```

It does not expose automatically:

```text
interaction_context
system id in markup
source/source_origin
review timestamps
provider/model configuration
credentials or API keys
prompts or conversations
customer content
private logs
```

Phase 5 introduces no automatic telemetry, cookies, remote API request or Kairoseth cloud dependency.

### Runtime acceptance

The authoritative E2E uses unique retry-safe data and proves:

```text
admin creates reviewed configured AI system
→ Tools → AI Disclosure reports Ready
→ exact shortcode generated
→ real public WordPress page published
→ anonymous visitor sees disclosure
→ private interaction_context absent
→ frontend stylesheet present
→ 390 px responsive acceptance green
→ 200% text-size acceptance green
→ serious/critical axe violations = 0
→ administrator disables disclosure
→ system becomes Not ready
→ same public page no longer renders disclosure
```

The inherited Registry, Discovery, Readiness and Multisite contracts also remain green.

Full evidence: [`PHASE5_RUNTIME_EVIDENCE.md`](PHASE5_RUNTIME_EVIDENCE.md).  
Acceptance checklist: [`PHASE5_ACCEPTANCE.md`](PHASE5_ACCEPTANCE.md).

### Resolved CI incident

CI #81 failed PHP Quality because of three WPCS assignment-alignment warnings in `class-disclosurepage.php`. There were zero code errors and no behavioral defect.

The assignments were aligned and the exact fixed head then passed CI #82 8/8. Post-merge CI #83 also passed 8/8.

### Explicitly deferred

The accepted first increment does not claim or implement:

```text
native Gutenberg custom block
widget/template automatic placement
third-party chatbot DOM injection
integration-specific JavaScript adapters
third-party cache purge integrations
universal cache/theme compatibility
custom arbitrary HTML disclosure copy
network-wide Multisite disclosure registry
automatic legal classification
```

### Exit

**Complete.** Phase 5 is accepted at merge `2770c7b7982ffbbe07ba58e8cedebd12d0add14a`; PR-head CI #82 and post-merge CI #83 are green, and blockers are zero.

---

## Español

### Objetivo alcanzado

La Fase 5 convierte el estado del registro revisado explícitamente en un aviso accesible de transparencia de IA para una ubicación de WordPress soportada de forma deliberada.

No decide si la ley exige un aviso. Solo renderiza desde un estado configurado por el administrador que cumple el contrato técnico de elegibilidad aceptado.

### Flujo aceptado

```text
AiSystem activo
+ review_status = reviewed
+ interaction_context no vacío
+ interaction_disclosure_required = true
→ Herramientas → AI Disclosure = Ready
→ el administrador coloca
   [kairoseth_ai_disclosure system="SYSTEM_ID"]
→ el servidor resuelve el registro local actual
→ DisclosureEngine evalúa la elegibilidad
→ aviso frontend localizado y accesible
```

La primera ubicación aceptada es contenido normal de entradas/páginas singulares de WordPress. El bloque Shortcode normal es compatible porque guarda el mismo contrato.

### Arquitectura implementada

```text
AI Systems Registry local del sitio
→ lookup exacto de AiSystem
→ DisclosureEngine
   ├ elegibilidad determinista
   ├ reason codes deterministas
   └ modelo Disclosure o ningún aviso
→ DisclosureShortcode
→ markup frontend semántico y escapado
```

Archivos implementados:

```text
src/Disclosure/class-disclosure.php
src/Disclosure/class-disclosureengine.php
src/Disclosure/class-disclosureshortcode.php
src/Admin/class-disclosurepage.php
assets/frontend.css
tests/DisclosureEngineTest.php
tests/e2e/admin.spec.js
```

No fue necesaria ninguna migración del registro. El schema v1 continúa siendo autoritativo.

### Contrato de elegibilidad

Un sistema solo es elegible cuando se cumplen las cuatro condiciones:

```text
status = active
review_status = reviewed
interaction_disclosure_required = true
trim(interaction_context) != vacío
```

El motor expone motivos acotados y deterministas para los registros no elegibles:

```text
archived
pending_review
missing_interaction_context
disclosure_not_configured
```

`source_origin` no concede elegibilidad. El id del shortcode es únicamente un selector y no puede sobrescribir el estado actual del registro en servidor.

Un sistema inexistente, desconocido o no elegible no genera markup de disclosure.

### Modelo Disclosure acotado

El modelo público e inmutable `Disclosure` contiene únicamente:

```text
id interno del sistema
nombre del sistema revisado por el administrador
versión estable de copy = inline_v1
```

El id interno sirve para la relación acotada de lookup/modelo, pero no se emite en el markup público del aviso.

### Copy público

Contrato localizado aceptado:

```text
Título EN:
AI transparency notice

Texto EN:
This interaction uses the AI system “%s”. Review important information or outcomes before relying on them.

Título ES:
Aviso de transparencia de IA

Texto ES:
Esta interacción utiliza el sistema de IA «%s». Revisa la información o los resultados importantes antes de confiar en ellos.
```

`%s` es el nombre escapado del sistema revisado por el administrador.

El texto no afirma obligación legal, certificación, cumplimiento, incumplimiento ni suficiencia jurídica.

### Renderer frontend

El renderer aceptado produce markup semántico simple renderizado en servidor, sin JavaScript.

Propiedades:

- título visible y `aria-label` localizados;
- namespace CSS limitado a `.ai-transparency-disclosure`;
- stylesheet cargado solo en contenido singular soportado que contiene el shortcode;
- el build de producción falla si falta `assets/frontend.css`.

### UX de administración

**Herramientas → AI Disclosure** es una superficie de solo lectura protegida por `manage_options`.

Para cada sistema muestra:

```text
Ready
→ shortcode exacto

Not ready
→ uno o varios motivos deterministas
→ enlace al flujo de edición del registro
```

La configuración sigue realizándose en **Herramientas → AI Transparency**, conservando capability + nonce para las mutaciones.

Los editores/no administradores no pueden acceder a la administración de Disclosure.

### Seguridad y privacidad

El flujo del shortcode público es:

```text
selector del atributo
→ sanitizar + acotar
→ lookup exacto del registro local
→ elegibilidad server-side
→ Disclosure acotado
→ salida escapada
```

La salida pública solo puede exponer:

```text
copy localizado
nombre revisado del sistema
```

No expone automáticamente `interaction_context`, id del sistema en markup, source/origin, timestamps, proveedor/modelo, credenciales, prompts, conversaciones, contenido de clientes ni logs privados.

Fase 5 no introduce telemetría automática, cookies, API remota ni dependencia del cloud de Kairoseth.

### Aceptación runtime

El E2E autoritativo utiliza datos únicos y retry-safe y demuestra:

```text
administrador crea sistema revisado y configurado
→ AI Disclosure = Ready
→ shortcode exacto
→ página WordPress pública real
→ visitante anónimo ve el aviso
→ interaction_context privado ausente
→ stylesheet frontend presente
→ responsive 390 px verde
→ texto 200% verde
→ axe serious/critical = 0
→ administrador desactiva disclosure
→ sistema = Not ready
→ la misma página deja de mostrar el aviso
```

También permanecen verdes Registry, Discovery, Readiness y el aislamiento Multisite heredados.

Evidencia completa: [`PHASE5_RUNTIME_EVIDENCE.md`](PHASE5_RUNTIME_EVIDENCE.md).  
Checklist: [`PHASE5_ACCEPTANCE.md`](PHASE5_ACCEPTANCE.md).

### Incidencia CI resuelta

CI #81 falló PHP Quality únicamente por tres warnings WPCS de alineación de asignaciones en `class-disclosurepage.php`. No hubo errores de código ni defecto de comportamiento.

Tras alinear las asignaciones, CI #82 pasó 8/8 y CI #83 post-merge volvió a pasar 8/8.

### Diferido explícitamente

El primer incremento aceptado no implementa ni afirma compatibilidad con:

```text
bloque Gutenberg nativo
colocación automática en widgets/templates
inyección en DOM de chatbots de terceros
adaptadores JavaScript por integración
purga de cachés de terceros
compatibilidad universal de caché/tema
HTML arbitrario de disclosure por sistema
registro Multisite global de red
clasificación legal automática
```

### Salida

**Completa.** La Fase 5 queda aceptada en el merge `2770c7b7982ffbbe07ba58e8cedebd12d0add14a`, con CI #82 y CI post-merge #83 verdes y blockers = 0.
