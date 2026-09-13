# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |
| [composer.json:1](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/composer.json#L1) | google/apiclient | ^2.7 |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [config-helper.php:3](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/config-helper.php#L3) | function_exists | `'directorist_social_login_get_version_from_content'` |
| [config-helper.php:15](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/config-helper.php#L15) | function_exists | `'directorist_social_login_get_version_from_file_content'` |
| [config.php:3](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/config.php#L3) | defined | `'SOCIAL_LOGIN_VERSION'` |
| [config.php:5](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/config.php#L5) | defined | `'DEB_DIR'` |
| [config.php:7](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/config.php#L7) | defined | `'DEB_URL'` |
| [config.php:9](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/config.php#L9) | defined | `'DEB_BASE'` |
| [config.php:11](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/config.php#L11) | defined | `'DEB_INC_DIR'` |
| [config.php:13](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/config.php#L13) | defined | `'DEB_ASSETS'` |
| [config.php:14](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/config.php#L14) | defined | `'DEB_PUBLIC_ASSETS'` |
| [config.php:15](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/config.php#L15) | defined | `'DEB_ADMIN_ASSETS'` |
| [config.php:17](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/config.php#L17) | defined | `'DEB_TEMPLATES_DIR'` |
| [config.php:19](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/config.php#L19) | defined | `'DEB_LANG_DIR'` |
| [config.php:21](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/config.php#L21) | defined | `'DEB_NAME'` |
| [config.php:24](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/config.php#L24) | defined | `'ATBDP_AUTHOR_URL'` |
| [config.php:28](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/config.php#L28) | defined | `'ATBDP_SOCIAL_LOGIN_POST_ID'` |
| [directorist-social-login.php:16](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L16) | defined | `'ABSPATH'` |
| [directorist-social-login.php:18](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L18) | class_exists | `'Directorist_Social_Login'` |
| [directorist-social-login.php:245](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L245) | wp_remote_post | `ATBDP_AUTHOR_URL` |
| [directorist-social-login.php:247](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L247) | wp_remote_retrieve_response_code | `$response` |
| [directorist-social-login.php:254](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L254) | wp_remote_retrieve_body | `$response` |
| [directorist-social-login.php:338](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L338) | wp_remote_post | `ATBDP_AUTHOR_URL` |
| [directorist-social-login.php:340](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L340) | wp_remote_retrieve_response_code | `$response` |
| [directorist-social-login.php:347](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L347) | wp_remote_retrieve_body | `$response` |
| [directorist-social-login.php:487](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L487) | wp_remote_get | `$url` |
| [directorist-social-login.php:489](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L489) | wp_remote_retrieve_response_code | `$response` |
| [directorist-social-login.php:493](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L493) | wp_remote_retrieve_body | `$response` |
| [directorist-social-login.php:663](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L663) | class_exists | `'Directorist_Base'` |
| [directorist-social-login.php:664](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L664) | class_exists | `'Directorist_Base'` |
| [directorist-social-login.php:665](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L665) | class_exists | `'Directorist_Base'` |
| [directorist-social-login.php:883](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L883) | class_exists | `'Directorist_Social_Login_Compatibility'` |
| [directorist-social-login.php:888](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L888) | class_exists | `'EDD_SL_Plugin_Updater'` |
| [directorist-social-login.php:914](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L914) | defined | `'DEB_FILE'` |
| [directorist-social-login.php:921](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L921) | function_exists | `'directorist_is_plugin_active'` |
| [directorist-social-login.php:927](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L927) | function_exists | `'directorist_is_plugin_active_for_network'` |
| [inc/class-compatibility.php:4](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/inc/class-compatibility.php#L4) | defined | `'ABSPATH'` |
| [inc/helper-functions.php:3](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/inc/helper-functions.php#L3) | defined | `'ABSPATH'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
