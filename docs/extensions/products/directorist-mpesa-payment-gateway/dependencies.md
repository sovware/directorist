# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |
| [composer.json:1](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/composer.json#L1) | php | >=7.4 |
| [composer.json:1](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/composer.json#L1) | wpmvc/framework | ^2.0.0 |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [directorist-mpesa.php:3](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/directorist-mpesa.php#L3) | defined | `'ABSPATH'` |
| [directorist-mpesa.php:64](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/directorist-mpesa.php#L64) | class_exists | `'Directorist_Base'` |
| [directorist-mpesa.php:68](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/directorist-mpesa.php#L68) | defined | `'ATBDP_VERSION'` |
| [directorist-mpesa.php:85](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/directorist-mpesa.php#L85) | defined | `'ATBDP_VERSION'` |
| [app/MPesa.php:5](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/MPesa.php#L5) | defined | `'ABSPATH'` |
| [app/Helpers/helper.php:3](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Helpers/helper.php#L3) | defined | `'ABSPATH'` |
| [app/Helpers/helper.php:117](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Helpers/helper.php#L117) | function_exists | `'directorist_pricing_plans_singleton'` |
| [app/Helpers/helper.php:117](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Helpers/helper.php#L117) | function_exists | `'directorist_user_package_repository'` |
| [app/Helpers/helper.php:121](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Helpers/helper.php#L121) | function_exists | `'directorist_pricing_plans_version'` |
| [app/Http/Controllers/CheckoutController.php:5](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Http/Controllers/CheckoutController.php#L5) | defined | `'ABSPATH'` |
| [app/Http/Controllers/Controller.php:5](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Http/Controllers/Controller.php#L5) | defined | `'ABSPATH'` |
| [app/Http/Controllers/OAuthController.php:5](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Http/Controllers/OAuthController.php#L5) | defined | `'ABSPATH'` |
| [app/Http/Controllers/UserController.php:5](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Http/Controllers/UserController.php#L5) | defined | `'ABSPATH'` |
| [app/Http/Middleware/EnsureIsUserAdmin.php:5](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Http/Middleware/EnsureIsUserAdmin.php#L5) | defined | `'ABSPATH'` |
| [app/Models/Comment.php:5](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Models/Comment.php#L5) | defined | `'ABSPATH'` |
| [app/Models/CommentMeta.php:5](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Models/CommentMeta.php#L5) | defined | `'ABSPATH'` |
| [app/Models/Option.php:5](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Models/Option.php#L5) | defined | `'ABSPATH'` |
| [app/Models/Post.php:5](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Models/Post.php#L5) | defined | `'ABSPATH'` |
| [app/Models/PostMeta.php:5](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Models/PostMeta.php#L5) | defined | `'ABSPATH'` |
| [app/Models/Term.php:5](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Models/Term.php#L5) | defined | `'ABSPATH'` |
| [app/Models/TermMeta.php:5](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Models/TermMeta.php#L5) | defined | `'ABSPATH'` |
| [app/Models/TermTaxonomy.php:5](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Models/TermTaxonomy.php#L5) | defined | `'ABSPATH'` |
| [app/Models/User.php:5](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Models/User.php#L5) | defined | `'ABSPATH'` |
| [app/Models/UserMeta.php:5](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Models/UserMeta.php#L5) | defined | `'ABSPATH'` |
| [app/Providers/CheckoutServiceProvider.php:5](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Providers/CheckoutServiceProvider.php#L5) | defined | `'ABSPATH'` |
| [app/Providers/CronServiceProvider.php:5](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Providers/CronServiceProvider.php#L5) | defined | `'ABSPATH'` |
| [app/Providers/DirectoristServiceProvider.php:5](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Providers/DirectoristServiceProvider.php#L5) | defined | `'ABSPATH'` |
| [app/Providers/Admin/SettingsServiceProvider.php:5](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Providers/Admin/SettingsServiceProvider.php#L5) | defined | `'ABSPATH'` |
| [app/Services/MpesaApi.php:5](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaApi.php#L5) | defined | `'ABSPATH'` |
| [app/Services/MpesaApi.php:127](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaApi.php#L127) | wp_remote_get | `$url` |
| [app/Services/MpesaApi.php:151](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaApi.php#L151) | wp_remote_post | `$url` |
| [app/Services/MpesaApi.php:186](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaApi.php#L186) | wp_remote_retrieve_response_code | `$response` |
| [app/Services/MpesaApi.php:187](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaApi.php#L187) | wp_remote_retrieve_body | `$response` |
| [app/Services/MpesaService.php:5](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L5) | defined | `'ABSPATH'` |
| [config/app.php:3](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/config/app.php#L3) | defined | `'ABSPATH'` |
| [database/Setup.php:5](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/database/Setup.php#L5) | defined | `'ABSPATH'` |
| [database/Seeders/DatabaseSeeder.php:5](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/database/Seeders/DatabaseSeeder.php#L5) | defined | `"ABSPATH"` |
| [enqueues/admin-enqueue.php:5](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/enqueues/admin-enqueue.php#L5) | defined | `'ABSPATH'` |
| [enqueues/frontend-enqueue.php:3](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/enqueues/frontend-enqueue.php#L3) | defined | `'ABSPATH'` |
| [resources/views/index.php:1](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/resources/views/index.php#L1) | defined | `'ABSPATH'` |
| [resources/views/settings.php:1](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/resources/views/settings.php#L1) | defined | `'ABSPATH'` |
| [routes/ajax/api.php:3](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/routes/ajax/api.php#L3) | defined | `'ABSPATH'` |
| [routes/rest/api.php:3](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/routes/rest/api.php#L3) | defined | `'ABSPATH'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
