# Directorist Advanced Review: review-data

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Review criteria and comments are linked records; activation/deactivation snapshots affect directory builder layouts. Core reviews and extension reviews must not be counted twice.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Create two criteria and unequal ratings, edit/delete a review, compare average, sorting and translated singular/plural text. Check activation snapshot restoration only in a disposable clone.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Http/Controllers/ReviewController.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Http/Controllers/ReviewController.php#L1) — 6 declarations
- [app/Providers/Admin/SettingsServiceProvider.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Providers/Admin/SettingsServiceProvider.php#L1) — 7 declarations
- [directorist-advanced-review.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/directorist-advanced-review.php#L1) — 6 declarations
- [app/DTO/ReactionDTO.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/DTO/ReactionDTO.php#L1) — 9 declarations
- [app/DTO/ReviewDTO.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/DTO/ReviewDTO.php#L1) — 23 declarations
- [app/DTO/ReviewReadDTO.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/DTO/ReviewReadDTO.php#L1) — 11 declarations
- [app/Helpers/helper.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Helpers/helper.php#L1) — 10 declarations
- [app/Http/Middleware/GuestReview.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Http/Middleware/GuestReview.php#L1) — 2 declarations

[Complete topic source/data ledger](review-data-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
