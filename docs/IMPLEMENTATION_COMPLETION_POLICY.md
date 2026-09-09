# Implementation Completion & Phase Sequencing Policy

[English](#english) · [Español](#español)

Status: **Canonical for this repository**

---

## English

### Principle — finish before advancing

A Kairoseth extension phase is complete only when implementation, required validation, acceptance evidence and documentation are all closed.

```text
implementation complete
+ required gates green on final SHA
+ acceptance criteria satisfied
+ EN/ES complete where customer-facing
+ documentation synchronized
+ unresolved blockers = 0
= phase complete
```

Only then may the next dependent phase begin.

### Definition of done

Before an implementation/phase is marked complete:

1. agreed scope is implemented with no required item silently deferred;
2. all gates selected by `CI_VALIDATION_POLICY.md` pass on the final SHA;
3. customer-facing functionality is complete in **English and Spanish at 100% coverage**;
4. security/capability/nonce/privacy/Multisite checks required by the phase pass;
5. responsive/accessibility/UX acceptance is complete when UI changed;
6. the generated production package reflects the implementation truth;
7. WordPress Plugin Check and release checks pass when the package contract changed;
8. known defects violating acceptance are fixed;
9. documentation and roadmap describe the real state;
10. qualifying engineering failures are recorded/updated in `docs/engineering-failures/`;
11. the PR is merged through the normal workflow when merge is part of the phase boundary;
12. post-merge/release verification is complete when required.

### No phase skipping

- Do not start Phase N+1 while Phase N has a failing required gate.
- Do not start it while EN/ES, acceptance or documentation is incomplete.
- Merged code with pending required acceptance remains incomplete.
- New feature work must not be used to avoid fixing failures from the current phase.
- Explicit future non-scope is allowed only when documented as deferred and not required for the current phase exit.

### Failure flow

```text
failure
→ structured diagnostic
→ consult engineering-failure memory
→ confirm/probably classify root cause with evidence
→ fix current implementation/workflow
→ rerun required validation
→ record/update reusable failure knowledge when material
→ synchronize acceptance/docs
→ close current phase
→ only then advance
```

### Phase-closing PR requirement

A closing PR/record must state:

- completed scope;
- final required gates and their status;
- EN/ES status;
- security/privacy/accessibility/responsive acceptance where applicable;
- package/release evidence where applicable;
- documentation updated;
- blockers = 0;
- explicit deferred non-scope;
- engineering-failure records created/consulted when relevant.

---

## Español

### Principio — terminar antes de avanzar

Una fase de una extensión Kairoseth solo está completa cuando implementación, validación obligatoria, evidencia de aceptación y documentación están cerradas.

```text
implementación completa
+ gates obligatorios verdes sobre el SHA final
+ criterios de aceptación cumplidos
+ EN/ES completo cuando sea customer-facing
+ documentación sincronizada
+ bloqueos sin resolver = 0
= fase completa
```

Solo entonces puede comenzar la siguiente fase dependiente.

### Definition of Done

Antes de marcar una implementación/fase como completa:

1. el alcance acordado está implementado sin trasladar silenciosamente requisitos obligatorios;
2. todos los gates seleccionados por `CI_VALIDATION_POLICY.md` pasan sobre el SHA final;
3. la funcionalidad customer-facing está completa **100% en inglés y español**;
4. pasan los checks de seguridad/capabilities/nonces/privacidad/Multisite necesarios;
5. responsive/accesibilidad/UX están aceptados cuando cambió UI;
6. el paquete de producción generado refleja la implementación real;
7. WordPress Plugin Check y checks de release pasan cuando cambió el contrato del paquete;
8. los defectos conocidos que incumplen aceptación están corregidos;
9. documentación y roadmap reflejan el estado real;
10. los fallos de ingeniería relevantes están registrados/actualizados en `docs/engineering-failures/`;
11. el PR se integra por el flujo normal cuando el merge forma parte del cierre;
12. la verificación post-merge/release está completa cuando corresponde.

### Prohibido saltar fases

- No iniciar Fase N+1 con gates obligatorios de Fase N fallando.
- No avanzar si EN/ES, aceptación o documentación están incompletos.
- Código integrado con aceptación requerida pendiente sigue incompleto.
- No usar nuevas funcionalidades para evitar corregir fallos de la fase actual.
- El non-scope futuro solo puede diferirse si está documentado y no forma parte del exit actual.

### Flujo ante fallos

```text
fallo
→ diagnóstico estructurado
→ consultar memoria de fallos
→ confirmar/clasificar causa con evidencia
→ corregir implementación/workflow actual
→ repetir validación obligatoria
→ registrar/actualizar conocimiento reutilizable si es material
→ sincronizar acceptance/docs
→ cerrar fase actual
→ solo entonces avanzar
```

### Requisito del PR de cierre

Debe dejar claro:

- alcance completado;
- gates finales y estado;
- estado EN/ES;
- aceptación seguridad/privacidad/accesibilidad/responsive cuando aplique;
- evidencia package/release cuando aplique;
- documentación actualizada;
- blockers = 0;
- non-scope diferido explícito;
- registros de fallos consultados/creados cuando corresponda.
