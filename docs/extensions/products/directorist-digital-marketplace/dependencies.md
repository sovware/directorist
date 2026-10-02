# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |
| [package.json:1](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/package.json#L1) | webpack-rtl-plugin | ^2.0.0 |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [directorist-digital-marketplace.php:28](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/directorist-digital-marketplace.php#L28) | function_exists | `'DirectoristDigitalMarketplace'` |
| [directorist-digital-marketplace.php:33](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/directorist-digital-marketplace.php#L33) | class_exists | `'EDD_SL_Plugin_Updater'` |
| [app/Helper/Serve.php:17](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/app/Helper/Serve.php#L17) | class_exists | `$service` |
| [app/Helper/Serve.php:37](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/app/Helper/Serve.php#L37) | class_exists | `$controller` |
| [app/Module/Core/Admin/Notice.php:18](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/app/Module/Core/Admin/Notice.php#L18) | class_exists | `'WooCommerce'` |
| [app/Module/Core/Admin/Notice.php:22](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/app/Module/Core/Admin/Notice.php#L22) | class_exists | `'Directorist_Base'` |
| [app/Module/Dashboard/Payouts.php:9](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/app/Module/Dashboard/Payouts.php#L9) | class_exists | `'BD_Booking'` |
| [app/Module/Dashboard/Tabs_Downloads.php:26](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/app/Module/Dashboard/Tabs_Downloads.php#L26) | class_exists | `'WooCommerce'` |
| [app/Module/Dashboard/Tabs_Downloads.php:26](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/app/Module/Dashboard/Tabs_Downloads.php#L26) | function_exists | `'WC'` |
| [app/Utility/Enqueuer.php:165](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/app/Utility/Enqueuer.php#L165) | class_exists | `$class_name` |
| [helper/const.php:3](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/helper/const.php#L3) | defined | `'DDM_VERSION'` |
| [helper/const.php:7](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/helper/const.php#L7) | defined | `'DDM_SCRIPT_VERSION'` |
| [helper/const.php:11](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/helper/const.php#L11) | defined | `'DDM_FILE'` |
| [helper/const.php:15](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/helper/const.php#L15) | defined | `'DDM_BASE'` |
| [helper/const.php:19](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/helper/const.php#L19) | defined | `'DDM_LANGUAGES'` |
| [helper/const.php:23](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/helper/const.php#L23) | defined | `'DDM_POST_TYPE'` |
| [helper/const.php:27](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/helper/const.php#L27) | defined | `'DDM_TEMPLATE_PATH'` |
| [helper/const.php:31](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/helper/const.php#L31) | defined | `'DDM_VIEW_PATH'` |
| [helper/const.php:35](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/helper/const.php#L35) | defined | `'DDM_URL'` |
| [helper/const.php:39](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/helper/const.php#L39) | defined | `'DDM_ASSET_URL'` |
| [helper/const.php:43](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/helper/const.php#L43) | defined | `'DDM_JS_PATH'` |
| [helper/const.php:47](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/helper/const.php#L47) | defined | `'DDM_CSS_PATH'` |
| [helper/const.php:51](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/helper/const.php#L51) | defined | `'DDM_LOAD_MIN_FILES'` |
| [helper/const.php:56](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/helper/const.php#L56) | defined | `'ATBDP_AUTHOR_URL'` |
| [helper/const.php:61](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/helper/const.php#L61) | defined | `'ATBDP_DDM_POST_ID'` |
| [templates/listing-form/extras.php:9](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/templates/listing-form/extras.php#L9) | defined | `'ABSPATH'` |
| [templates/listing-form/file.php:9](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/templates/listing-form/file.php#L9) | defined | `'ABSPATH'` |
| [templates/listing-form/hourly.php:9](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/templates/listing-form/hourly.php#L9) | defined | `'ABSPATH'` |
| [templates/listing-form/monthly.php:9](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/templates/listing-form/monthly.php#L9) | defined | `'ABSPATH'` |
| [templates/listing-form/quantity.php:9](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/templates/listing-form/quantity.php#L9) | defined | `'ABSPATH'` |
| [templates/listing-form/tiers.php:9](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/templates/listing-form/tiers.php#L9) | defined | `'ABSPATH'` |
| [templates/single-listing/buy-now.php:9](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/templates/single-listing/buy-now.php#L9) | defined | `'ABSPATH'` |
| [templates/single-listing/extras.php:12](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/templates/single-listing/extras.php#L12) | defined | `'ABSPATH'` |
| [templates/single-listing/form.php:9](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/templates/single-listing/form.php#L9) | defined | `'ABSPATH'` |
| [templates/single-listing/pricing.php:12](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/templates/single-listing/pricing.php#L12) | defined | `'ABSPATH'` |
| [templates/single-listing/quantity.php:9](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/templates/single-listing/quantity.php#L9) | defined | `'ABSPATH'` |
| [templates/single-listing/tiers.php:12](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/templates/single-listing/tiers.php#L12) | defined | `'ABSPATH'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
