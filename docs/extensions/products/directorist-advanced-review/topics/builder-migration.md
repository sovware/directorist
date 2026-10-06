# Directorist Advanced Review: builder-migration

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Activation/deactivation snapshots preserve builder review-section layouts while extension tables store criteria/reactions. Restoring a layout is distinct from migrating review data.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

On a disposable clone capture builder meta and reviews, activate/deactivate and compare snapshots/restored layout. Validate no data loss or duplicate review section after repeated activation.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Providers/FrontendServiceProvider.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Providers/FrontendServiceProvider.php#L1) — 4 declarations
- [database/Migrations/CreateDB.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/database/Migrations/CreateDB.php#L1) — 3 declarations
- [database/Migrations/Snap/DirectoryBuilderSnap.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/database/Migrations/Snap/DirectoryBuilderSnap.php#L1) — 17 declarations
- [database/Migrations/SnapMigration.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/database/Migrations/SnapMigration.php#L1) — 3 declarations
- [database/Migrations/TestMigration.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/database/Migrations/TestMigration.php#L1) — 3 declarations

[Complete topic source/data ledger](builder-migration-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
