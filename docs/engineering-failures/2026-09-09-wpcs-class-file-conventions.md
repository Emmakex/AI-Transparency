# WPCS class-file convention mismatch

[English](#english) · [Español](#español)

Status: mitigated  
Date first observed: 2026-09-09  
Affected area: PHP quality / WordPress Coding Standards  
Severity: medium

---

## English

### Symptom

The bootstrap PR failed `PHP quality → Run coding standards and unit tests` even though PHP syntax was valid. WPCS reported class filenames such as `Plugin.php`, `AiSystem.php`, `AdminPage.php` and `AiSystemsRegistry.php` as invalid, and also reported camelCase/property/documentation convention issues.

### Root cause

The initial source structure used PSR-style OOP naming conventions while the repository had intentionally selected WordPress Coding Standards as an enforced contract. The implementation and the validation policy therefore disagreed about class-file and naming conventions.

### Resolution

The production source was converted to WordPress-style filenames and naming conventions, including the exact filenames expected by the active WPCS ruleset, snake_case methods/properties where required, and complete docblocks.

### Prevention

- Production PHP remains governed by `phpcs.xml.dist`.
- New production classes must be introduced using the filename convention accepted by the active WPCS version before the PR grows substantially.
- Do not silence filename/naming sniffs simply to preserve an incompatible convention.
- `composer verify` is required before merge.

### Verification

The corrected bootstrap PR passed `PHP quality`, PHP compatibility/syntax gates and the subsequent post-merge CI.

### Related

- PR #1
- `phpcs.xml.dist`
- `docs/ENGINEERING_RULES.md`

---

## Español

### Síntoma

El PR de bootstrap falló en `PHP quality → Run coding standards and unit tests` aunque la sintaxis PHP era válida. WPCS rechazó nombres como `Plugin.php`, `AiSystem.php`, `AdminPage.php` y `AiSystemsRegistry.php`, además de señalar convenciones camelCase/propiedades/docblocks.

### Causa raíz

La estructura inicial utilizó convenciones OOP estilo PSR mientras el repositorio había elegido WordPress Coding Standards como contrato obligatorio. Implementación y política de validación no estaban alineadas en las convenciones de archivos/clases.

### Solución

El código de producción se adaptó a nombres de archivo y convenciones WordPress, incluyendo los nombres exactos esperados por el WPCS activo, métodos/propiedades snake_case cuando correspondía y docblocks completos.

### Prevención

- El PHP de producción sigue gobernado por `phpcs.xml.dist`.
- Las nuevas clases deben usar desde el inicio la convención aceptada por la versión activa de WPCS.
- No se silencian sniffs de naming solo para conservar una convención incompatible.
- `composer verify` es obligatorio antes del merge.

### Verificación

El PR corregido superó `PHP quality`, los gates de compatibilidad/sintaxis PHP y el CI posterior al merge.

### Relacionado

- PR #1
- `phpcs.xml.dist`
- `docs/ENGINEERING_RULES.md`
