# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [directorist-gamipress-integration.php:18](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/directorist-gamipress-integration.php#L18) | defined | `'ABSPATH'` |
| [directorist-gamipress-integration.php:23](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/directorist-gamipress-integration.php#L23) | defined | `'ATBDP_AUTHOR_URL'` |
| [directorist-gamipress-integration.php:28](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/directorist-gamipress-integration.php#L28) | defined | `'ATBDP_DIR_GAMIPRESS_POST_ID'` |
| [directorist-gamipress-integration.php:103](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/directorist-gamipress-integration.php#L103) | class_exists | `'EDD_SL_Plugin_Updater'` |
| [directorist-gamipress-integration.php:108](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/directorist-gamipress-integration.php#L108) | class_exists | `'Directorist_Base'` |
| [directorist-gamipress-integration.php:116](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/directorist-gamipress-integration.php#L116) | class_exists | `'GamiPress'` |
| [inc/directory_type.php:5](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/inc/directory_type.php#L5) | class_exists | `'FAQS_Post_Type_Manager'` |
| [inc/helper-functions.php:3](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/inc/helper-functions.php#L3) | defined | `'ABSPATH'` |
| [inc/helper-functions.php:5](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/inc/helper-functions.php#L5) | function_exists | `'atbdp_get_option'` |
| [inc/helper-functions.php:36](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/inc/helper-functions.php#L36) | function_exists | `'atbdp_sanitize_array'` |
| [includes/class-assets.php:7](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-assets.php#L7) | defined | `'ABSPATH'` |
| [includes/class-author.php:7](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-author.php#L7) | defined | `'ABSPATH'` |
| [includes/class-coupon-manager.php:9](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-coupon-manager.php#L9) | defined | `'ABSPATH'` |
| [includes/class-dashboard.php:7](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-dashboard.php#L7) | defined | `'ABSPATH'` |
| [includes/class-listeners.php:9](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-listeners.php#L9) | defined | `'ABSPATH'` |
| [includes/class-requirements.php:7](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-requirements.php#L7) | defined | `'ABSPATH'` |
| [includes/class-rules-engine.php:9](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-rules-engine.php#L9) | defined | `'ABSPATH'` |
| [includes/class-settings.php:7](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-settings.php#L7) | defined | `'ABSPATH'` |
| [includes/class-triggers.php:7](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-triggers.php#L7) | defined | `'ABSPATH'` |
| [includes/class-utils.php:7](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-utils.php#L7) | defined | `'ABSPATH'` |
| [includes/class-utils.php:54](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-utils.php#L54) | class_exists | `'SWBDPCoupon'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
