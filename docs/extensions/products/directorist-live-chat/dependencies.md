# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [directorist-live-chat.php:14](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/directorist-live-chat.php#L14) | defined | `'ABSPATH'` |
| [directorist-live-chat.php:15](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/directorist-live-chat.php#L15) | class_exists | `'Directorist_Live_Chat'` |
| [directorist-live-chat.php:190](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/directorist-live-chat.php#L190) | wp_remote_post | `ATBDP_AUTHOR_URL` |
| [directorist-live-chat.php:192](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/directorist-live-chat.php#L192) | wp_remote_retrieve_response_code | `$response` |
| [directorist-live-chat.php:199](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/directorist-live-chat.php#L199) | wp_remote_retrieve_body | `$response` |
| [directorist-live-chat.php:284](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/directorist-live-chat.php#L284) | wp_remote_post | `ATBDP_AUTHOR_URL` |
| [directorist-live-chat.php:286](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/directorist-live-chat.php#L286) | wp_remote_retrieve_response_code | `$response` |
| [directorist-live-chat.php:293](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/directorist-live-chat.php#L293) | wp_remote_retrieve_body | `$response` |
| [directorist-live-chat.php:1188](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/directorist-live-chat.php#L1188) | defined | `'DLC_VERSION'` |
| [directorist-live-chat.php:1192](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/directorist-live-chat.php#L1192) | defined | `'DLC_DIR'` |
| [directorist-live-chat.php:1194](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/directorist-live-chat.php#L1194) | defined | `'DLC_TEMPLATES_DIR'` |
| [directorist-live-chat.php:1197](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/directorist-live-chat.php#L1197) | defined | `'ATBDP_AUTHOR_URL'` |
| [directorist-live-chat.php:1201](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/directorist-live-chat.php#L1201) | defined | `'ATBDP_DLC_POST_ID'` |
| [directorist-live-chat.php:1219](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/directorist-live-chat.php#L1219) | class_exists | `'EDD_SL_Plugin_Updater'` |
| [directorist-live-chat.php:1306](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/directorist-live-chat.php#L1306) | function_exists | `'directorist_is_plugin_active'` |
| [directorist-live-chat.php:1312](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/directorist-live-chat.php#L1312) | function_exists | `'directorist_is_plugin_active_for_network'` |
| [includes/directory_type.php:5](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/includes/directory_type.php#L5) | class_exists | `'DLC_Directory_Type_Manager'` |
| [includes/directory_type.php:36](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/includes/directory_type.php#L36) | function_exists | `'directorist_is_listing_feature_available'` |
| [includes/helper.php:2](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/includes/helper.php#L2) | defined | `'ABSPATH'` |
| [includes/helper.php:9](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/includes/helper.php#L9) | function_exists | `'atbdp_check_live_chat_restriction'` |
| [includes/helper.php:18](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/includes/helper.php#L18) | function_exists | `'get_chats'` |
| [includes/helper.php:59](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/includes/helper.php#L59) | function_exists | `'get_chat_by_user'` |
| [includes/helper.php:100](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/includes/helper.php#L100) | function_exists | `'get_chatted_listings'` |
| [includes/helper.php:123](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/includes/helper.php#L123) | function_exists | `'listing_chat_exists_by_user'` |
| [includes/helper.php:154](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/includes/helper.php#L154) | function_exists | `'listing_chat_by_admin'` |
| [includes/helper.php:184](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/includes/helper.php#L184) | function_exists | `'all_chatted_user_by_listing'` |
| [includes/helper.php:207](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/includes/helper.php#L207) | function_exists | `'send_email_notification'` |
| [templates/chat.php:3](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/templates/chat.php#L3) | defined | `'ABSPATH'` |
| [templates/live_chat.php:3](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/templates/live_chat.php#L3) | defined | `'ABSPATH'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
