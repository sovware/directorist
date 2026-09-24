# Compare Listings: comparison

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Comparison page/shortcode and directory field configuration determine which fields display.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Compare listings with missing and populated custom fields from one/two directories; check page setup, values, image output and mobile horizontal overflow.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [directorist-compare-listing.php:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/directorist-compare-listing.php#L1) — 12 declarations
- [Inc/Controller/Base/Activate.php:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/Controller/Base/Activate.php#L1) — 8 declarations
- [Inc/Controller/Base/ExtensionSettings.php:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/Controller/Base/ExtensionSettings.php#L1) — 9 declarations
- [Inc/Controller/Shortcodes/ShortcodeListingCompare.php:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/Controller/Shortcodes/ShortcodeListingCompare.php#L1) — 4 declarations
- [Inc/View/compare-page.php:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/View/compare-page.php#L1) — 0 declarations

[Complete topic source/data ledger](comparison-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
