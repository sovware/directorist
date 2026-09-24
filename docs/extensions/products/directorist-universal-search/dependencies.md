# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |
| [composer.json:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/composer.json#L1) | php | >=7.4 |
| [composer.json:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/composer.json#L1) | wpmvc/framework | 1.1.2 |
| [package.json:1](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/package.json#L1) | wp-scripts | ^0.0.1-security |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [directorist-universal-search.php:18](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/directorist-universal-search.php#L18) | defined | `'ABSPATH'` |
| [app/Helpers/helper.php:3](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Helpers/helper.php#L3) | defined | `'ABSPATH'` |
| [app/Helpers/helper.php:43](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Helpers/helper.php#L43) | function_exists | `'directorist_us_frontend_localizations'` |
| [app/Helpers/helper.php:57](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Helpers/helper.php#L57) | function_exists | `'directorist_us_get_result_page_link'` |
| [app/Helpers/helper.php:71](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Helpers/helper.php#L71) | function_exists | `'atbdp_required_polylang_url'` |
| [app/Helpers/helper.php:71](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Helpers/helper.php#L71) | function_exists | `'pll_get_post'` |
| [app/Helpers/helper.php:80](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Helpers/helper.php#L80) | function_exists | `'directorist_us_get_all_categories_page'` |
| [app/Helpers/helper.php:95](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Helpers/helper.php#L95) | function_exists | `'atbdp_required_polylang_url'` |
| [app/Helpers/helper.php:95](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Helpers/helper.php#L95) | function_exists | `'pll_get_post'` |
| [app/Helpers/helper.php:106](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Helpers/helper.php#L106) | function_exists | `'directorist_us_get_all_directory_types'` |
| [app/Helpers/helper.php:115](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Helpers/helper.php#L115) | function_exists | `'directorist_is_multi_directory_enabled'` |
| [app/Helpers/helper.php:130](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Helpers/helper.php#L130) | function_exists | `'directorist_us_get_search_result_page'` |
| [app/Helpers/helper.php:149](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Helpers/helper.php#L149) | function_exists | `'directorist_us_form'` |
| [app/Http/Controllers/UniversalSearchController.php:5](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Http/Controllers/UniversalSearchController.php#L5) | defined | `"ABSPATH"` |
| [app/Http/Middleware/EnsureIsUserAdmin.php:5](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Http/Middleware/EnsureIsUserAdmin.php#L5) | defined | `"ABSPATH"` |
| [app/Providers/DirectoristServiceProvider.php:5](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Providers/DirectoristServiceProvider.php#L5) | defined | `"ABSPATH"` |
| [app/Providers/ShortcodeServiceProvider.php:6](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Providers/ShortcodeServiceProvider.php#L6) | defined | `"ABSPATH"` |
| [app/Providers/Admin/DirectoristSettingsProvider.php:5](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Providers/Admin/DirectoristSettingsProvider.php#L5) | defined | `"ABSPATH"` |
| [app/Providers/Admin/PageServiceProvider.php:5](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Providers/Admin/PageServiceProvider.php#L5) | defined | `"ABSPATH"` |
| [app/Repositories/AutoSuggestionRepository.php:5](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Repositories/AutoSuggestionRepository.php#L5) | defined | `"ABSPATH"` |
| [app/Repositories/ListingsRepository.php:5](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Repositories/ListingsRepository.php#L5) | defined | `"ABSPATH"` |
| [app/Repositories/ResultsRepository.php:5](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Repositories/ResultsRepository.php#L5) | defined | `"ABSPATH"` |
| [app/Repositories/TaxonomiesRepository.php:5](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Repositories/TaxonomiesRepository.php#L5) | defined | `"ABSPATH"` |
| [app/Setup/Activation.php:5](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Setup/Activation.php#L5) | defined | `'ABSPATH'` |
| [app/Setup/Deactivation.php:5](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/app/Setup/Deactivation.php#L5) | defined | `'ABSPATH'` |
| [config/app.php:3](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/config/app.php#L3) | defined | `'ABSPATH'` |
| [enqueues/admin-enqueue.php:5](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/enqueues/admin-enqueue.php#L5) | defined | `'ABSPATH'` |
| [enqueues/frontend-enqueue.php:5](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/enqueues/frontend-enqueue.php#L5) | defined | `'ABSPATH'` |
| [resources/views/autosuggestions.php:13](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/autosuggestions.php#L13) | defined | `'ABSPATH'` |
| [resources/views/index.php:3](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/index.php#L3) | defined | `'ABSPATH'` |
| [resources/views/search-form-wrapper.php:11](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/search-form-wrapper.php#L11) | defined | `'ABSPATH'` |
| [resources/views/search-form.php:2](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/search-form.php#L2) | defined | `'ABSPATH'` |
| [resources/views/search-results-categories.php:13](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/search-results-categories.php#L13) | defined | `'ABSPATH'` |
| [resources/views/search-results-empty.php:10](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/search-results-empty.php#L10) | defined | `'ABSPATH'` |
| [resources/views/search-results-header.php:13](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/search-results-header.php#L13) | defined | `'ABSPATH'` |
| [resources/views/search-results-listings.php:13](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/search-results-listings.php#L13) | defined | `'ABSPATH'` |
| [resources/views/search-results-status.php:2](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/search-results-status.php#L2) | defined | `'ABSPATH'` |
| [resources/views/search-results-wrapper.php:2](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/search-results-wrapper.php#L2) | defined | `'ABSPATH'` |
| [resources/views/search-results.php:15](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/resources/views/search-results.php#L15) | defined | `'ABSPATH'` |
| [routes/ajax/api.php:2](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/routes/ajax/api.php#L2) | defined | `'ABSPATH'` |
| [routes/rest/api.php:2](https://github.com/sovware/directorist-universal-search/blob/9a0d3ca53ba331cb1a473965b4c86d9904a6eb9a/routes/rest/api.php#L2) | defined | `'ABSPATH'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
