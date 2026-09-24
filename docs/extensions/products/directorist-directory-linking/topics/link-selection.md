# Multi Directory Linking: link-selection

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Selection stores listing IDs, while presentation resolves linked listings and their directory/review data. IDs and scalar/array representations require normalization.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Link multiple listings from another directory, edit/remove/reorder and reload. Test deleted/private target and empty selection.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Builder/Builder.php:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/Builder/Builder.php#L1) — 10 declarations
- [app/base.php:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/base.php#L1) — 14 declarations
- [templates/add-listing.php:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/templates/add-listing.php#L1) — 0 declarations
- [templates/single-listing.php:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/templates/single-listing.php#L1) — 0 declarations
- [admin/js/admin.js:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/admin/js/admin.js#L1) — 0 declarations
- [assets/js/main.js:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/assets/js/main.js#L1) — 0 declarations

[Complete topic source/data ledger](link-selection-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-divi-integration](../../directorist-divi-integration/README.md) — only if active configuration or source connects it.
