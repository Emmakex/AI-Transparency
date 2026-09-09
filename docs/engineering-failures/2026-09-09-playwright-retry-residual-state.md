# Playwright retry reused residual registry state

Status: resolved; prevention extended during Phase 4  
First observed: 9 September 2026  
Affected area: WordPress browser acceptance / test determinism  
Severity: medium

## Symptom

### Phase 2

The Phase 2 CRUD browser test became flaky on retry. A row locator intended to match one newly created record resolved to two rows:

```text
Phase 2 Runtime Assistant
Phase 2 Runtime Assistant Updated
```

Playwright strict mode therefore rejected the ambiguous locator.

### Phase 4 recurrence

Phase 4 CI #73 exposed the same underlying runtime property in two new forms:

```text
Discovery retry
→ first attempt had already added AI Engine to the persistent registry
→ retry waited for an Add to registry button that correctly no longer existed

Readiness assertion
→ migration smoke had intentionally left one active legacy record in the registry
→ AI Engine contributed 2 findings
→ legacy record contributed 3 findings
→ global finding count was 5, not the assumed 2
```

The product behavior was correct. The browser acceptance had incorrectly assumed a clean global registry and a non-persisted retry state.

## Root cause

The WordPress runtime/database intentionally survives Playwright retries and earlier acceptance steps within the same job. Tests had encoded hidden assumptions that:

1. a retry starts from a clean registry;
2. a preceding migration smoke leaves no meaningful application state;
3. global result counts belong only to the subject created by the current test.

Those assumptions are invalid for stateful end-to-end acceptance.

## Resolution

### Phase 2

The CRUD acceptance creates a unique record name per test execution/retry and uses that exact identifier through add/edit/archive assertions.

### Phase 4

The Discovery acceptance is retry-safe:

- when `Add to registry` exists, it exercises the explicit persistence action;
- when a retry finds `Already in registry`, it accepts that state and continues verifying the resulting record.

The Readiness acceptance now scopes findings to the **AI Engine** subject before asserting exactly two expected findings. It no longer interprets unrelated, valid registry findings created by migration acceptance as a Phase 4 failure.

## Prevention

- Runtime tests must assume persisted WordPress state can survive both earlier steps and Playwright retries.
- Tests that create mutable records should either reset only their owned state or use unique identifiers.
- Tests for derived collections must scope assertions to the intended subject/rule instead of assuming global counts, unless a clean global state is itself the contract being tested.
- Persistent actions must be retry-safe: a retry should recognize an already-completed valid state rather than waiting for a control that correctly disappeared after the first attempt.
- Do not delete valid state created by an earlier acceptance gate merely to make a later test simpler; isolate the later assertion instead.
- Strict locators remain enabled; ambiguity should fail instead of silently selecting the first match.

## Verification

Phase 2 prevention was verified by CI run `#53` (`34367111247`) on closing Phase 2 SHA `e5927a8a2b4526f01a4649c4ba5d3a25ae3c0353`.

Phase 4 recurrence was diagnosed in CI run `#73` (`34380922915`) with browser diagnostic signature:

```text
2cbedebbaab67fa9c91c1e34d5d433b286d85bd6851c7733ff7f60edbb71e4bc
```

Final Phase 4 verification must be recorded after the corrected PR head passes the full runtime suite.

## Related evidence

- PR #5
- CI run #50 browser acceptance retry failure
- PR #9
- CI run #73 runtime browser acceptance failure
- `tests/e2e/admin.spec.js`
- `tests/runtime/single-site-smoke.php`
