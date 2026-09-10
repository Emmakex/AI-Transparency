# Phase 7 — Contextual Support / Custom Integration

[English](#english) · [Español](#español)

Status: **closed — accepted, merged and verified end-to-end**  
Last reviewed / Última revisión: **10 September 2026 / 10 de septiembre de 2026**

Canonical destination: `https://kairoseth.com/custom-requests`

---

## English

### Goal achieved

Phase 7 adds an explicit, optional and privacy-bounded path from the WordPress plugin to Kairoseth support/custom work without weakening the local-first product.

The user deliberately chooses to leave the WordPress admin surface. The plugin does not automatically create leads, transmit Registry/evidence content, send telemetry or call Kairoseth in the background.

### Accepted WordPress surface

```text
Tools → AI Transparency Support
capability: manage_options
```

The page is informational and read-only with respect to Registry state.

Accepted actions:

```text
Get support
→ requestType=implementation_support

Request custom integration
→ requestType=third_party_integration
```

Both navigate to the same verified Kairoseth Custom Requests intake with different bounded request types.

### Navigation architecture

```text
administrator
→ Tools → AI Transparency Support
→ page load remains local
→ explicit CTA click
→ plugin builds bounded contextual URL server-side
→ browser navigates to https://kairoseth.com/custom-requests
→ Kairoseth normalizes/uses allowed context
→ user decides what personal/business information to enter
→ explicit consent + submit on Kairoseth
```

No server-to-server request is made by the WordPress plugin.

### Implemented architecture

```text
src/Support/class-supportcontext.php
src/Support/class-supporturlbuilder.php
src/Admin/class-supportpage.php
```

Responsibilities:

```text
SupportContext
  immutable bounded non-sensitive product/host context
  extension identity fixed by plugin code
  plugin and WordPress versions bounded
  WordPress locale normalized to en/es

SupportUrlBuilder
  exact canonical destination validation
  requestType allow-list
  exact query-key allow-list
  RFC3986 query encoding
  fail-closed HTTP/foreign-host/wrong-path/userinfo/port/query/fragment handling

SupportPage
  Tools UI
  manage_options authority
  EN/ES copy
  explicit support/custom actions
  no Registry mutation
  no automatic network request
```

### Context allow-list

The plugin generates only:

```text
source=extension
extensionSlug=ai-transparency
extensionName=Kairoseth AI Transparency
extensionVersion=<real plugin version>
hostPlatform=wordpress
hostPlatformVersion=<real WordPress version>
locale=<current locale normalized to en|es>
requestType=<bounded enum>
```

Accepted `requestType` values:

```text
implementation_support
third_party_integration
business_customization
automation
additional_feature
other
```

### Forbidden automatically transmitted context

The plugin does **not** automatically place any of the following in the contextual URL or a background request:

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

If support requires site URL or diagnostic material, the user chooses whether to provide it on the Kairoseth form.

### Server authority

Trusted context is resolved by plugin/server state:

```text
plugin slug/name = plugin-owned constants
plugin version = KAIROSETH_AI_TRANSPARENCY_VERSION
host platform = wordpress
WordPress version = runtime WordPress state
locale = WordPress locale API
requestType = SupportUrlBuilder allow-list
canonical destination = KAIROSETH_AI_TRANSPARENCY_CUSTOM_REQUESTS_URL
```

Browser/client values do not grant permissions, select the destination or override product identity.

### Destination safety

The accepted destination must satisfy:

```text
scheme = https
host = kairoseth.com
path = /custom-requests
no URL userinfo/password
no custom port
no preloaded query
no fragment
```

Anything else fails closed.

### User-initiated privacy model

```text
GET WordPress support page
→ zero Kairoseth request

explicit CTA click
→ normal browser navigation to Kairoseth
→ bounded technical/product query only
```

The WordPress plugin does not submit the request. Kairoseth owns the form, privacy notice, consent, backend validation, rate limiting and final delivery.

### Local independence

These local capabilities remain operational without using Kairoseth:

```text
Registry
Discovery
Readiness
Disclosure
Evidence Export
```

Kairoseth availability is not an entitlement, licensing or feature-unlock dependency.

### Kairoseth production contract

Production route:

```text
https://kairoseth.com/custom-requests
POST https://kairoseth.com/api/public/custom-requests
```

The Kairoseth backend re-normalizes incoming extension context and discards unknown context fields. The request form requires explicit consent and user-entered request/contact details.

Recipient authority is server-side:

```text
CUSTOM_REQUESTS_TO
→ otherwise SMTP_USER
→ otherwise fail closed
```

No browser, extension query parameter or form field can choose the destination mailbox.

### Validation evidence

WordPress tests prove:

- exact context/query allow-lists;
- bounded plugin/WordPress versions;
- locale normalization;
- every accepted request type and rejection of arbitrary types;
- RFC3986 encoding;
- canonical HTTPS Kairoseth destination only;
- rejection of HTTP, foreign/lookalike host, wrong path, userinfo, custom port, preloaded query and fragment;
- no Kairoseth request on support-page load;
- exact bounded URLs for both CTAs;
- Editor denied;
- 390 px and 200% acceptance;
- axe serious/critical = 0;
- inherited local workflow and Multisite regressions green.

Closure evidence:

```text
Contract PR: #17
Contract CI: #95 / 34405157557 — SUCCESS
Contract merge: cbc04eea07b20af60f3ec4b3621a9aa89c92ae84
Contract post-merge CI: #96 / 34405183831 — SUCCESS

Implementation PR: #18
Accepted head: e49721eb00b85b0a4cfbf72d53e876d80dc96f44
PR-head CI: #101 / 34436069862 — SUCCESS — 8/8 green
Implementation merge: f225646808f604b5758bbc960417451af8c31738
Post-merge main CI: #102 / 34436374187 — SUCCESS — 8/8 green

Kairoseth final merge: 5c01adfd40151da6392c8d780203230c315c19fb
Kairoseth post-merge CI #897 / 34437075381 — SUCCESS
Kairoseth Production Smoke #119 / 34437075355 — SUCCESS
Synthetic SMTP E2E proof #4 / 34437244753 — SUCCESS
Blockers: 0
```

Full evidence: [`PHASE7_RUNTIME_EVIDENCE.md`](PHASE7_RUNTIME_EVIDENCE.md).

### Explicitly deferred

```text
automatic lead submission from WordPress
server-to-server support API from WordPress
uploading Evidence Export files
automatic diagnostic bundle
site URL transmission
Registry/findings transmission
support-ticket history inside WordPress
remote entitlement/licensing
paid local feature gating
in-plugin chat
CRM synchronization from WordPress
plugin telemetry/click tracking
```

### Phase 7 exit

Phase 7 exit: **complete.**

---

## Español

### Objetivo alcanzado

La Fase 7 añade una ruta explícita, opcional y acotada por privacidad desde el plugin WordPress hacia soporte/trabajo personalizado de Kairoseth sin debilitar el funcionamiento local-first.

El usuario decide salir del administrador de WordPress. El plugin no crea leads automáticamente, no transmite Registry/evidencias, no añade telemetría y no llama a Kairoseth en segundo plano.

### Superficie WordPress aceptada

```text
Herramientas → AI Transparency Support
capability: manage_options
```

La página es informativa y de solo lectura respecto al Registry.

Acciones:

```text
Get support
→ requestType=implementation_support

Request custom integration
→ requestType=third_party_integration
```

### Arquitectura de navegación

```text
administrador
→ Herramientas → AI Transparency Support
→ la carga permanece local
→ clic explícito
→ el plugin construye server-side la URL contextual acotada
→ navegador abre https://kairoseth.com/custom-requests
→ Kairoseth normaliza el contexto permitido
→ el usuario decide qué información personal/empresarial introducir
→ consentimiento + envío explícito en Kairoseth
```

El plugin WordPress no realiza una petición server-to-server.

### Arquitectura implementada

```text
src/Support/class-supportcontext.php
src/Support/class-supporturlbuilder.php
src/Admin/class-supportpage.php
```

`SupportContext` mantiene el contexto técnico no sensible e inmutable; `SupportUrlBuilder` aplica allow-lists, codificación y validación fail-closed; `SupportPage` implementa la UI de Herramientas bajo `manage_options`, EN/ES y sin mutar el Registry.

### Allow-list automática

El plugin genera únicamente:

```text
source=extension
extensionSlug=ai-transparency
extensionName=Kairoseth AI Transparency
extensionVersion=<versión real del plugin>
hostPlatform=wordpress
hostPlatformVersion=<versión real de WordPress>
locale=<locale actual normalizado a en|es>
requestType=<enum acotado>
```

Tipos aceptados:

```text
implementation_support
third_party_integration
business_customization
automation
additional_feature
other
```

### Contexto prohibido

No se adjuntan automáticamente URL del sitio, identidad de administrador/cliente, Registry, nombres de sistemas IA, `interaction_context`, Discovery, findings, Disclosure, Evidence Export/firma, inventario de plugins/temas, paths/IP, cookies/nonces/sesión, credenciales/tokens, prompts/conversaciones/contenido cliente, logs, BD ni options arbitrarias.

Si soporte necesita URL del sitio o material diagnóstico, el usuario decide si lo introduce directamente en el formulario de Kairoseth.

### Autoridad server-side

```text
slug/nombre plugin = constantes del plugin
versión plugin = KAIROSETH_AI_TRANSPARENCY_VERSION
host = wordpress
versión WordPress = runtime WordPress
locale = API de locale WordPress
requestType = allow-list SupportUrlBuilder
destino = KAIROSETH_AI_TRANSPARENCY_CUSTOM_REQUESTS_URL
```

El navegador no puede conceder permisos, cambiar la identidad del producto ni seleccionar un destino alternativo.

### Seguridad del destino

Solo se acepta:

```text
https://kairoseth.com/custom-requests
```

HTTP, host distinto/lookalike, ruta incorrecta, userinfo, puerto personalizado, query preexistente o fragment hacen fallar la construcción de forma cerrada.

### Privacidad iniciada por el usuario

```text
GET página soporte WordPress
→ 0 peticiones Kairoseth

clic CTA explícito
→ navegación normal del navegador
→ solo contexto técnico/producto permitido
```

Kairoseth controla el formulario, aviso de privacidad, consentimiento, validación backend, rate limit y entrega final.

### Independencia local

Registry, Discovery, Readiness, Disclosure y Evidence Export continúan funcionando sin utilizar Kairoseth. No existe dependencia de entitlement, licencia ni desbloqueo de funciones.

### Contrato producción Kairoseth

```text
https://kairoseth.com/custom-requests
POST https://kairoseth.com/api/public/custom-requests
```

El backend vuelve a normalizar el contexto de extensión y descarta campos desconocidos. El destinatario se resuelve solo en servidor:

```text
CUSTOM_REQUESTS_TO
→ si no existe, SMTP_USER
→ si tampoco existe, fail closed
```

Ningún valor enviado por navegador, plugin o formulario puede elegir el buzón de destino.

### Evidencia de cierre

```text
PR contrato: #17
CI contrato: #95 / 34405157557 — SUCCESS
Merge contrato: cbc04eea07b20af60f3ec4b3621a9aa89c92ae84
CI post-merge contrato: #96 / 34405183831 — SUCCESS

PR implementación: #18
Head aceptado: e49721eb00b85b0a4cfbf72d53e876d80dc96f44
CI PR: #101 / 34436069862 — SUCCESS — 8/8 verde
Merge implementación: f225646808f604b5758bbc960417451af8c31738
CI main post-merge: #102 / 34436374187 — SUCCESS — 8/8 verde

Merge final Kairoseth: 5c01adfd40151da6392c8d780203230c315c19fb
CI Kairoseth #897 / 34437075381 — SUCCESS
Production Smoke #119 / 34437075355 — SUCCESS
Prueba SMTP E2E #4 / 34437244753 — SUCCESS
Bloqueadores: 0
```

Evidencia completa: [`PHASE7_RUNTIME_EVIDENCE.md`](PHASE7_RUNTIME_EVIDENCE.md).

### Diferido explícitamente

```text
envío automático de leads desde WordPress
API server-to-server de soporte desde WordPress
subida de Evidence Export
bundle diagnóstico automático
transmisión automática de URL del sitio
transmisión de Registry/findings
historial de tickets dentro de WordPress
entitlement/licencias remotas
bloqueo de funciones locales de pago
chat dentro del plugin
sincronización CRM desde WordPress
telemetría/click tracking del plugin
```

Salida Fase 7: **completa.**
