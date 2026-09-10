# Phase 7 — Runtime & End-to-End Evidence

Status: **closed — accepted, merged and verified end-to-end on 10 September 2026**  
Canonical destination: `https://kairoseth.com/custom-requests`

[English](#english) · [Español](#español)

---

## English

### Accepted user flow

```text
WordPress administrator
→ Tools → AI Transparency Support
→ page load stays local
→ explicit Get support / Request custom integration click
→ plugin builds a bounded HTTPS URL server-side
→ https://kairoseth.com/custom-requests
→ Kairoseth shows the bounded extension context
→ user chooses what personal/business information to enter
→ explicit consent + submit on Kairoseth
→ Kairoseth backend validates + rate-limits + sends the request through SMTP
```

### WordPress implementation evidence

```text
Phase 7 contract PR: #17
Contract head: 703e04cd073e2c7572ec65b367ef5cbfd5b9c78a
Contract PR CI: #95 / 34405157557 — SUCCESS
Contract merge: cbc04eea07b20af60f3ec4b3621a9aa89c92ae84
Contract post-merge CI: #96 / 34405183831 — SUCCESS

Implementation PR: #18
Accepted implementation head: e49721eb00b85b0a4cfbf72d53e876d80dc96f44
PR-head CI: #101 / 34436069862 — SUCCESS — 8/8 jobs green
Implementation merge: f225646808f604b5758bbc960417451af8c31738
Post-merge main CI: #102 / 34436374187 — SUCCESS — 8/8 jobs green
```

The final WordPress runtime acceptance proved:

```text
real administrator Tools page
+ manage_options authority
+ Editor denied
+ no Kairoseth request on page load
+ two explicit external CTAs only
+ exact canonical HTTPS host/path
+ exact 8-key automatic context allow-list
+ no site URL / user identity / Registry / evidence / secrets attached
+ bounded requestType enum
+ 390 px acceptance
+ 200% text acceptance
+ axe serious/critical = 0
+ inherited Registry/Discovery/Readiness/Disclosure/Evidence Export regressions green
+ real Multisite isolation green
```

### Kairoseth production evidence

The shared Kairoseth Custom Requests intake was implemented and production-proved independently of the WordPress plugin.

```text
Custom Requests implementation PR: kairoseth-platform #211
Implementation merge: 6855dfacd3ce6616c2f58d254e058ad3df59416c
Permanent production-proof PR: kairoseth-platform #212
Production-proof merge: 7d8752634e9b5e186a794080cb557c7b8cc6f349
Production Smoke #116 / 34434998950 — SUCCESS

SMTP-recipient fallback PR: kairoseth-platform #213
PR-head CI #896 / 34436853412 — SUCCESS
PR-head Production Smoke #118 / 34436853390 — SUCCESS
Fallback merge: 5c01adfd40151da6392c8d780203230c315c19fb
Post-merge CI #897 / 34437075381 — SUCCESS
Post-merge Production Smoke #119 / 34437075355 — SUCCESS
Final synthetic delivery proof #4 / 34437244753 — SUCCESS
```

Production Smoke #119 used `EXPECT_CUSTOM_REQUESTS=true`, so `/custom-requests` remained part of the required public-production acceptance after the recipient change.

### Final SMTP proof

The final synthetic proof sent one deliberately non-personal acceptance payload to the production endpoint:

```text
POST https://kairoseth.com/api/public/custom-requests
source=extension
extensionSlug=ai-transparency
extensionName=Kairoseth AI Transparency
extensionVersion=0.1.0
hostPlatform=wordpress
hostPlatformVersion=6.8.2
locale=en
requestType=implementation_support
```

The proof job selected:

```text
PASS - production SMTP delivery completed
```

and completed with `SUCCESS`. The failure branches for email-unconfigured, SMTP-delivery-failed, rate-limit-unavailable, rate-limited and unexpected transport/route responses were all skipped.

This demonstrates that the deployed production path completed:

```text
request validation
→ persistent production rate-limit evaluation
→ server-authoritative recipient resolution
→ transactional SMTP configuration resolution
→ SMTP send
→ HTTP 201 / submitted
```

No real customer, administrator or WordPress-site data was used by the synthetic proof.

### Recipient authority

Kairoseth production resolves the recipient server-side only:

```text
CUSTOM_REQUESTS_TO
→ otherwise SMTP_USER
→ otherwise fail closed
```

The browser, WordPress query context and submitted form fields cannot choose the destination mailbox.

### Privacy boundary proved

The WordPress plugin automatically generates only:

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

It does **not** automatically attach site/home URL, administrator or customer identity, Registry data, AI-system names, `interaction_context`, Discovery evidence, Readiness findings, Disclosure state, Evidence Export data/signature, plugin/theme inventory, credentials, prompts, conversations, logs, database contents or arbitrary WordPress options.

The user supplies any personal/business/request details only on the Kairoseth form and explicitly consents before submission.

### Closure

```text
Canonical route verified: YES
Plugin implementation merged: YES
Plugin post-merge CI: GREEN
Real WordPress runtime: GREEN
EN/ES 100%: GREEN
Responsive/accessibility: GREEN
Multisite/regressions: GREEN
Kairoseth production page: GREEN
Kairoseth production backend: GREEN
SMTP delivery: GREEN
Blockers: 0
```

Phase 7 exit: **complete.**

---

## Español

### Flujo aceptado

```text
administrador WordPress
→ Herramientas → AI Transparency Support
→ la carga de la página permanece local
→ clic explícito en soporte / integración personalizada
→ el plugin construye server-side una URL HTTPS acotada
→ https://kairoseth.com/custom-requests
→ Kairoseth muestra el contexto técnico permitido
→ el usuario decide qué datos personales/empresariales introducir
→ consentimiento + envío explícito en Kairoseth
→ backend Kairoseth valida + aplica rate limit + envía por SMTP
```

### Evidencia WordPress

```text
PR contrato Fase 7: #17
Head contrato: 703e04cd073e2c7572ec65b367ef5cbfd5b9c78a
CI contrato: #95 / 34405157557 — SUCCESS
Merge contrato: cbc04eea07b20af60f3ec4b3621a9aa89c92ae84
CI post-merge contrato: #96 / 34405183831 — SUCCESS

PR implementación: #18
Head aceptado: e49721eb00b85b0a4cfbf72d53e876d80dc96f44
CI del PR: #101 / 34436069862 — SUCCESS — 8/8 verde
Merge implementación: f225646808f604b5758bbc960417451af8c31738
CI main post-merge: #102 / 34436374187 — SUCCESS — 8/8 verde
```

La aceptación runtime real demostró permisos `manage_options`, denegación a Editor, ausencia de tráfico Kairoseth al cargar la página, allow-list exacta de ocho claves, ausencia de datos sensibles automáticos, funcionamiento a 390 px y 200%, axe serious/critical = 0 y regresiones/Multisite verdes.

### Evidencia Kairoseth producción

```text
PR Custom Requests: kairoseth-platform #211
Merge: 6855dfacd3ce6616c2f58d254e058ad3df59416c
PR prueba permanente producción: kairoseth-platform #212
Merge: 7d8752634e9b5e186a794080cb557c7b8cc6f349
Production Smoke #116 / 34434998950 — SUCCESS

PR fallback destinatario SMTP: kairoseth-platform #213
CI PR #896 / 34436853412 — SUCCESS
Production Smoke PR #118 / 34436853390 — SUCCESS
Merge: 5c01adfd40151da6392c8d780203230c315c19fb
CI post-merge #897 / 34437075381 — SUCCESS
Production Smoke post-merge #119 / 34437075355 — SUCCESS
Prueba final de entrega #4 / 34437244753 — SUCCESS
```

La prueba sintética final utilizó únicamente datos ficticios/no personales y seleccionó el step **`PASS - production SMTP delivery completed`**. Esto confirma que producción completó validación, rate limit persistente, resolución server-side del destinatario, configuración SMTP y envío, devolviendo el resultado aceptado `HTTP 201 / submitted`.

El destinatario se resuelve exclusivamente en servidor:

```text
CUSTOM_REQUESTS_TO
→ si no existe, SMTP_USER
→ si tampoco existe, fail closed
```

El navegador, WordPress y los campos del formulario no pueden elegir el buzón de destino.

### Cierre

```text
Ruta canónica verificada: SÍ
Implementación plugin mergeada: SÍ
CI post-merge plugin: VERDE
WordPress runtime real: VERDE
EN/ES 100%: VERDE
Responsive/accesibilidad: VERDE
Multisite/regresiones: VERDE
Kairoseth producción frontend: VERDE
Kairoseth producción backend: VERDE
Entrega SMTP: VERDE
Bloqueadores: 0
```

Salida Fase 7: **completa.**
