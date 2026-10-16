# Listings Slider & Carousel: slider-motion

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Frontend assets and responsive settings determine carousel navigation/autoplay independently of the listing query.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Test desktop/mobile controls, keyboard/focus, one/zero results, multiple sliders and reduced-motion preference. Verify emitted options before changing global scripts.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [bd-directorist-slider.php:1](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L1) — 17 declarations
- [assets/admin/main.js:1](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/assets/admin/main.js#L1) — 0 declarations
- [assets/admin/style-rtl.css:1](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/assets/admin/style-rtl.css#L1) — 0 declarations
- [assets/admin/style.css:1](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/assets/admin/style.css#L1) — 0 declarations
- [assets/css/style-rtl.css:1](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/assets/css/style-rtl.css#L1) — 0 declarations
- [assets/css/style.css:1](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/assets/css/style.css#L1) — 0 declarations

[Complete topic source/data ledger](slider-motion-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
