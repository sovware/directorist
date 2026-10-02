# Image Gallery: gallery-import

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

CSV/gallery import scheduling and remote sideload are distinct from ordinary form uploads; cron schedule registration can affect execution.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Use two test image URLs plus failure; run relevant local import event twice, verify deduplication/status and gallery frontend. Inspect theme/builder image context separately.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [bd-directorist-gallery.php:1](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/bd-directorist-gallery.php#L1) — 40 declarations
- [inc/directory_type.php:1](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/inc/directory_type.php#L1) — 6 declarations
- [inc/importer.php:1](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/inc/importer.php#L1) — 15 declarations
- [templates/gallery-img-field.php:1](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/templates/gallery-img-field.php#L1) — 0 declarations
- [templates/gallery_image_upload.php:1](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/templates/gallery_image_upload.php#L1) — 0 declarations
- [templates/view_gallery.php:1](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/templates/view_gallery.php#L1) — 0 declarations
- [public/assets/js/main.js:1](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/public/assets/js/main.js#L1) — 0 declarations

[Complete topic source/data ledger](gallery-import-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md) — only if active configuration or source connects it.
- [directorist-divi-integration](../../directorist-divi-integration/README.md) — only if active configuration or source connects it.
