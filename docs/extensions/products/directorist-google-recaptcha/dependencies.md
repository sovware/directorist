# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [config.php:3](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/config.php#L3) | defined | `'DGR_VERSION'` |
| [config.php:5](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/config.php#L5) | defined | `'DGR_DIR'` |
| [config.php:7](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/config.php#L7) | defined | `'DGR_URL'` |
| [config.php:9](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/config.php#L9) | defined | `'DGR_BASE'` |
| [config.php:11](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/config.php#L11) | defined | `'DGR_INC_DIR'` |
| [config.php:13](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/config.php#L13) | defined | `'DGR_ASSETS'` |
| [config.php:15](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/config.php#L15) | defined | `'DGR_TEMPLATES_DIR'` |
| [config.php:17](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/config.php#L17) | defined | `'DGR_LANG_DIR'` |
| [config.php:19](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/config.php#L19) | defined | `'DGR_NAME'` |
| [config.php:23](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/config.php#L23) | defined | `'ATBDP_AUTHOR_URL'` |
| [config.php:27](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/config.php#L27) | defined | `'ATBDP_RECAPTCHA_POST_ID'` |
| [directorist-google-recaptcha.php:15](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/directorist-google-recaptcha.php#L15) | defined | `'ABSPATH'` |
| [directorist-google-recaptcha.php:16](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/directorist-google-recaptcha.php#L16) | class_exists | `'BD_Google_Recaptcha'` |
| [directorist-google-recaptcha.php:236](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/directorist-google-recaptcha.php#L236) | wp_remote_post | `ATBDP_AUTHOR_URL` |
| [directorist-google-recaptcha.php:238](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/directorist-google-recaptcha.php#L238) | wp_remote_retrieve_response_code | `$response` |
| [directorist-google-recaptcha.php:245](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/directorist-google-recaptcha.php#L245) | wp_remote_retrieve_body | `$response` |
| [directorist-google-recaptcha.php:330](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/directorist-google-recaptcha.php#L330) | wp_remote_post | `ATBDP_AUTHOR_URL` |
| [directorist-google-recaptcha.php:332](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/directorist-google-recaptcha.php#L332) | wp_remote_retrieve_response_code | `$response` |
| [directorist-google-recaptcha.php:339](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/directorist-google-recaptcha.php#L339) | wp_remote_retrieve_body | `$response` |
| [directorist-google-recaptcha.php:462](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/directorist-google-recaptcha.php#L462) | wp_remote_post | `$verify_url` |
| [directorist-google-recaptcha.php:463](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/directorist-google-recaptcha.php#L463) | wp_remote_retrieve_body | `$response` |
| [directorist-google-recaptcha.php:651](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/directorist-google-recaptcha.php#L651) | wp_remote_post | `$verify_url` |
| [directorist-google-recaptcha.php:652](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/directorist-google-recaptcha.php#L652) | wp_remote_retrieve_body | `$response` |
| [directorist-google-recaptcha.php:778](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/directorist-google-recaptcha.php#L778) | wp_remote_post | `$verify_url` |
| [directorist-google-recaptcha.php:779](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/directorist-google-recaptcha.php#L779) | wp_remote_retrieve_body | `$response` |
| [directorist-google-recaptcha.php:904](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/directorist-google-recaptcha.php#L904) | wp_remote_post | `$verify_url` |
| [directorist-google-recaptcha.php:905](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/directorist-google-recaptcha.php#L905) | wp_remote_retrieve_body | `$response` |
| [directorist-google-recaptcha.php:1044](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/directorist-google-recaptcha.php#L1044) | class_exists | `'EDD_SL_Plugin_Updater'` |
| [directorist-google-recaptcha.php:1083](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/directorist-google-recaptcha.php#L1083) | defined | `'DGR_FILE'` |
| [directorist-google-recaptcha.php:1093](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/directorist-google-recaptcha.php#L1093) | function_exists | `'directorist_is_plugin_active'` |
| [directorist-google-recaptcha.php:1099](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/directorist-google-recaptcha.php#L1099) | function_exists | `'directorist_is_plugin_active_for_network'` |
| [inc/directory_type.php:5](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/inc/directory_type.php#L5) | class_exists | `'Directorist_GR_Directory_Type_Manager'` |
| [inc/helper-functions.php:3](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/inc/helper-functions.php#L3) | defined | `'ABSPATH'` |
| [inc/helper-functions.php:7](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/inc/helper-functions.php#L7) | function_exists | `'atbdp_get_option'` |
| [inc/helper-functions.php:38](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/inc/helper-functions.php#L38) | function_exists | `'atbdp_sanitize_array'` |
| [inc/helper-functions.php:66](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/inc/helper-functions.php#L66) | function_exists | `'is_directoria_active'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
