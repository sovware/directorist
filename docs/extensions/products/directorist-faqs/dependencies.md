# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [config.php:3](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/config.php#L3) | defined | `'FAQS_VERSION'` |
| [config.php:5](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/config.php#L5) | defined | `'FAQS_DIR'` |
| [config.php:7](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/config.php#L7) | defined | `'FAQS_URL'` |
| [config.php:9](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/config.php#L9) | defined | `'FAQS_BASE'` |
| [config.php:11](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/config.php#L11) | defined | `'FAQS_INC_DIR'` |
| [config.php:13](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/config.php#L13) | defined | `'FAQS_ASSETS'` |
| [config.php:15](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/config.php#L15) | defined | `'FAQS_TEMPLATES_DIR'` |
| [config.php:17](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/config.php#L17) | defined | `'FAQS_LANG_DIR'` |
| [config.php:19](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/config.php#L19) | defined | `'FAQS_NAME'` |
| [config.php:22](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/config.php#L22) | defined | `'ATBDP_AUTHOR_URL'` |
| [config.php:26](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/config.php#L26) | defined | `'ATBDP_FAQS_POST_ID'` |
| [directorist-faqs.php:16](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L16) | defined | `'ABSPATH'` |
| [directorist-faqs.php:17](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L17) | class_exists | `'Listings_fAQs'` |
| [directorist-faqs.php:197](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L197) | wp_remote_post | `ATBDP_AUTHOR_URL` |
| [directorist-faqs.php:199](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L199) | wp_remote_retrieve_response_code | `$response` |
| [directorist-faqs.php:206](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L206) | wp_remote_retrieve_body | `$response` |
| [directorist-faqs.php:291](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L291) | wp_remote_post | `ATBDP_AUTHOR_URL` |
| [directorist-faqs.php:293](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L293) | wp_remote_retrieve_response_code | `$response` |
| [directorist-faqs.php:300](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L300) | wp_remote_retrieve_body | `$response` |
| [directorist-faqs.php:533](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L533) | class_exists | `'EDD_SL_Plugin_Updater'` |
| [directorist-faqs.php:577](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L577) | defined | `'FAQS_FILE'` |
| [directorist-faqs.php:584](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L584) | function_exists | `'directorist_is_plugin_active'` |
| [directorist-faqs.php:590](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L590) | function_exists | `'directorist_is_plugin_active_for_network'` |
| [inc/directory_type.php:5](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/inc/directory_type.php#L5) | class_exists | `'FAQS_Post_Type_Manager'` |
| [inc/helper-functions.php:3](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/inc/helper-functions.php#L3) | defined | `'ABSPATH'` |
| [inc/helper-functions.php:5](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/inc/helper-functions.php#L5) | function_exists | `'atbdp_get_option'` |
| [inc/helper-functions.php:36](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/inc/helper-functions.php#L36) | function_exists | `'atbdp_sanitize_array'` |
| [templates/add-faq.php:3](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/templates/add-faq.php#L3) | defined | `'ABSPATH'` |
| [templates/faqs.php:8](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/templates/faqs.php#L8) | defined | `'ABSPATH'` |
| [templates/view-faqs.php:3](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/templates/view-faqs.php#L3) | defined | `'ABSPATH'` |
| [templates/ajax/faqs-ajax.php:3](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/templates/ajax/faqs-ajax.php#L3) | defined | `'ABSPATH'` |
| [widgets/class-widget.php:3](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/widgets/class-widget.php#L3) | defined | `'ABSPATH'` |
| [widgets/class-widget.php:37](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/widgets/class-widget.php#L37) | class_exists | `'ATBDP_Fee_Manager'` |
| [widgets/class-widget.php:40](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/widgets/class-widget.php#L40) | function_exists | `'directorist_is_listing_feature_available'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
