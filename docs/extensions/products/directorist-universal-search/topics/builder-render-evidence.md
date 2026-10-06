# Directorist Universal Search: builder-render

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Shortcode and builder renderers must target the extension pages and load interactive assets. Editor placeholders are not frontend search results.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Compare shortcode, Divi form/result modules and frontend after save. Submit with keyboard, clear filters and revisit result URL directly.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Entry points and feature coverage

Read the canonical snapshot rows first. Local/default/development rows are available for variant comparison only. Every matching source file is listed with declarations; supporting styles/configuration may have no symbols.

| Snapshot / source | Functions or classes |
| --- | --- |
| universal-search--master / [app/Providers/ShortcodeServiceProvider.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Providers/ShortcodeServiceProvider.php#L1) | `ShortcodeServiceProvider` (L12), `boot` (L17), `render_search_form` (L22), `render_search_result` (L44), `View` (L62) |
| universal-search--master / [config/app.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/config/app.php#L1) | `ShortcodeServiceProvider` (L52), `DirectoristSettingsProvider` (L53), `PageServiceProvider` (L60), `EnsureIsUserAdmin` (L61) |
| universal-search--master / [resources/js/app.js:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/js/app.js#L1) | `searchData` (L7), `createSkeletonItem` (L9), `skeletonItem` (L10), `showSkeleton` (L25), `container` (L26), `skeletonItem` (L33), `realtimeSearch` (L39), `searchInput` (L40), `resultElement` (L43), `searchText` (L49), `updateResultCount` (L58), `listingsCount` (L59), `categoriesCount` (L61), `listingsElement` (L64), `categoriesElement` (L67), `updateListings` (L81), `apiUrl` (L93), `response` (L95), `listings` (L104), `categories` (L105), `hasNoResults` (L108), `viewBtnResults` (L109), `wrapperContainer` (L110), `noResults` (L113), `crossBorder` (L116), `updatePopularListing` (L137), `containerParent` (L138), `container` (L141), `itemElement` (L156), `updateBudapestCategories` (L193), `containerParent` (L194), `container` (L197), `itemElement` (L211), `to` (L257), `debounce` (L258), `debouncedSearch` (L268), `searchInput` (L280), `dropdownMenu` (L283), `searchQuery` (L289), `replaceContainerFluid` (L316), `containerFluids` (L317), `change` (L324), `find` (L324), `add` (L324), `addActiveClass` (L325), `directoryTypes` (L327), `from` (L330), `to` (L335), `formSubmissionBehavior` (L343), `form` (L344), `submitLink` (L345) |
| universal-search--master / [resources/sass/1.global/_font.scss:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/sass/1.global/_font.scss#L1) | template / configuration / styling; inspect file |
| universal-search--master / [resources/sass/1.global/_general.scss:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/sass/1.global/_general.scss#L1) | template / configuration / styling; inspect file |
| universal-search--master / [resources/sass/1.global/global.scss:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/sass/1.global/global.scss#L1) | template / configuration / styling; inspect file |
| universal-search--master / [resources/sass/2.mixins/_helper.scss:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/sass/2.mixins/_helper.scss#L1) | template / configuration / styling; inspect file |
| universal-search--master / [resources/sass/2.mixins/_kew-frames.scss:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/sass/2.mixins/_kew-frames.scss#L1) | template / configuration / styling; inspect file |
| universal-search--master / [resources/sass/2.mixins/_media-queries.scss:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/sass/2.mixins/_media-queries.scss#L1) | template / configuration / styling; inspect file |
| universal-search--master / [resources/sass/2.mixins/mixins.scss:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/sass/2.mixins/mixins.scss#L1) | template / configuration / styling; inspect file |
| universal-search--master / [resources/sass/3.plugins/_search.scss:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/sass/3.plugins/_search.scss#L1) | template / configuration / styling; inspect file |
| universal-search--master / [resources/sass/3.plugins/plugins.scss:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/sass/3.plugins/plugins.scss#L1) | template / configuration / styling; inspect file |
| universal-search--master / [resources/sass/4.block/block.scss:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/sass/4.block/block.scss#L1) | template / configuration / styling; inspect file |
| universal-search--master / [resources/sass/app.scss:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/sass/app.scss#L1) | template / configuration / styling; inspect file |
| universal-search--master / [resources/views/autosuggestions.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/autosuggestions.php#L1) | template / configuration / styling; inspect file |
| universal-search--master / [resources/views/index.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/index.php#L1) | template / configuration / styling; inspect file |
| universal-search--master / [resources/views/search-form-wrapper.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/search-form-wrapper.php#L1) | template / configuration / styling; inspect file |
| universal-search--master / [resources/views/search-form.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/search-form.php#L1) | template / configuration / styling; inspect file |
| universal-search--master / [resources/views/search-results-categories.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/search-results-categories.php#L1) | template / configuration / styling; inspect file |
| universal-search--master / [resources/views/search-results-empty.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/search-results-empty.php#L1) | template / configuration / styling; inspect file |
| universal-search--master / [resources/views/search-results-header.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/search-results-header.php#L1) | template / configuration / styling; inspect file |
| universal-search--master / [resources/views/search-results-listings.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/search-results-listings.php#L1) | template / configuration / styling; inspect file |
| universal-search--master / [resources/views/search-results-status.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/search-results-status.php#L1) | template / configuration / styling; inspect file |
| universal-search--master / [resources/views/search-results-wrapper.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/search-results-wrapper.php#L1) | template / configuration / styling; inspect file |
| universal-search--master / [resources/views/search-results.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/search-results.php#L1) | template / configuration / styling; inspect file |
| universal-search--development / [resources/sass/3.plugins/_search.scss:1](https://github.com/sovware/directorist-universal-search/blob/3abafc6bd041a96e14cadeb17d0596d90497545c/resources/sass/3.plugins/_search.scss#L1) | template / configuration / styling; inspect file |

## Data readers, writers and lifecycle

Keys/callback expressions below are extracted without stored values. `get` reads; `update/add/delete` writes; scheduled/remote calls are side effects. ORM repositories and schema definitions are linked as source even where literal-key extraction cannot resolve them.

| Snapshot / source | Operation and key |
| --- | --- |
| universal-search--master / [resources/views/search-results-header.php:15](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/search-results-header.php#L15) | `get_directorist_option('search_result_page')` |

## Core and integration context

Load [Core listing/form/query contracts](../../../core/listings.md) for a form/data/query issue, [rendering and builders](../../../core/rendering.md) for display, or [payment lifecycle](../../../core/payments.md) for orders. Do not load all three automatically.

Conditional integration candidates (load only when installed configuration or the cited hook/guard connects them):
- [directorist-ai-search](../../directorist-ai-search/README.md)
- [directorist-divi-integration](../../directorist-divi-integration/README.md)

For exact cross-repository hook matches, use `python3 docs/extensions/scripts/query.py hooks HOOK_NAME`; a same-name hook is only a candidate edge. For dynamic calls, inspect the referenced source and actual active callback list.
