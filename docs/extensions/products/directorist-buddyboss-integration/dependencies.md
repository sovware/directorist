# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [directorist-buddyboss-integration.php:22](https://github.com/sovware/directorist-buddyboss-integration/blob/c0d40913b46a04e151ac76f0867fa96e8c3c2827/directorist-buddyboss-integration.php#L22) | defined | `'ABSPATH'` |
| [directorist-buddyboss-integration.php:25](https://github.com/sovware/directorist-buddyboss-integration/blob/c0d40913b46a04e151ac76f0867fa96e8c3c2827/directorist-buddyboss-integration.php#L25) | defined | `'ATBDP_AUTHOR_URL'` |
| [directorist-buddyboss-integration.php:30](https://github.com/sovware/directorist-buddyboss-integration/blob/c0d40913b46a04e151ac76f0867fa96e8c3c2827/directorist-buddyboss-integration.php#L30) | defined | `'ATBDP_DIR_BUDDYBOSS_POST_ID'` |
| [directorist-buddyboss-integration.php:118](https://github.com/sovware/directorist-buddyboss-integration/blob/c0d40913b46a04e151ac76f0867fa96e8c3c2827/directorist-buddyboss-integration.php#L118) | class_exists | `'EDD_SL_Plugin_Updater'` |
| [directorist-buddyboss-integration.php:307](https://github.com/sovware/directorist-buddyboss-integration/blob/c0d40913b46a04e151ac76f0867fa96e8c3c2827/directorist-buddyboss-integration.php#L307) | function_exists | `'directorist_is_plugin_active'` |
| [directorist-buddyboss-integration.php:313](https://github.com/sovware/directorist-buddyboss-integration/blob/c0d40913b46a04e151ac76f0867fa96e8c3c2827/directorist-buddyboss-integration.php#L313) | function_exists | `'directorist_is_plugin_active_for_network'` |
| [includes/bp-listings-actions.php:6](https://github.com/sovware/directorist-buddyboss-integration/blob/c0d40913b46a04e151ac76f0867fa96e8c3c2827/includes/bp-listings-actions.php#L6) | defined | `'ABSPATH'` |
| [includes/bp-listings-actions.php:93](https://github.com/sovware/directorist-buddyboss-integration/blob/c0d40913b46a04e151ac76f0867fa96e8c3c2827/includes/bp-listings-actions.php#L93) | function_exists | `'bp_activity_delete'` |
| [includes/bp-listings-activity.php:34](https://github.com/sovware/directorist-buddyboss-integration/blob/c0d40913b46a04e151ac76f0867fa96e8c3c2827/includes/bp-listings-activity.php#L34) | function_exists | `'bp_activity_add'` |
| [includes/bp-listings-functions.php:7](https://github.com/sovware/directorist-buddyboss-integration/blob/c0d40913b46a04e151ac76f0867fa96e8c3c2827/includes/bp-listings-functions.php#L7) | defined | `'ABSPATH'` |
| [includes/class-bp-listings-component.php:10](https://github.com/sovware/directorist-buddyboss-integration/blob/c0d40913b46a04e151ac76f0867fa96e8c3c2827/includes/class-bp-listings-component.php#L10) | defined | `'ABSPATH'` |
| [includes/class-bp-listings-component.php:144](https://github.com/sovware/directorist-buddyboss-integration/blob/c0d40913b46a04e151ac76f0867fa96e8c3c2827/includes/class-bp-listings-component.php#L144) | defined | `'THEME_HOOK_PREFIX'` |
| [includes/class-bp-listings-loader.php:8](https://github.com/sovware/directorist-buddyboss-integration/blob/c0d40913b46a04e151ac76f0867fa96e8c3c2827/includes/class-bp-listings-loader.php#L8) | defined | `'ABSPATH'` |
| [includes/class-builder.php:10](https://github.com/sovware/directorist-buddyboss-integration/blob/c0d40913b46a04e151ac76f0867fa96e8c3c2827/includes/class-builder.php#L10) | defined | `'ABSPATH'` |
| [includes/class-group-listings-extension.php:10](https://github.com/sovware/directorist-buddyboss-integration/blob/c0d40913b46a04e151ac76f0867fa96e8c3c2827/includes/class-group-listings-extension.php#L10) | defined | `'ABSPATH'` |
| [includes/class-settings.php:10](https://github.com/sovware/directorist-buddyboss-integration/blob/c0d40913b46a04e151ac76f0867fa96e8c3c2827/includes/class-settings.php#L10) | defined | `'ABSPATH'` |
| [includes/class-template.php:10](https://github.com/sovware/directorist-buddyboss-integration/blob/c0d40913b46a04e151ac76f0867fa96e8c3c2827/includes/class-template.php#L10) | defined | `'ABSPATH'` |
| [includes/listings-common-functions.php:7](https://github.com/sovware/directorist-buddyboss-integration/blob/c0d40913b46a04e151ac76f0867fa96e8c3c2827/includes/listings-common-functions.php#L7) | defined | `'ABSPATH'` |
| [includes/listings-common-functions.php:148](https://github.com/sovware/directorist-buddyboss-integration/blob/c0d40913b46a04e151ac76f0867fa96e8c3c2827/includes/listings-common-functions.php#L148) | defined | `'BP_PLATFORM_VERSION'` |
| [includes/listings-common-functions.php:159](https://github.com/sovware/directorist-buddyboss-integration/blob/c0d40913b46a04e151ac76f0867fa96e8c3c2827/includes/listings-common-functions.php#L159) | function_exists | `'directorist_get_user_favorites'` |
| [includes/templates/directorist/archive-message-button.php:14](https://github.com/sovware/directorist-buddyboss-integration/blob/c0d40913b46a04e151ac76f0867fa96e8c3c2827/includes/templates/directorist/archive-message-button.php#L14) | defined | `'ABSPATH'` |
| [includes/templates/directorist/single-message-button.php:13](https://github.com/sovware/directorist-buddyboss-integration/blob/c0d40913b46a04e151ac76f0867fa96e8c3c2827/includes/templates/directorist/single-message-button.php#L13) | defined | `'ABSPATH'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
