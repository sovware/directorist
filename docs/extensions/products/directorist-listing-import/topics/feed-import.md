# Directorist Listing Importer: feed-import

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Feed discovery, preview, feed configuration and scheduler are separate; duplicate detection must remain stable across scheduled retries.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Use a local test feed with unique IDs, updated item and malformed response. Preview, run twice, schedule once and check counts, mappings, logs and cleanup.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [admin/class-admin-page.php:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/admin/class-admin-page.php#L1) — 19 declarations
- [google/includes/class-admin-page.php:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-admin-page.php#L1) — 12 declarations
- [google/includes/class-ajax-import.php:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-ajax-import.php#L1) — 7 declarations
- [google/includes/class-field-mapping.php:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-field-mapping.php#L1) — 44 declarations
- [google/includes/class-google-places-client.php:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-google-places-client.php#L1) — 19 declarations
- [google/includes/class-importer.php:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-importer.php#L1) — 24 declarations
- [google/includes/class-installer.php:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-installer.php#L1) — 3 declarations
- [google/includes/class-plugin.php:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-plugin.php#L1) — 5 declarations

[Complete topic source/data ledger](feed-import-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-business-hours](../../directorist-business-hours/README.md) — only if active configuration or source connects it.
- [directorist-gallery](../../directorist-gallery/README.md) — only if active configuration or source connects it.
- [directorist-advanced-review](../../directorist-advanced-review/README.md) — only if active configuration or source connects it.
