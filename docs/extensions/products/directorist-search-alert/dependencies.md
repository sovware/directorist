# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [const.php:3](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/const.php#L3) | defined | `'DSA_LANG_PATH'` |
| [const.php:6](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/const.php#L6) | defined | `'DSA_PLUGIN_PATH'` |
| [const.php:9](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/const.php#L9) | defined | `'DSA_PLUGIN_DIR_URI'` |
| [const.php:12](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/const.php#L12) | defined | `'DSA_ASSET_URI'` |
| [const.php:15](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/const.php#L15) | defined | `'DSA_CSS_URI'` |
| [const.php:18](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/const.php#L18) | defined | `'DSA_JS_URI'` |
| [const.php:21](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/const.php#L21) | defined | `'DSA_DIR'` |
| [const.php:24](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/const.php#L24) | defined | `'DSA_VERSION'` |
| [const.php:28](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/const.php#L28) | defined | `'DSA_POST_ID'` |
| [const.php:32](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/const.php#L32) | defined | `'ATBDP_AUTHOR_URL'` |
| [const.php:36](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/const.php#L36) | defined | `'DSA_VERSION'` |
| [directorist-search-alert.php:24](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/directorist-search-alert.php#L24) | defined | `'ABSPATH'` |
| [directorist-search-alert.php:27](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/directorist-search-alert.php#L27) | defined | `'DSA_PLUGIN_FILE'` |
| [directorist-search-alert.php:39](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/directorist-search-alert.php#L39) | class_exists | `'EDD_SL_Plugin_Updater'` |
| [directorist-search-alert.php:47](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/directorist-search-alert.php#L47) | class_exists | `'EDD_SL_Plugin_Updater'` |
| [directorist-search-alert.php:61](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/directorist-search-alert.php#L61) | class_exists | `'Directorist_Search_Alert'` |
| [app/base.php:2](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/base.php#L2) | defined | `'ABSPATH'` |
| [app/base.php:205](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/base.php#L205) | class_exists | `$class_name` |
| [app/base.php:230](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/base.php#L230) | function_exists | `'directorist_is_plugin_active'` |
| [app/base.php:242](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/base.php#L242) | function_exists | `'directorist_is_plugin_active_for_network'` |
| [helpers/helpers.php:42](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/helpers/helpers.php#L42) | function_exists | `'dsa_get_version_from_content'` |
| [helpers/helpers.php:54](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/helpers/helpers.php#L54) | function_exists | `'dsa_get_version_from_file_content'` |
| [helpers/trait-search-helper.php:4](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/helpers/trait-search-helper.php#L4) | defined | `'ABSPATH'` |
| [templates/search-result.php:8](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/templates/search-result.php#L8) | defined | `'ABSPATH'` |
| [templates/dashboard/admin-dashboard.php:8](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/templates/dashboard/admin-dashboard.php#L8) | defined | `'ABSPATH'` |
| [templates/dashboard/user-dashboard.php:8](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/templates/dashboard/user-dashboard.php#L8) | defined | `'ABSPATH'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
