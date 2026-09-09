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

Customer-facing PHP strings use the plugin text domain:

```text
kairoseth-ai-transparency
```

Source language is English. Spanish source translations live in:

```text
languages/kairoseth-ai-transparency-es_ES.po
```

The release build compiles the Spanish catalog into the production package.

### CI gate

The bilingual gate must fail when:

1. a discovered runtime gettext string is missing from the Spanish catalog;
2. a Spanish `msgstr` is empty;
3. a source gettext call uses the wrong text domain;
4. the production build omits the Spanish catalog/compiled translation;
5. a customer-facing PR explicitly declares EN/ES incomplete.

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

Cada extensión Kairoseth publica **inglés y español juntos con cobertura customer-facing del 100%**.

No es un objetivo futuro de traducción. Es una condición de merge y release.

### Qué significa “100%”

En cada release funcional de cara al usuario:

- cada cadena runtime traducible tiene una cadena fuente en inglés;
- cada cadena runtime traducible tiene traducción española no vacía;
- ninguna cadena customer-facing nueva cae involuntariamente al inglés con locale español;
- EN y ES describen la misma funcionalidad/estado sin copy desactualizado o contradictorio;
- se incluyen errores, warnings, notices, validaciones, disclosures, botones, labels, ayuda y CTA de Custom Requests;
- exports/emails/informes customer-facing creados por el plugin son bilingües cuando exista esa superficie;
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

Las cadenas PHP customer-facing usan el text domain:

```text
kairoseth-ai-transparency
```

El idioma fuente es inglés. Las traducciones españolas viven en:

```text
languages/kairoseth-ai-transparency-es_ES.po
```

El build de release compila el catálogo español dentro del paquete de producción.

### Gate CI

El gate bilingüe debe fallar cuando:

1. una cadena gettext runtime detectada falta en el catálogo español;
2. un `msgstr` español está vacío;
3. una llamada gettext usa un text domain incorrecto;
4. el build de producción omite el catálogo/traducción compilada española;
5. un PR customer-facing declara explícitamente EN/ES incompleto.

WPCS/Plugin Check siguen comprobando las convenciones de internacionalización WordPress. El checker bilingüe Kairoseth añade la regla más estricta de negocio: **la cobertura española no puede quedar incompleta**.

### Requisito del PR

Cada PR customer-facing declara:

```text
Impacto EN: completo / no aplica
Impacto ES: completo / no aplica
Gate bilingüe: pass
```

`no aplica` solo es válido cuando el cambio realmente no afecta lenguaje ni UX customer-facing.

### Locales no soportados

En idiomas distintos de EN/ES, WordPress puede usar inglés como fallback hasta disponer de traducción adicional. Inglés y español son el baseline obligatorio de Kairoseth.
