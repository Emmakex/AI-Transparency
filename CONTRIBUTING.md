# Contributing

Thanks for helping improve Kairoseth AI Transparency.

## Engineering workflow

```text
issue / product contract
→ feature branch
→ minimum sufficient local validation
→ pull request
→ public CI
→ review
→ merge
→ release verification when applicable
```

Do not push feature work directly to `main`.

## Before opening a pull request

Run:

```bash
composer install
composer verify
```

Also verify any customer-facing change in both English and Spanish, and test the WordPress admin experience at relevant responsive widths.

## Product boundaries

Contributions must preserve these rules:

- no claim that the plugin certifies or guarantees legal compliance;
- no generic probabilistic AI-written-text detector in the v1 scope;
- no automatic telemetry or off-site transmission without an explicit product/consent contract;
- no client credentials, private data or proprietary customer logic in the public repository;
- no browser/model output may grant permissions;
- WordPress capability checks remain server-authoritative;
- evidence and findings must distinguish detected facts from user declarations and guidance.

## WordPress.org compatibility

Code intended for WordPress.org must remain compatible with current Plugin Directory rules, including GPL-compatible licensing of all bundled code/assets, human-readable source, no trialware, no non-consensual tracking, no dashboard hijacking and no public-site promotional links without permission.

## Commit and PR quality

A useful PR description includes:

- problem/contract changed;
- implementation summary;
- security/privacy impact;
- validation run;
- screenshots for customer-facing UI where relevant;
- EN/ES impact;
- known limitations or explicitly deferred work.

## Security reports

Do not open public issues for exploitable vulnerabilities. Follow `SECURITY.md`.
