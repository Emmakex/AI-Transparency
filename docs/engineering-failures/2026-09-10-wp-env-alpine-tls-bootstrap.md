# wp-env Alpine TLS bootstrap failure

Status: **resolved / external transient**  
First observed: **10 September 2026**  
Affected area: **CI → WordPress runtime acceptance → wp-env bootstrap**  
Severity: **medium — blocking CI, no product regression**

## Symptom

The Phase 7 closure PR CI failed at:

```text
WordPress runtime acceptance
→ Start single-site WordPress
→ npm exec -- wp-env start
```

The Docker build reported:

```text
WARNING: fetching https://dl-cdn.alpinelinux.org/alpine/v3.24/main/x86_64/APKINDEX.tar.gz: TLS: unspecified error
ERROR: unable to select packages:
  linux-headers (no such package)
```

The actionable diagnostics wrapper emitted exit code `1` with signature:

```text
8684f2ddce5963142b799f763fb032eccdf961e4a9d068496150ee8ca44cb108
```

## Root cause

Confirmed external transient dependency failure.

The `wordpress:cli-php8.3`/`wp-env` CLI image build attempted to fetch Alpine package indexes from `dl-cdn.alpinelinux.org`. The remote package-index request failed with a TLS transport error. Because the index was unavailable, `apk` subsequently reported `linux-headers` as unavailable even though that package exists for the target Alpine repository.

The failure happened before the plugin was activated and before any plugin runtime, migration, browser acceptance or Multisite test executed.

## Resolution

Re-run the failed WordPress runtime job without modifying plugin code.

On retry:

- Alpine package index download succeeded;
- `wp-env` single-site bootstrap succeeded;
- plugin activation/migration succeeded;
- browser acceptance succeeded;
- Multisite acceptance succeeded;
- the complete job finished green.

The later post-merge CI also completed the same `wp-env` bootstrap and full runtime suite successfully without code changes.

## Prevention / diagnostic rule

When `wp-env start` fails while building its Docker CLI image:

1. inspect the earliest package-manager/network failure rather than treating a later `no such package` line as the root cause;
2. determine whether plugin activation has been reached;
3. if the error is an external TLS/index/mirror failure and the same source state previously passed deterministic gates, retry only the affected runtime job before changing product code;
4. do not classify the failure as a plugin regression unless it reproduces after the external dependency succeeds or plugin execution is actually reached;
5. preserve the diagnostics signature and exact failing build stage.

A future CI hardening change may add bounded retry around `wp-env start`, but such retry must not hide reproducible plugin/runtime failures.

## Verification

```text
Initial failing PR CI: #103 / 34437601453
Failing job: WordPress runtime acceptance
Failing step: Start single-site WordPress
Signature: 8684f2ddce5963142b799f763fb032eccdf961e4a9d068496150ee8ca44cb108
Retry: same source state, successful full runtime acceptance
Final post-merge main CI: #104 / 34438101671 — SUCCESS — 8/8 jobs green
```

## Related evidence

- `bin/run-with-diagnostics.sh`
- `.github/workflows/ci.yml`
- `docs/CI_FAILURE_DIAGNOSTICS_POLICY.md`
- `docs/engineering-failures/README.md`
