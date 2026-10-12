# DTour private-product identity QA — 2026-10-12

## Change

Use a validated API slug to key purchased products. Preserve the existing permalink fallback for older responses and existing associative keys for legacy/filter records without a slug. Normalize cached records before purchase filters and normalize filter results as well. Store successful activation records using the same identity rule. Account session checks, remote permission decisions, item IDs, license values and package URLs are unchanged.

## Local verification

- Red/green regression: the new private-permalink assertion failed before the fix and passes after it.
- `php tests/php/purchased-product-identity.php`: 39 checks pass. Covers private query permalinks, legacy public HTTP/HTTPS permalinks, malformed/invalid slugs, existing caches, filters, duplicate aliases, active/inactive theme matching, OneListing Pro update metadata, and native activation request IDs/licenses.
- Activation tests use in-memory HTTP/metadata doubles; they do not contact EDD or persist user records.
- PHP syntax checks and repository PHPCS with the same warning-severity=0 setting used by CI pass for the changed PHP files.
- Actual local WordPress account cache: DTour is resolved under dtour without rewriting the stored malformed key or reconnecting the account. OneListing Pro and all other purchase records compare unchanged.
- Local browser: the previous active DTour plus not-installed dTour pair becomes a single active DTour row after reload. The existing OneListing Pro subscription row and its install/demo/details controls remain available.
- During browser QA, a temporary local MU guard blocked outbound WordPress HTTP calls. The guard was removed after verification.

## Boundaries

No live settings, products, permissions, licenses or code were changed. No live download, license activation or complete OneListing Pro upgrade was performed. This validates the Core identity/matching regression and preserves the existing native flow; it does not establish DTour custom updater API readiness. No theme code or server licensing patch is included in this fix.

Browser evidence and sanitized local diagnostics are retained in the task's local artifacts, not in account-contract documentation.
