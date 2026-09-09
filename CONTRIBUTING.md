# Contributing / Contribuir

[English](#english) · [Español](#español)

---

## English

Thanks for helping improve Kairoseth AI Transparency.

### Engineering workflow

```text
issue / product contract
→ consult engineering rules + known failures
→ feature branch
→ minimum sufficient local validation
→ pull request
→ public CI
→ review
→ merge
→ post-merge/release verification when applicable
```

Do not push feature work directly to `main`.

### Mandatory policies

Read before contributing:

- [`docs/ENGINEERING_RULES.md`](docs/ENGINEERING_RULES.md)
- [`docs/BILINGUAL_EN_ES_POLICY.md`](docs/BILINGUAL_EN_ES_POLICY.md)
- [`docs/CI_VALIDATION_POLICY.md`](docs/CI_VALIDATION_POLICY.md)
- [`docs/IMPLEMENTATION_COMPLETION_POLICY.md`](docs/IMPLEMENTATION_COMPLETION_POLICY.md)
- [`docs/CI_FAILURE_DIAGNOSTICS_POLICY.md`](docs/CI_FAILURE_DIAGNOSTICS_POLICY.md)
- [`docs/engineering-failures/README.md`](docs/engineering-failures/README.md)

Product-specific rules may be stricter but cannot weaken these contracts.

### Before opening a PR

```bash
composer install
composer verify
bash bin/build-plugin.sh
```

Also run any additional gate required by the changed contract.

For every customer-facing change:

```text
EN impact: complete / not applicable
ES impact: complete / not applicable
Bilingual coverage gate: pass
Responsive/UX acceptance: complete / not applicable
Accessibility acceptance: complete / not applicable
```

`not applicable` is valid only when that surface genuinely did not change.

### Product boundaries

Contributions must preserve:

- no claim that the plugin certifies or guarantees legal compliance;
- no generic probabilistic AI-authorship detector in v1;
- no automatic telemetry/off-site transmission without explicit product and consent/privacy contract;
- no customer credentials/private data/proprietary logic in the public repository;
- browser/model output never grants permissions;
- WordPress capability checks remain server-authoritative;
- nonces protect state-changing actions where applicable;
- site/Multisite boundaries stay explicit;
- findings distinguish observed facts, administrator declarations and guidance;
- public Free and private Custom customer repositories remain separate.

### WordPress.org compatibility

Code intended for WordPress.org must remain compatible with current Plugin Directory rules, including GPL-compatible licensing of bundled code/assets, human-readable source, no trialware, no non-consensual tracking, no dashboard hijacking and no public-site promotional links without permission.

The official Plugin Check runs against the generated production package, not the development repository root.

### Failure handling

If CI/build/test/package/runtime fails:

1. read the structured diagnostic first;
2. consult `docs/engineering-failures/` for a matching signature/component;
3. do not invent root cause;
4. fix the current contract;
5. rerun required validation;
6. record/update a failure record when the finding is material or reusable.

### PR quality

A useful PR states:

- problem/contract changed;
- implementation summary;
- security/privacy/Multisite impact;
- EN/ES impact;
- validation run and final SHA evidence;
- screenshots/acceptance evidence for UI when relevant;
- known limitations/deferred non-scope;
- related engineering-failure records.

### Security reports

Do not open public issues for exploitable vulnerabilities. Follow [`SECURITY.md`](SECURITY.md).

---

## Español

Gracias por ayudar a mejorar Kairoseth AI Transparency.

### Flujo de ingeniería

```text
issue / contrato de producto
→ consultar reglas + fallos conocidos
→ feature branch
→ validación local mínima suficiente
→ pull request
→ CI público
→ review
→ merge
→ verificación post-merge/release cuando corresponda
```

No se introduce trabajo funcional directamente en `main`.

### Políticas obligatorias

Leer antes de contribuir:

- [`docs/ENGINEERING_RULES.md`](docs/ENGINEERING_RULES.md)
- [`docs/BILINGUAL_EN_ES_POLICY.md`](docs/BILINGUAL_EN_ES_POLICY.md)
- [`docs/CI_VALIDATION_POLICY.md`](docs/CI_VALIDATION_POLICY.md)
- [`docs/IMPLEMENTATION_COMPLETION_POLICY.md`](docs/IMPLEMENTATION_COMPLETION_POLICY.md)
- [`docs/CI_FAILURE_DIAGNOSTICS_POLICY.md`](docs/CI_FAILURE_DIAGNOSTICS_POLICY.md)
- [`docs/engineering-failures/README.md`](docs/engineering-failures/README.md)

Las reglas específicas de producto pueden ser más estrictas, pero nunca debilitar estos contratos.

### Antes de abrir un PR

```bash
composer install
composer verify
bash bin/build-plugin.sh
```

Además, ejecutar cualquier gate adicional exigido por el contrato modificado.

Para cada cambio customer-facing:

```text
Impacto EN: completo / no aplica
Impacto ES: completo / no aplica
Gate bilingüe: pass
Aceptación responsive/UX: completa / no aplica
Aceptación accesibilidad: completa / no aplica
```

`no aplica` solo es válido cuando esa superficie realmente no cambió.

### Límites de producto

Las contribuciones deben preservar:

- no afirmar certificación ni cumplimiento legal garantizado;
- no detector probabilístico genérico de autoría IA en v1;
- no telemetría/transmisión externa automática sin contrato explícito de producto y consentimiento/privacidad;
- no credenciales/datos privados/lógica propietaria de clientes en el repo público;
- browser/modelo nunca concede permisos;
- capabilities WordPress siguen siendo server-authoritative;
- nonces protegen acciones con cambio de estado cuando corresponde;
- límites site/Multisite explícitos;
- findings separan hechos observados, declaraciones del administrador y guidance;
- Free público y Custom privado permanecen separados.

### Compatibilidad WordPress.org

El código destinado a WordPress.org debe respetar las reglas actuales del directorio: licencias GPL-compatible de código/assets incluidos, código legible, sin trialware, sin tracking no consentido, sin secuestro del dashboard ni enlaces promocionales en la web pública sin permiso.

Plugin Check oficial valida el paquete de producción generado y no la raíz del repositorio de desarrollo.

### Manejo de fallos

Ante un fallo CI/build/test/package/runtime:

1. leer primero el diagnóstico estructurado;
2. consultar `docs/engineering-failures/` buscando firma/componente relacionado;
3. no inventar causa raíz;
4. corregir el contrato actual;
5. repetir la validación requerida;
6. registrar/actualizar un fallo cuando el aprendizaje sea material o reutilizable.

### Calidad del PR

Un PR útil indica:

- problema/contrato modificado;
- resumen de implementación;
- impacto seguridad/privacidad/Multisite;
- impacto EN/ES;
- validación y evidencia del SHA final;
- capturas/evidencia de aceptación UI cuando corresponda;
- limitaciones/non-scope diferido;
- registros de fallos relacionados.

### Reportes de seguridad

No abras issues públicos para vulnerabilidades explotables. Sigue [`SECURITY.md`](SECURITY.md).
