# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |
| [composer.json:1](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/composer.json#L1) | php | >=7.4 |
| [composer.json:1](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/composer.json#L1) | wpmvc/framework | 1.2.01 |
| [composer.json:1](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/composer.json#L1) | paypal/paypal-server-sdk | 2.0.0 |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [directorist-paypal.php:3](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/directorist-paypal.php#L3) | defined | `'ABSPATH'` |
| [directorist-paypal.php:59](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/directorist-paypal.php#L59) | defined | `'DIRECTORIST_PAYPAL_FILE'` |
| [app/PayPal.php:5](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/PayPal.php#L5) | defined | `'ABSPATH'` |
| [app/PayPal.php:387](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/PayPal.php#L387) | function_exists | `'directorist_pricing_plans_singleton'` |
| [app/Helpers/helper.php:3](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Helpers/helper.php#L3) | defined | `'ABSPATH'` |
| [app/Helpers/helper.php:97](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Helpers/helper.php#L97) | wp_remote_post | `directorist_paypal_get_api_base_url( $is_test_mode ) . '/v1/oauth2/token'` |
| [app/Helpers/helper.php:113](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Helpers/helper.php#L113) | wp_remote_retrieve_response_code | `$response` |
| [app/Helpers/helper.php:114](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Helpers/helper.php#L114) | wp_remote_retrieve_body | `$response` |
| [app/Helpers/helper.php:173](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Helpers/helper.php#L173) | wp_remote_request | `directorist_paypal_get_api_base_url( $is_test_mode ) . $path` |
| [app/Helpers/helper.php:182](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Helpers/helper.php#L182) | wp_remote_retrieve_response_code | `$response` |
| [app/Helpers/helper.php:183](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Helpers/helper.php#L183) | wp_remote_retrieve_body | `$response` |
| [app/Helpers/helper.php:240](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Helpers/helper.php#L240) | function_exists | `'directorist_pricing_plans_singleton'` |
| [app/Http/Controllers/CheckoutController.php:5](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Http/Controllers/CheckoutController.php#L5) | defined | `'ABSPATH'` |
| [app/Http/Controllers/Controller.php:5](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Http/Controllers/Controller.php#L5) | defined | `'ABSPATH'` |
| [app/Http/Controllers/WebhookController.php:5](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Http/Controllers/WebhookController.php#L5) | defined | `'ABSPATH'` |
| [app/Providers/CheckoutServiceProvider.php:5](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Providers/CheckoutServiceProvider.php#L5) | defined | `'ABSPATH'` |
| [app/Providers/DirectoristServiceProvider.php:5](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Providers/DirectoristServiceProvider.php#L5) | defined | `'ABSPATH'` |
| [app/Providers/Admin/SettingsServiceProvider.php:5](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Providers/Admin/SettingsServiceProvider.php#L5) | defined | `'ABSPATH'` |
| [app/Providers/Admin/UpdateServiceProvider.php:5](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Providers/Admin/UpdateServiceProvider.php#L5) | defined | `'ABSPATH'` |
| [config/app.php:3](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/config/app.php#L3) | defined | `'ABSPATH'` |
| [enqueues/admin-enqueue.php:5](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/enqueues/admin-enqueue.php#L5) | defined | `'ABSPATH'` |
| [enqueues/frontend-enqueue.php:3](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/enqueues/frontend-enqueue.php#L3) | defined | `'ABSPATH'` |
| [resources/views/index.php:3](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/resources/views/index.php#L3) | defined | `'ABSPATH'` |
| [routes/rest/api.php:3](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/routes/rest/api.php#L3) | defined | `'ABSPATH'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
