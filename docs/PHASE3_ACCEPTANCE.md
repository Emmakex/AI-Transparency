# Phase 3 — Deterministic Discovery Acceptance

Status: active acceptance  
Last reviewed: 9 September 2026

## Scope

This acceptance contract applies only to the first deterministic discovery increment for **AI Engine 3.7.7**.

## Blocking gates

### Identity and evidence

- [ ] exact plugin file `ai-engine/ai-engine.php` observed
- [ ] exact text domain `ai-engine` observed
- [ ] active WordPress state required
- [ ] exact validated version `3.7.7` accepted
- [ ] other AI Engine versions reported outside validated boundary
- [ ] unrelated/inactive plugins ignored
- [ ] stable 64-character SHA-256 evidence signature

### Security/privacy

- [ ] Discovery does not read AI provider credentials/API keys
- [ ] Discovery does not read prompts/conversations/content
- [ ] browser does not provide authoritative plugin/version/signature values
- [ ] server re-observes WordPress before registry persistence
- [ ] acceptance requires `manage_options` + nonce
- [ ] Editor cannot access Discovery administration
- [ ] no automatic external request/telemetry introduced

### Candidate semantics

- [ ] Discovery never writes automatically
- [ ] explicit administrator action required
- [ ] accepted candidate uses `source_origin = discovered`
- [ ] accepted candidate uses `review_status = pending`
- [ ] accepted candidate uses `type = other`
- [ ] disclosure requirement defaults to false
- [ ] actual use/context remains for administrator review

### EN/ES and UX

- [ ] EN/ES 100% runtime-string coverage green
- [ ] Spanish `.mo` exists in production package
- [ ] Discovery page responsive at 390 px
- [ ] serious/critical axe violations = 0
- [ ] evidence signature remains readable/wrapped on narrow viewports

### Runtime authority

- [ ] CI installs exact WordPress.org AI Engine 3.7.7 fixture
- [ ] AI Engine fixture activates in WordPress 7.1 / PHP 8.3 runtime
- [ ] browser detects supported AI Engine result
- [ ] browser sees exact plugin file/version/signature
- [ ] explicit Add to registry succeeds
- [ ] resulting registry row is `Other + Pending review + Active`

### Regression/release gates

- [ ] PHPUnit/WPCS/PHPCompatibility green
- [ ] PHP 7.4 / 8.1 / 8.3 / 8.5 syntax green
- [ ] WordPress Plugin Check green on `build/ai-transparency/`
- [ ] existing registry migration/CRUD/permissions acceptance remains green
- [ ] Multisite registry isolation remains green
- [ ] PR merged to `main`
- [ ] post-merge `main` verification green
- [ ] engineering failure memory synchronized if material regression occurs
- [ ] blockers = 0

## Exit

Phase 3 may be declared closed only when the first detector is merged and the exact `main` commit passes the complete required validation set above.
