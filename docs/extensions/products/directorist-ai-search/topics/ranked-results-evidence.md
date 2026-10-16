# Directorist AI Search: ranked-results

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Semantic candidates are constrained by directory/filter context. Native fallback, score thresholds and Universal Search interception require independent checks.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Use two directories and restrictive category/price filters. Compare native, semantic success, empty response and API timeout; ensure excluded listings never leak through ranking.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Entry points and feature coverage

Read the canonical snapshot rows first. Local/default/development rows are available for variant comparison only. Every matching source file is listed with declarations; supporting styles/configuration may have no symbols.

| Snapshot / source | Functions or classes |
| --- | --- |
| ai-search--main / [assets/css/frontend-search.css:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/assets/css/frontend-search.css#L1) | template / configuration / styling; inspect file |
| ai-search--main / [assets/js/frontend-search.js:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/assets/js/frontend-search.js#L1) | `config` (L4), `minChars` (L5), `debounceMs` (L6), `changeDelayMs` (L7), `resultLimit` (L8), `inputSelector` (L9), `state` (L10), `debounce` (L16), `debounced` (L19), `args` (L20), `getState` (L28), `getForm` (L40), `serializeForm` (L44), `data` (L45), `requestParams` (L51), `params` (L52), `requestKey` (L68), `ensureDropdown` (L72), `field` (L73), `submit` (L99), `form` (L102), `show` (L112), `hide` (L117), `setLoading` (L122), `section` (L123), `list` (L124), `empty` (L125), `skeletons` (L126), `escapeHtml` (L145), `render` (L157), `listings` (L158), `count` (L159), `section` (L160), `list` (L161), `empty` (L162), `countEl` (L163), `title` (L184), `type` (L185), `thumbnail` (L186), `url` (L187), `search` (L214), `query` (L215), `form` (L216), `dropdown` (L217), `itemState` (L218), `params` (L225), `key` (L226), `bindInput` (L268), `debouncedSearch` (L275), `changeSearch` (L278), `form` (L281), `dropdown` (L304), `bindAll` (L312), `shouldBind` (L324) |
| ai-search--main / [inc/Services/DirectoristQueryInterceptor.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/DirectoristQueryInterceptor.php#L1) | `DirectoristQueryInterceptor` (L14), `__construct` (L36), `filter_query_args` (L50), `filter_archive_fields_for_semantic_results` (L118), `candidate_limit_for_query` (L162), `allowed_ids_for_query` (L176), `has_restrictive_filters` (L212), `query_has_clauses` (L252), `meta_query_has_restrictive_clauses` (L276), `is_sole_directory_meta_query` (L304), `fallback_query_args` (L342), `remove_implicit_default_directory_filter` (L366), `has_explicit_directory_filter` (L402), `is_default_directory_meta_query` (L421) |
| ai-search--main / [inc/Services/FrontendAssetsService.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendAssetsService.php#L1) | `FrontendAssetsService` (L14), `__construct` (L41), `enqueue` (L53), `add_no_optimize_attribute` (L104), `add_no_optimize_inline_attribute` (L119), `exclude_litespeed_js_optimization` (L136) |
| ai-search--main / [inc/Services/FrontendRestController.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendRestController.php#L1) | `FrontendRestController` (L15), `__construct` (L23), `register_routes` (L32), `search` (L50), `request_payload` (L100), `validate_identity` (L116), `response_payload` (L161), `cacheable_response` (L184), `display_limit` (L201) |
| ai-search--main / [inc/Services/FrontendResultsService.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendResultsService.php#L1) | `FrontendResultsService` (L16), `search_listings` (L27), `empty_listings` (L74), `format_listing` (L87), `listing_title` (L107), `display_text` (L117), `get_directory` (L127), `get_media` (L150), `Helper` (L157), `directory_taxonomy` (L176) |
| ai-search--main / [inc/Services/SearchContextService.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/SearchContextService.php#L1) | `SearchContextService` (L14), `normalize_frontend_payload` (L26), `get_query` (L56), `build_context_query` (L66), `build_api_payload` (L77), `build_filtered_query_args` (L105), `candidate_limit` (L182), `id_list` (L194), `sanitize_deep` (L208), `append_context_line` (L224), `directory_label` (L232), `directory_term` (L238), `term_labels` (L251), `price_label` (L282), `price_range_label` (L302), `rating_label` (L314), `custom_field_labels` (L326), `custom_field_label` (L347), `add_price_meta_query` (L363), `add_scalar_meta_query` (L395), `add_phone_meta_query` (L409), `add_address_query` (L431), `add_rating_meta_query` (L464), `add_custom_field_meta_queries` (L481), `listing_post_type` (L549), `directory_taxonomy` (L553), `category_taxonomy` (L557), `location_taxonomy` (L561), `tag_taxonomy` (L565) |
| ai-search--main / [inc/Services/SemanticCandidateService.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/SemanticCandidateService.php#L1) | `SemanticCandidateService` (L14), `search` (L24), `failure` (L86) |
| ai-search--main / [inc/Services/UniversalSearchInterceptor.php:1](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/UniversalSearchInterceptor.php#L1) | `UniversalSearchInterceptor` (L15), `__construct` (L23), `filter_response` (L35) |

## Data readers, writers and lifecycle

Keys/callback expressions below are extracted without stored values. `get` reads; `update/add/delete` writes; scheduled/remote calls are side effects. ORM repositories and schema definitions are linked as source even where literal-key extraction cannot resolve them.

| Snapshot / source | Operation and key |
| --- | --- |
| ai-search--main / [inc/Services/DirectoristQueryInterceptor.php:37](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/DirectoristQueryInterceptor.php#L37) | `add_filter('atbdp_listing_search_query_argument', [ $this, 'filter_query_args' ])` |
| ai-search--main / [inc/Services/DirectoristQueryInterceptor.php:38](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/DirectoristQueryInterceptor.php#L38) | `add_filter('directorist_listing_archive_fields', [ $this, 'filter_archive_fields_for_semantic_results' ])` |
| ai-search--main / [inc/Services/DirectoristQueryInterceptor.php:133](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/DirectoristQueryInterceptor.php#L133) | `get_post_meta($post_id, '_directory_type')` |
| ai-search--main / [inc/Services/FrontendAssetsService.php:42](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendAssetsService.php#L42) | `add_action('wp_enqueue_scripts', [ $this, 'enqueue' ])` |
| ai-search--main / [inc/Services/FrontendAssetsService.php:43](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendAssetsService.php#L43) | `add_filter('script_loader_tag', [ $this, 'add_no_optimize_attribute' ])` |
| ai-search--main / [inc/Services/FrontendAssetsService.php:44](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendAssetsService.php#L44) | `add_filter('wp_inline_script_attributes', [ $this, 'add_no_optimize_inline_attribute' ])` |
| ai-search--main / [inc/Services/FrontendAssetsService.php:45](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendAssetsService.php#L45) | `add_filter('litespeed_optimize_js_excludes', [ $this, 'exclude_litespeed_js_optimization' ])` |
| ai-search--main / [inc/Services/FrontendRestController.php:24](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendRestController.php#L24) | `add_action('rest_api_init', [ $this, 'register_routes' ])` |
| ai-search--main / [inc/Services/FrontendRestController.php:33](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendRestController.php#L33) | `register_rest_route('directorist-ai-search/v1', '/frontend-search')` |
| ai-search--main / [inc/Services/FrontendResultsService.php:128](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendResultsService.php#L128) | `get_post_meta($post_id, '_directory_type')` |
| ai-search--main / [inc/Services/SearchContextService.php:183](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/SearchContextService.php#L183) | `apply_filters('directorist_ai_search_candidate_limit')` |
| ai-search--main / [inc/Services/UniversalSearchInterceptor.php:24](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/UniversalSearchInterceptor.php#L24) | `add_filter('directorist-universal-search_rest_response_filter', [ $this, 'filter_response' ])` |

## Core and integration context

Load [Core listing/form/query contracts](../../../core/listings.md) for a form/data/query issue, [rendering and builders](../../../core/rendering.md) for display, or [payment lifecycle](../../../core/payments.md) for orders. Do not load all three automatically.

Conditional integration candidates (load only when installed configuration or the cited hook/guard connects them):
- [directorist-universal-search](../../directorist-universal-search/README.md)

For exact cross-repository hook matches, use `python3 docs/extensions/scripts/query.py hooks HOOK_NAME`; a same-name hook is only a candidate edge. For dynamic calls, inspect the referenced source and actual active callback list.
