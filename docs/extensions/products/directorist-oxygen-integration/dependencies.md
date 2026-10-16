# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [class-control.php:9](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/class-control.php#L9) | defined | `'ABSPATH'` |
| [class-element.php:9](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/class-element.php#L9) | defined | `'ABSPATH'` |
| [directorist-oxygen-integration.php:21](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/directorist-oxygen-integration.php#L21) | defined | `'ABSPATH'` |
| [directorist-oxygen-integration.php:24](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/directorist-oxygen-integration.php#L24) | defined | `'ATBDP_AUTHOR_URL'` |
| [directorist-oxygen-integration.php:29](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/directorist-oxygen-integration.php#L29) | defined | `'ATBDP_OXEEl_POST_ID'` |
| [directorist-oxygen-integration.php:67](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/directorist-oxygen-integration.php#L67) | defined | `'BREAKDANCE_MODE'` |
| [directorist-oxygen-integration.php:80](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/directorist-oxygen-integration.php#L80) | class_exists | `'\Breakdance\Elements\ElementCategoriesController'` |
| [directorist-oxygen-integration.php:129](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/directorist-oxygen-integration.php#L129) | function_exists | `'\Breakdance\Elements\registerCategory'` |
| [directorist-oxygen-integration.php:129](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/directorist-oxygen-integration.php#L129) | function_exists | `'\Breakdance\ElementStudio\registerSaveLocation'` |
| [directorist-oxygen-integration.php:168](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/directorist-oxygen-integration.php#L168) | class_exists | `'\Breakdance\Elements\Element'` |
| [directorist-oxygen-integration.php:231](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/directorist-oxygen-integration.php#L231) | class_exists | `'\OxyEl'` |
| [directorist-oxygen-integration.php:249](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/directorist-oxygen-integration.php#L249) | class_exists | `'\Directorist\Directorist_Template_Hooks'` |
| [directorist-oxygen-integration.php:254](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/directorist-oxygen-integration.php#L254) | class_exists | `'\EDD_SL_Plugin_Updater'` |
| [directorist-oxygen-integration.php:365](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/directorist-oxygen-integration.php#L365) | defined | `'ATPP_DIR'` |
| [directorist-oxygen-integration.php:365](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/directorist-oxygen-integration.php#L365) | defined | `'ATPP_ASSETS'` |
| [directorist-oxygen-integration.php:376](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/directorist-oxygen-integration.php#L376) | defined | `'WP_PLUGIN_DIR'` |
| [directorist-oxygen-integration.php:416](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/directorist-oxygen-integration.php#L416) | defined | `'ATPP_VERSION'` |
| [directorist-oxygen-integration.php:438](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/directorist-oxygen-integration.php#L438) | function_exists | `'directorist_is_plugin_active'` |
| [directorist-oxygen-integration.php:450](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/directorist-oxygen-integration.php#L450) | function_exists | `'directorist_is_plugin_active_for_network'` |
| [elements/add-listing.php:9](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/elements/add-listing.php#L9) | defined | `'ABSPATH'` |
| [elements/all-categories.php:9](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/elements/all-categories.php#L9) | defined | `'ABSPATH'` |
| [elements/all-listing.php:9](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/elements/all-listing.php#L9) | defined | `'ABSPATH'` |
| [elements/all-locations.php:9](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/elements/all-locations.php#L9) | defined | `'ABSPATH'` |
| [elements/author-profile.php:9](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/elements/author-profile.php#L9) | defined | `'ABSPATH'` |
| [elements/category.php:9](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/elements/category.php#L9) | defined | `'ABSPATH'` |
| [elements/checkout.php:9](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/elements/checkout.php#L9) | defined | `'ABSPATH'` |
| [elements/custom-registration.php:9](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/elements/custom-registration.php#L9) | defined | `'ABSPATH'` |
| [elements/location.php:9](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/elements/location.php#L9) | defined | `'ABSPATH'` |
| [elements/payment-receipt.php:9](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/elements/payment-receipt.php#L9) | defined | `'ABSPATH'` |
| [elements/search-listing.php:9](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/elements/search-listing.php#L9) | defined | `'ABSPATH'` |
| [elements/search-result.php:9](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/elements/search-result.php#L9) | defined | `'ABSPATH'` |
| [elements/tag.php:9](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/elements/tag.php#L9) | defined | `'ABSPATH'` |
| [elements/transaction-failure.php:9](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/elements/transaction-failure.php#L9) | defined | `'ABSPATH'` |
| [elements/user-dashboard.php:9](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/elements/user-dashboard.php#L9) | defined | `'ABSPATH'` |
| [elements/user-login.php:9](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/elements/user-login.php#L9) | defined | `'ABSPATH'` |
| [oxygen-6/elements/directorist-elements/element.php:16](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/oxygen-6/elements/directorist-elements/element.php#L16) | defined | `'ABSPATH'` |
| [oxygen-6/elements/directorist-elements/element.php:559](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/oxygen-6/elements/directorist-elements/element.php#L559) | defined | `'ATBDP_POST_TYPE'` |
| [oxygen-6/elements/directorist-elements/element.php:566](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/oxygen-6/elements/directorist-elements/element.php#L566) | class_exists | `'\Directorist\Asset_Loader\Asset_Loader'` |
| [oxygen-6/elements/directorist-elements/support/bootstrap.php:8](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/oxygen-6/elements/directorist-elements/support/bootstrap.php#L8) | defined | `'ABSPATH'` |
| [oxygen-6/elements/directorist-elements/support/bootstrap.php:10](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/oxygen-6/elements/directorist-elements/support/bootstrap.php#L10) | function_exists | `__NAMESPACE__ . '\oxygen6_t'` |
| [oxygen-6/elements/directorist-elements/support/element-fields.php:10](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/oxygen-6/elements/directorist-elements/support/element-fields.php#L10) | defined | `'ABSPATH'` |
| [oxygen-6/elements/directorist-elements/support/element-fields.php:313](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/oxygen-6/elements/directorist-elements/support/element-fields.php#L313) | class_exists | `Helper::class` |
| [oxygen-6/elements/directorist-elements/support/element-shortcode-runtime.php:8](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/oxygen-6/elements/directorist-elements/support/element-shortcode-runtime.php#L8) | defined | `'ABSPATH'` |
| [oxygen-6/elements/directorist-elements/support/element-shortcode-runtime.php:158](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/oxygen-6/elements/directorist-elements/support/element-shortcode-runtime.php#L158) | class_exists | `'\Directorist\Asset_Loader\Asset_Loader'` |
| [oxygen-6/elements/directorist-elements/support/element-shortcode-runtime.php:183](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/oxygen-6/elements/directorist-elements/support/element-shortcode-runtime.php#L183) | class_exists | `'\Directorist\Asset_Loader\Localized_Data'` |
| [oxygen-6/elements/directorist-elements/support/element-shortcode-runtime.php:197](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/oxygen-6/elements/directorist-elements/support/element-shortcode-runtime.php#L197) | class_exists | `'\Directorist\Asset_Loader\Asset_Loader'` |
| [oxygen-6/elements/directorist-elements/support/element-shortcode-runtime.php:215](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/oxygen-6/elements/directorist-elements/support/element-shortcode-runtime.php#L215) | function_exists | `'\Breakdance\isRequestFromBuilderSsr'` |
| [oxygen-6/elements/directorist-elements/support/element-shortcode-runtime.php:217](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/oxygen-6/elements/directorist-elements/support/element-shortcode-runtime.php#L217) | class_exists | `'\Breakdance\Render\ScriptAndStyleHolder'` |
| [oxygen-6/elements/directorist-elements/support/element-ui.php:11](https://github.com/sovware/directorist-oxygen-integration/blob/6830012382294315a8c6dad1dedf5789540f1e99/oxygen-6/elements/directorist-elements/support/element-ui.php#L11) | defined | `'ABSPATH'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
