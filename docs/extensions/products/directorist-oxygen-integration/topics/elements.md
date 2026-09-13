# Oxygen Builder Integration: elements

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

The Oxygen 6 branch can differ substantially from legacy shortcode elements. Identify the actual integration files and Oxygen generation before changing controls.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Build listings/search/detail in the installed Oxygen generation; save/reopen and compare filter IDs, pagination and native form behavior.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [class-control.php:1](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/class-control.php#L1) — 7 declarations
- [class-element.php:1](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/class-element.php#L1) — 10 declarations
- [directorist-oxygen-integration.php:1](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/directorist-oxygen-integration.php#L1) — 20 declarations
- [elements/add-listing.php:1](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/elements/add-listing.php#L1) — 5 declarations
- [elements/all-categories.php:1](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/elements/all-categories.php#L1) — 5 declarations
- [elements/all-listing.php:1](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/elements/all-listing.php#L1) — 5 declarations
- [elements/all-locations.php:1](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/elements/all-locations.php#L1) — 5 declarations
- [elements/author-profile.php:1](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/elements/author-profile.php#L1) — 4 declarations

[Complete topic source/data ledger](elements-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
