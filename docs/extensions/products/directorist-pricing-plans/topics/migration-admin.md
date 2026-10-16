# Pricing Plans: migration-admin

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Migration/background repair and admin assignment can change plan relations and expiration. Keep migration status, legacy IDs and new IDs traceable; do not rerun destructive migration on client data.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Clone representative legacy plans/orders into disposable DB; capture counts and relations, migrate, retry interrupted batches, verify no duplicate packages and preserved lifetime/period dates.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Http/Controllers/Admin/DirectoryController.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Http/Controllers/Admin/DirectoryController.php#L1) — 11 declarations
- [app/Http/Controllers/Admin/ListingController.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Http/Controllers/Admin/ListingController.php#L1) — 4 declarations
- [app/Http/Controllers/Admin/MigrationController.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Http/Controllers/Admin/MigrationController.php#L1) — 10 declarations
- [app/Http/Controllers/Admin/PackageController.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Http/Controllers/Admin/PackageController.php#L1) — 20 declarations
- [app/Http/Controllers/Admin/PlanController.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Http/Controllers/Admin/PlanController.php#L1) — 10 declarations
- [app/Providers/Admin/BuilderFormFields.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Providers/Admin/BuilderFormFields.php#L1) — 8 declarations
- [app/Providers/Admin/ListingPlanColumnProvider.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Providers/Admin/ListingPlanColumnProvider.php#L1) — 7 declarations
- [app/Providers/Admin/ListingPlanMetaboxProvider.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Providers/Admin/ListingPlanMetaboxProvider.php#L1) — 26 declarations

[Complete topic source/data ledger](migration-admin-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-stripe](../../directorist-stripe/README.md) — only if active configuration or source connects it.
- [directorist-paypal](../../directorist-paypal/README.md) — only if active configuration or source connects it.
- [directorist-authorize-net](../../directorist-authorize-net/README.md) — only if active configuration or source connects it.
- [directorist-coupon](../../directorist-coupon/README.md) — only if active configuration or source connects it.
- [directorist-claim-listing](../../directorist-claim-listing/README.md) — only if active configuration or source connects it.
