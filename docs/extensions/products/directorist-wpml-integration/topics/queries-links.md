# WPML Integration: queries-links

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Translated page links and language-filtered queries can differ from the default language and cached results.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Search a translated listing, follow pagination and dashboard/add-listing links, then switch language. Verify current-language term IDs, URLs and no unintended cross-language results.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app.php:1](https://github.com/sovware/directorist-wpml-integration/blob/7d9224d9f6937cf970a562c1abfcd1079c6aad15/app.php#L1) — 12 declarations
- [app/Controller/Hook/Directory_Builder_String_Package.php:1](https://github.com/sovware/directorist-wpml-integration/blob/7d9224d9f6937cf970a562c1abfcd1079c6aad15/app/Controller/Hook/Directory_Builder_String_Package.php#L1) — 86 declarations
- [app/Controller/Hook/Directory_Translation.php:1](https://github.com/sovware/directorist-wpml-integration/blob/7d9224d9f6937cf970a562c1abfcd1079c6aad15/app/Controller/Hook/Directory_Translation.php#L1) — 10 declarations
- [app/Controller/Hook/Filter_Permalinks.php:1](https://github.com/sovware/directorist-wpml-integration/blob/7d9224d9f6937cf970a562c1abfcd1079c6aad15/app/Controller/Hook/Filter_Permalinks.php#L1) — 24 declarations
- [app/Controller/Hook/Init.php:1](https://github.com/sovware/directorist-wpml-integration/blob/7d9224d9f6937cf970a562c1abfcd1079c6aad15/app/Controller/Hook/Init.php#L1) — 21 declarations
- [app/Controller/Hook/Listing_Count_Filter.php:1](https://github.com/sovware/directorist-wpml-integration/blob/7d9224d9f6937cf970a562c1abfcd1079c6aad15/app/Controller/Hook/Listing_Count_Filter.php#L1) — 4 declarations
- [app/Controller/Hook/Page_Setup_Translation.php:1](https://github.com/sovware/directorist-wpml-integration/blob/7d9224d9f6937cf970a562c1abfcd1079c6aad15/app/Controller/Hook/Page_Setup_Translation.php#L1) — 7 declarations
- [app/Controller/Hook/Page_Shortcode_UI_Translation.php:1](https://github.com/sovware/directorist-wpml-integration/blob/7d9224d9f6937cf970a562c1abfcd1079c6aad15/app/Controller/Hook/Page_Shortcode_UI_Translation.php#L1) — 83 declarations

[Complete topic source/data ledger](queries-links-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
