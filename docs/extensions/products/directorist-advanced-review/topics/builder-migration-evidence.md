# Directorist Advanced Review: builder-migration

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Activation/deactivation snapshots preserve builder review-section layouts while extension tables store criteria/reactions. Restoring a layout is distinct from migrating review data.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

On a disposable clone capture builder meta and reviews, activate/deactivate and compare snapshots/restored layout. Validate no data loss or duplicate review section after repeated activation.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Entry points and feature coverage

Read the canonical snapshot rows first. Local/default/development rows are available for variant comparison only. Every matching source file is listed with declarations; supporting styles/configuration may have no symbols.

| Snapshot / source | Functions or classes |
| --- | --- |
| advanced-review--fix-review-translation-pluralization / [app/Providers/FrontendServiceProvider.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Providers/FrontendServiceProvider.php#L1) | `FrontendServiceProvider` (L11), `boot` (L12), `add_template_path` (L17), `enqueue_frontend_assets` (L25) |
| advanced-review--fix-review-translation-pluralization / [database/Migrations/CreateDB.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/database/Migrations/CreateDB.php#L1) | `CreateDB` (L9), `more_than_version` (L12), `execute` (L16) |
| advanced-review--fix-review-translation-pluralization / [database/Migrations/Snap/DirectoryBuilderSnap.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/database/Migrations/Snap/DirectoryBuilderSnap.php#L1) | `DirectoryBuilderSnap` (L7), `migrate_activation_snap` (L8), `migrate_deactivation_snap` (L17), `restore_snaps` (L28), `get_default_snap` (L47), `get_default_core_snap` (L51), `get_extension_review_section_fields` (L76), `get_default_extension_snap` (L99), `get_current_snaps` (L124), `get_directory_snap_data` (L146), `get_directory_snap_data_for_single_listings_contents` (L159), `update_directory_snap_data_for_single_listings_contents` (L217), `update_directory_with_snap` (L283), `get_saved_snaps` (L295), `update_snaps` (L300), `delete_snaps` (L304), `get_terms` (L308) |
| advanced-review--fix-review-translation-pluralization / [database/Migrations/SnapMigration.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/database/Migrations/SnapMigration.php#L1) | `SnapMigration` (L9), `migrate_activation_snap` (L10), `migrate_deactivation_snap` (L14) |
| advanced-review--fix-review-translation-pluralization / [database/Migrations/TestMigration.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/database/Migrations/TestMigration.php#L1) | `TestMigration` (L9), `more_than_version` (L10), `execute` (L14) |

## Data readers, writers and lifecycle

Keys/callback expressions below are extracted without stored values. `get` reads; `update/add/delete` writes; scheduled/remote calls are side effects. ORM repositories and schema definitions are linked as source even where literal-key extraction cannot resolve them.

| Snapshot / source | Operation and key |
| --- | --- |
| advanced-review--fix-review-translation-pluralization / [app/Providers/FrontendServiceProvider.php:13](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Providers/FrontendServiceProvider.php#L13) | `add_filter('directorist_template_file_path', [$this, 'add_template_path'])` |
| advanced-review--fix-review-translation-pluralization / [app/Providers/FrontendServiceProvider.php:14](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Providers/FrontendServiceProvider.php#L14) | `add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets'])` |
| advanced-review--fix-review-translation-pluralization / [database/Migrations/CreateDB.php:53](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/database/Migrations/CreateDB.php#L53) | `dbDelta($sql)` |
| advanced-review--fix-review-translation-pluralization / [database/Migrations/Snap/DirectoryBuilderSnap.php:160](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/database/Migrations/Snap/DirectoryBuilderSnap.php#L160) | `get_term_meta($directory_id, 'single_listings_contents')` |
| advanced-review--fix-review-translation-pluralization / [database/Migrations/Snap/DirectoryBuilderSnap.php:218](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/database/Migrations/Snap/DirectoryBuilderSnap.php#L218) | `get_term_meta($directory_id, 'single_listings_contents')` |
| advanced-review--fix-review-translation-pluralization / [database/Migrations/Snap/DirectoryBuilderSnap.php:279](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/database/Migrations/Snap/DirectoryBuilderSnap.php#L279) | `update_term_meta($directory_id, 'single_listings_contents')` |
| advanced-review--fix-review-translation-pluralization / [database/Migrations/Snap/DirectoryBuilderSnap.php:296](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/database/Migrations/Snap/DirectoryBuilderSnap.php#L296) | `get_option("directorist_advanced_review_directory_snaps:{$type)` |
| advanced-review--fix-review-translation-pluralization / [database/Migrations/Snap/DirectoryBuilderSnap.php:301](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/database/Migrations/Snap/DirectoryBuilderSnap.php#L301) | `update_option("directorist_advanced_review_directory_snaps:{$type)` |
| advanced-review--fix-review-translation-pluralization / [database/Migrations/Snap/DirectoryBuilderSnap.php:305](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/database/Migrations/Snap/DirectoryBuilderSnap.php#L305) | `delete_option("directorist_advanced_review_directory_snaps:{$type)` |

## Core and integration context

Load [Core listing/form/query contracts](../../../core/listings.md) for a form/data/query issue, [rendering and builders](../../../core/rendering.md) for display, or [payment lifecycle](../../../core/payments.md) for orders. Do not load all three automatically.

For exact cross-repository hook matches, use `python3 docs/extensions/scripts/query.py hooks HOOK_NAME`; a same-name hook is only a candidate edge. For dynamic calls, inspect the referenced source and actual active callback list.
