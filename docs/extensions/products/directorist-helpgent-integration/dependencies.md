# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |
| [composer.json:1](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/composer.json#L1) | php | >=7.4 |
| [composer.json:1](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/composer.json#L1) | wpmvc/framework | 1.0.0 |
| [composer.json:1](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/composer.json#L1) | wpmvc/dependent-manager | 1.0.0 |
| [package.json:1](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/package.json#L1) | react-router-dom | ^6.17.0 |
| [package.json:1](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/package.json#L1) | styled-components | ^6.1.1 |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [directorist-helpgent-integration.php:3](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/directorist-helpgent-integration.php#L3) | defined | `'ABSPATH'` |
| [directorist-helpgent-integration.php:84](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/directorist-helpgent-integration.php#L84) | defined | `'HELPGENT_DEPENDENCY_VERSION'` |
| [app/Helpers/helper.php:3](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/app/Helpers/helper.php#L3) | defined | `'ABSPATH'` |
| [app/Helpers/helper.php:179](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/app/Helpers/helper.php#L179) | function_exists | `'is_plugin_active'` |
| [app/Helpers/helper.php:180](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/app/Helpers/helper.php#L180) | function_exists | `'helpgent_pro'` |
| [app/Helpers/helper.php:183](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/app/Helpers/helper.php#L183) | is_plugin_active | `'helpgent-pro/helpgent-pro.php'` |
| [app/Providers/MenuServiceProvider.php:271](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/app/Providers/MenuServiceProvider.php#L271) | function_exists | `"helpgent_pro"` |
| [app/Providers/MenuServiceProvider.php:292](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/app/Providers/MenuServiceProvider.php#L292) | function_exists | `"helpgent_pro"` |
| [config/app.php:3](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/config/app.php#L3) | defined | `'ABSPATH'` |
| [config/licensing.php:3](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/config/licensing.php#L3) | defined | `'ABSPATH'` |
| [enqueues/admin-enqueue.php:5](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/enqueues/admin-enqueue.php#L5) | defined | `'ABSPATH'` |
| [enqueues/frontend-enqueue.php:5](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/enqueues/frontend-enqueue.php#L5) | defined | `'ABSPATH'` |
| [enqueues/frontend-enqueue.php:10](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/enqueues/frontend-enqueue.php#L10) | function_exists | `"helpgent_pro"` |
| [resources/views/index.php:3](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/resources/views/index.php#L3) | defined | `'ABSPATH'` |
| [routes/rest/api.php:60](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/routes/rest/api.php#L60) | function_exists | `"helpgent_pro"` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
