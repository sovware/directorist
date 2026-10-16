# Image Gallery: gallery-edit

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Upload, retained-image values and deselected-image cleanup must preserve images still attached to the listing.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Upload three images, remove one, save/edit again, test permissions and invalid files; compare attachment IDs and display settings.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [bd-directorist-gallery.php:1](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/bd-directorist-gallery.php#L1) — 40 declarations
- [inc/directory_type.php:1](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/inc/directory_type.php#L1) — 6 declarations
- [templates/gallery-img-field.php:1](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/templates/gallery-img-field.php#L1) — 0 declarations
- [templates/gallery_image_upload.php:1](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/templates/gallery_image_upload.php#L1) — 0 declarations
- [templates/view_gallery.php:1](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/templates/view_gallery.php#L1) — 0 declarations
- [admin/assets/js/main.js:1](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/admin/assets/js/main.js#L1) — 0 declarations

[Complete topic source/data ledger](gallery-edit-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md) — only if active configuration or source connects it.
- [directorist-divi-integration](../../directorist-divi-integration/README.md) — only if active configuration or source connects it.
