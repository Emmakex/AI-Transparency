# Phase 7 — Contextual Support / Custom Integration Acceptance

Status: **closed — accepted, merged and verified end-to-end**  
Last reviewed: 10 September 2026

## Scope

Phase 7 adds a user-initiated support/custom path from the WordPress plugin to the shared Kairoseth Custom Requests experience while preserving local-first independence and strict data minimization.

Canonical destination:

```text
https://kairoseth.com/custom-requests
```

## Blocking gates

### Canonical destination

- [x] one real production Custom Requests route exists under a Kairoseth-controlled HTTPS domain
- [x] exact route was verified before closure
- [x] plugin does not ship an invented placeholder route as production behavior
- [x] destination scheme is HTTPS
- [x] destination host is exactly `kairoseth.com`
- [x] destination path is exactly `/custom-requests`
- [x] invalid/non-Kairoseth destination fails closed
- [x] URL userinfo/credentials are rejected
- [x] custom port, preloaded query and fragment are rejected

### WordPress authority

- [x] **Tools → AI Transparency Support** exists
- [x] page requires `manage_options`
- [x] Editor/non-administrator cannot access it
- [x] plugin slug/name/version are resolved server-side
- [x] WordPress version is resolved server-side
- [x] locale is resolved from WordPress
- [x] `requestType` is chosen from a server-side allow-list
- [x] browser values cannot override canonical product identity or destination authority

### User-initiated network boundary

- [x] loading the support page performs no Kairoseth request
- [x] no background AJAX/fetch/XHR/server-to-server call to Kairoseth
- [x] only an explicit CTA click navigates to Kairoseth
- [x] plugin itself does not submit the lead/request
- [x] Kairoseth destination owns form consent/privacy/final submission

### Context allow-list

Only these initial keys are generated automatically:

```text
source
extensionSlug
extensionName
extensionVersion
hostPlatform
hostPlatformVersion
locale
requestType
```

Required accepted values:

```text
source=extension
extensionSlug=ai-transparency
extensionName=Kairoseth AI Transparency
hostPlatform=wordpress
```

Accepted initial `requestType` enum:

```text
implementation_support
third_party_integration
business_customization
automation
additional_feature
other
```

- [x] query keys are exact allow-list only
- [x] values are length-bounded
- [x] values are RFC3986 URL encoded
- [x] arbitrary query injection is rejected
- [x] unknown request type fails closed

### Forbidden automatic context

The generated URL/request does not automatically contain:

- [x] site/home URL
- [x] administrator name/email/id
- [x] customer/user identity
- [x] Registry contents
- [x] AI system names
- [x] `interaction_context`
- [x] Discovery evidence
- [x] Readiness findings
- [x] Disclosure state
- [x] Evidence Export JSON
- [x] `snapshot_signature`
- [x] plugin/theme inventory
- [x] server paths
- [x] IP address
- [x] cookies/nonces/session data
- [x] credentials/API/OAuth tokens
- [x] prompts/conversations/customer content
- [x] logs/debug output
- [x] database content
- [x] arbitrary WordPress options

### Local Free independence

- [x] Registry works without Kairoseth
- [x] Discovery works without Kairoseth
- [x] Readiness works without Kairoseth
- [x] Disclosure works without Kairoseth
- [x] Evidence Export works without Kairoseth
- [x] unavailable/invalid support destination does not break plugin boot or local workflows
- [x] no local feature unlock depends on support/custom submission
- [x] no entitlement/licensing request introduced

### Administrator UX / EN-ES

- [x] page purpose is concise in English and Spanish
- [x] page states that local features work without contacting Kairoseth
- [x] page states that clicking the CTA opens Kairoseth
- [x] page states no Registry/evidence/personal data is automatically attached
- [x] user chooses what personal/business information to submit on Kairoseth
- [x] support and custom-integration actions are clearly differentiated
- [x] customer-facing strings have EN/ES 100% coverage
- [x] compiled Spanish `.mo` ships in the production package

### Accessibility / responsive

- [x] 390 px layout passes
- [x] horizontal overflow <= 1 px
- [x] 200% text remains usable
- [x] keyboard navigation works
- [x] visible focus exists
- [x] no color-only meaning
- [x] axe serious/critical violations = 0

### Unit/security tests

- [x] accepted request types build correctly
- [x] unknown request type rejected
- [x] context key allow-list exact
- [x] arbitrary sensitive keys cannot be injected
- [x] values bounded/encoded
- [x] non-HTTPS URL rejected
- [x] non-Kairoseth host rejected
- [x] URL userinfo rejected
- [x] custom port rejected
- [x] preloaded query/fragment rejected
- [x] canonical route required
- [x] server-resolved version/locale context used

### Real WordPress runtime

- [x] administrator opens Tools → AI Transparency Support
- [x] no automatic Kairoseth request occurs on page load
- [x] support CTA contains only allowed context
- [x] custom CTA contains only allowed context
- [x] destination is canonical HTTPS Kairoseth host/path
- [x] Editor denied
- [x] Registry remains unchanged
- [x] inherited local workflows remain green
- [x] runtime test is retry-safe
- [x] real Multisite isolation remains green

### Kairoseth end-to-end

- [x] real Custom Requests destination is available
- [x] bounded context arrives correctly
- [x] request type is represented correctly
- [x] user can review/edit request details
- [x] personal/business data is entered by user on Kairoseth side
- [x] privacy/consent belongs to the Kairoseth form
- [x] successful form submission completes through the Kairoseth Custom Requests backend
- [x] production rate-limit persistence is operational
- [x] server-authoritative recipient resolution is operational
- [x] production SMTP delivery is operational
- [x] no WordPress-secret/private context is automatically submitted

### Regression/release gates

- [x] PHPUnit/WPCS/PHPCompatibility green
- [x] PHP 7.4 / 8.1 / 8.3 / 8.5 syntax green
- [x] EN/ES = 100%
- [x] production package Plugin Check green
- [x] Registry / Discovery / Readiness / Disclosure / Evidence Export regressions green
- [x] Multisite remains green
- [x] actionable diagnostics cover material failures
- [x] engineering failure memory updated for material regression found during implementation
- [x] implementation PR merged
- [x] exact post-merge `main` verification green
- [x] Kairoseth production surface verified after final recipient change
- [x] Kairoseth SMTP delivery verified with synthetic non-personal E2E
- [x] documentation synchronized in dedicated closure PR
- [x] blockers = 0

## Closure evidence

### WordPress plugin

```text
Contract PR: #17
Contract head: 703e04cd073e2c7572ec65b367ef5cbfd5b9c78a
Contract CI: #95 / 34405157557 — SUCCESS
Contract merge: cbc04eea07b20af60f3ec4b3621a9aa89c92ae84
Contract post-merge CI: #96 / 34405183831 — SUCCESS

Implementation PR: #18
Accepted implementation head: e49721eb00b85b0a4cfbf72d53e876d80dc96f44
PR-head CI: #101 / 34436069862 — SUCCESS — 8/8 jobs green
Implementation merge: f225646808f604b5758bbc960417451af8c31738
Post-merge main CI: #102 / 34436374187 — SUCCESS — 8/8 jobs green
```

### Kairoseth production

```text
Custom Requests implementation PR: kairoseth-platform #211
Implementation merge: 6855dfacd3ce6616c2f58d254e058ad3df59416c
Permanent production proof PR: kairoseth-platform #212
Production proof merge: 7d8752634e9b5e186a794080cb557c7b8cc6f349
Production Smoke #116 / 34434998950 — SUCCESS

SMTP recipient fallback PR: kairoseth-platform #213
PR-head CI #896 / 34436853412 — SUCCESS
PR-head Production Smoke #118 / 34436853390 — SUCCESS
Fallback merge: 5c01adfd40151da6392c8d780203230c315c19fb
Post-merge CI #897 / 34437075381 — SUCCESS
Post-merge Production Smoke #119 / 34437075355 — SUCCESS
Final synthetic delivery proof #4 / 34437244753 — SUCCESS
```

The final production proof selected `PASS - production SMTP delivery completed` and the assertion step also completed successfully.

Full runtime record: [`PHASE7_RUNTIME_EVIDENCE.md`](PHASE7_RUNTIME_EVIDENCE.md).

## Explicitly deferred

```text
automatic lead submission from WordPress
server-to-server support API from WordPress
Evidence Export upload
automatic diagnostic bundle
site URL transmission
Registry/findings transmission
support ticket history in WordPress
remote entitlement/licensing
paid local feature gating
in-plugin live chat
CRM synchronization from WordPress
plugin telemetry/click analytics
```

## Current blocker

```text
none
```

## Exit

Phase 7 exit: **complete.**
