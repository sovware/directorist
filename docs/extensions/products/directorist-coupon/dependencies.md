# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [directorist-coupon.php:16](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/directorist-coupon.php#L16) | defined | `'ABSPATH'` |
| [directorist-coupon.php:18](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/directorist-coupon.php#L18) | function_exists | `'swbdpc_get_version_from_file_content'` |
| [directorist-coupon.php:36](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/directorist-coupon.php#L36) | function_exists | `'swbdpc_define_plugin_constants'` |
| [directorist-coupon.php:49](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/directorist-coupon.php#L49) | defined | `$constant` |
| [directorist-coupon.php:62](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/directorist-coupon.php#L62) | defined | `$constant` |
| [directorist-coupon.php:67](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/directorist-coupon.php#L67) | defined | `'ATBDP_AUTHOR_URL'` |
| [directorist-coupon.php:71](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/directorist-coupon.php#L71) | defined | `'ATBDP_COUPON_POST_ID'` |
| [directorist-coupon.php:79](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/directorist-coupon.php#L79) | class_exists | `'SWBDPCoupon'` |
| [directorist-coupon.php:148](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/directorist-coupon.php#L148) | class_exists | `'EDD_SL_Plugin_Updater'` |
| [directorist-coupon.php:239](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/directorist-coupon.php#L239) | defined | `'ATBDP_VERSION'` |
| [includes.php:6](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/includes.php#L6) | class_exists | `'SWBDPCIncludeClasses'` |
| [Inc/Controller/Admin/CustomWpListTable.php:7](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Admin/CustomWpListTable.php#L7) | class_exists | `'SWBDPCCustomWpListTable'` |
| [Inc/Controller/Admin/SWBDPCouponCPT.php:7](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Admin/SWBDPCouponCPT.php#L7) | class_exists | `'SWBDPCouponCPT'` |
| [Inc/Controller/Base/Activate.php:7](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/Activate.php#L7) | class_exists | `'SWBDPCActivate'` |
| [Inc/Controller/Base/CouponHandler.php:8](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/CouponHandler.php#L8) | class_exists | `'SWBDPCouponHandler'` |
| [Inc/Controller/Base/CouponHandler.php:61](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/CouponHandler.php#L61) | function_exists | `'directorist_price'` |
| [Inc/Controller/Base/Enqueue.php:6](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/Enqueue.php#L6) | class_exists | `'SWBDPCEnqueue'` |
| [Inc/Controller/Base/ExtensionSettings.php:7](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/ExtensionSettings.php#L7) | class_exists | `'SWBDPCExtensionSettings'` |
| [Inc/Controller/Base/ExtensionSettings.php:55](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/ExtensionSettings.php#L55) | wp_remote_post | `ATBDP_AUTHOR_URL` |
| [Inc/Controller/Base/ExtensionSettings.php:57](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/ExtensionSettings.php#L57) | wp_remote_retrieve_response_code | `$response` |
| [Inc/Controller/Base/ExtensionSettings.php:63](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/ExtensionSettings.php#L63) | wp_remote_retrieve_body | `$response` |
| [Inc/Controller/Base/ExtensionSettings.php:149](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/ExtensionSettings.php#L149) | wp_remote_post | `ATBDP_AUTHOR_URL` |
| [Inc/Controller/Base/ExtensionSettings.php:151](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/ExtensionSettings.php#L151) | wp_remote_retrieve_response_code | `$response` |
| [Inc/Controller/Base/ExtensionSettings.php:158](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/ExtensionSettings.php#L158) | wp_remote_retrieve_body | `$response` |
| [Inc/Controller/Base/HelperFunctions.php:8](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/HelperFunctions.php#L8) | class_exists | `'SWBDPCHelperFunctions'` |
| [Inc/Controller/Base/HelperFunctions.php:201](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/HelperFunctions.php#L201) | function_exists | `'directorist_pricing_plans_singleton'` |
| [Inc/Controller/Base/HelperFunctions.php:201](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/HelperFunctions.php#L201) | class_exists | `'DirectoristPricingPlan\App\Repositories\Admin\PlanRepository'` |
| [Inc/Controller/Base/ShortcodeHandler.php:6](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/ShortcodeHandler.php#L6) | class_exists | `'SWBDPCShortcodeHandler'` |
| [Inc/View/AdminCallbacks.php:7](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/View/AdminCallbacks.php#L7) | class_exists | `'SWBDPCAdminCallbacks'` |
| [Inc/View/AdminCallbacks.php:215](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/View/AdminCallbacks.php#L215) | function_exists | `'directorist_pricing_plans_singleton'` |
| [Inc/View/AdminCallbacks.php:215](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/View/AdminCallbacks.php#L215) | class_exists | `'DirectoristPricingPlan\App\Repositories\Admin\PlanRepository'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
