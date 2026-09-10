# Phase 7 WPCS exception output and EOF newline

[English](#english) · [Español](#español)

---

## English

**Status:** resolved on the Phase 7 implementation branch  
**First observed:** 2026-09-10  
**Affected area:** PHP quality / WordPress Coding Standards  
**Severity:** blocking CI

### Symptom

PR #18 CI #97 failed in `PHP quality` while running:

```text
bash bin/run-with-diagnostics.sh "PHP quality" -- composer verify
```

Exit code: `2`.

Two blocking sniffs were reported:

```text
ai-transparency.php:39
PSR2.Files.EndFileNewline.NoneFound
Expected 1 newline at end of file; 0 found

src/Support/class-supportcontext.php:130
WordPress.Security.EscapeOutput.ExceptionNotEscaped
Dynamic $field value used in an InvalidArgumentException message
```

Structured diagnostics signature:

```text
6b48a8a870fbf9e3431c98705136950325a21c62cd988d4b69f3048dda33ed09
```

### Root cause

The plugin bootstrap replacement omitted its final newline. Separately, `SupportContext::bounded_version()` interpolated a diagnostic field name into an exception string. WPCS treats exception messages as output and therefore rejected the dynamic value even though the exception belongs to pure domain validation and is not intended as customer-facing output.

### Resolution

- restored the required trailing newline in `ai-transparency.php`;
- removed the unnecessary dynamic field parameter from `bounded_version()`;
- replaced the interpolated exception with one static bounded diagnostic message.

No escaping bypass or blanket PHPCS suppression was added.

### Prevention

- preserve POSIX final newlines when replacing PHP entry files;
- prefer static exception messages for internal validation when dynamic context is not required;
- do not assume PHPUnit was reached when `composer verify` stops in the PHPCS stage.

### Verification

CI #99 on the corrected PR #18 head passed `PHP quality`, including coding standards, PHPUnit and bilingual verification.

### Related evidence

- PR #18 — Phase 7 contextual support implementation
- CI #97 / run `34435766921` — failed
- CI #99 / run `34435913278` — PHP quality passed after the fix

---

## Español

**Estado:** resuelto en la rama de implementación de Fase 7  
**Primera observación:** 2026-09-10  
**Área afectada:** PHP quality / WordPress Coding Standards  
**Severidad:** CI bloqueante

### Síntoma

El CI #97 del PR #18 falló en `PHP quality` al ejecutar:

```text
bash bin/run-with-diagnostics.sh "PHP quality" -- composer verify
```

Código de salida: `2`.

Se detectaron dos sniffs bloqueantes:

```text
ai-transparency.php:39
PSR2.Files.EndFileNewline.NoneFound
Faltaba el salto de línea final

src/Support/class-supportcontext.php:130
WordPress.Security.EscapeOutput.ExceptionNotEscaped
Se interpolaba el valor dinámico $field en InvalidArgumentException
```

Firma del diagnóstico estructurado:

```text
6b48a8a870fbf9e3431c98705136950325a21c62cd988d4b69f3048dda33ed09
```

### Causa raíz

El reemplazo del bootstrap omitió el salto de línea final. Además, `SupportContext::bounded_version()` interpolaba el nombre del campo en el mensaje de excepción. WPCS considera los mensajes de excepción como salida y rechazó el valor dinámico aunque se tratase de una validación interna de dominio.

### Solución

- se restauró el salto de línea final de `ai-transparency.php`;
- se eliminó el parámetro dinámico innecesario de `bounded_version()`;
- el mensaje de excepción pasó a ser estático y acotado.

No se añadió una excepción global de PHPCS ni un bypass de escaping.

### Prevención

- conservar el salto de línea POSIX al reemplazar archivos PHP de entrada;
- preferir mensajes de excepción estáticos cuando el contexto dinámico no sea necesario;
- no asumir que PHPUnit llegó a ejecutarse si `composer verify` se detiene en PHPCS.

### Verificación

El CI #99 del head corregido del PR #18 pasó `PHP quality`, incluyendo coding standards, PHPUnit y verificación bilingüe.

### Evidencia relacionada

- PR #18 — implementación de soporte contextual Fase 7
- CI #97 / run `34435766921` — falló
- CI #99 / run `34435913278` — `PHP quality` verde tras la corrección
