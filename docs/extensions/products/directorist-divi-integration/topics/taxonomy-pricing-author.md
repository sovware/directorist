# Directorist Divi Integration: taxonomy-pricing-author

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Taxonomy loops, plan-card composition and author profiles use different object contexts and controllers. Plan action buttons must retain native entitlements/checkout; taxonomy card IDs must not be listing IDs.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Use two categories/locations, two plan types and two authors. Compare counts, term links, plan price/features/action URL and author privacy in editor/frontend. Save/reopen modules and verify native destination flow.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Http/Controllers/AllCategoriesController.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/Http/Controllers/AllCategoriesController.php#L1) — 10 declarations
- [app/Http/Controllers/AllLocationsController.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/Http/Controllers/AllLocationsController.php#L1) — 10 declarations
- [app/Http/Controllers/DirectoryTypesController.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/Http/Controllers/DirectoryTypesController.php#L1) — 2 declarations
- [app/Http/Controllers/PricingPlansController.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/Http/Controllers/PricingPlansController.php#L1) — 11 declarations
- [app/DiviModules/AllCategories/AllCategories.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/DiviModules/AllCategories/AllCategories.php#L1) — 73 declarations
- [app/DiviModules/AllLocations/AllLocations.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/DiviModules/AllLocations/AllLocations.php#L1) — 44 declarations
- [app/DiviModules/AuthorProfileAddress/AuthorProfileAddress.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/DiviModules/AuthorProfileAddress/AuthorProfileAddress.php#L1) — 3 declarations
- [app/DiviModules/AuthorProfileAvatar/AuthorProfileAvatar.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/DiviModules/AuthorProfileAvatar/AuthorProfileAvatar.php#L1) — 3 declarations

[Complete topic source/data ledger](taxonomy-pricing-author-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-business-hours](../../directorist-business-hours/README.md) — only if active configuration or source connects it.
- [directorist-claim-listing](../../directorist-claim-listing/README.md) — only if active configuration or source connects it.
- [directorist-faqs](../../directorist-faqs/README.md) — only if active configuration or source connects it.
- [directorist-gallery](../../directorist-gallery/README.md) — only if active configuration or source connects it.
- [directorist-booking](../../directorist-booking/README.md) — only if active configuration or source connects it.
- [directorist-universal-search](../../directorist-universal-search/README.md) — only if active configuration or source connects it.
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md) — only if active configuration or source connects it.
