# Ephemeral runtime credentials appeared in CI command logs

Status: resolved  
First observed: 9 September 2026  
Affected area: CI security hygiene / WordPress runtime acceptance  
Severity: medium

## Symptom

The runtime acceptance workflow generated random WordPress test passwords correctly, but `wp-env` echoed expanded WP-CLI commands containing those ephemeral passwords into the GitHub Actions log.

No production, provider, customer or persistent credential was involved; the affected passwords belonged only to disposable users inside the destroyed CI environment.

## Root cause

The passwords were generated locally in the runner and passed as WP-CLI arguments before GitHub Actions had been instructed to mask their concrete values. Shell variables themselves are not automatically redacted.

## Resolution

Immediately after generating each ephemeral password, the workflow emits the GitHub Actions masking command before invoking any command that can echo the value. The credentials remain job-local and are exported only for the subsequent browser acceptance step.

## Prevention

- Mask dynamically generated secrets before first use, not after.
- Never rely on variable names to provide redaction.
- Keep runtime credentials ephemeral and isolated from production/customer credentials.
- Review CI tooling that echoes complete command lines.

## Verification

The closing Phase 2 runtime workflow uses pre-use masking and CI run `#53` (`34367111247`) passed the complete acceptance chain. Future runtime logs must show masked values rather than the generated passwords.

## Related evidence

- PR #5
- `.github/workflows/ci.yml`
- CI run #50 exposed only disposable runtime credentials; environment destroyed after the job
