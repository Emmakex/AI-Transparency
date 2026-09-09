# CI Validation Policy — Kairoseth WordPress Extensions

[English](#english) · [Español](#español)

Status: **Canonical for this repository**

---

## English

### Principle — minimum sufficient validation

Run the smallest blocking validation set that is sufficient to prove the changed contract.

```text
changed surface
+ directly affected contracts
+ shared dependencies actually touched
+ risk-specific release gates
= required CI scope
```

More tests are not automatically better evidence. Unrelated work adds noise and runner time without increasing confidence.

### Required scopes

**Documentation only**
- no Composer install unless the documentation changes CI/build contracts that must be exercised;
- no unrelated PHP, WordPress or Plugin Check suite.

**Production PHP/domain changes**
- WPCS + PHPCompatibility;
- affected PHPUnit tests;
- PHP syntax gates for the supported matrix;
- bilingual gate if any customer-facing string changes.

**Customer-facing UI/copy changes**
- EN/ES 100% gate;
- responsive/UX acceptance for affected admin/frontend surfaces;
- accessibility validation when interactive markup or presentation changes;
- Plugin Check when the generated release package changes.

**Authorization/security/data changes**
- capabilities/nonces/security tests for the changed action;
- site/Multisite isolation tests when storage/scope changes;
- no off-site transmission without explicit contract and corresponding privacy/security acceptance.

**Build/package/release changes**
- production package build;
- bilingual compiled catalog verification;
- official WordPress Plugin Check against `build/kairoseth-ai-transparency`;
- install/activation/release checks when the release artifact contract changes.

**CI/diagnostic tooling changes**
- full relevant CI because the validation mechanism itself changed;
- diagnostic runner must preserve the original exit code and remain usable when application dependencies fail.

### Fail-safe rule

If a change cannot be safely mapped to a bounded surface, escalate validation rather than silently skip a required gate.

### Stale runs

A newer PR commit supersedes older runs. CI uses `cancel-in-progress` so evidence corresponds to the latest head SHA.

### Review requirement

Every PR must make clear:

1. what contract changed;
2. which gates prove it;
3. whether EN/ES is affected;
4. whether security/privacy/Multisite/package boundaries are affected;
5. whether a known engineering-failure record matched the problem;
6. why any broader validation is necessary.

---

## Español

### Principio — validación mínima suficiente

Se ejecuta el conjunto bloqueante más pequeño que sea suficiente para demostrar el contrato modificado.

```text
superficie modificada
+ contratos directamente afectados
+ dependencias compartidas realmente tocadas
+ gates de riesgo específicos
= alcance CI obligatorio
```

Más tests no significan automáticamente mejor evidencia. Trabajo no relacionado añade ruido y tiempo de runner sin aumentar la confianza.

### Alcances obligatorios

**Solo documentación**
- no instalar Composer salvo que la documentación cambie contratos de CI/build que deban probarse;
- no ejecutar suites PHP/WordPress/Plugin Check no relacionadas.

**Cambios PHP/domain de producción**
- WPCS + PHPCompatibility;
- tests PHPUnit afectados;
- matriz de sintaxis PHP soportada;
- gate bilingüe si cambia cualquier texto customer-facing.

**Cambios de UI/copy customer-facing**
- gate EN/ES 100%;
- aceptación responsive/UX de las superficies afectadas;
- validación de accesibilidad cuando cambie markup/interacción/presentación;
- Plugin Check cuando cambie el paquete de release.

**Cambios de autorización/seguridad/datos**
- tests de capabilities/nonces/seguridad de la acción modificada;
- tests de aislamiento site/Multisite cuando cambie almacenamiento/scope;
- ninguna transmisión externa sin contrato explícito y aceptación de privacidad/seguridad.

**Cambios de build/package/release**
- build del paquete de producción;
- verificación del catálogo español compilado;
- WordPress Plugin Check oficial sobre `build/kairoseth-ai-transparency`;
- instalación/activación/release checks cuando cambie el contrato del artefacto.

**Cambios de CI/diagnóstico**
- CI relevante completo porque cambia el mecanismo de validación;
- el runner diagnóstico conserva el exit code original y debe funcionar aunque fallen dependencias de aplicación.

### Regla fail-safe

Si un cambio no puede asociarse de forma segura a una superficie limitada, se amplía la validación en lugar de omitir silenciosamente gates necesarios.

### Runs obsoletos

Un commit nuevo del PR sustituye a los runs anteriores. CI usa `cancel-in-progress` para que la evidencia corresponda al SHA final.

### Requisito de review

Cada PR debe dejar claro:

1. qué contrato cambió;
2. qué gates lo prueban;
3. si afecta EN/ES;
4. si afecta seguridad/privacidad/Multisite/package;
5. si coincide con un fallo conocido;
6. por qué sería necesaria una validación más amplia.
