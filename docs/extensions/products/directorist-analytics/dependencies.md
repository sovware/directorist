# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |
| [composer.json:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/composer.json#L1) | php | >=7.4 |
| [composer.json:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/composer.json#L1) | wpmvc/framework | 1.2.01 |
| [package.json:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/package.json#L1) | @emotion/react | ^11.14.0 |
| [package.json:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/package.json#L1) | @emotion/styled | ^11.14.1 |
| [package.json:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/package.json#L1) | @mui/material | ^7.3.4 |
| [package.json:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/package.json#L1) | @mui/x-date-pickers | ^8.15.0 |
| [package.json:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/package.json#L1) | @react-pdf/renderer | ^4.3.1 |
| [package.json:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/package.json#L1) | @wordpress/components | ^28.11.0 |
| [package.json:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/package.json#L1) | @wordpress/element | ^6.11.0 |
| [package.json:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/package.json#L1) | @wordpress/i18n | ^5.11.0 |
| [package.json:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/package.json#L1) | apexcharts | ^3.45.0 |
| [package.json:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/package.json#L1) | axios | ^1.13.1 |
| [package.json:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/package.json#L1) | country-flag-icons | ^1.5.21 |
| [package.json:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/package.json#L1) | dayjs | ^1.11.18 |
| [package.json:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/package.json#L1) | react-apexcharts | 1.4.1 |
| [package.json:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/package.json#L1) | react-csv | ^2.2.2 |
| [package.json:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/package.json#L1) | react-multi-date-picker | ^4.5.2 |
| [package.json:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/package.json#L1) | react-svg-worldmap | 2.0.0-alpha.16 |
| [package.json:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/package.json#L1) | styled-components | ^6.1.8 |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [directorist-analytics.php:3](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/directorist-analytics.php#L3) | defined | `'ABSPATH'` |
| [app/Helpers/helper.php:3](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Helpers/helper.php#L3) | defined | `'ABSPATH'` |
| [app/Hooks/Analytics.php:14](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Hooks/Analytics.php#L14) | defined | `'ABSPATH'` |
| [app/Hooks/Cron.php:6](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Hooks/Cron.php#L6) | defined | `'ABSPATH'` |
| [app/Http/Controllers/AnalyticsController.php:14](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Http/Controllers/AnalyticsController.php#L14) | defined | `'ABSPATH'` |
| [app/Http/Controllers/Controller.php:14](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Http/Controllers/Controller.php#L14) | defined | `'ABSPATH'` |
| [app/Http/Controllers/ExportController.php:14](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Http/Controllers/ExportController.php#L14) | defined | `'ABSPATH'` |
| [app/Http/Controllers/TrackingController.php:14](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Http/Controllers/TrackingController.php#L14) | defined | `'ABSPATH'` |
| [app/Http/Controllers/UserController.php:5](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Http/Controllers/UserController.php#L5) | defined | `'ABSPATH'` |
| [app/Http/Middleware/EnsureIsUserAdmin.php:5](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Http/Middleware/EnsureIsUserAdmin.php#L5) | defined | `'ABSPATH'` |
| [app/Models/Activity.php:14](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Models/Activity.php#L14) | defined | `'ABSPATH'` |
| [app/Models/Click.php:14](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Models/Click.php#L14) | defined | `'ABSPATH'` |
| [app/Models/CountryStatDaily.php:14](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Models/CountryStatDaily.php#L14) | defined | `'ABSPATH'` |
| [app/Models/DailyStat.php:14](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Models/DailyStat.php#L14) | defined | `'ABSPATH'` |
| [app/Models/Post.php:5](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Models/Post.php#L5) | defined | `'ABSPATH'` |
| [app/Models/PostMeta.php:5](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Models/PostMeta.php#L5) | defined | `'ABSPATH'` |
| [app/Models/ReferralStatDaily.php:14](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Models/ReferralStatDaily.php#L14) | defined | `'ABSPATH'` |
| [app/Models/Search.php:14](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Models/Search.php#L14) | defined | `'ABSPATH'` |
| [app/Models/SearchEngineStatDaily.php:14](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Models/SearchEngineStatDaily.php#L14) | defined | `'ABSPATH'` |
| [app/Models/TechStatDaily.php:14](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Models/TechStatDaily.php#L14) | defined | `'ABSPATH'` |
| [app/Models/User.php:5](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Models/User.php#L5) | defined | `'ABSPATH'` |
| [app/Models/UserMeta.php:5](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Models/UserMeta.php#L5) | defined | `'ABSPATH'` |
| [app/Models/View.php:14](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Models/View.php#L14) | defined | `'ABSPATH'` |
| [app/Providers/AnalyticsServiceProvider.php:13](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Providers/AnalyticsServiceProvider.php#L13) | defined | `'ABSPATH'` |
| [app/Providers/AnalyticsServiceProvider.php:34](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Providers/AnalyticsServiceProvider.php#L34) | class_exists | `'Directorist_Base'` |
| [app/Providers/MenuServiceProvider.php:5](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Providers/MenuServiceProvider.php#L5) | defined | `'ABSPATH'` |
| [app/Repositories/AdminRepository.php:14](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Repositories/AdminRepository.php#L14) | defined | `'ABSPATH'` |
| [app/Repositories/AnalyticsRepository.php:14](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Repositories/AnalyticsRepository.php#L14) | defined | `'ABSPATH'` |
| [app/Repositories/AuthorRepository.php:14](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Repositories/AuthorRepository.php#L14) | defined | `'ABSPATH'` |
| [app/Repositories/ExportRepository.php:14](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Repositories/ExportRepository.php#L14) | defined | `'ABSPATH'` |
| [app/Repositories/OverviewRepository.php:14](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Repositories/OverviewRepository.php#L14) | defined | `'ABSPATH'` |
| [app/Services/ActivityService.php:14](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Services/ActivityService.php#L14) | defined | `'ABSPATH'` |
| [app/Services/AuthorLoginService.php:14](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Services/AuthorLoginService.php#L14) | defined | `'ABSPATH'` |
| [app/Services/PrivacyService.php:4](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Services/PrivacyService.php#L4) | defined | `'ABSPATH'` |
| [app/Services/StatsService.php:14](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Services/StatsService.php#L14) | defined | `'ABSPATH'` |
| [app/Services/TrackingService.php:14](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Services/TrackingService.php#L14) | defined | `'ABSPATH'` |
| [app/Services/TrackingService.php:344](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Services/TrackingService.php#L344) | wp_remote_get | `"http://ip-api.com/json/{$ip` |
| [app/Services/TrackingService.php:346](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Services/TrackingService.php#L346) | wp_remote_retrieve_response_code | `$response` |
| [app/Services/TrackingService.php:347](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Services/TrackingService.php#L347) | wp_remote_retrieve_body | `$response` |
| [config/app.php:3](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/config/app.php#L3) | defined | `'ABSPATH'` |
| [database/Setup.php:14](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/database/Setup.php#L14) | defined | `'ABSPATH'` |
| [database/Migrations/TestMigration.php:5](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/database/Migrations/TestMigration.php#L5) | defined | `'ABSPATH'` |
| [enqueues/admin-enqueue.php:5](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/enqueues/admin-enqueue.php#L5) | defined | `'ABSPATH'` |
| [enqueues/admin-enqueue.php:10](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/enqueues/admin-enqueue.php#L10) | defined | `'ABSPATH'` |
| [enqueues/frontend-enqueue.php:3](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/enqueues/frontend-enqueue.php#L3) | defined | `'ABSPATH'` |
| [resources/views/index.php:3](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/resources/views/index.php#L3) | defined | `'ABSPATH'` |
| [resources/views/admin/analytics.php:8](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/resources/views/admin/analytics.php#L8) | defined | `'ABSPATH'` |
| [routes/ajax/api.php:3](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/routes/ajax/api.php#L3) | defined | `'ABSPATH'` |
| [routes/rest/api.php:11](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/routes/rest/api.php#L11) | defined | `'ABSPATH'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
