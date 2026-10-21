# Kairoseth Extensions — 100% EN/ES Policy

[English](#english) · [Español](#español)

Status: **Mandatory release policy**

---

## English

### Product rule

Every Kairoseth extension ships **English and Spanish together at 100% customer-facing coverage**.

This is not a translation backlog target. It is a merge/release condition.

### What “100%” means

For each customer-facing functional release:

- every runtime translatable string has an English source string;
- every runtime translatable string has a non-empty Spanish translation;
- no newly introduced customer-facing string unintentionally falls back to English in Spanish locale;
- EN and ES describe the same feature/state, without stale or contradictory copy;
- errors, warnings, notices, validation messages, disclosure copy, buttons, labels, help and Custom Request CTA text are included;
- customer-facing exports/emails/reports introduced by the plugin are bilingual when that surface exists;
- customer documentation for the released feature is available in EN and ES, either in one bilingual document or equivalent localized documents.

### Normal exceptions

These items do not require translation unless product context specifically demands it:

- `Kairoseth`, product/brand names and trademarks;
- code identifiers, slugs, option keys, API field names and hashes;
- provider names and third-party registered names;
- standards/regulation identifiers such as `Article 50`, `EU AI Act`, `WCAG`, `JSON` when preserving the official identifier is clearer;
- immutable values that must remain exact for interoperability.

Exceptions must never be used to bypass translation of surrounding explanatory text.

### WordPress implementation contract

Customer-facing PHP strings use the canonical plugin text domain:

```text
kairoseth-ai-transparency
```

Source language is English. Spanish source translations live in:

```text
languages/ai-transparency-es_ES.po
```

The production build compiles that maintained source catalog to the canonical runtime fallback path:

```text
languages/kairoseth-ai-transparency-es_ES.mo
```

WordPress.org language packs remain authoritative. When an official language pack is installed under `WP_LANG_DIR/plugins`, WordPress resolves it before the plugin-local fallback. The bundled `es_ES` catalog exists so a Spanish WordPress installation still receives complete Spanish UI while a WordPress.org translation is awaiting validation, propagation or download.

The plugin does not expose its own language selector. Runtime language follows the WordPress locale. English remains the source/fallback language for unsupported locales.

### CI gate

The bilingual gate must fail when:

1. a discovered runtime gettext string is missing from the Spanish source catalog;
2. a Spanish `msgstr` is empty;
3. a source gettext call uses the wrong text domain;
4. the production build omits the compiled Spanish fallback catalog;
5. source `.po`/`.pot` catalogs leak into the production ZIP unnecessarily;
6. a real `es_ES` WordPress runtime does not resolve representative plugin strings to Spanish;
7. an English WordPress runtime does not retain the English source strings;
8. a customer-facing PR explicitly declares EN/ES incomplete.

WPCS/Plugin Check continue to enforce WordPress internationalization conventions. The Kairoseth bilingual checker adds the stronger business rule that **Spanish coverage cannot be incomplete**.

### PR requirement

Every customer-facing PR states:

```text
EN impact: complete / not applicable
ES impact: complete / not applicable
Bilingual coverage gate: pass
```

`not applicable` is only valid when the change genuinely contains no customer-facing language or UX impact.

### Unsupported locales

For locales other than EN/ES, WordPress may fall back to English until additional translations exist. English and Spanish are the mandatory Kairoseth baseline.

---

## Español

### Regla de producto

Cada extensión Kairoseth publica **inglés y español juntos con cobertura de cara al usuario del 100%**.

No es un objetivo futuro de traducción. Es una condición de merge y release.

### Qué significa “100%”

En cada release funcional de cara al usuario:

- cada cadena runtime traducible tiene una cadena fuente en inglés;
- cada cadena runtime traducible tiene traducción española no vacía;
- ninguna cadena de cara al usuario nueva cae involuntariamente al inglés con locale español;
- EN y ES describen la misma funcionalidad/estado sin copy desactualizado o contradictorio;
- se incluyen errores, warnings, notices, validaciones, disclosures, botones, labels, ayuda y CTA de Custom Requests;
- exports/emails/informes de cara al usuario creados por el plugin son bilingües cuando exista esa superficie;
- la documentación de usuario de la función publicada está disponible en EN y ES, dentro del mismo documento bilingüe o en equivalentes localizados.

### Excepciones normales

No requieren traducción salvo que el contexto de producto lo exija:

- `Kairoseth`, nombres de producto/marca y trademarks;
- identificadores de código, slugs, option keys, campos API y hashes;
- nombres de proveedores y marcas de terceros;
- identificadores oficiales como `Article 50`, `EU AI Act`, `WCAG`, `JSON` cuando mantenerlos exactos sea más claro;
- valores inmutables que deben conservarse por interoperabilidad.

Estas excepciones nunca permiten dejar sin traducir el texto explicativo que las acompaña.

### Contrato WordPress

Las cadenas PHP de cara al usuario usan el text domain canónico:

```text
kairoseth-ai-transparency
```

El idioma fuente es inglés. Las traducciones españolas fuente viven en:

```text
languages/ai-transparency-es_ES.po
```

El build de producción compila ese catálogo mantenido al fallback runtime canónico:

```text
languages/kairoseth-ai-transparency-es_ES.mo
```

Los paquetes de idioma de WordPress.org siguen siendo autoritativos. Cuando existe un paquete oficial en `WP_LANG_DIR/plugins`, WordPress lo resuelve antes que el fallback local del plugin. El catálogo `es_ES` incluido garantiza que una instalación de WordPress en español siga mostrando el plugin completamente en español mientras la traducción de WordPress.org espera validación, propagación o descarga.

El plugin no incluye un selector de idioma propio. El idioma de ejecución sigue el locale de WordPress. El inglés permanece como idioma fuente/fallback para locales no soportados.

### Gate CI

El gate bilingüe debe fallar cuando:

1. una cadena gettext runtime detectada falta en el catálogo español fuente;
2. un `msgstr` español está vacío;
3. una llamada gettext usa un text domain incorrecto;
4. el build de producción omite el catálogo español compilado de fallback;
5. los catálogos fuente `.po`/`.pot` se filtran innecesariamente al ZIP de producción;
6. un WordPress real en `es_ES` no resuelve cadenas representativas del plugin al español;
7. un WordPress en inglés no conserva las cadenas fuente en inglés;
8. un PR de cara al usuario declara explícitamente EN/ES incompleto.

WPCS/Plugin Check siguen comprobando las convenciones de internacionalización WordPress. El checker bilingüe Kairoseth añade la regla más estricta de negocio: **la cobertura española no puede quedar incompleta**.

### Requisito del PR

Cada PR de cara al usuario declara:

```text
Impacto EN: completo / no aplica
Impacto ES: completo / no aplica
Gate bilingüe: pass
```

`no aplica` solo es válido cuando el cambio realmente no afecta lenguaje ni UX de cara al usuario.

### Locales no soportados

En idiomas distintos de EN/ES, WordPress puede usar inglés como fallback hasta disponer de traducción adicional. Inglés y español son el baseline obligatorio de Kairoseth.
