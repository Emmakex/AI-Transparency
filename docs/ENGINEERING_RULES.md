# Kairoseth Extensions Engineering Rules

[English](#english) · [Español](#español)

Status: **Canonical for this extension repository**  
Parent policy: `Emmakex/kairoseth-platform/docs/GLOBAL_ENGINEERING_RULES.md`

This policy adapts the Kairoseth global engineering rules to public WordPress extensions. Product-specific rules may be stricter, but they must never weaken this contract.

---

## English

### Mandatory rules

1. **WordPress authorization is server-authoritative.** Every privileged action must be authorized with the appropriate WordPress capability on the server. State-changing requests also require CSRF protection such as a valid nonce where applicable. Browser state, request payloads, JavaScript, AI/model output, query parameters or hidden fields never grant authority.
2. **No client/model privilege grants.** Browser/client/model output never grants roles, capabilities, entitlements, credentials, filesystem access, remote-service permissions or privileged plugin actions.
3. **Secrets stay private.** Provider keys, application passwords, API secrets, tokens and customer credentials must never be shipped in the public repository, exposed to the browser, written to model context, diagnostic artifacts or public logs.
4. **Site and Multisite boundaries are explicit.** Single-site data is scoped to that site. Multisite/network operations require an explicit storage and authorization contract. Data must never cross blog/site/network boundaries accidentally.
5. **No silent off-site transmission.** The Free plugin is local-first. Telemetry, lead submission, remote AI calls, diagnostics upload or any other off-site data transfer requires an explicit documented product purpose and user action/consent where applicable.
6. **English and Spanish ship together at 100%.** Every customer-facing functional change must ship English and Spanish in the same PR and release. A feature is incomplete if either language is missing, stale or falls back unintentionally.
7. **All customer-facing strings are internationalized.** Runtime UI, notices, validation/error messages, emails, exports, disclosure copy, help text, Custom Request copy and other shipped user-facing text must use the WordPress internationalization layer. Brand names, code identifiers and legally required immutable labels are the only normal exceptions.
8. **Responsive, accessibility and UX acceptance are required.** Customer-facing admin and frontend changes must be usable at relevant desktop/mobile widths, keyboard-accessible where interactive, and compatible with the plugin's accessibility contract.
9. **Evidence and claims remain truthful.** The plugin must distinguish observed facts, administrator declarations and guidance. It never claims legal certification, guaranteed EU AI Act compliance or outcomes it cannot verify.
10. **Minimum sufficient validation.** Run the gates required by the changed contract, not unrelated work. A translation-only change does not require unrelated functional suites; an authorization change does require its security/permission gates.
11. **Finish before advancing.** Phase N+1 cannot start until Phase N implementation, required gates, acceptance evidence, blockers and documentation are complete. Explicitly deferred future work is not an unfinished current dependency.
12. **Controlled delivery path.** Feature branch → PR → public CI → review → merge → post-merge/release verification. Feature work does not go directly to `main`.
13. **Learn from failures.** Significant failures, regressions and non-obvious fixes are recorded in `docs/engineering-failures/` with root cause, resolution, prevention and verification. Related records must be consulted before changing the affected area again.
14. **Actionable failure diagnostics.** Material CI/build/test/package/runtime failures must surface a concise structured diagnostic in addition to raw logs: workflow/job/step, operation, exit/status, primary error, file/line when available, bounded context, stable signature, failure class, root-cause state and known-incident reference when matched.
15. **Production package is the release authority.** WordPress.org/release validation targets the generated production package, not the development repository root. Tests, CI configuration, development-only tooling and private engineering material must not leak into the distributable package.
16. **Free and Custom remain separated.** The public Free repository contains reusable public product code. Customer-specific proprietary integrations, credentials, business rules and paid custom work live in separate private repositories.
17. **Dependencies are deliberate.** Runtime dependencies must be justified, compatible with WordPress.org rules, reviewable and included according to their licenses. Development tooling must never become an accidental runtime dependency.
18. **Legal/compliance wording is bounded.** The product may provide readiness, evidence, workflow and technical disclosure tooling. It must not represent installation alone as legal compliance or certification.

### Adaptation from the platform rules

| Platform rule | WordPress extension adaptation |
|---|---|
| Server-side `productSlug` authorization | Server-side WordPress capabilities + nonces for privileged/state-changing actions |
| Platform Admin separation | WordPress site/network admin boundaries remain explicit |
| No client/model privilege grants | Applies unchanged |
| Provider credentials stay private | Applies unchanged; additionally never enter the public repo/package |
| Server-authoritative provider/model selection | Any future provider/model choice with security impact is controlled by trusted server-side plugin configuration |
| Tenant isolation | Adapted to site/blog/network isolation and customer-private repo boundaries |
| EN/ES ships together | Strengthened to **100% bilingual coverage gate** |
| Responsive/UX acceptance | Applies unchanged to WP Admin and frontend |
| Minimum sufficient validation | Applies unchanged |
| Finish before advancing | Applies unchanged |
| Feature branch → PR → CI → merge → production verification | Applies unchanged; CI is public for Free extensions |
| Learn from failures | Applies unchanged with local `docs/engineering-failures/` |
| Actionable diagnostics | Applies unchanged and is required in extension CI |

### Pull request completion test

A customer-facing PR is not ready to merge until all applicable answers are **yes**:

```text
EN complete?
ES complete?
No untranslated runtime strings?
Capabilities/nonces correct?
No new off-site data transfer without contract?
Responsive/accessibility acceptance complete?
Required tests/CI green?
Known-failure records consulted?
New material failure recorded if applicable?
Production package contains only distributable files?
Documentation synchronized?
```

---

## Español

### Reglas obligatorias

1. **La autorización WordPress es server-authoritative.** Toda acción privilegiada debe autorizarse en servidor con la capability de WordPress correspondiente. Las peticiones que modifican estado también requieren protección CSRF, como un nonce válido cuando corresponda. El estado del navegador, payloads, JavaScript, output de IA/modelo, parámetros o campos ocultos nunca conceden autoridad.
2. **El cliente/modelo no concede privilegios.** Browser, cliente u output del modelo nunca conceden roles, capabilities, entitlements, credenciales, acceso a archivos, permisos de servicios remotos ni acciones privilegiadas del plugin.
3. **Los secretos permanecen privados.** Claves de proveedor, Application Passwords, secretos API, tokens y credenciales de clientes nunca se publican en el repositorio público, navegador, contexto del modelo, artefactos diagnósticos ni logs públicos.
4. **Los límites de sitio y Multisite son explícitos.** Los datos single-site quedan limitados a ese sitio. Operaciones Multisite/network requieren contrato explícito de almacenamiento y autorización. Los datos nunca deben cruzar accidentalmente límites de blog/sitio/red.
5. **No existe transmisión externa silenciosa.** El plugin Free es local-first. Telemetría, envío de leads, llamadas remotas de IA, subida de diagnósticos o cualquier transferencia externa requiere un propósito documentado y acción/consentimiento del usuario cuando corresponda.
6. **Inglés y español se publican juntos al 100%.** Todo cambio funcional de cara al usuario debe incluir inglés y español en el mismo PR y release. Una función está incompleta si falta un idioma, está desactualizado o cae involuntariamente al idioma de fallback.
7. **Todo texto de usuario se internacionaliza.** UI runtime, avisos, validaciones/errores, emails, exports, disclosures, ayuda, Custom Requests y demás copy entregado al usuario debe usar la capa de internacionalización de WordPress. Marcas, identificadores de código y etiquetas legalmente inmutables son las excepciones normales.
8. **Responsive, accesibilidad y UX son obligatorios.** Los cambios en WP Admin o frontend deben funcionar en anchos relevantes de escritorio/móvil, ser accesibles por teclado cuando sean interactivos y respetar el contrato de accesibilidad.
9. **La evidencia y los claims son veraces.** El plugin distingue hechos observados, declaraciones del administrador y orientación. Nunca afirma certificación legal, cumplimiento garantizado del AI Act ni resultados no verificables.
10. **Validación mínima suficiente.** Se ejecutan los gates necesarios para el contrato modificado, no suites sin relación. Un cambio solo de traducción no exige suites funcionales ajenas; un cambio de autorización sí exige sus gates de seguridad/permisos.
11. **Terminar antes de avanzar.** Fase N+1 no comienza hasta completar implementación, gates, evidencia de aceptación, blockers y documentación de Fase N. Trabajo futuro explícitamente diferido no cuenta como dependencia actual incompleta.
12. **Ruta de entrega controlada.** Feature branch → PR → CI público → review → merge → verificación post-merge/release. El trabajo funcional no entra directamente en `main`.
13. **Aprender de los fallos.** Fallos relevantes, regresiones y fixes no evidentes se registran en `docs/engineering-failures/` con causa raíz, solución, prevención y verificación. Antes de volver a modificar esa zona se consultan los registros relacionados.
14. **Diagnóstico accionable.** Todo fallo material de CI/build/test/package/runtime debe mostrar un diagnóstico corto además del log crudo: workflow/job/step, operación, exit/status, error principal, archivo/línea cuando exista, contexto acotado, firma estable, clase de fallo, estado de causa raíz e incidente conocido cuando haya match.
15. **El paquete de producción es la autoridad de release.** WordPress.org y los gates de release validan el paquete generado, no la raíz de desarrollo. Tests, CI, tooling de desarrollo y material interno no deben entrar accidentalmente en el distribuible.
16. **Free y Custom permanecen separados.** El repositorio público Free contiene código reutilizable público. Integraciones propietarias, credenciales, reglas de negocio y trabajo Custom de clientes viven en repositorios privados separados.
17. **Las dependencias son deliberadas.** Toda dependencia runtime debe estar justificada, cumplir reglas de WordPress.org, ser revisable y respetar licencias. Herramientas de desarrollo nunca deben convertirse por accidente en dependencias runtime.
18. **Los claims legales están limitados.** El producto puede ofrecer readiness, evidencia, workflow y tooling técnico de transparencia. Nunca representa la instalación por sí sola como cumplimiento legal o certificación.

### Adaptación desde las reglas de Platform

| Regla Platform | Adaptación para extensiones WordPress |
|---|---|
| Autorización `productSlug` server-side | Capabilities WordPress server-side + nonces en acciones privilegiadas/con cambio de estado |
| Separación Platform Admin | Límites de administración sitio/red WordPress explícitos |
| Cliente/modelo no concede privilegios | Aplica sin cambios |
| Credenciales privadas | Aplica sin cambios y nunca entran en repo/paquete público |
| Proveedor/modelo server-authoritative | Cualquier futura selección con impacto de seguridad se controla mediante configuración confiable server-side |
| Aislamiento tenant | Se adapta a aislamiento site/blog/network y repos privados de cliente |
| EN/ES juntos | Se refuerza a **gate de cobertura bilingüe 100%** |
| Responsive/UX | Aplica a WP Admin y frontend |
| Validación mínima suficiente | Aplica sin cambios |
| Terminar antes de avanzar | Aplica sin cambios |
| Feature branch → PR → CI → merge → verificación | Aplica sin cambios; CI público para extensiones Free |
| Aprender de los fallos | Aplica con `docs/engineering-failures/` local |
| Diagnóstico accionable | Aplica y debe implementarse en el CI de la extensión |

### Test de finalización de PR

Un PR customer-facing no está listo para merge hasta que todas las respuestas aplicables sean **sí**:

```text
¿EN completo?
¿ES completo?
¿Sin cadenas runtime sin traducir?
¿Capabilities/nonces correctos?
¿Sin nueva transmisión externa no contratada?
¿Responsive/accesibilidad aceptados?
¿Tests/CI requeridos verdes?
¿Registros de fallos conocidos consultados?
¿Nuevo fallo material registrado si aplica?
¿El paquete de producción contiene solo archivos distribuibles?
¿Documentación sincronizada?
```
