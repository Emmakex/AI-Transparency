# wp-env CLI command tokenization failure

Status: resolved  
First observed: 9 September 2026  
Affected area: WordPress runtime acceptance / CI  
Severity: medium

## Symptom

`WordPress runtime acceptance` started WordPress successfully, but the plugin activation step failed with exit code `127` and:

```text
OCI runtime exec failed: exec: "wp plugin is-active ai-transparency": executable file not found in $PATH
```

## Root cause

The workflow passed `"wp plugin is-active ai-transparency"` to `wp-env run cli` as one quoted argument. `wp-env` therefore attempted to execute that entire string as the executable name instead of running `wp` with separate arguments.

## Resolution

Pass the command and arguments as separate tokens after the `--` separator:

```text
npm exec -- wp-env run cli -- wp plugin is-active ai-transparency
```

The same tokenized form is required for `wp eval-file`, `wp user ...` and Multisite runtime commands.

## Prevention

- Do not wrap a complete WP-CLI command in one quoted argument when using `wp-env run`.
- Keep a real plugin-activation check early in runtime acceptance so command-runner regressions fail before browser tests.
- Preserve the structured CI diagnostic signature and exact failing command.

## Verification

CI run `#53` (`34367111247`) on closing Phase 2 SHA `e5927a8a2b4526f01a4649c4ba5d3a25ae3c0353` passed `Verify production plugin activation`, real migration, browser acceptance and Multisite isolation.

## Related evidence

- PR #5
- CI failed run #47: plugin activation exited 127
- CI green run #53: `WordPress runtime acceptance` success
