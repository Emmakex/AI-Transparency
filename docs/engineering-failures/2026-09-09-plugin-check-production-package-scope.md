# Plugin Check validated the development repository instead of the release package

[English](#english) · [Español](#español)

Status: mitigated  
Date first observed: 2026-09-09  
Affected area: WordPress Plugin Check / release packaging  
Severity: medium

---

## English

### Symptom

The official WordPress Plugin Check gate failed after all PHP gates were green. It reported:

- text-domain mismatch because the checked directory slug was `AI-Transparency` while the plugin text domain was `kairoseth-ai-transparency`;
- development files such as `phpcs.xml.dist` and `phpunit.xml.dist` as application files;
- `.github`, `.gitignore`, tests and unexpected development Markdown in the checked plugin root.

### Root cause

Plugin Check was pointed at the GitHub development repository root. WordPress.org validates the distributable plugin directory, whose slug and contents are different from the engineering repository. The gate therefore tested the wrong artifact contract.

### Resolution

A deterministic production build was introduced:

```text
repository
→ bin/build-plugin.sh
→ build/kairoseth-ai-transparency/
→ WordPress Plugin Check
```

Only release files are copied into the production package. Plugin Check now validates that package rather than the repository root.

### Prevention

- The production package is the release authority.
- `wordpress/plugin-check-action` must point to `build/kairoseth-ai-transparency`.
- Development-only files must never be copied by `bin/build-plugin.sh` unless explicitly required at runtime.
- Translation assets, release metadata and runtime source must be tested in the exact package structure users receive.

### Verification

The corrected PR passed the official WordPress Plugin Check categories `plugin_repo`, `security`, `accessibility` and `performance`, then passed the full post-merge CI.

### Related

- PR #1
- `bin/build-plugin.sh`
- `.github/workflows/ci.yml`
- `docs/ENGINEERING_RULES.md`

---

## Español

### Síntoma

El gate oficial WordPress Plugin Check falló cuando todos los gates PHP ya estaban verdes. Reportó:

- mismatch del text domain porque el directorio revisado tenía slug `AI-Transparency` mientras el plugin usa `kairoseth-ai-transparency`;
- archivos de desarrollo como `phpcs.xml.dist` y `phpunit.xml.dist` como application files;
- `.github`, `.gitignore`, tests y Markdown de desarrollo inesperado dentro de la raíz revisada.

### Causa raíz

Plugin Check estaba analizando la raíz del repositorio GitHub de desarrollo. WordPress.org valida el directorio distribuible del plugin, cuyo slug y contenido no coinciden con el repositorio de ingeniería. Por tanto el gate estaba comprobando el artefacto equivocado.

### Solución

Se introdujo un build de producción determinista:

```text
repositorio
→ bin/build-plugin.sh
→ build/kairoseth-ai-transparency/
→ WordPress Plugin Check
```

Solo los archivos de release se copian al paquete de producción. Plugin Check valida ahora ese paquete y no la raíz del repositorio.

### Prevención

- El paquete de producción es la autoridad de release.
- `wordpress/plugin-check-action` debe apuntar a `build/kairoseth-ai-transparency`.
- Los archivos solo de desarrollo nunca se copian desde `bin/build-plugin.sh` salvo necesidad runtime explícita.
- Traducciones, metadata y código runtime se validan en la misma estructura que recibe el usuario.

### Verificación

El PR corregido superó las categorías oficiales `plugin_repo`, `security`, `accessibility` y `performance`, y después el CI completo post-merge.

### Relacionado

- PR #1
- `bin/build-plugin.sh`
- `.github/workflows/ci.yml`
- `docs/ENGINEERING_RULES.md`
