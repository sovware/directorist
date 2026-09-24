# Feature and infrastructure coverage

Canonical snapshot: `live-chat--fix-live-chat-visibility-socket-fallback`.

Feature-linked means declarations/file boundaries are connected to an authored user workflow/topic. It does not prove every conditional path was manually audited. Infrastructure is explicitly accounted for rather than presented as another supported feature. Support/generated/vendor-library files are not runtime proof.

Counts: feature-linked=11, infrastructure=2, support-or-generated=6.

Unresolved feature candidates: 0.

| Source | Classification | Topic or reason |
| --- | --- | --- |
| [.gitignore:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/.gitignore#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [PR_DESCRIPTION.md:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/PR_DESCRIPTION.md#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [composer.json:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/composer.json#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [composer.lock:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/composer.lock#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [directorist-live-chat.php:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/directorist-live-chat.php#L1) | feature-linked | [conversation](topics/conversation.md), [socket-delivery](topics/socket-delivery.md) |
| [index.php:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/index.php#L1) | infrastructure | Plugin bootstrap/header or shared root configuration; inspect include/registration chain. |
| [phpcs.xml.dist:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/phpcs.xml.dist#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [assets/admin/main.css:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/assets/admin/main.css#L1) | feature-linked | [socket-delivery](topics/socket-delivery.md) |
| [assets/admin/main.js:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/assets/admin/main.js#L1) | feature-linked | [socket-delivery](topics/socket-delivery.md) |
| [assets/public/main.js:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/assets/public/main.js#L1) | feature-linked | [socket-delivery](topics/socket-delivery.md) |
| [assets/public/style-rtl.css:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/assets/public/style-rtl.css#L1) | feature-linked | [socket-delivery](topics/socket-delivery.md) |
| [assets/public/style.css:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/assets/public/style.css#L1) | feature-linked | [socket-delivery](topics/socket-delivery.md) |
| [class/admin-chat.php:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/class/admin-chat.php#L1) | feature-linked | [conversation](topics/conversation.md) |
| [includes/EDD_SL_Plugin_Updater.php:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/includes/EDD_SL_Plugin_Updater.php#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [includes/directory_type.php:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/includes/directory_type.php#L1) | feature-linked | [conversation](topics/conversation.md) |
| [includes/helper.php:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/includes/helper.php#L1) | feature-linked | [conversation](topics/conversation.md), [socket-delivery](topics/socket-delivery.md) |
| [languages/directorist-live-chat.pot:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/languages/directorist-live-chat.pot#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [templates/chat.php:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/templates/chat.php#L1) | feature-linked | [conversation](topics/conversation.md) |
| [templates/live_chat.php:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/templates/live_chat.php#L1) | feature-linked | [conversation](topics/conversation.md) |
