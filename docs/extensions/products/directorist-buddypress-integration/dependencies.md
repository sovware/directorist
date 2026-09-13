# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [directorist-buddypress-integration.php:20](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/directorist-buddypress-integration.php#L20) | defined | `'ABSPATH'` |
| [directorist-buddypress-integration.php:23](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/directorist-buddypress-integration.php#L23) | defined | `'ATBDP_AUTHOR_URL'` |
| [directorist-buddypress-integration.php:28](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/directorist-buddypress-integration.php#L28) | defined | `'ATBDP_DIR_BUDDYPRESS_POST_ID'` |
| [directorist-buddypress-integration.php:116](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/directorist-buddypress-integration.php#L116) | class_exists | `'EDD_SL_Plugin_Updater'` |
| [directorist-buddypress-integration.php:124](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/directorist-buddypress-integration.php#L124) | class_exists | `'Directorist_Base'` |
| [directorist-buddypress-integration.php:132](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/directorist-buddypress-integration.php#L132) | class_exists | `'BuddyPress'` |
| [directorist-buddypress-integration.php:185](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/directorist-buddypress-integration.php#L185) | class_exists | `'Directorist_Base'` |
| [includes/bp-listings-actions.php:6](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/bp-listings-actions.php#L6) | defined | `'ABSPATH'` |
| [includes/bp-listings-activity.php:36](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/bp-listings-activity.php#L36) | function_exists | `'bp_activity_add'` |
| [includes/bp-listings-functions.php:7](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/bp-listings-functions.php#L7) | defined | `'ABSPATH'` |
| [includes/class-bp-listings-component.php:10](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/class-bp-listings-component.php#L10) | defined | `'ABSPATH'` |
| [includes/class-bp-listings-loader.php:8](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/class-bp-listings-loader.php#L8) | defined | `'ABSPATH'` |
| [includes/class-builder.php:10](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/class-builder.php#L10) | defined | `'ABSPATH'` |
| [includes/class-group-listings-extension.php:10](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/class-group-listings-extension.php#L10) | defined | `'ABSPATH'` |
| [includes/class-settings.php:10](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/class-settings.php#L10) | defined | `'ABSPATH'` |
| [includes/class-template.php:10](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/class-template.php#L10) | defined | `'ABSPATH'` |
| [includes/listings-common-functions.php:7](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/listings-common-functions.php#L7) | defined | `'ABSPATH'` |
| [includes/templates/buddypress/add-listing.php:8](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/templates/buddypress/add-listing.php#L8) | defined | `'ABSPATH'` |
| [includes/templates/buddypress/favorites.php:8](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/templates/buddypress/favorites.php#L8) | defined | `'ABSPATH'` |
| [includes/templates/buddypress/my-listings.php:8](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/templates/buddypress/my-listings.php#L8) | defined | `'ABSPATH'` |
| [includes/templates/directorist/archive-message-button.php:14](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/templates/directorist/archive-message-button.php#L14) | defined | `'ABSPATH'` |
| [includes/templates/directorist/single-message-button.php:13](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/templates/directorist/single-message-button.php#L13) | defined | `'ABSPATH'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
