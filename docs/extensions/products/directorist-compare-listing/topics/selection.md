# Compare Listings: selection

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Selection add/remove, sidebar response and compare-page IDs must remain synchronized for guest and authenticated contexts.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Select two listings, remove one, refresh, clear and open comparison. Test repeated clicks and selection across pages.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [Inc/Controller/Base/AjaxHandler.php:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/Controller/Base/AjaxHandler.php#L1) — 10 declarations
- [Inc/Controller/Base/CompareButton.php:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/Controller/Base/CompareButton.php#L1) — 7 declarations
- [Inc/Controller/Base/HelperFunctions.php:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/Controller/Base/HelperFunctions.php#L1) — 4 declarations
- [Inc/View/ajax-select-window.php:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/View/ajax-select-window.php#L1) — 0 declarations
- [Inc/View/select-window.php:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/View/select-window.php#L1) — 0 declarations
- [Inc/View/selected-listings-sidebar.php:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/View/selected-listings-sidebar.php#L1) — 0 declarations
- [assets/frontend/js/atdlc-frontend-ajax.js:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/assets/frontend/js/atdlc-frontend-ajax.js#L1) — 14 declarations
- [assets/admin/css/atdlc-admin.css:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/assets/admin/css/atdlc-admin.css#L1) — 0 declarations

[Complete topic source/data ledger](selection-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
