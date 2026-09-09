# Phase 6 WPCS formatting and `@throws` interpretation

Status: **resolved**  
First observed: 9 September 2026  
Affected area: PHP Quality / WordPress Coding Standards  
Severity: low — non-behavioral CI blocker

## Symptom

Phase 6 Evidence Export implementation initially failed the repository `composer verify` gate before PHPUnit could complete.

### CI #88

```text
run: 34401564420
job: PHP Quality
step: Run coding standards, tests and bilingual coverage
command: composer verify
exit: 2
signature: 2a713bc20438829d660dc95e2e37ae7ee4f63636d066a158e0ddf13c82bcb26e
```

Reported:

```text
src/Export/class-evidencesnapshotbuilder.php:47
  Universal.Operators.DisallowShortTernary.Found

src/Export/class-evidencesnapshotbuilder.php:48
  Universal.Operators.DisallowShortTernary.Found

src/Export/class-evidencesnapshotbuilder.php:61
  Squiz.Commenting.FunctionCommentThrowTag.WrongNumber

src/Admin/class-evidenceexportpage.php:158
  Generic.Formatting.MultipleStatementAlignment.NotSameWarning
```

After the first formatting fix, CI #90 still failed:

```text
run: 34401789546
job: PHP Quality
step: Run coding standards, tests and bilingual coverage
command: composer verify
exit: 1
signature: 6b48a8a870fbf9e3431c98705136950325a21c62cd988d4b69f3048dda33ed09

src/Export/class-evidencesnapshotbuilder.php:60
  Squiz.Commenting.FunctionCommentThrowTag.Missing
  Missing @throws tag for InvalidArgumentException
```

## Root cause

Confirmed.

Two repository-specific WPCS expectations were violated:

1. the active standard disallows PHP short ternary syntax (`?:`) in this codebase;
2. the Squiz function-comment rule expects exception documentation to match the exception directly thrown by each method and did not treat the attempted combined/union-style declaration as satisfying the direct `InvalidArgumentException` contract.

A separate alignment warning in `EvidenceExportPage` was also blocking because `composer verify` treats the configured coding-standard result as a required gate.

This was not an Evidence Export logic failure. The deterministic snapshot contract, authorization boundary and runtime behavior were unchanged.

## Resolution

- replaced short ternaries with explicit conditional expressions;
- aligned assignments to repository WPCS expectations;
- documented `InvalidArgumentException` directly on `EvidenceSnapshotBuilder::build()`;
- kept `RuntimeException` documented on `canonical_json()`, the method that directly throws it.

## Prevention

Before pushing new PHP classes in this repository:

```text
1. follow existing WordPress-style explicit conditional syntax;
2. keep @throws documentation method-local to exceptions directly thrown there;
3. run the repository WPCS/`composer verify` gate before treating PHPUnit as reached;
4. when PHP Quality fails, inspect the structured diagnostic signature before changing behavior;
5. do not interpret a pre-PHPUnit WPCS failure as a functional regression.
```

For new exception contracts, prefer one clear directly thrown exception per documented `@throws` line/method unless the repository's active Squiz rule has already demonstrated support for another form.

## Verification

Resolved by:

```text
CI #91 / 34402108452 — SUCCESS — 8/8 jobs green
accepted head: 2b9ebe93820e98d9ce6e0abb4deb235fdeeda57c

Implementation merge:
bd07261751471fe7866e62049e3a66b7bd767afe

Post-merge main CI #92 / 34402685906 — SUCCESS — 8/8 jobs green
```

Both PHP Quality and the full runtime/Plugin Check matrix passed after the formatting/docblock corrections.

## Related evidence

- PR #15 — Phase 6 Evidence Export implementation.
- `docs/PHASE6_ACCEPTANCE.md`.
- `docs/PHASE6_RUNTIME_EVIDENCE.md`.
- `src/Export/class-evidencesnapshotbuilder.php`.
- `src/Admin/class-evidenceexportpage.php`.
