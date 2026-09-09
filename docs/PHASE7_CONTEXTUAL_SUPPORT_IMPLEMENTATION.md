# Phase 7 — Contextual Support / Custom Integration

[English](#english) · [Español](#español)

Status: **active contract — implementation not started**  
Last reviewed / Última revisión: **9 September 2026 / 9 de septiembre de 2026**

---

## English

### Goal

Phase 7 adds an explicit, optional and privacy-bounded path from the WordPress plugin to Kairoseth support/custom work without weakening the local-first Free product.

The user must deliberately choose to leave the WordPress admin surface. The plugin must not automatically create leads, transmit Registry/evidence content, send telemetry or call Kairoseth in the background.

### Product boundary

The accepted commercial model for this plugin remains:

```text
useful local Free product
→ optional Kairoseth support/custom CTA
→ user explicitly opens Kairoseth
→ user reviews/submits a request there
→ no local feature becomes conditional on submission
```

Phase 7 does **not** introduce:

```text
paid local feature locks
trial expiry
remote entitlement checks
mandatory Kairoseth account
background lead submission
automatic telemetry
cloud dependency for Registry/Discovery/Readiness/Disclosure/Evidence Export
```

### First supported WordPress surface

Planned first surface:

```text
Tools → AI Transparency Support
```

Access:

```text
manage_options
```

The page is informational and read-only with respect to Registry state.

It should provide two clearly separated user-initiated actions:

```text
Get support
Request custom integration
```

Both actions may resolve to the same Kairoseth Custom Requests intake with different bounded `requestType` values.

### Kairoseth dependency

The final destination belongs to the shared **Kairoseth Platform Custom Requests** module.

Phase 7 may not hard-code an invented production route while that route is unverified.

Implementation requires one verified canonical HTTPS destination under the Kairoseth-controlled domain:

```text
https://kairoseth.com/<verified-custom-request-route>
```

The exact route becomes a release-authoritative plugin constant/config value only after the destination exists and is validated end-to-end.

Phase 7 can be implemented and accepted only when the real target is available. Documentation may proceed before that dependency.

### Navigation architecture

Accepted direction:

```text
administrator
→ Tools → AI Transparency Support
→ explicit click
→ plugin builds bounded contextual URL server-side
→ browser navigates to canonical Kairoseth HTTPS destination
→ Kairoseth form shows context
→ user decides what personal/business information to submit
```

No server-to-server request is required from the WordPress plugin for the first increment.

### Context allow-list

The plugin may include only non-sensitive technical/product context needed to prefill the Kairoseth request surface.

Allowed initial context:

```text
source=extension
extensionSlug=ai-transparency
extensionName=Kairoseth AI Transparency
extensionVersion=<real plugin version>
hostPlatform=wordpress
hostPlatformVersion=<real WordPress version>
locale=<current locale>
requestType=<bounded enum>
```

Accepted first `requestType` values:

```text
implementation_support
third_party_integration
business_customization
automation
additional_feature
other
```

The plugin may add a canonical Kairoseth product route later when that route exists and is verified.

### Forbidden automatically transmitted context

Phase 7 must **not** automatically place any of the following in query parameters, POST bodies, headers or background requests:

```text
site URL / home URL
administrator name/email/user id
customer/user identities
Registry contents
AI system names
interaction_context
Discovery evidence
Readiness findings
Disclosure state
Evidence Export JSON or snapshot_signature
plugin/theme inventory
server paths
IP address
cookies/nonces/session data
credentials/API keys/OAuth tokens
prompts/conversations/customer content
logs/debug output
database contents
arbitrary WordPress options
```

If a future workflow genuinely needs site URL or diagnostic material, the user must provide it explicitly on the Kairoseth side under a separate accepted privacy contract.

### Server authority

The browser must not supply trusted product identity or host-version context to the WordPress page for the plugin to echo back blindly.

The plugin resolves context server-side from authoritative constants/functions:

```text
plugin slug/name = plugin-owned constants
plugin version = KAIROSETH_AI_TRANSPARENCY_VERSION
host platform = wordpress
WordPress version = server runtime global/API
locale = WordPress locale API
requestType = server-side allow-list selected by explicit action
canonical Kairoseth base URL = plugin-owned verified HTTPS configuration
```

### URL safety

The contextual destination must satisfy all of these rules:

```text
scheme = https
host = kairoseth.com or explicitly accepted Kairoseth subdomain
path = verified canonical Custom Requests route
no credentials/userinfo in URL
no fragment-based sensitive payload
query keys = allow-list only
query values = bounded + encoded
```

If the configured destination fails validation, the plugin must fail closed and show a bounded local error rather than redirect to an arbitrary host.

### User-initiated privacy model

Opening the CTA is the first network interaction introduced by Phase 7.

```text
page GET inside WordPress
→ no Kairoseth request

explicit CTA click
→ normal browser navigation to Kairoseth
```

The WordPress plugin itself does not submit the lead/request. The Kairoseth destination owns its own form, privacy notice, consent and final submission.

### Local Free independence

All accepted local product capabilities remain available without Phase 7 use:

```text
Registry
Discovery
Readiness
Disclosure
Evidence Export
```

Failure/unavailability of Kairoseth Support/Custom Requests must not break those workflows.

### Planned implementation architecture

```text
src/Support/class-supportcontext.php
src/Support/class-supporturlbuilder.php
src/Admin/class-supportpage.php
```

Responsibilities:

```text
SupportContext
  immutable bounded non-sensitive product/host context

SupportUrlBuilder
  verified Kairoseth base URL
  requestType allow-list
  query allow-list + encoding
  host/scheme validation

SupportPage
  Tools UI
  manage_options
  EN/ES copy
  explicit support/custom actions
  no Registry mutation
```

Exact class names may simplify, but privacy, server-authority and fail-closed URL validation are blocking.

### EN/ES and accessibility

Customer-facing Phase 7 UI must ship English and Spanish together.

Acceptance requires:

```text
390 px
200% text
keyboard navigation
visible focus
no color-only meaning
axe serious/critical = 0
```

The page must clearly state:

- local plugin features continue to work without contacting Kairoseth;
- clicking a CTA opens Kairoseth;
- no Registry/evidence/personal data is automatically attached;
- the user chooses what to submit on the Kairoseth form.

### Analytics / telemetry boundary

The WordPress plugin does not add analytics or telemetry for CTA impressions/clicks in Phase 7.

If Kairoseth Platform measures arrival/submission, that is handled on the Kairoseth destination under its own privacy/analytics contract.

### Validation strategy

Unit tests should prove:

- only accepted `requestType` values build URLs;
- plugin/WordPress version context comes from server-authoritative input;
- context key allow-list is exact;
- forbidden keys cannot be introduced by arbitrary input;
- query values are bounded and encoded;
- non-HTTPS destination fails closed;
- non-Kairoseth host fails closed;
- destination with userinfo/unsafe structure fails closed;
- EN/ES runtime strings remain complete.

Real WordPress acceptance must prove:

```text
administrator opens Tools → AI Transparency Support
→ no automatic network request occurs
→ explicit support CTA contains only bounded context
→ explicit custom CTA contains only bounded context
→ destination host/scheme are canonical
→ Editor denied
→ Registry unchanged
→ existing local workflows remain functional when destination is unreachable
→ 390 px / 200% / axe acceptance green
```

Full end-to-end Phase 7 closure additionally requires the real Kairoseth Custom Requests destination to receive the bounded context and let the user reach a working request form.

### Dependency / blocker

Current external dependency:

```text
verified production Kairoseth Custom Requests route
```

Until that route exists and is validated, Phase 7 may be **contracted** but not declared implemented/closed.

### Explicitly deferred

```text
automatic lead submission
server-to-server support API
uploading Evidence Export files
automatic diagnostic bundle
site URL transmission
Registry/findings transmission
support-ticket history inside WordPress
remote entitlement/licensing
paid local feature gating
in-plugin chat
CRM synchronization from WordPress
telemetry/click tracking in plugin
```

### Phase 7 exit

Phase 7 closes only when:

```text
verified canonical Kairoseth Custom Requests route exists
+ bounded support/custom context implemented
+ HTTPS/Kairoseth host fail-closed validation implemented
+ Tools support surface accepted
+ manage_options enforced
+ no automatic network request on page load
+ no sensitive/Registry/evidence context transmitted
+ local Free workflows remain independent
+ real user-initiated navigation reaches working Kairoseth intake
+ EN/ES 100%
+ responsive/accessibility green
+ required CI green
+ implementation PR merged
+ post-merge main verification green
+ documentation synchronized
+ blockers = 0
```

---

## Español

### Objetivo

La Fase 7 añade una vía explícita, opcional y acotada por privacidad desde el plugin WordPress hacia soporte/trabajo personalizado de Kairoseth sin debilitar el producto Free local-first.

El usuario debe elegir conscientemente salir del admin de WordPress. El plugin no crea leads automáticamente, no transmite Registry/evidencia, no añade telemetría y no llama a Kairoseth en segundo plano.

### Frontera de producto

```text
producto Free local útil
→ CTA opcional de soporte/personalización
→ usuario abre Kairoseth explícitamente
→ usuario revisa y envía la solicitud allí
→ ninguna función local depende del envío
```

No se introducen bloqueos de funciones locales, trial, entitlement remoto, cuenta Kairoseth obligatoria ni dependencia cloud para Registry/Discovery/Readiness/Disclosure/Evidence Export.

### Primera superficie

Objetivo:

```text
Herramientas → AI Transparency Support
```

Solo `manage_options`.

Acciones:

```text
Obtener soporte
Solicitar integración personalizada
```

### Dependencia Kairoseth

El destino pertenece al módulo compartido **Kairoseth Platform Custom Requests**.

No se inventará una ruta productiva. La implementación requiere una URL HTTPS real y verificada bajo dominio Kairoseth:

```text
https://kairoseth.com/<ruta-custom-requests-verificada>
```

Hasta que exista, Fase 7 puede tener contrato pero no cerrarse.

### Contexto permitido

```text
source=extension
extensionSlug=ai-transparency
extensionName=Kairoseth AI Transparency
extensionVersion=<versión real>
hostPlatform=wordpress
hostPlatformVersion=<versión real WordPress>
locale=<locale actual>
requestType=<enum acotado>
```

`requestType` inicial:

```text
implementation_support
third_party_integration
business_customization
automation
additional_feature
other
```

### Contexto prohibido automáticamente

No se transmiten automáticamente:

```text
URL del sitio
identidad/email/id del administrador
identidades de clientes/usuarios
contenido del Registry
nombres de sistemas IA
interaction_context
evidencia Discovery
findings Readiness
estado Disclosure
Evidence Export / snapshot_signature
inventario plugins/themes
rutas servidor
IP
cookies/nonces/sesión
credenciales/tokens
prompts/conversaciones/contenido cliente
logs
BD
options arbitrarias
```

Si en el futuro se necesita información diagnóstica, el usuario la aportará deliberadamente en Kairoseth bajo un contrato de privacidad separado.

### Autoridad y seguridad de URL

Producto, versión, WordPress, locale, `requestType` y URL base se resuelven server-side.

El destino debe cumplir:

```text
HTTPS
host Kairoseth aceptado
ruta Custom Requests verificada
sin userinfo
query allow-list
valores acotados y codificados
```

Una URL inválida falla cerrada y no redirige a hosts arbitrarios.

### Privacidad user-initiated

```text
GET página WordPress
→ cero petición a Kairoseth

click explícito CTA
→ navegación normal del navegador a Kairoseth
```

El plugin no envía la solicitud final. El formulario Kairoseth controla datos personales, privacidad, consentimiento y envío.

### Independencia del Free local

Siguen funcionando sin Kairoseth:

```text
Registry
Discovery
Readiness
Disclosure
Evidence Export
```

Una caída del destino de soporte no puede romperlos.

### Arquitectura planificada

```text
src/Support/class-supportcontext.php
src/Support/class-supporturlbuilder.php
src/Admin/class-supportpage.php
```

### Aceptación

Debe probarse EN/ES, `manage_options`, ausencia de red automática, allow-list estricta, destino HTTPS/Kairoseth, Editor bloqueado, Registry intacto, independencia local, 390 px, 200%, teclado/focus y axe serious/critical = 0.

El cierre E2E requiere además que la navegación real llegue al formulario funcional de Kairoseth Custom Requests con el contexto acotado correcto.

### Cierre

Fase 7 solo se marca `CLOSED` cuando exista la ruta productiva verificada, la integración local esté aceptada, el E2E Kairoseth funcione, CI/post-merge estén verdes, documentación esté sincronizada y bloqueadores = 0.
