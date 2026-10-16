# Pricing Plans: checkout-renewal

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Checkout completion, recurring collection, package activation, listing renewal, cancellation and fallback plans must preserve distinct states. A paid order alone is not proof the correct package/listing was activated.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

In a sandbox compare free, one-off, recurring and trial orders; duplicate webhook, failed renewal, cancel-at-period-end, expired listing renewal and plan change. Assert owner, order reference, package limits and dates.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Http/Controllers/Admin/PackageController.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Http/Controllers/Admin/PackageController.php#L1) — 20 declarations
- [app/Http/Controllers/PackageController.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Http/Controllers/PackageController.php#L1) — 11 declarations
- [app/Providers/Admin/ListingPlanMetaboxProvider.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Providers/Admin/ListingPlanMetaboxProvider.php#L1) — 26 declarations
- [app/Providers/Admin/MigrationNoticeServiceProvider.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Providers/Admin/MigrationNoticeServiceProvider.php#L1) — 21 declarations
- [app/Providers/CheckoutServiceProvider.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Providers/CheckoutServiceProvider.php#L1) — 55 declarations
- [app/Providers/DirectCheckout.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Providers/DirectCheckout.php#L1) — 6 declarations
- [app/Providers/EmailService/PackageNotificationProvider.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Providers/EmailService/PackageNotificationProvider.php#L1) — 9 declarations
- [app/Providers/ListingDashboardServiceProvider.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Providers/ListingDashboardServiceProvider.php#L1) — 15 declarations

[Complete topic source/data ledger](checkout-renewal-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-stripe](../../directorist-stripe/README.md) — only if active configuration or source connects it.
- [directorist-paypal](../../directorist-paypal/README.md) — only if active configuration or source connects it.
- [directorist-authorize-net](../../directorist-authorize-net/README.md) — only if active configuration or source connects it.
- [directorist-coupon](../../directorist-coupon/README.md) — only if active configuration or source connects it.
- [directorist-claim-listing](../../directorist-claim-listing/README.md) — only if active configuration or source connects it.
