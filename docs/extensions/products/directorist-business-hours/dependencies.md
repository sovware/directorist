# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [bd-business-hour.php:15](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/bd-business-hour.php#L15) | defined | `'ABSPATH'` |
| [bd-business-hour.php:16](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/bd-business-hour.php#L16) | class_exists | `'BD_Business_Hour'` |
| [bd-business-hour.php:51](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/bd-business-hour.php#L51) | defined | `'ATBDP_VERSION'` |
| [bd-business-hour.php:340](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/bd-business-hour.php#L340) | function_exists | `'directorist_is_listing_feature_available'` |
| [bd-business-hour.php:461](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/bd-business-hour.php#L461) | class_exists | `'EDD_SL_Plugin_Updater'` |
| [bd-business-hour.php:491](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/bd-business-hour.php#L491) | defined | `'BDBH_FILE'` |
| [bd-business-hour.php:511](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/bd-business-hour.php#L511) | function_exists | `'directorist_is_plugin_active'` |
| [bd-business-hour.php:517](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/bd-business-hour.php#L517) | function_exists | `'directorist_is_plugin_active_for_network'` |
| [bd-business-hour.php:551](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/bd-business-hour.php#L551) | class_exists | `'Directorist_Base'` |
| [bd-business-hour.php:556](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/bd-business-hour.php#L556) | defined | `'ATBDP_VERSION'` |
| [config-helper.php:3](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/config-helper.php#L3) | function_exists | `'bdbh_get_version_from_content'` |
| [config-helper.php:15](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/config-helper.php#L15) | function_exists | `'bdbh_get_version_from_file_content'` |
| [config.php:3](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/config.php#L3) | defined | `'BDBH_VERSION'` |
| [config.php:5](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/config.php#L5) | defined | `'BDBH_DIR'` |
| [config.php:7](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/config.php#L7) | defined | `'BDBH_URL'` |
| [config.php:9](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/config.php#L9) | defined | `'BDBH_BASE'` |
| [config.php:11](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/config.php#L11) | defined | `'BDBH_INC_DIR'` |
| [config.php:13](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/config.php#L13) | defined | `'BDBH_ASSETS'` |
| [config.php:15](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/config.php#L15) | defined | `'BDBH_TEMPLATES_DIR'` |
| [config.php:17](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/config.php#L17) | defined | `'BDBH_LANG_DIR'` |
| [config.php:19](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/config.php#L19) | defined | `'BDBH_NAME'` |
| [config.php:21](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/config.php#L21) | defined | `'ATBDP_AUTHOR_URL'` |
| [config.php:25](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/config.php#L25) | defined | `'ATBDP_BUSINESS_HOURS_POST_ID'` |
| [inc/asset-loader.php:12](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/asset-loader.php#L12) | defined | `'ABSPATH'` |
| [inc/class-rest-response.php:3](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/class-rest-response.php#L3) | defined | `'ABSPATH'` |
| [inc/class-rest-response.php:5](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/class-rest-response.php#L5) | class_exists | `'DBH_REST_Response'` |
| [inc/class-rest-response.php:94](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/class-rest-response.php#L94) | function_exists | `'wp_timezone'` |
| [inc/class-rest-response.php:98](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/class-rest-response.php#L98) | function_exists | `'wp_timezone_string'` |
| [inc/csv_manager.php:8](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/csv_manager.php#L8) | defined | `'ABSPATH'` |
| [inc/csv_manager.php:10](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/csv_manager.php#L10) | class_exists | `'Directorist_Business_Hours_CSV_Manager'` |
| [inc/directory_types_manager.php:3](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/directory_types_manager.php#L3) | defined | `'ABSPATH'` |
| [inc/directory_types_manager.php:4](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/directory_types_manager.php#L4) | class_exists | `'DBH_Directory_Type'` |
| [inc/directory_types_manager.php:166](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/directory_types_manager.php#L166) | function_exists | `'directorist_get_conditional_logic_field'` |
| [inc/helper-functions.php:3](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/helper-functions.php#L3) | defined | `'ABSPATH'` |
| [inc/helper-functions.php:5](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/helper-functions.php#L5) | function_exists | `'atbdp_get_option'` |
| [inc/helper-functions.php:39](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/helper-functions.php#L39) | function_exists | `'atbdp_sanitize_array'` |
| [inc/helper-functions.php:126](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/helper-functions.php#L126) | function_exists | `'dbh_is_open'` |
| [inc/helper-functions.php:207](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/helper-functions.php#L207) | function_exists | `'atbdp_time_calculationn'` |
| [inc/helper-functions.php:275](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/helper-functions.php#L275) | function_exists | `'atbdp_hours_badge'` |
| [inc/helper-functions.php:293](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/helper-functions.php#L293) | function_exists | `'business_open_close_status'` |
| [inc/helper-functions.php:331](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/helper-functions.php#L331) | function_exists | `'directorist_open_close_badge_class'` |
| [inc/helper-functions.php:379](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/helper-functions.php#L379) | function_exists | `'directorist_business_open_by_hours'` |
| [inc/helper-functions.php:431](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/helper-functions.php#L431) | function_exists | `'directorist_calculated_status'` |
| [inc/helper-functions.php:520](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/helper-functions.php#L520) | function_exists | `'directorist_business_open_close_status'` |
| [inc/helper-functions.php:607](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/helper-functions.php#L607) | function_exists | `'directorist_day'` |
| [inc/helper-functions.php:634](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/helper-functions.php#L634) | function_exists | `'show_business_hours'` |
| [inc/helper-functions.php:833](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/helper-functions.php#L833) | function_exists | `'directorist_show_open_close_badge'` |
| [inc/helper-functions.php:857](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/helper-functions.php#L857) | function_exists | `'directorist_listings_show_open_close_badge'` |
| [inc/helper-functions.php:1459](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/helper-functions.php#L1459) | function_exists | `'directorist_hours_cache_plugin_compatibility'` |
| [inc/helper-functions.php:1468](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/helper-functions.php#L1468) | function_exists | `'directorist_is_business_hour_enabled_for_listing'` |
| [templates/badge.php:3](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/templates/badge.php#L3) | defined | `'ABSPATH'` |
| [templates/search.php:3](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/templates/search.php#L3) | defined | `'ABSPATH'` |
| [templates/show_hours.php:3](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/templates/show_hours.php#L3) | defined | `'ABSPATH'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
