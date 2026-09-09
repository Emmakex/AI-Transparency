# Security Policy

## Supported versions

Kairoseth AI Transparency is currently in pre-release development. Security fixes target the current development line until the first stable release policy is published.

## Reporting a vulnerability

Please do **not** publish exploitable vulnerability details in a public GitHub issue.

Until a dedicated private reporting channel is published, contact Kairoseth through the canonical product/support contact path and clearly mark the message as a security report.

A useful report includes:

- affected plugin version or commit;
- WordPress and PHP versions;
- concise reproduction steps;
- security impact;
- whether authentication or a specific capability is required;
- minimal sanitized evidence.

Do not include real customer personal data, credentials, API keys, cookies, nonces or private AI-system configuration in the report.

## Security principles

- WordPress capabilities are checked server-side for privileged operations.
- State-changing actions require WordPress nonce/CSRF protection.
- Input is validated and sanitized at the boundary; output is escaped for its rendering context.
- The Free baseline does not send automatic telemetry to Kairoseth.
- Secrets and customer-specific credentials do not belong in this public repository.
- AI/provider credentials must never be exposed to browser output, logs or model context.
- Findings are evidence-backed and must not grant permissions or execute arbitrary model output.

## Disclosure

We support coordinated disclosure. Once a vulnerability is confirmed and a fix/release path exists, public disclosure can reference the fixed version and remediation guidance without exposing unrelated customer information.
