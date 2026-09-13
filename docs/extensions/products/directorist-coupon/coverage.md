# Feature and infrastructure coverage

Canonical snapshot: `coupon--development`.

Feature-linked means declarations/file boundaries are connected to an authored user workflow/topic. It does not prove every conditional path was manually audited. Infrastructure is explicitly accounted for rather than presented as another supported feature. Support/generated/vendor-library files are not runtime proof.

Counts: feature-linked=11, infrastructure=7, support-or-generated=3.

Unresolved feature candidates: 0.

| Source | Classification | Topic or reason |
| --- | --- | --- |
| [.gitignore:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/.gitignore#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [directorist-coupon.php:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/directorist-coupon.php#L1) | feature-linked | [discount-rules](topics/discount-rules.md), [gateway-usage](topics/gateway-usage.md) |
| [includes.php:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/includes.php#L1) | infrastructure | Bootstrap, shared helper, configuration, enqueue or update dependency; linked from identity/contracts rather than a separate user feature. |
| [index.php:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/index.php#L1) | infrastructure | Plugin bootstrap/header or shared root configuration; inspect include/registration chain. |
| [Inc/Controller/Admin/CustomWpListTable.php:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Admin/CustomWpListTable.php#L1) | feature-linked | [discount-rules](topics/discount-rules.md) |
| [Inc/Controller/Admin/EDD_SL_Plugin_Updater.php:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Admin/EDD_SL_Plugin_Updater.php#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [Inc/Controller/Admin/SWBDPCouponCPT.php:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Admin/SWBDPCouponCPT.php#L1) | feature-linked | [discount-rules](topics/discount-rules.md) |
| [Inc/Controller/Base/Activate.php:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/Activate.php#L1) | infrastructure | Registration/framework/test provider; active reachability must be verified against bootstrap. |
| [Inc/Controller/Base/CouponHandler.php:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/CouponHandler.php#L1) | feature-linked | [discount-rules](topics/discount-rules.md), [gateway-usage](topics/gateway-usage.md) |
| [Inc/Controller/Base/Enqueue.php:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/Enqueue.php#L1) | infrastructure | Bootstrap, shared helper, configuration, enqueue or update dependency; linked from identity/contracts rather than a separate user feature. |
| [Inc/Controller/Base/ExtensionSettings.php:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/ExtensionSettings.php#L1) | feature-linked | [discount-rules](topics/discount-rules.md) |
| [Inc/Controller/Base/HelperFunctions.php:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/HelperFunctions.php#L1) | feature-linked | [discount-rules](topics/discount-rules.md) |
| [Inc/Controller/Base/ShortcodeHandler.php:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/ShortcodeHandler.php#L1) | feature-linked | [discount-rules](topics/discount-rules.md) |
| [Inc/View/AdminCallbacks.php:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/View/AdminCallbacks.php#L1) | feature-linked | [discount-rules](topics/discount-rules.md) |
| [Inc/View/coupon-input-field.php:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/View/coupon-input-field.php#L1) | feature-linked | [discount-rules](topics/discount-rules.md) |
| [assets/admin/css/swbdpc-admin.css:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/assets/admin/css/swbdpc-admin.css#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [assets/admin/js/swbdpc-admin.js:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/assets/admin/js/swbdpc-admin.js#L1) | feature-linked | [discount-rules](topics/discount-rules.md) |
| [assets/frontend/css/swbdpc-frontend-rtl.css:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/assets/frontend/css/swbdpc-frontend-rtl.css#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [assets/frontend/css/swbdpc-frontend.css:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/assets/frontend/css/swbdpc-frontend.css#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [assets/frontend/js/swbdpc-frontend-ajax.js:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/assets/frontend/js/swbdpc-frontend-ajax.js#L1) | feature-linked | [discount-rules](topics/discount-rules.md) |
| [languages/directorist-coupon.pot:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/languages/directorist-coupon.pot#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
