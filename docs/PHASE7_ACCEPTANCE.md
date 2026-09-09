# Phase 7 — Contextual Support / Custom Integration Acceptance

Status: **active contract — implementation not started**  
Last reviewed: 9 September 2026

## Scope

Phase 7 adds a user-initiated support/custom path from the WordPress plugin to the shared Kairoseth Custom Requests experience while preserving local-first independence and strict data minimization.

## Blocking gates

### Canonical destination

- [ ] one real production Custom Requests route exists under a Kairoseth-controlled HTTPS domain
- [ ] exact route is verified before implementation claims availability
- [ ] plugin does not ship an invented placeholder route as production behavior
- [ ] destination scheme must be HTTPS
- [ ] destination host must be `kairoseth.com` or an explicitly accepted Kairoseth subdomain
- [ ] invalid/non-Kairoseth destination fails closed
- [ ] URL userinfo/credentials are rejected

### WordPress authority

- [ ] **Tools → AI Transparency Support** exists
- [ ] page requires `manage_options`
- [ ] Editor/non-administrator cannot access it
- [ ] plugin slug/name/version are resolved server-side
- [ ] WordPress version is resolved server-side
- [ ] locale is resolved from WordPress
- [ ] `requestType` is chosen from a server-side allow-list
- [ ] browser values cannot override canonical product identity or destination authority

### User-initiated network boundary

- [ ] loading the support page performs no Kairoseth request
- [ ] no background AJAX/fetch/XHR/server-to-server call to Kairoseth
- [ ] only an explicit CTA click navigates to Kairoseth
- [ ] plugin itself does not submit the lead/request
- [ ] Kairoseth destination owns form consent/privacy/final submission

### Context allow-list

Only these initial keys may be generated automatically:

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

- [ ] query keys are exact allow-list only
- [ ] values are length-bounded
- [ ] values are URL encoded
- [ ] arbitrary query injection is rejected
- [ ] unknown request type fails closed

### Forbidden automatic context

The generated URL/request must not automatically contain:

- [ ] site/home URL
- [ ] administrator name/email/id
- [ ] customer/user identity
- [ ] Registry contents
- [ ] AI system names
- [ ] `interaction_context`
- [ ] Discovery evidence
- [ ] Readiness findings
- [ ] Disclosure state
- [ ] Evidence Export JSON
- [ ] `snapshot_signature`
- [ ] plugin/theme inventory
- [ ] server paths
- [ ] IP address
- [ ] cookies/nonces/session data
- [ ] credentials/API/OAuth tokens
- [ ] prompts/conversations/customer content
- [ ] logs/debug output
- [ ] database content
- [ ] arbitrary WordPress options

### Local Free independence

- [ ] Registry works without Kairoseth
- [ ] Discovery works without Kairoseth
- [ ] Readiness works without Kairoseth
- [ ] Disclosure works without Kairoseth
- [ ] Evidence Export works without Kairoseth
- [ ] unavailable/invalid support destination does not break plugin boot or local workflows
- [ ] no local feature unlock depends on support/custom submission
- [ ] no entitlement/licensing request introduced

### Administrator UX / EN-ES

- [ ] page purpose is concise in English and Spanish
- [ ] page states that local features work without contacting Kairoseth
- [ ] page states that clicking the CTA opens Kairoseth
- [ ] page states no Registry/evidence/personal data is automatically attached
- [ ] user chooses what personal/business information to submit on Kairoseth
- [ ] support and custom-integration actions are clearly differentiated
- [ ] customer-facing strings have EN/ES 100% coverage

### Accessibility / responsive

- [ ] 390 px layout passes
- [ ] horizontal overflow <= 1 px
- [ ] 200% text remains usable
- [ ] keyboard navigation works
- [ ] visible focus exists
- [ ] no color-only meaning
- [ ] axe serious/critical violations = 0

### Unit/security tests

- [ ] accepted request types build correctly
- [ ] unknown request type rejected
- [ ] context key allow-list exact
- [ ] arbitrary sensitive keys cannot be injected
- [ ] values bounded/encoded
- [ ] non-HTTPS URL rejected
- [ ] non-Kairoseth host rejected
- [ ] URL userinfo rejected
- [ ] canonical route required
- [ ] server-resolved version/locale context used

### Real WordPress runtime

- [ ] administrator opens Tools → AI Transparency Support
- [ ] no automatic Kairoseth request occurs on page load
- [ ] support CTA contains only allowed context
- [ ] custom CTA contains only allowed context
- [ ] destination is canonical HTTPS Kairoseth host
- [ ] Editor denied
- [ ] Registry remains unchanged
- [ ] Evidence Export/other local workflows remain operational when support destination is unavailable
- [ ] runtime test is retry-safe

### Kairoseth end-to-end

- [ ] real Custom Requests destination is available
- [ ] bounded context arrives correctly
- [ ] request type is preselected or represented correctly
- [ ] user can review/edit request details
- [ ] personal/business data is entered by user on Kairoseth side
- [ ] privacy/consent belongs to the Kairoseth form
- [ ] successful form submission persists through the Kairoseth Custom Requests backend
- [ ] no WordPress-secret/private context appears in the persisted request

### Regression/release gates

- [ ] PHPUnit/WPCS/PHPCompatibility green when implementation code changes
- [ ] PHP 7.4 / 8.1 / 8.3 / 8.5 syntax green
- [ ] EN/ES = 100%
- [ ] production package Plugin Check green
- [ ] Registry / Discovery / Readiness / Disclosure / Evidence Export regressions green
- [ ] Multisite remains green
- [ ] actionable diagnostics cover material failures
- [ ] engineering failure memory updated for material regressions
- [ ] implementation PR merged
- [ ] exact post-merge `main` verification green
- [ ] documentation synchronized
- [ ] blockers = 0

## Explicitly deferred

```text
automatic lead submission
server-to-server support API
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
verified production Kairoseth Custom Requests route
```

This external dependency does not block contract documentation, but it blocks Phase 7 implementation closure.

## Exit

Phase 7 may be declared closed only after the real user-initiated WordPress → Kairoseth Custom Requests flow passes the complete privacy, server-authority, EN/ES, accessibility, local-independence and end-to-end persistence gates above, with blockers = 0.
