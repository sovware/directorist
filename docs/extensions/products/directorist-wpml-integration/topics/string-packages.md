# WPML Integration: string-packages

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Directory-builder and page/shortcode/block/Elementor UI strings are registered/synchronized through WPML package workflows. Completed ATE translations and runtime fallback strings have separate readers/writers.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Translate directory field labels and native page controls via a completed test translation job, save source again and verify update flags, translated frontend/editor labels and unchanged translated object relationships.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Controller/Asset/AdminAsset.php:1](https://github.com/sovware/directorist-wpml-integration/blob/7d9224d9f6937cf970a562c1abfcd1079c6aad15/app/Controller/Asset/AdminAsset.php#L1) — 5 declarations
- [app/Controller/Asset/AssetEnqueuer.php:1](https://github.com/sovware/directorist-wpml-integration/blob/7d9224d9f6937cf970a562c1abfcd1079c6aad15/app/Controller/Asset/AssetEnqueuer.php#L1) — 6 declarations
- [app/Controller/Asset/Init.php:1](https://github.com/sovware/directorist-wpml-integration/blob/7d9224d9f6937cf970a562c1abfcd1079c6aad15/app/Controller/Asset/Init.php#L1) — 4 declarations
- [app/Controller/Asset/PublicAsset.php:1](https://github.com/sovware/directorist-wpml-integration/blob/7d9224d9f6937cf970a562c1abfcd1079c6aad15/app/Controller/Asset/PublicAsset.php#L1) — 5 declarations
- [app/Controller/Hook/Admin_Text_Translation.php:1](https://github.com/sovware/directorist-wpml-integration/blob/7d9224d9f6937cf970a562c1abfcd1079c6aad15/app/Controller/Hook/Admin_Text_Translation.php#L1) — 60 declarations
- [app/Controller/Hook/Directory_Builder_String_Package.php:1](https://github.com/sovware/directorist-wpml-integration/blob/7d9224d9f6937cf970a562c1abfcd1079c6aad15/app/Controller/Hook/Directory_Builder_String_Package.php#L1) — 86 declarations
- [app/Controller/Hook/Directory_Builder_UI_String_Package.php:1](https://github.com/sovware/directorist-wpml-integration/blob/7d9224d9f6937cf970a562c1abfcd1079c6aad15/app/Controller/Hook/Directory_Builder_UI_String_Package.php#L1) — 45 declarations
- [app/Controller/Hook/Directory_Type_Translation_Management_Button.php:1](https://github.com/sovware/directorist-wpml-integration/blob/7d9224d9f6937cf970a562c1abfcd1079c6aad15/app/Controller/Hook/Directory_Type_Translation_Management_Button.php#L1) — 9 declarations

[Complete topic source/data ledger](string-packages-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
