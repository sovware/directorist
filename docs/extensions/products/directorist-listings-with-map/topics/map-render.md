# Listings with Map: map-render

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Google/OpenStreetMap templates and asset loading depend on configured provider; keys, geolocation permission and script order affect rendered behavior.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Check both supported providers with authorized keys, denied geolocation and narrow viewport. Verify marker click/card focus and no stale pins after AJAX.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [directorist-listings-map.php:1](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/directorist-listings-map.php#L1) — 21 declarations
- [inc/Listings_With_Map_Model.php:1](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/inc/Listings_With_Map_Model.php#L1) — 69 declarations
- [inc/settings.php:1](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/inc/settings.php#L1) — 5 declarations
- [admin/assets/js/index.php:1](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/admin/assets/js/index.php#L1) — 0 declarations
- [public/assets/css/index.php:1](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/public/assets/css/index.php#L1) — 0 declarations
- [public/assets/js/index.php:1](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/public/assets/js/index.php#L1) — 0 declarations
- [templates/all-listings/columns-one/index.php:1](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/templates/all-listings/columns-one/index.php#L1) — 0 declarations
- [templates/all-listings/columns-one/map-listing.php:1](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/templates/all-listings/columns-one/map-listing.php#L1) — 0 declarations

[Complete topic source/data ledger](map-render-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-business-hours](../../directorist-business-hours/README.md) — only if active configuration or source connects it.
