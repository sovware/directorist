# Directorist Divi Integration: search-archive

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Search composition, directory state and AJAX archive requests must retain the same filter contract across editor and frontend.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Use two directories with different fields, compose search/results, filter/sort/paginate and direct-open URL. Compare editor attributes, request parameters and rendered IDs.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Http/Controllers/ArchiveAjaxController.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/Http/Controllers/ArchiveAjaxController.php#L1) — 12 declarations
- [app/Http/Controllers/HomepageSearchController.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/Http/Controllers/HomepageSearchController.php#L1) — 10 declarations
- [app/Http/Controllers/ListingsController.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/Http/Controllers/ListingsController.php#L1) — 87 declarations
- [app/Http/Controllers/PaginationController.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/Http/Controllers/PaginationController.php#L1) — 2 declarations
- [app/Http/Controllers/SearchFormFieldsController.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/Http/Controllers/SearchFormFieldsController.php#L1) — 7 declarations
- [app/Providers/ArchiveAjaxProvider.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/Providers/ArchiveAjaxProvider.php#L1) — 3 declarations
- [app/Providers/ThemeBuilderServiceProvider.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/Providers/ThemeBuilderServiceProvider.php#L1) — 50 declarations
- [app/DiviModules/AllCategories/AllCategories.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/DiviModules/AllCategories/AllCategories.php#L1) — 73 declarations

[Complete topic source/data ledger](search-archive-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-business-hours](../../directorist-business-hours/README.md) — only if active configuration or source connects it.
- [directorist-claim-listing](../../directorist-claim-listing/README.md) — only if active configuration or source connects it.
- [directorist-faqs](../../directorist-faqs/README.md) — only if active configuration or source connects it.
- [directorist-gallery](../../directorist-gallery/README.md) — only if active configuration or source connects it.
- [directorist-booking](../../directorist-booking/README.md) — only if active configuration or source connects it.
- [directorist-universal-search](../../directorist-universal-search/README.md) — only if active configuration or source connects it.
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md) — only if active configuration or source connects it.
