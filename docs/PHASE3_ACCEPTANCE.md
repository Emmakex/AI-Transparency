# Phase 3 — Deterministic Discovery Acceptance

Status: **closed — accepted, merged and post-merge verified**  
Last reviewed: 9 September 2026

## Scope

This acceptance contract covers the first deterministic discovery increment for **AI Engine 3.7.7**.

## Blocking gates

### Identity and evidence

- [x] exact plugin file `ai-engine/ai-engine.php` observed
- [x] exact text domain `ai-engine` observed
- [x] active WordPress state required
- [x] exact validated version `3.7.7` accepted
- [x] other AI Engine versions reported outside validated boundary
- [x] unrelated/inactive plugins ignored
- [x] stable 64-character SHA-256 evidence signature

### Security/privacy

- [x] Discovery does not read AI provider credentials/API keys
- [x] Discovery does not read prompts/conversations/content
- [x] browser does not provide authoritative plugin/version/signature values
- [x] server re-observes WordPress before registry persistence
- [x] acceptance requires `manage_options` + nonce
- [x] Editor cannot access Discovery administration
- [x] no automatic external request/telemetry introduced

### Candidate semantics

- [x] Discovery never writes automatically
- [x] explicit administrator action required
- [x] accepted candidate uses `source_origin = discovered`
- [x] accepted candidate uses `review_status = pending`
- [x] accepted candidate uses `type = other`
- [x] disclosure requirement defaults to false
- [x] actual use/context remains for administrator review

### EN/ES and UX

- [x] EN/ES 100% runtime-string coverage green
- [x] Spanish `.mo` exists in production package
- [x] Discovery page responsive at 390 px
- [x] serious/critical axe violations = 0
- [x] evidence signature remains readable/wrapped on narrow viewports

### Runtime authority

- [x] CI installs exact WordPress.org AI Engine 3.7.7 fixture
- [x] AI Engine fixture activates in WordPress 7.1 / PHP 8.3 runtime
- [x] browser detects supported AI Engine result
- [x] browser sees exact plugin file/version/signature
- [x] explicit Add to registry succeeds
- [x] resulting registry row is `Other + Pending review + Active`

### Regression/release gates

- [x] PHPUnit/WPCS/PHPCompatibility green
- [x] PHP 7.4 / 8.1 / 8.3 / 8.5 syntax green
- [x] WordPress Plugin Check green on `build/ai-transparency/`
- [x] existing registry migration/CRUD/permissions acceptance remains green
- [x] Multisite registry isolation remains green
- [x] PR #7 merged to `main`
- [x] post-merge `main` verification green
- [x] engineering failure memory synchronized if material regression occurs — no new material regression required a new incident record
- [x] blockers = 0

## Evidence

```text
Pull request: #7
Accepted PR head: af44fe2156bde2947519e78112ea1d7a39abb0ff
Pre-merge CI: #68 / 34373934127
Merge commit: d595819a7a8f7d292bb23a7c919bec22f6138381
Post-merge CI: #69 / 34377130702
```

Detailed runtime evidence: [`PHASE3_RUNTIME_EVIDENCE.md`](PHASE3_RUNTIME_EVIDENCE.md).

## Exit

**Complete.** The first supported detector is merged and the exact `main` commit passed the complete required validation set. Phase 4 is unblocked.
