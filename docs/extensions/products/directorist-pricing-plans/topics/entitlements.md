# Pricing Plans: entitlements

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Plan entitlements can suppress fields independently of saved listing metadata. Legacy post/meta plans and newer package tables are separate storage generations; select the actual implementation first.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Use two directory types, free/paid plans, exhausted and available quotas, and a listing with no assigned plan. Compare add/edit/detail rendering, assignment permission and package usage.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Http/Controllers/Admin/DirectoryController.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Http/Controllers/Admin/DirectoryController.php#L1) — 11 declarations
- [app/Http/Controllers/Admin/PackageController.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Http/Controllers/Admin/PackageController.php#L1) — 20 declarations
- [app/Http/Controllers/Admin/PlanController.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Http/Controllers/Admin/PlanController.php#L1) — 10 declarations
- [app/Http/Controllers/Legacy/PlanController.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Http/Controllers/Legacy/PlanController.php#L1) — 26 declarations
- [app/Http/Controllers/ListingsController.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Http/Controllers/ListingsController.php#L1) — 7 declarations
- [app/Http/Controllers/PlanController.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Http/Controllers/PlanController.php#L1) — 3 declarations
- [app/Providers/Admin/BuilderFormFields.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Providers/Admin/BuilderFormFields.php#L1) — 8 declarations
- [app/Providers/Admin/ListingPlanMetaboxProvider.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Providers/Admin/ListingPlanMetaboxProvider.php#L1) — 26 declarations

[Complete topic source/data ledger](entitlements-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-stripe](../../directorist-stripe/README.md) — only if active configuration or source connects it.
- [directorist-paypal](../../directorist-paypal/README.md) — only if active configuration or source connects it.
- [directorist-authorize-net](../../directorist-authorize-net/README.md) — only if active configuration or source connects it.
- [directorist-coupon](../../directorist-coupon/README.md) — only if active configuration or source connects it.
- [directorist-claim-listing](../../directorist-claim-listing/README.md) — only if active configuration or source connects it.
