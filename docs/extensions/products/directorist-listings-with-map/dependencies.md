# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [directorist-listings-map.php:15](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/directorist-listings-map.php#L15) | defined | `'ABSPATH'` |
| [directorist-listings-map.php:16](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/directorist-listings-map.php#L16) | class_exists | `'BD_Map_View'` |
| [directorist-listings-map.php:121](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/directorist-listings-map.php#L121) | wp_remote_post | `ATBDP_AUTHOR_URL` |
| [directorist-listings-map.php:123](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/directorist-listings-map.php#L123) | wp_remote_retrieve_response_code | `$response` |
| [directorist-listings-map.php:130](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/directorist-listings-map.php#L130) | wp_remote_retrieve_body | `$response` |
| [directorist-listings-map.php:215](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/directorist-listings-map.php#L215) | wp_remote_post | `ATBDP_AUTHOR_URL` |
| [directorist-listings-map.php:217](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/directorist-listings-map.php#L217) | wp_remote_retrieve_response_code | `$response` |
| [directorist-listings-map.php:224](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/directorist-listings-map.php#L224) | wp_remote_retrieve_body | `$response` |
| [directorist-listings-map.php:438](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/directorist-listings-map.php#L438) | class_exists | `'EDD_SL_Plugin_Updater'` |
| [directorist-listings-map.php:497](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/directorist-listings-map.php#L497) | defined | `'BDM_VERSION'` |
| [directorist-listings-map.php:499](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/directorist-listings-map.php#L499) | defined | `'BDM_DIR'` |
| [directorist-listings-map.php:501](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/directorist-listings-map.php#L501) | defined | `'BDM_URL'` |
| [directorist-listings-map.php:503](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/directorist-listings-map.php#L503) | defined | `'BDM_FILE'` |
| [directorist-listings-map.php:504](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/directorist-listings-map.php#L504) | defined | `'BDM_BASE'` |
| [directorist-listings-map.php:505](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/directorist-listings-map.php#L505) | defined | `'BDM_BASE'` |
| [directorist-listings-map.php:507](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/directorist-listings-map.php#L507) | defined | `'BDM_TEXTDOMAIN'` |
| [directorist-listings-map.php:509](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/directorist-listings-map.php#L509) | defined | `'BDM_LANG_DIR'` |
| [directorist-listings-map.php:511](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/directorist-listings-map.php#L511) | defined | `'BDM_TEMPLATES_DIR'` |
| [directorist-listings-map.php:513](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/directorist-listings-map.php#L513) | defined | `'ATBDP_AUTHOR_URL'` |
| [directorist-listings-map.php:517](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/directorist-listings-map.php#L517) | defined | `'ATBDP_LISTINGS_MAP_POST_ID'` |
| [directorist-listings-map.php:524](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/directorist-listings-map.php#L524) | function_exists | `'directorist_is_plugin_active'` |
| [directorist-listings-map.php:530](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/directorist-listings-map.php#L530) | function_exists | `'directorist_is_plugin_active_for_network'` |
| [inc/Listings_With_Map_Model.php:2](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/inc/Listings_With_Map_Model.php#L2) | class_exists | `'Listings_With_Map_Model'` |
| [inc/ajax.php:3](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/inc/ajax.php#L3) | defined | `'ABSPATH'` |
| [inc/helper.php:3](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/inc/helper.php#L3) | function_exists | `'bdmv_init_template_data'` |
| [inc/helper.php:15](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/inc/helper.php#L15) | function_exists | `'bdmv_reset_template_data'` |
| [inc/helper.php:22](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/inc/helper.php#L22) | function_exists | `'bdmv_get_template'` |
| [inc/helper.php:28](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/inc/helper.php#L28) | function_exists | `'bdmv_load_more_filter'` |
| [inc/helper.php:112](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/inc/helper.php#L112) | function_exists | `'bdmv_list_view'` |
| [inc/helper.php:535](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/inc/helper.php#L535) | function_exists | `'bdmv_header_advance_search'` |
| [inc/helper.php:868](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/inc/helper.php#L868) | function_exists | `'bdmv_custom_post'` |
| [inc/hooks.php:3](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/inc/hooks.php#L3) | defined | `'ABSPATH'` |
| [inc/settings.php:3](https://github.com/sovware/directorist-listings-with-map/blob/7a0d7a04f9b17b6053c8dee1f1440f84821268ad/inc/settings.php#L3) | defined | `'ABSPATH'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
