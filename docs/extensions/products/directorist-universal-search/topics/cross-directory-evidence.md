# Directorist Universal Search: cross-directory

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

The extension owns cross-directory query/response and dedicated pages; Core still owns underlying listing/taxonomy data. AI interception may alter response ordering.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Create identically named listings in two directories with distinct taxonomy filters. Submit the universal form and verify result links, pagination, empty query and active directory state.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Entry points and feature coverage

Read the canonical snapshot rows first. Local/default/development rows are available for variant comparison only. Every matching source file is listed with declarations; supporting styles/configuration may have no symbols.

| Snapshot / source | Functions or classes |
| --- | --- |
| universal-search--master / [app/Helpers/helper.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Helpers/helper.php#L1) | `directorist_us` (L11), `directorist_us_config` (L15), `directorist_us_app_config` (L19), `directorist_us_version` (L23), `directorist_us_container` (L27), `directorist_us_singleton` (L31), `directorist_us_url` (L35), `directorist_us_dir` (L39), `directorist_us_frontend_localizations` (L49), `directorist_us_get_result_page_link` (L63), `directorist_us_get_all_categories_page` (L87), `directorist_us_get_all_directory_types` (L112), `directorist_us_get_search_result_page` (L139), `directorist_us_form` (L158) |
| universal-search--master / [app/Http/Controllers/UniversalSearchController.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Http/Controllers/UniversalSearchController.php#L1) | `UniversalSearchController` (L12), `__construct` (L15), `index` (L28) |
| universal-search--master / [app/Providers/Admin/DirectoristSettingsProvider.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Providers/Admin/DirectoristSettingsProvider.php#L1) | `DirectoristSettingsProvider` (L9), `boot` (L10), `atbdp_pages_settings_fields` (L15), `atbdp_listing_type_settings_field_list` (L20) |
| universal-search--master / [app/Providers/Admin/PageServiceProvider.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Providers/Admin/PageServiceProvider.php#L1) | `PageServiceProvider` (L9), `boot` (L10), `display_universal_search_post_state` (L21) |
| universal-search--master / [app/Providers/DirectoristServiceProvider.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Providers/DirectoristServiceProvider.php#L1) | `DirectoristServiceProvider` (L10), `boot` (L11), `directorist_get_directory_type_nav_url` (L15) |
| universal-search--master / [app/Providers/ShortcodeServiceProvider.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Providers/ShortcodeServiceProvider.php#L1) | `ShortcodeServiceProvider` (L12), `boot` (L17), `render_search_form` (L22), `render_search_result` (L44), `View` (L62) |
| universal-search--master / [app/Repositories/AutoSuggestionRepository.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Repositories/AutoSuggestionRepository.php#L1) | `AutoSuggestionRepository` (L10), `__construct` (L15), `get_search_suggestions` (L26) |
| universal-search--master / [app/Repositories/ListingsRepository.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Repositories/ListingsRepository.php#L1) | `ListingsRepository` (L13), `get_query_args` (L16), `get_directory_data` (L54), `get_listing_media` (L60), `get_listing_thumbnail_source` (L72), `get_existing_attachment_image_source` (L82), `uploaded_image_exists` (L96), `format_listing_data` (L117), `get_listings_by_search_term` (L127), `get_listings_with_type_by_search_term` (L151), `get_listings_data` (L218) |
| universal-search--master / [app/Repositories/ResultsRepository.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Repositories/ResultsRepository.php#L1) | `ResultsRepository` (L7), `__construct` (L15), `get_total_data` (L20), `get_results_found` (L35) |
| universal-search--master / [app/Repositories/TaxonomiesRepository.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Repositories/TaxonomiesRepository.php#L1) | `TaxonomiesRepository` (L12), `__construct` (L17), `get_query_args` (L21), `filter_terms_by_directory` (L49), `get_directory_ids` (L62), `has_matching_directory` (L67), `filter_directory_ids_with_posts` (L77), `get_directory_info` (L98), `format_directory_list` (L113), `format_category_data` (L127), `format_category_data_results` (L154), `get_categories_by_search_term` (L178), `get_categories_by_directory` (L207), `get_empty_response` (L240), `group_categories_by_directory` (L251) |
| universal-search--master / [app/Setup/Activation.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Setup/Activation.php#L1) | `Activation` (L7), `create_pages` (L8), `setup_pages` (L39) |
| universal-search--master / [app/Setup/Deactivation.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Setup/Deactivation.php#L1) | `Deactivation` (L9), `__construct` (L10) |
| universal-search--master / [config/app.php:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/config/app.php#L1) | `ShortcodeServiceProvider` (L52), `DirectoristSettingsProvider` (L53), `PageServiceProvider` (L60), `EnsureIsUserAdmin` (L61) |
| universal-search--master / [resources/sass/2.mixins/_helper.scss:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/sass/2.mixins/_helper.scss#L1) | template / configuration / styling; inspect file |
| universal-search--development / [app/Repositories/ListingsRepository.php:1](https://github.com/sovware/directorist-universal-search/blob/3abafc6bd041a96e14cadeb17d0596d90497545c/app/Repositories/ListingsRepository.php#L1) | `ListingsRepository` (L13), `get_query_args` (L16), `get_directory_data` (L54), `get_listing_media` (L60), `format_listing_data` (L71), `get_listings_by_search_term` (L81), `get_listings_with_type_by_search_term` (L105), `get_listings_data` (L172) |
| universal-search--development / [app/Repositories/TaxonomiesRepository.php:1](https://github.com/sovware/directorist-universal-search/blob/3abafc6bd041a96e14cadeb17d0596d90497545c/app/Repositories/TaxonomiesRepository.php#L1) | `TaxonomiesRepository` (L12), `__construct` (L17), `get_query_args` (L21), `filter_terms_by_directory` (L49), `get_directory_ids` (L62), `has_matching_directory` (L67), `filter_directory_ids_with_posts` (L77), `get_directory_info` (L98), `format_directory_list` (L113), `format_category_data` (L127), `format_category_data_results` (L154), `get_categories_by_search_term` (L178), `get_categories_by_directory` (L207), `get_empty_response` (L240), `group_categories_by_directory` (L251) |

## Data readers, writers and lifecycle

Keys/callback expressions below are extracted without stored values. `get` reads; `update/add/delete` writes; scheduled/remote calls are side effects. ORM repositories and schema definitions are linked as source even where literal-key extraction cannot resolve them.

| Snapshot / source | Operation and key |
| --- | --- |
| universal-search--master / [app/Helpers/helper.php:65](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Helpers/helper.php#L65) | `get_directorist_option('universal_search_result_page')` |
| universal-search--master / [app/Helpers/helper.php:89](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Helpers/helper.php#L89) | `get_directorist_option('all_categories_page')` |
| universal-search--master / [app/Providers/Admin/DirectoristSettingsProvider.php:11](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Providers/Admin/DirectoristSettingsProvider.php#L11) | `add_filter('atbdp_pages_settings_fields', [$this, 'atbdp_pages_settings_fields'])` |
| universal-search--master / [app/Providers/Admin/DirectoristSettingsProvider.php:12](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Providers/Admin/DirectoristSettingsProvider.php#L12) | `add_filter('atbdp_listing_type_settings_field_list', [$this, 'atbdp_listing_type_settings_field_list'])` |
| universal-search--master / [app/Providers/Admin/PageServiceProvider.php:11](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Providers/Admin/PageServiceProvider.php#L11) | `add_filter('display_post_states', [$this, 'display_universal_search_post_state'])` |
| universal-search--master / [app/Providers/Admin/PageServiceProvider.php:22](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Providers/Admin/PageServiceProvider.php#L22) | `get_directorist_option('universal_search_result_page')` |
| universal-search--master / [app/Providers/DirectoristServiceProvider.php:12](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Providers/DirectoristServiceProvider.php#L12) | `add_filter('directorist_get_directory_type_nav_url', [$this, 'directorist_get_directory_type_nav_url'])` |
| universal-search--master / [app/Providers/DirectoristServiceProvider.php:16](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Providers/DirectoristServiceProvider.php#L16) | `get_directorist_option('universal_search_result_page')` |
| universal-search--master / [app/Repositories/ListingsRepository.php:55](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Repositories/ListingsRepository.php#L55) | `get_post_meta($post_id, '_directory_type')` |
| universal-search--master / [app/Repositories/ListingsRepository.php:64](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Repositories/ListingsRepository.php#L64) | `get_directorist_option('preview_image_quality')` |
| universal-search--master / [app/Repositories/TaxonomiesRepository.php:63](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Repositories/TaxonomiesRepository.php#L63) | `get_term_meta($term->term_id, '_directory_type')` |
| universal-search--master / [app/Repositories/TaxonomiesRepository.php:129](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Repositories/TaxonomiesRepository.php#L129) | `get_term_meta($term->term_id, '_category_icon')` |
| universal-search--master / [app/Repositories/TaxonomiesRepository.php:156](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Repositories/TaxonomiesRepository.php#L156) | `get_term_meta($term->term_id, '_category_icon')` |
| universal-search--master / [app/Setup/Activation.php:41](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Setup/Activation.php#L41) | `get_directorist_option('universal_search_result_page')` |
| universal-search--master / [app/Setup/Activation.php:44](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Setup/Activation.php#L44) | `update_directorist_option('universal_search_result_page')` |
| universal-search--master / [app/Setup/Deactivation.php:12](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Setup/Deactivation.php#L12) | `update_option('helpgent_dismiss_nginx_setup_notice')` |
| universal-search--master / [app/Setup/Deactivation.php:13](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Setup/Deactivation.php#L13) | `do_action('helpgent_after_deactivation')` |
| universal-search--development / [app/Repositories/ListingsRepository.php:55](https://github.com/sovware/directorist-universal-search/blob/3abafc6bd041a96e14cadeb17d0596d90497545c/app/Repositories/ListingsRepository.php#L55) | `get_post_meta($post_id, '_directory_type')` |
| universal-search--development / [app/Repositories/TaxonomiesRepository.php:63](https://github.com/sovware/directorist-universal-search/blob/3abafc6bd041a96e14cadeb17d0596d90497545c/app/Repositories/TaxonomiesRepository.php#L63) | `get_term_meta($term->term_id, '_directory_type')` |
| universal-search--development / [app/Repositories/TaxonomiesRepository.php:129](https://github.com/sovware/directorist-universal-search/blob/3abafc6bd041a96e14cadeb17d0596d90497545c/app/Repositories/TaxonomiesRepository.php#L129) | `get_term_meta($term->term_id, 'category_icon')` |
| universal-search--development / [app/Repositories/TaxonomiesRepository.php:156](https://github.com/sovware/directorist-universal-search/blob/3abafc6bd041a96e14cadeb17d0596d90497545c/app/Repositories/TaxonomiesRepository.php#L156) | `get_term_meta($term->term_id, '_category_icon')` |

## Core and integration context

Load [Core listing/form/query contracts](../../../core/listings.md) for a form/data/query issue, [rendering and builders](../../../core/rendering.md) for display, or [payment lifecycle](../../../core/payments.md) for orders. Do not load all three automatically.

Conditional integration candidates (load only when installed configuration or the cited hook/guard connects them):
- [directorist-ai-search](../../directorist-ai-search/README.md)
- [directorist-divi-integration](../../directorist-divi-integration/README.md)

For exact cross-repository hook matches, use `python3 docs/extensions/scripts/query.py hooks HOOK_NAME`; a same-name hook is only a candidate edge. For dynamic calls, inspect the referenced source and actual active callback list.
