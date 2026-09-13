# WPML Integration: translation

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Language IDs, directory builder data and translated taxonomy/listing relationships require WPML-aware mapping; translating page text alone does not translate listing configuration.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Create two languages, two directories and translated listing/category pairs; switch language on add/edit/detail/search and compare object IDs and labels.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app.php:1](https://github.com/sovware/directorist-wpml-integration/blob/7d9224d9f6937cf970a562c1abfcd1079c6aad15/app.php#L1) — 12 declarations
- [directorist-wpml-integration.php:1](https://github.com/sovware/directorist-wpml-integration/blob/7d9224d9f6937cf970a562c1abfcd1079c6aad15/directorist-wpml-integration.php#L1) — 1 declarations
- [app/Controller/Ajax/Get_Directory_Type_Translations.php:1](https://github.com/sovware/directorist-wpml-integration/blob/7d9224d9f6937cf970a562c1abfcd1079c6aad15/app/Controller/Ajax/Get_Directory_Type_Translations.php#L1) — 6 declarations
- [app/Controller/Hook/Admin_Text_Translation.php:1](https://github.com/sovware/directorist-wpml-integration/blob/7d9224d9f6937cf970a562c1abfcd1079c6aad15/app/Controller/Hook/Admin_Text_Translation.php#L1) — 60 declarations
- [app/Controller/Hook/Category_Directory_Sync.php:1](https://github.com/sovware/directorist-wpml-integration/blob/7d9224d9f6937cf970a562c1abfcd1079c6aad15/app/Controller/Hook/Category_Directory_Sync.php#L1) — 4 declarations
- [app/Controller/Hook/Directory_Builder_Actions.php:1](https://github.com/sovware/directorist-wpml-integration/blob/7d9224d9f6937cf970a562c1abfcd1079c6aad15/app/Controller/Hook/Directory_Builder_Actions.php#L1) — 6 declarations
- [app/Controller/Hook/Directory_Builder_String_Package.php:1](https://github.com/sovware/directorist-wpml-integration/blob/7d9224d9f6937cf970a562c1abfcd1079c6aad15/app/Controller/Hook/Directory_Builder_String_Package.php#L1) — 86 declarations
- [app/Controller/Hook/Directory_Builder_UI_String_Package.php:1](https://github.com/sovware/directorist-wpml-integration/blob/7d9224d9f6937cf970a562c1abfcd1079c6aad15/app/Controller/Hook/Directory_Builder_UI_String_Package.php#L1) — 45 declarations

[Complete topic source/data ledger](translation-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
