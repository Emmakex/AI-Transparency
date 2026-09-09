# Playwright retry reused residual registry state

Status: resolved  
First observed: 9 September 2026  
Affected area: WordPress browser acceptance / test determinism  
Severity: medium

## Symptom

The Phase 2 CRUD browser test became flaky on retry. A row locator intended to match one newly created record resolved to two rows:

```text
Phase 2 Runtime Assistant
Phase 2 Runtime Assistant Updated
```

Playwright strict mode therefore rejected the ambiguous locator.

## Root cause

The test used a fixed record name while the WordPress runtime/database state survived Playwright retries. The first attempt could leave a record behind, so the retry was not operating on a clean logical namespace.

## Resolution

The CRUD acceptance now creates a unique record name per test execution/retry and uses that exact identifier through add/edit/archive assertions.

## Prevention

- Runtime tests must either reset persisted state between retries or create unique test data.
- Prefer unique identifiers for acceptance records when the host runtime intentionally persists state for the duration of a job.
- Strict locators remain enabled; ambiguity should fail instead of silently selecting the first match.

## Verification

CI run `#53` (`34367111247`) on closing Phase 2 SHA `e5927a8a2b4526f01a4649c4ba5d3a25ae3c0353` passed the complete administrator CRUD acceptance without flakiness.

## Related evidence

- PR #5
- CI run #50 browser acceptance retry failure
- `tests/e2e/admin.spec.js`
