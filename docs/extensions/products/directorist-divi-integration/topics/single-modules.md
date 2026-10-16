# Directorist Divi Integration: single-modules

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Extension modules depend on installed plugin basenames and queried listing context. Theme Builder body-layout ID must not replace actual listing ID.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Render Business Hours, Claim, FAQ and a custom field for two listings via Theme Builder. Compare editor preview vs frontend and unavailable dependency notice without deactivating client plugins.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Http/Controllers/ListingsController.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/Http/Controllers/ListingsController.php#L1) — 87 declarations
- [app/Providers/DiviEnqueueServiceProvider.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/Providers/DiviEnqueueServiceProvider.php#L1) — 32 declarations
- [app/Providers/DiviModulesProvider.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/Providers/DiviModulesProvider.php#L1) — 4 declarations
- [directorist-divi-integration.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/directorist-divi-integration.php#L1) — 6 declarations
- [app/DiviModules/AllCategories/AllCategories.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/DiviModules/AllCategories/AllCategories.php#L1) — 73 declarations
- [app/DiviModules/AllLocations/AllLocations.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/DiviModules/AllLocations/AllLocations.php#L1) — 44 declarations
- [app/DiviModules/ArchiveFilter/ArchiveFilter.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/DiviModules/ArchiveFilter/ArchiveFilter.php#L1) — 4 declarations
- [app/DiviModules/ArchiveHeader/ArchiveHeader.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/DiviModules/ArchiveHeader/ArchiveHeader.php#L1) — 9 declarations

[Complete topic source/data ledger](single-modules-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-business-hours](../../directorist-business-hours/README.md) — only if active configuration or source connects it.
- [directorist-claim-listing](../../directorist-claim-listing/README.md) — only if active configuration or source connects it.
- [directorist-faqs](../../directorist-faqs/README.md) — only if active configuration or source connects it.
- [directorist-gallery](../../directorist-gallery/README.md) — only if active configuration or source connects it.
- [directorist-booking](../../directorist-booking/README.md) — only if active configuration or source connects it.
- [directorist-universal-search](../../directorist-universal-search/README.md) — only if active configuration or source connects it.
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md) — only if active configuration or source connects it.
