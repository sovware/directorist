# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |
| [composer.json:1](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/composer.json#L1) | php | >=7.4 |
| [composer.json:1](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/composer.json#L1) | wpmvc/framework | 1.2.01 |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [directorist-authorize-net.php:3](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/directorist-authorize-net.php#L3) | defined | `'ABSPATH'` |
| [app/AuthorizeNet.php:5](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/app/AuthorizeNet.php#L5) | defined | `'ABSPATH'` |
| [app/Helpers/helper.php:3](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/app/Helpers/helper.php#L3) | defined | `'ABSPATH'` |
| [app/Helpers/helper.php:90](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/app/Helpers/helper.php#L90) | wp_remote_post | `$api_url` |
| [app/Helpers/helper.php:107](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/app/Helpers/helper.php#L107) | wp_remote_retrieve_response_code | `$response` |
| [app/Helpers/helper.php:113](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/app/Helpers/helper.php#L113) | wp_remote_retrieve_body | `$response` |
| [app/Http/Controllers/CheckoutController.php:5](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/app/Http/Controllers/CheckoutController.php#L5) | defined | `'ABSPATH'` |
| [app/Providers/CheckoutServiceProvider.php:5](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/app/Providers/CheckoutServiceProvider.php#L5) | defined | `'ABSPATH'` |
| [app/Providers/DirectoristServiceProvider.php:5](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/app/Providers/DirectoristServiceProvider.php#L5) | defined | `'ABSPATH'` |
| [app/Providers/Admin/SettingsServiceProvider.php:5](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/app/Providers/Admin/SettingsServiceProvider.php#L5) | defined | `'ABSPATH'` |
| [config/app.php:3](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/config/app.php#L3) | defined | `'ABSPATH'` |
| [enqueues/admin-enqueue.php:5](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/enqueues/admin-enqueue.php#L5) | defined | `'ABSPATH'` |
| [enqueues/frontend-enqueue.php:3](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/enqueues/frontend-enqueue.php#L3) | defined | `'ABSPATH'` |
| [resources/views/index.php:3](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/resources/views/index.php#L3) | defined | `'ABSPATH'` |
| [routes/rest/api.php:3](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/routes/rest/api.php#L3) | defined | `'ABSPATH'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
