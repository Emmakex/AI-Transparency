# Phase 3 — Runtime Evidence

Status: **accepted — merged and post-merge verified**  
Last reviewed: 9 September 2026

This file is the evidence ledger for the first deterministic discovery detector.

## Runtime authority

```text
WordPress: 7.1
PHP: 8.3
production package: build/ai-transparency/
AI Engine fixture: WordPress.org 3.7.7
browser: Chromium / Playwright
```

## Accepted implementation

```text
Pull request: #7 — feat: add deterministic AI Engine discovery
Accepted PR head: af44fe2156bde2947519e78112ea1d7a39abb0ff
Pre-merge CI: #68 / 34373934127
Merge commit: d595819a7a8f7d292bb23a7c919bec22f6138381
Post-merge CI: #69 / 34377130702
Blockers: 0
```

## Pre-merge evidence — CI #68

All required jobs passed:

```text
PHP quality                                      green
EN/ES 100% coverage                             green
PHP 7.4 syntax                                  green
PHP 8.1 syntax                                  green
PHP 8.3 syntax                                  green
PHP 8.5 syntax                                  green
WordPress Plugin Check                          green
WordPress runtime acceptance                    green
```

The runtime acceptance proved:

```text
production ai-transparency package activates
AI Engine 3.7.7 installs from WordPress.org and activates
legacy registry migration remains green
administrator registry CRUD remains green
AI Engine is detected as the supported 3.7.7 boundary
exact plugin file/version evidence is rendered
64-character SHA-256 evidence signature is rendered
explicit Add to registry succeeds
created record is Other + Pending review + Active
Editor cannot access Discovery
390 px responsive acceptance is green
axe serious/critical accessibility acceptance is green
Multisite registry isolation remains green
```

## Post-merge evidence — CI #69

The exact `main` merge commit `d595819a7a8f7d292bb23a7c919bec22f6138381` repeated the complete required validation set and passed.

The post-merge runtime again proved:

```text
AI Engine 3.7.7 fixture install/activation       green
discovery browser acceptance                    green
registry regression acceptance                  green
Multisite isolation                             green
Plugin Check on exact production package        green
EN/ES 100%                                      green
```

## Evidence boundary

This evidence proves only the documented deterministic detector contract. It does not prove that AI Engine is configured as a chatbot, which provider/model is used, whether generated content exists, or whether a specific legal disclosure obligation applies.
