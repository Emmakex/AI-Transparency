# Phase 5 — Disclosure Tooling

[English](#english) · [Español](#español)

Status: **active design — implementation not started**  
Last reviewed / Última revisión: **9 September 2026 / 9 de septiembre de 2026**

---

## English

### Goal

Phase 5 turns an administrator-reviewed registry declaration into an explicit, accessible frontend AI transparency notice for a deliberately supported placement.

The phase does **not** decide whether law requires a notice. It only renders a notice when the site administrator has explicitly configured and reviewed the corresponding AI workflow.

### First supported workflow

The first increment supports a **manually placed inline disclosure in WordPress post/page content** through a shortcode:

```text
[kairoseth_ai_disclosure system="SYSTEM_ID"]
```

This gives the administrator explicit control over placement and avoids unsafe automatic content injection.

The Shortcode block in the WordPress editor is part of this supported placement because it stores the same shortcode in normal post/page content.

The first increment does not claim support for automatic placement in widgets, navigation, arbitrary theme templates, AJAX fragments, feeds or third-party application UIs.

### Registry authority

No Phase 5 schema migration is required for the first increment.

The current `AiSystem` already provides the required authority fields:

```text
status
review_status
interaction_context
interaction_disclosure_required
```

A system is eligible to render a disclosure only when **all** of these conditions are true:

```text
status = active
review_status = reviewed
interaction_disclosure_required = true
trim(interaction_context) != empty
```

`source_origin` does not grant or deny eligibility by itself. A manual, discovered or imported record can become eligible only after the same administrator-review conditions are satisfied.

If the system id is missing, unknown or ineligible, the public shortcode returns no disclosure markup.

### Why reviewed + context are blocking

The plugin must not publish a frontend notice from an unreviewed detector candidate or an incomplete registry record.

The existing Phase 4 findings already identify pending review and missing interaction context. Phase 5 converts only corrected, explicit registry state into a public component.

### Proposed domain architecture

```text
site-local AI Systems Registry
→ exact AiSystem lookup
→ DisclosureEngine
   ├ eligibility evaluation
   ├ deterministic reason codes
   └ eligible Disclosure model or no disclosure
→ DisclosureShortcode
→ escaped accessible frontend markup
```

Planned classes:

```text
src/Disclosure/class-disclosure.php
src/Disclosure/class-disclosureengine.php
src/Disclosure/class-disclosureshortcode.php
src/Admin/class-disclosurepage.php
```

The exact file split may be adjusted during implementation if a smaller structure remains clearer, but the authority boundaries below are blocking.

### Disclosure model

The first immutable `Disclosure` model should contain only the bounded public rendering context needed by the component:

```text
subject system id       internal lookup identity
subject system name     public notice value
copy/version code       stable renderer contract
```

The model must not expose the registry's internal interaction-context description, source metadata, review timestamps or provider information to the frontend.

### Deterministic public copy

Initial copy contract:

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

The notice reports the configured workflow. It does not state that a legal duty exists, that the implementation is legally sufficient, or that the site is compliant/certified.

### Frontend markup contract

The first renderer should emit simple semantic markup with no JavaScript dependency, for example:

```html
<aside class="ai-transparency-disclosure" aria-label="AI transparency notice">
  <strong class="ai-transparency-disclosure__title">AI transparency notice</strong>
  <p class="ai-transparency-disclosure__body">…</p>
</aside>
```

The actual localized `aria-label` must use the same EN/ES catalog as the visible title.

The component must:

```text
remain readable at 200% text zoom
wrap long system names
avoid fixed heights/widths that clip content
not depend on color alone
have no motion requirement
create no keyboard trap
produce serious/critical axe violations = 0
```

### Styling scope

Phase 5 may add a small plugin-owned frontend stylesheet. The accepted target is normal singular WordPress post/page content.

The implementation should avoid globally invasive selectors and use the `ai-transparency-disclosure` namespace.

If style loading is conditional, the condition must match the supported shortcode placement. The shortcode's semantic content must remain understandable even if theme CSS changes presentation.

### Administrator UX

A new read-only **Tools → AI Disclosure** screen is planned.

It requires:

```text
manage_options
```

The page should show current registry systems and their deterministic disclosure readiness:

```text
Ready
→ exact shortcode available for copy/use

Not ready
→ one or more bounded reasons such as:
   pending_review
   missing_interaction_context
   disclosure_not_configured
   archived
```

The page does not mutate registry state. Configuration continues to happen in **Tools → AI Transparency**, where existing capability + nonce protection remains authoritative.

The Disclosure screen should link the administrator back to the registry edit workflow when a system is not ready.

### Shortcode security boundary

The shortcode attribute is a lookup selector, not authority.

```text
shortcode system id
→ sanitize/bound
→ exact server-side registry lookup
→ server-side eligibility evaluation
→ escaped output
```

A page author cannot make an ineligible record publish a notice merely by supplying its id.

The public shortcode does not require a WordPress capability because the accepted disclosure is intentionally public content. The privileged configuration/admin surfaces remain protected by `manage_options`.

### Privacy boundary

The rendered notice may expose only information intentionally needed for the public disclosure:

```text
localized notice copy
administrator-reviewed system name
```

It must not render automatically:

```text
interaction_context
system id
source/source_origin
review timestamps
provider/model configuration
API keys or credentials
prompts or conversations
customer content
private logs
```

Phase 5 introduces no automatic telemetry, cookies, remote API call or Kairoseth cloud dependency.

### No automatic placement

The first increment must not:

- scan arbitrary page text for AI content;
- inject a notice because AI Engine or another plugin is merely present;
- modify chatbot DOM owned by another plugin;
- guess where the relevant interaction occurs;
- turn a Phase 4 finding into frontend output automatically.

The administrator must deliberately review the system, configure disclosure, document context and place the shortcode in the intended interaction context.

### Cache and theme boundary

The disclosure output is server-rendered and contains no user-specific state, so it is suitable for ordinary page caching.

The first acceptance target does **not** claim universal cache-plugin invalidation. If a third-party full-page cache stores an older rendered page after registry configuration changes, that cache may require its normal purge workflow.

Phase 5 acceptance will verify:

```text
core WordPress runtime
supported singular page/post placement
current test theme/runtime
responsive/accessibility behavior
no user-specific output
correct output immediately after registry changes in the uncached acceptance runtime
```

Named third-party cache/theme integrations require their own explicit compatibility evidence before being claimed.

### First runtime path

The planned authoritative E2E path is:

```text
administrator creates/uses one dedicated runtime AI system
→ status active
→ review reviewed
→ interaction context non-empty
→ interaction disclosure configured = true
→ Tools → AI Disclosure reports Ready
→ administrator places generated shortcode on a public page
→ anonymous visitor opens the page
→ localized AI transparency notice is visible
→ internal interaction-context text is not exposed
→ 390 px responsive gate green
→ serious/critical axe violations = 0
→ administrator disables disclosure configuration
→ same shortcode no longer renders the notice
```

The acceptance data must use a unique runtime record so Playwright retries do not depend on a clean global registry.

### Phase 5 exit

Phase 5 may close only when:

```text
Disclosure model/engine implemented
+ exact eligibility rules deterministic
+ Tools → AI Disclosure accepted
+ shortcode public renderer accepted
+ no automatic placement
+ EN/ES 100%
+ accessibility/responsive green
+ public output excludes internal registry context
+ disabling eligibility removes output
+ inherited Phase 2/3/4 regressions green
+ WordPress Plugin Check green
+ PR merged
+ post-merge main verification green
+ documentation synchronized
+ blockers = 0
```

---

## Español

### Objetivo

La Fase 5 convierte una declaración del registro revisada por un administrador en un aviso explícito y accesible de transparencia de IA para una ubicación soportada de forma deliberada.

La fase **no** decide si la ley exige un aviso. Solo renderiza el aviso cuando el administrador del sitio ha configurado y revisado explícitamente el flujo de IA correspondiente.

### Primer flujo soportado

El primer incremento soporta un **aviso inline colocado manualmente en el contenido de entradas/páginas de WordPress** mediante shortcode:

```text
[kairoseth_ai_disclosure system="SYSTEM_ID"]
```

Esto da al administrador control explícito sobre la ubicación y evita inyecciones automáticas inseguras.

El bloque Shortcode del editor de WordPress forma parte de la ubicación soportada porque guarda el mismo shortcode en contenido normal de entrada/página.

El primer incremento no afirma compatibilidad con colocación automática en widgets, navegación, templates arbitrarios del tema, fragmentos AJAX, feeds o interfaces de aplicaciones de terceros.

### Autoridad del registro

El primer incremento no necesita migrar el schema de Fase 2.

El `AiSystem` actual ya aporta los campos de autoridad necesarios:

```text
status
review_status
interaction_context
interaction_disclosure_required
```

Un sistema solo puede renderizar un aviso cuando **todas** estas condiciones son verdaderas:

```text
status = active
review_status = reviewed
interaction_disclosure_required = true
trim(interaction_context) != vacío
```

`source_origin` no concede ni elimina elegibilidad por sí solo. Un registro manual, descubierto o importado solo puede ser elegible después de cumplir las mismas condiciones de revisión administrativa.

Si el id del sistema falta, es desconocido o no es elegible, el shortcode público no devuelve markup de disclosure.

### Por qué revisión + contexto son bloqueantes

El plugin no debe publicar un aviso frontend desde un candidato detectado sin revisar ni desde un registro incompleto.

Los hallazgos de Fase 4 ya identifican revisión pendiente y ausencia de contexto. Fase 5 transforma en componente público únicamente el estado explícito y corregido del registro.

### Arquitectura propuesta

```text
AI Systems Registry local del sitio
→ lookup exacto de AiSystem
→ DisclosureEngine
   ├ evaluación de elegibilidad
   ├ reason codes deterministas
   └ modelo Disclosure elegible o ningún disclosure
→ DisclosureShortcode
→ markup frontend accesible y escapado
```

Clases previstas:

```text
src/Disclosure/class-disclosure.php
src/Disclosure/class-disclosureengine.php
src/Disclosure/class-disclosureshortcode.php
src/Admin/class-disclosurepage.php
```

La división exacta de archivos puede simplificarse durante la implementación si una estructura menor resulta más clara, pero los límites de autoridad definidos aquí son bloqueantes.

### Modelo Disclosure

El primer modelo inmutable `Disclosure` debe contener solo el contexto público limitado necesario para renderizar:

```text
id del sistema          identidad interna para lookup
nombre del sistema      valor público del aviso
código de copy/versión  contrato estable del renderer
```

El modelo no debe exponer al frontend la descripción interna de `interaction_context`, metadata de origen, timestamps de revisión ni información de proveedores.

### Copy público determinista

Contrato inicial:

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

El aviso informa sobre el flujo configurado. No afirma que exista una obligación legal, que la implementación sea jurídicamente suficiente ni que el sitio esté compliant/certificado.

### Contrato de markup frontend

El primer renderer debe emitir markup semántico simple, sin dependencia de JavaScript, por ejemplo:

```html
<aside class="ai-transparency-disclosure" aria-label="Aviso de transparencia de IA">
  <strong class="ai-transparency-disclosure__title">Aviso de transparencia de IA</strong>
  <p class="ai-transparency-disclosure__body">…</p>
</aside>
```

El `aria-label` localizado debe utilizar el mismo catálogo EN/ES que el título visible.

El componente debe:

```text
seguir legible con zoom de texto al 200%
hacer wrap de nombres largos
no usar alturas/anchuras fijas que recorten contenido
no depender únicamente del color
no requerir movimiento
no crear keyboard traps
producir 0 violaciones axe serious/critical
```

### Alcance de estilos

Fase 5 puede añadir un stylesheet frontend pequeño y propio del plugin. El target aceptado es contenido normal de entradas/páginas singulares de WordPress.

La implementación debe evitar selectores globales invasivos y usar el namespace `ai-transparency-disclosure`.

Si la carga del estilo es condicional, la condición debe coincidir con la ubicación de shortcode soportada. El contenido semántico debe seguir siendo comprensible aunque el CSS del tema modifique la presentación.

### UX de administración

Se prevé una nueva pantalla de solo lectura **Herramientas → AI Disclosure**.

Requiere:

```text
manage_options
```

La pantalla mostrará sistemas actuales y su readiness determinista para disclosure:

```text
Ready
→ shortcode exacto disponible para copiar/usar

Not ready
→ uno o varios motivos acotados, por ejemplo:
   pending_review
   missing_interaction_context
   disclosure_not_configured
   archived
```

La pantalla no modifica el registro. La configuración continúa en **Herramientas → AI Transparency**, donde siguen siendo autoritativos capability + nonce.

Cuando un sistema no esté listo, la pantalla debe enlazar al flujo de edición del registro.

### Límite de seguridad del shortcode

El atributo del shortcode es un selector de lookup, no autoridad.

```text
id del shortcode
→ sanitizar/acotar
→ lookup exacto en el registro server-side
→ evaluación de elegibilidad server-side
→ salida escapada
```

Un autor de página no puede hacer que un registro no elegible publique un aviso simplemente indicando su id.

El shortcode público no requiere capability porque el disclosure aceptado está diseñado como contenido público. Las superficies privilegiadas de configuración/administración siguen protegidas por `manage_options`.

### Límite de privacidad

El aviso renderizado solo puede exponer lo necesario para el disclosure público:

```text
copy localizado
nombre del sistema revisado por administrador
```

No debe renderizar automáticamente:

```text
interaction_context
id del sistema
source/source_origin
timestamps de revisión
configuración de proveedor/modelo
API keys o credenciales
prompts o conversaciones
contenido de clientes
logs privados
```

Fase 5 no introduce telemetría automática, cookies, llamadas API remotas ni dependencia del cloud de Kairoseth.

### Sin colocación automática

El primer incremento no debe:

- escanear texto arbitrario buscando contenido de IA;
- inyectar un aviso porque AI Engine u otro plugin simplemente esté presente;
- modificar el DOM de un chatbot propiedad de otro plugin;
- adivinar dónde ocurre la interacción relevante;
- convertir automáticamente un finding de Fase 4 en salida frontend.

El administrador debe revisar deliberadamente el sistema, configurar disclosure, documentar el contexto y colocar el shortcode en el contexto de interacción correcto.

### Límite de caché y tema

El disclosure se renderiza server-side y no contiene estado específico del usuario, por lo que es apto para caché normal de páginas.

El primer acceptance target **no** afirma invalidación universal de plugins de caché. Si un full-page cache externo conserva una página antigua después de cambiar la configuración del registro, puede requerir su flujo habitual de purge.

Fase 5 verificará:

```text
runtime WordPress core
ubicación soportada en entrada/página singular
tema/runtime actual de pruebas
responsive/accesibilidad
sin salida específica del usuario
salida correcta inmediatamente después de cambios de registro en el runtime de aceptación sin caché
```

Cualquier integración específica con caché/tema de terceros necesita evidencia propia antes de anunciar compatibilidad.

### Primer recorrido runtime

El E2E autoritativo previsto es:

```text
administrador crea/usa un sistema IA runtime dedicado
→ status active
→ review reviewed
→ interaction context no vacío
→ interaction disclosure configured = true
→ Herramientas → AI Disclosure muestra Ready
→ administrador coloca el shortcode generado en una página pública
→ visitante anónimo abre la página
→ aviso localizado de transparencia de IA visible
→ interaction_context interno no se expone
→ gate responsive 390 px verde
→ violaciones axe serious/critical = 0
→ administrador desactiva la configuración de disclosure
→ el mismo shortcode deja de renderizar el aviso
```

Los datos de aceptación deben usar un registro runtime único para que los retries de Playwright no dependan de un registro global limpio.

### Cierre de Fase 5

Fase 5 solo puede cerrarse cuando:

```text
modelo/motor Disclosure implementados
+ reglas exactas de elegibilidad deterministas
+ Herramientas → AI Disclosure aceptado
+ renderer público shortcode aceptado
+ sin colocación automática
+ EN/ES 100%
+ accesibilidad/responsive verdes
+ salida pública no expone contexto interno del registro
+ desactivar elegibilidad elimina la salida
+ regresiones heredadas Fases 2/3/4 verdes
+ WordPress Plugin Check verde
+ PR fusionado
+ verificación post-merge de main verde
+ documentación sincronizada
+ blockers = 0
```
