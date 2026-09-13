# Listings with Map: map-query

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

AJAX query state and rendered listing IDs must agree with map markers, directory and taxonomy filters; map-service failures are not automatically query defects.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Use three geocoded listings across two directories; filter, change view, paginate and pan where supported. Compare visible IDs/markers and request parameters, including zero results.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [inc/Listings_With_Map_Model.php:1](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/inc/Listings_With_Map_Model.php#L1) — 69 declarations
- [inc/ajax.php:1](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/inc/ajax.php#L1) — 7 declarations
- [inc/helper.php:1](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/inc/helper.php#L1) — 7 declarations
- [inc/hooks.php:1](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/inc/hooks.php#L1) — 9 declarations
- [public/assets/js/map-viewport-sync.js:1](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/public/assets/js/map-viewport-sync.js#L1) — 31 declarations

[Complete topic source/data ledger](map-query-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-business-hours](../../directorist-business-hours/README.md) — only if active configuration or source connects it.
