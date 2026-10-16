# Pricing Plans: plan-presentation

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Public plan presentation filters published/hidden plans and renders plan actions, pricing and features from repository/configuration data. Presentation must not grant package entitlement.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Create published, hidden and draft plans with different intervals; compare native plan list, price/tax/feature labels and selected-plan checkout link. Test logged-out, existing package and trial-ineligible users.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Providers/ListingFormManager.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Providers/ListingFormManager.php#L1) — 14 declarations
- [app/Providers/ShortcodeServiceProvider.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Providers/ShortcodeServiceProvider.php#L1) — 3 declarations
- [app/Models/PlanAppConfiguration.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Models/PlanAppConfiguration.php#L1) — 3 declarations
- [app/Repositories/Admin/PlanAppConfigurationRepository.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Repositories/Admin/PlanAppConfigurationRepository.php#L1) — 4 declarations
- [app/Repositories/Admin/PlanRepository.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Repositories/Admin/PlanRepository.php#L1) — 27 declarations
- [app/Repositories/PlanRepository.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Repositories/PlanRepository.php#L1) — 5 declarations
- [app/Utils/View.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/app/Utils/View.php#L1) — 2 declarations
- [config/app.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/config/app.php#L1) — 34 declarations

[Complete topic source/data ledger](plan-presentation-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-stripe](../../directorist-stripe/README.md) — only if active configuration or source connects it.
- [directorist-paypal](../../directorist-paypal/README.md) — only if active configuration or source connects it.
- [directorist-authorize-net](../../directorist-authorize-net/README.md) — only if active configuration or source connects it.
- [directorist-coupon](../../directorist-coupon/README.md) — only if active configuration or source connects it.
- [directorist-claim-listing](../../directorist-claim-listing/README.md) — only if active configuration or source connects it.
