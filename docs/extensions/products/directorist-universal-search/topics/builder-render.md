# Directorist Universal Search: builder-render

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Shortcode and builder renderers must target the extension pages and load interactive assets. Editor placeholders are not frontend search results.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Compare shortcode, Divi form/result modules and frontend after save. Submit with keyboard, clear filters and revisit result URL directly.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Providers/ShortcodeServiceProvider.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Providers/ShortcodeServiceProvider.php#L1) — 5 declarations
- [config/app.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/config/app.php#L1) — 4 declarations
- [resources/views/autosuggestions.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/autosuggestions.php#L1) — 0 declarations
- [resources/views/index.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/index.php#L1) — 0 declarations
- [resources/views/search-form-wrapper.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/search-form-wrapper.php#L1) — 0 declarations
- [resources/views/search-form.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/search-form.php#L1) — 0 declarations
- [resources/views/search-results-categories.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/search-results-categories.php#L1) — 0 declarations
- [resources/views/search-results-empty.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/search-results-empty.php#L1) — 0 declarations

[Complete topic source/data ledger](builder-render-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-ai-search](../../directorist-ai-search/README.md) — only if active configuration or source connects it.
- [directorist-divi-integration](../../directorist-divi-integration/README.md) — only if active configuration or source connects it.
