# Directorist Universal Search: cross-directory

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

The extension owns cross-directory query/response and dedicated pages; Core still owns underlying listing/taxonomy data. AI interception may alter response ordering.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Create identically named listings in two directories with distinct taxonomy filters. Submit the universal form and verify result links, pagination, empty query and active directory state.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Http/Controllers/UniversalSearchController.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Http/Controllers/UniversalSearchController.php#L1) — 3 declarations
- [app/Providers/Admin/DirectoristSettingsProvider.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Providers/Admin/DirectoristSettingsProvider.php#L1) — 4 declarations
- [app/Providers/Admin/PageServiceProvider.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Providers/Admin/PageServiceProvider.php#L1) — 3 declarations
- [app/Providers/DirectoristServiceProvider.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Providers/DirectoristServiceProvider.php#L1) — 3 declarations
- [app/Providers/ShortcodeServiceProvider.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Providers/ShortcodeServiceProvider.php#L1) — 5 declarations
- [app/Helpers/helper.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Helpers/helper.php#L1) — 14 declarations
- [app/Repositories/AutoSuggestionRepository.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Repositories/AutoSuggestionRepository.php#L1) — 3 declarations
- [app/Repositories/ListingsRepository.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Repositories/ListingsRepository.php#L1) — 11 declarations

[Complete topic source/data ledger](cross-directory-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-ai-search](../../directorist-ai-search/README.md) — only if active configuration or source connects it.
- [directorist-divi-integration](../../directorist-divi-integration/README.md) — only if active configuration or source connects it.
