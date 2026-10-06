# Directorist Divi Integration: templates-import

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Theme Builder conditions, template library imports and author context have distinct persistence/remapping paths. Import permission is not implied by reading a template.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

In disposable clone import a small approved package; verify ID/media remaps, directory conditions and author route. Check previous content retained and native editing after save.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Providers/CustomSingleListingPageServiceProvider.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/Providers/CustomSingleListingPageServiceProvider.php#L1) — 14 declarations
- [app/Providers/TemplateLibraryServiceProvider.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/Providers/TemplateLibraryServiceProvider.php#L1) — 25 declarations
- [app/Providers/ThemeBuilderServiceProvider.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/Providers/ThemeBuilderServiceProvider.php#L1) — 50 declarations
- [app/DiviModules/AuthorProfileAddress/AuthorProfileAddress.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/DiviModules/AuthorProfileAddress/AuthorProfileAddress.php#L1) — 3 declarations
- [app/DiviModules/AuthorProfileAvatar/AuthorProfileAvatar.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/DiviModules/AuthorProfileAvatar/AuthorProfileAvatar.php#L1) — 3 declarations
- [app/DiviModules/AuthorProfileBio/AuthorProfileBio.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/DiviModules/AuthorProfileBio/AuthorProfileBio.php#L1) — 2 declarations
- [app/DiviModules/AuthorProfileButton/AuthorProfileButton.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/DiviModules/AuthorProfileButton/AuthorProfileButton.php#L1) — 2 declarations
- [app/DiviModules/AuthorProfileEmail/AuthorProfileEmail.php:1](https://github.com/sovware/directorist-divi-integration/blob/e3c8cafacaeae1422a5fe56fba16a0eced9eee18/app/DiviModules/AuthorProfileEmail/AuthorProfileEmail.php#L1) — 3 declarations

[Complete topic source/data ledger](templates-import-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-business-hours](../../directorist-business-hours/README.md) — only if active configuration or source connects it.
- [directorist-claim-listing](../../directorist-claim-listing/README.md) — only if active configuration or source connects it.
- [directorist-faqs](../../directorist-faqs/README.md) — only if active configuration or source connects it.
- [directorist-gallery](../../directorist-gallery/README.md) — only if active configuration or source connects it.
- [directorist-booking](../../directorist-booking/README.md) — only if active configuration or source connects it.
- [directorist-universal-search](../../directorist-universal-search/README.md) — only if active configuration or source connects it.
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md) — only if active configuration or source connects it.
