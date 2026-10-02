# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |
| [composer.json:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/composer.json#L1) | minishlink/web-push | ^6.0.7 |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [directorist-notifications-pro.php:16](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/directorist-notifications-pro.php#L16) | defined | `'ABSPATH'` |
| [directorist-notifications-pro.php:18](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/directorist-notifications-pro.php#L18) | defined | `'DIRECTORIST_NOTIFICATIONS_PRO_FILE'` |
| [directorist-notifications-pro.php:22](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/directorist-notifications-pro.php#L22) | defined | `'DIRECTORIST_NOTIFICATIONS_PRO_VERSION'` |
| [directorist-notifications-pro.php:33](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/directorist-notifications-pro.php#L33) | defined | `'DIRECTORIST_NOTIFICATIONS_PRO_PATH'` |
| [directorist-notifications-pro.php:37](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/directorist-notifications-pro.php#L37) | defined | `'DIRECTORIST_NOTIFICATIONS_PRO_URL'` |
| [directorist-notifications-pro.php:41](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/directorist-notifications-pro.php#L41) | defined | `'DIRECTORIST_NOTIFICATIONS_PRO_AUTHOR_URL'` |
| [directorist-notifications-pro.php:45](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/directorist-notifications-pro.php#L45) | defined | `'DIRECTORIST_NOTIFICATIONS_PRO_ITEM_ID'` |
| [directorist-notifications-pro.php:49](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/directorist-notifications-pro.php#L49) | class_exists | `'Directorist_Notifications_Pro'` |
| [directorist-notifications-pro.php:110](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/directorist-notifications-pro.php#L110) | is_plugin_active | `'directorist/directorist-base.php'` |
| [directorist-notifications-pro.php:110](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/directorist-notifications-pro.php#L110) | defined | `'ATBDP_VERSION'` |
| [inc/class-admin-log.php:8](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-admin-log.php#L8) | defined | `'ABSPATH'` |
| [inc/class-admin-log.php:10](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-admin-log.php#L10) | class_exists | `'Directorist_Notifications_Pro_Admin_Log'` |
| [inc/class-event-integration.php:8](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-event-integration.php#L8) | defined | `'ABSPATH'` |
| [inc/class-event-integration.php:10](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-event-integration.php#L10) | class_exists | `'Directorist_Notifications_Pro_Event_Integration'` |
| [inc/class-event-integration.php:498](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-event-integration.php#L498) | function_exists | `'directorist_get_order_by_id'` |
| [inc/class-event-integration.php:670](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-event-integration.php#L670) | function_exists | `'get_comment_link'` |
| [inc/class-frontend.php:8](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-frontend.php#L8) | defined | `'ABSPATH'` |
| [inc/class-frontend.php:10](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-frontend.php#L10) | class_exists | `'Directorist_Notifications_Pro_Frontend'` |
| [inc/class-license.php:8](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-license.php#L8) | defined | `'ABSPATH'` |
| [inc/class-license.php:10](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-license.php#L10) | class_exists | `'Directorist_Notifications_Pro_License'` |
| [inc/class-license.php:78](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-license.php#L78) | class_exists | `'EDD_SL_Plugin_Updater'` |
| [inc/class-license.php:138](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-license.php#L138) | class_exists | `'EDD_SL_Plugin_Updater'` |
| [inc/class-rest-api.php:8](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-rest-api.php#L8) | defined | `'ABSPATH'` |
| [inc/class-rest-api.php:10](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-rest-api.php#L10) | class_exists | `'Directorist_Notifications_Pro_REST_API'` |
| [inc/class-sender.php:8](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-sender.php#L8) | defined | `'ABSPATH'` |
| [inc/class-sender.php:10](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-sender.php#L10) | class_exists | `'Directorist_Notifications_Pro_Sender'` |
| [inc/class-sender.php:89](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-sender.php#L89) | class_exists | `'Minishlink\\WebPush\\WebPush'` |
| [inc/class-sender.php:89](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-sender.php#L89) | class_exists | `'Minishlink\\WebPush\\Subscription'` |
| [inc/class-sender.php:223](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-sender.php#L223) | function_exists | `'get_directorist_option'` |
| [inc/class-sender.php:298](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-sender.php#L298) | function_exists | `'get_directorist_option'` |
| [inc/class-sender.php:301](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-sender.php#L301) | function_exists | `'get_directorist_option'` |
| [inc/class-settings.php:8](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-settings.php#L8) | defined | `'ABSPATH'` |
| [inc/class-settings.php:10](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-settings.php#L10) | class_exists | `'Directorist_Notifications_Pro_Settings'` |
| [inc/class-web-push.php:8](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-web-push.php#L8) | defined | `'ABSPATH'` |
| [inc/class-web-push.php:10](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-web-push.php#L10) | class_exists | `'Directorist_Notifications_Pro_Web_Push'` |
| [inc/class-web-push.php:508](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-web-push.php#L508) | function_exists | `'get_directorist_option'` |
| [inc/class-web-push.php:568](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-web-push.php#L568) | function_exists | `'openssl_pkey_new'` |
| [inc/class-web-push.php:568](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-web-push.php#L568) | defined | `'OPENSSL_KEYTYPE_EC'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
