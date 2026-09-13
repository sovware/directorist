# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |
| [composer.json:1](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/composer.json#L1) | php | >=7.4 |
| [composer.json:1](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/composer.json#L1) | wpmvc/framework | 1.2.01 |
| [composer.json:1](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/composer.json#L1) | stripe/stripe-php | v17.7.0-beta.1 |
| [package.json:1](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/package.json#L1) | @stripe/stripe-js | ^7.3.1 |
| [package.json:1](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/package.json#L1) | @wordpress/api-fetch | ^7.24.0 |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [directorist-stripe.php:3](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/directorist-stripe.php#L3) | defined | `'ABSPATH'` |
| [directorist-stripe.php:49](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/directorist-stripe.php#L49) | class_exists | `'Directorist_Base'` |
| [directorist-stripe.php:55](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/directorist-stripe.php#L55) | defined | `'ATBDP_VERSION'` |
| [directorist-stripe.php:75](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/directorist-stripe.php#L75) | class_exists | `'Directorist\\PaymentProcessors\\Payment'` |
| [directorist-stripe.php:76](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/directorist-stripe.php#L76) | class_exists | `'Directorist\\Repositories\\PaymentRepository'` |
| [directorist-stripe.php:77](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/directorist-stripe.php#L77) | class_exists | `'Directorist\\DTO\\Order\\DTO'` |
| [directorist-stripe.php:100](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/directorist-stripe.php#L100) | defined | `'DIRECTORIST_STRIPE_FILE'` |
| [app/Stripe.php:5](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Stripe.php#L5) | defined | `"ABSPATH"` |
| [app/Stripe.php:349](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Stripe.php#L349) | function_exists | `'directorist_pricing_plans_singleton'` |
| [app/Helpers/helper.php:3](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Helpers/helper.php#L3) | defined | `'ABSPATH'` |
| [app/Helpers/helper.php:46](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Helpers/helper.php#L46) | function_exists | `'directorist_pricing_plans_singleton'` |
| [app/Http/Controllers/CheckoutController.php:5](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Http/Controllers/CheckoutController.php#L5) | defined | `'ABSPATH'` |
| [app/Http/Controllers/CheckoutController.php:558](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Http/Controllers/CheckoutController.php#L558) | function_exists | `'directorist_pricing_plans_singleton'` |
| [app/Http/Controllers/Controller.php:5](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Http/Controllers/Controller.php#L5) | defined | `'ABSPATH'` |
| [app/Http/Controllers/LegacyWebhookController.php:5](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Http/Controllers/LegacyWebhookController.php#L5) | defined | `'ABSPATH'` |
| [app/Http/Controllers/UserController.php:5](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Http/Controllers/UserController.php#L5) | defined | `'ABSPATH'` |
| [app/Http/Controllers/WebhookController.php:5](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Http/Controllers/WebhookController.php#L5) | defined | `'ABSPATH'` |
| [app/Http/Middleware/EnsureIsUserAdmin.php:5](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Http/Middleware/EnsureIsUserAdmin.php#L5) | defined | `'ABSPATH'` |
| [app/Models/Post.php:5](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Models/Post.php#L5) | defined | `'ABSPATH'` |
| [app/Models/PostMeta.php:5](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Models/PostMeta.php#L5) | defined | `'ABSPATH'` |
| [app/Models/User.php:5](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Models/User.php#L5) | defined | `'ABSPATH'` |
| [app/Models/UserMeta.php:5](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Models/UserMeta.php#L5) | defined | `'ABSPATH'` |
| [app/Providers/CheckoutServiceProvider.php:5](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Providers/CheckoutServiceProvider.php#L5) | defined | `"ABSPATH"` |
| [app/Providers/DirectoristServiceProvider.php:5](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Providers/DirectoristServiceProvider.php#L5) | defined | `"ABSPATH"` |
| [app/Providers/MenuServiceProvider.php:5](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Providers/MenuServiceProvider.php#L5) | defined | `'ABSPATH'` |
| [app/Providers/Admin/LicenseServiceProvider.php:5](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Providers/Admin/LicenseServiceProvider.php#L5) | defined | `"ABSPATH"` |
| [app/Providers/Admin/SettingsServiceProvider.php:5](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Providers/Admin/SettingsServiceProvider.php#L5) | defined | `"ABSPATH"` |
| [app/Providers/Admin/UpdateServiceProvider.php:5](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Providers/Admin/UpdateServiceProvider.php#L5) | defined | `'ABSPATH'` |
| [config/app.php:3](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/config/app.php#L3) | defined | `'ABSPATH'` |
| [database/Migrations/TestMigration.php:5](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/database/Migrations/TestMigration.php#L5) | defined | `'ABSPATH'` |
| [enqueues/admin-enqueue.php:5](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/enqueues/admin-enqueue.php#L5) | defined | `'ABSPATH'` |
| [enqueues/frontend-enqueue.php:3](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/enqueues/frontend-enqueue.php#L3) | defined | `'ABSPATH'` |
| [resources/views/index.php:3](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/resources/views/index.php#L3) | defined | `'ABSPATH'` |
| [routes/ajax/api.php:3](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/routes/ajax/api.php#L3) | defined | `'ABSPATH'` |
| [routes/rest/api.php:3](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/routes/rest/api.php#L3) | defined | `'ABSPATH'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
