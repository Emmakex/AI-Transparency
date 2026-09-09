# Admin archive action contrast failure

Status: resolved  
First observed: 9 September 2026  
Affected area: WordPress admin UX / accessibility  
Severity: medium

## Symptom

The real browser acceptance gate failed axe WCAG 2 AA `color-contrast` for the `Archive` action in the AI Systems Registry.

Observed runtime evidence:

```text
foreground: #d63638
background: #efefef
contrast: 4.1:1
required: 4.5:1
font: 13px normal
```

## Root cause

The plugin inherited WordPress destructive-link styling that did not meet the minimum contrast threshold in the tested admin background/context at the rendered text size.

## Resolution

The plugin-owned admin stylesheet now overrides the destructive action to a darker accessible foreground and preserves a clear hover/focus state while keeping the semantic destructive affordance.

## Prevention

- Run axe against the actual WordPress admin surface, not isolated markup only.
- Treat `serious` and `critical` accessibility findings as blocking for customer-facing admin UX.
- Verify destructive/status actions in the real host theme/background because inherited platform colors can change effective contrast.

## Verification

CI run `#53` (`34367111247`) on closing Phase 2 SHA `e5927a8a2b4526f01a4649c4ba5d3a25ae3c0353` passed the responsive/accessibility browser acceptance with no serious or critical axe violations.

## Related evidence

- PR #5
- CI run #50 browser acceptance failure
- `assets/admin.css`
- `tests/e2e/admin.spec.js`
