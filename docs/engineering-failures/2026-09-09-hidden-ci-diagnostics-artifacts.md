# Hidden CI diagnostics were not uploaded

Status: mitigated  
Date first observed: 2026-09-09  
Affected area: CI diagnostics / GitHub Actions artifacts  
Severity: medium

## Symptom

`bin/run-with-diagnostics.sh` correctly generated a normalized signature and wrote diagnostic files under `.ci-diagnostics/`, but the failing `PHP quality` job reported:

```text
No files were found with the provided path: .ci-diagnostics/. No artifacts will be uploaded.
```

The raw GitHub Actions job log still contained the failure, but the durable diagnostic artifact promised by the engineering policy was missing.

## Root cause

Confirmed: `actions/upload-artifact@v4` excludes hidden files/directories by default. The repository stored diagnostics in the hidden directory `.ci-diagnostics/`, while the workflow did not set `include-hidden-files: true`.

The diagnostic runner itself was functioning; the upload boundary silently excluded its output.

## Resolution

Every diagnostics upload step in `.github/workflows/ci.yml` now sets:

```yaml
include-hidden-files: true
```

This applies to PHP quality, bilingual coverage, PHP syntax and package diagnostics.

## Prevention

- Keep `.ci-diagnostics/` as the canonical bounded diagnostics directory.
- Every `actions/upload-artifact` step targeting that directory must explicitly opt into hidden files.
- When diagnostics are expected after a forced/real failure, verify both the step summary and downloadable artifact exist.
- Treat `if-no-files-found: ignore` only as resilience for jobs where no repository-controlled diagnostic can exist; do not interpret a skipped upload as evidence that diagnostics were persisted.

## Verification

Confirmed by CI run `34356351642` on PR #3 after the fix. The repository-controlled `PHP quality` failure generated three diagnostic files and `actions/upload-artifact@v4` successfully uploaded `php-quality-diagnostics.zip`.

Evidence:

```text
artifact: php-quality-diagnostics
artifact_id: 10105937328
files uploaded: 3
artifact size: 2339 bytes
result: successfully finalized and uploaded
```

This verifies the hidden-directory upload boundary is fixed. The failing PHP quality command itself remained a separate WPCS formatting issue and was diagnosed from the structured output rather than conflated with the artifact transport fix.

## Related

- `docs/CI_FAILURE_DIAGNOSTICS_POLICY.md`
- `bin/run-with-diagnostics.sh`
- `.github/workflows/ci.yml`
- PR #3
- CI run `34356351642`
