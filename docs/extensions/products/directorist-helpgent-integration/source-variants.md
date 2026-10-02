# Source variants

Canonical means the investigation starting point selected from actual branch/file evidence, not a released build. A newer commit date identifies a candidate; compare changed files before choosing an issue-specific owner.

| Snapshot | Branch / commit | File delta versus baseline | Evidence |
| --- | --- | --- | --- |
| helpgent-integration--master | master / 681495a66d0f8072924937b8ae00e6f514ac68ab | 0 | [machine index](evidence/helpgent-integration--master.json) |
| helpgent-integration--development | development / 1637cb93e267271b00d49faa04234a5fdec0a719 | 46 | [machine index](evidence/helpgent-integration--development.json) |

## helpgent-integration--development differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- [app/Helpers/helper.php:1](https://github.com/sovware/directorist-helpgent-integration/blob/1637cb93e267271b00d49faa04234a5fdec0a719/app/Helpers/helper.php#L1)
- `app/Http/Controllers/ContactController.php` (absent in this snapshot)
- `app/Http/Controllers/FormController.php` (absent in this snapshot)
- `app/Http/Controllers/ResponseController.php` (absent in this snapshot)
- `app/Http/Controllers/SummaryController.php` (absent in this snapshot)
- `app/Http/Controllers/TagController.php` (absent in this snapshot)
- `app/Http/Controllers/UserController.php` (absent in this snapshot)
- `app/Http/Middleware/Auth.php` (absent in this snapshot)
- `app/Http/Middleware/EnsureIsUserAdmin.php` (absent in this snapshot)
- [app/Http/Middleware/ListingOwner.php:1](https://github.com/sovware/directorist-helpgent-integration/blob/1637cb93e267271b00d49faa04234a5fdec0a719/app/Http/Middleware/ListingOwner.php#L1)
- [app/Http/Middleware/ListingOwnerPro.php:1](https://github.com/sovware/directorist-helpgent-integration/blob/1637cb93e267271b00d49faa04234a5fdec0a719/app/Http/Middleware/ListingOwnerPro.php#L1)
- [app/Providers/Admin/DirectoristSettingsServiceProvider.php:1](https://github.com/sovware/directorist-helpgent-integration/blob/1637cb93e267271b00d49faa04234a5fdec0a719/app/Providers/Admin/DirectoristSettingsServiceProvider.php#L1)
- [app/Providers/MenuServiceProvider.php:1](https://github.com/sovware/directorist-helpgent-integration/blob/1637cb93e267271b00d49faa04234a5fdec0a719/app/Providers/MenuServiceProvider.php#L1)
- [app/Providers/QueryServiceProvider.php:1](https://github.com/sovware/directorist-helpgent-integration/blob/1637cb93e267271b00d49faa04234a5fdec0a719/app/Providers/QueryServiceProvider.php#L1)
- [composer.json:1](https://github.com/sovware/directorist-helpgent-integration/blob/1637cb93e267271b00d49faa04234a5fdec0a719/composer.json#L1)
- [config/app.php:1](https://github.com/sovware/directorist-helpgent-integration/blob/1637cb93e267271b00d49faa04234a5fdec0a719/config/app.php#L1)
- [directorist-helpgent-integration.php:1](https://github.com/sovware/directorist-helpgent-integration/blob/1637cb93e267271b00d49faa04234a5fdec0a719/directorist-helpgent-integration.php#L1)
- [enqueues/frontend-enqueue.php:1](https://github.com/sovware/directorist-helpgent-integration/blob/1637cb93e267271b00d49faa04234a5fdec0a719/enqueues/frontend-enqueue.php#L1)
- [package.json:1](https://github.com/sovware/directorist-helpgent-integration/blob/1637cb93e267271b00d49faa04234a5fdec0a719/package.json#L1)
- [resources/js/admin.js:1](https://github.com/sovware/directorist-helpgent-integration/blob/1637cb93e267271b00d49faa04234a5fdec0a719/resources/js/admin.js#L1)
- [resources/js/app.js:1](https://github.com/sovware/directorist-helpgent-integration/blob/1637cb93e267271b00d49faa04234a5fdec0a719/resources/js/app.js#L1)
- [resources/js/frontend.js:1](https://github.com/sovware/directorist-helpgent-integration/blob/1637cb93e267271b00d49faa04234a5fdec0a719/resources/js/frontend.js#L1)
- [resources/js/index.js:1](https://github.com/sovware/directorist-helpgent-integration/blob/1637cb93e267271b00d49faa04234a5fdec0a719/resources/js/index.js#L1)
- [resources/js/pages/FormEdit.js:1](https://github.com/sovware/directorist-helpgent-integration/blob/1637cb93e267271b00d49faa04234a5fdec0a719/resources/js/pages/FormEdit.js#L1)
- [resources/js/pages/FormTable.js:1](https://github.com/sovware/directorist-helpgent-integration/blob/1637cb93e267271b00d49faa04234a5fdec0a719/resources/js/pages/FormTable.js#L1)
- [resources/js/pages/Leads.js:1](https://github.com/sovware/directorist-helpgent-integration/blob/1637cb93e267271b00d49faa04234a5fdec0a719/resources/js/pages/Leads.js#L1)
- [resources/js/pages/PreMadeTemplate.js:1](https://github.com/sovware/directorist-helpgent-integration/blob/1637cb93e267271b00d49faa04234a5fdec0a719/resources/js/pages/PreMadeTemplate.js#L1)
- [resources/js/pages/PreMadeTemplatePreview.js:1](https://github.com/sovware/directorist-helpgent-integration/blob/1637cb93e267271b00d49faa04234a5fdec0a719/resources/js/pages/PreMadeTemplatePreview.js#L1)
- [resources/js/pages/Response.js:1](https://github.com/sovware/directorist-helpgent-integration/blob/1637cb93e267271b00d49faa04234a5fdec0a719/resources/js/pages/Response.js#L1)
- [resources/js/pages/Summary.js:1](https://github.com/sovware/directorist-helpgent-integration/blob/1637cb93e267271b00d49faa04234a5fdec0a719/resources/js/pages/Summary.js#L1)
- [resources/js/pages/Tags.js:1](https://github.com/sovware/directorist-helpgent-integration/blob/1637cb93e267271b00d49faa04234a5fdec0a719/resources/js/pages/Tags.js#L1)
- [resources/js/style.js:1](https://github.com/sovware/directorist-helpgent-integration/blob/1637cb93e267271b00d49faa04234a5fdec0a719/resources/js/style.js#L1)
- `resources/js/utils/formSettings.js` (absent in this snapshot)
- [routes/rest/api.php:1](https://github.com/sovware/directorist-helpgent-integration/blob/1637cb93e267271b00d49faa04234a5fdec0a719/routes/rest/api.php#L1)

## Visible remote branch tips

All returned refs are recorded in [branch inventory](../../branches.json). The selected snapshots cover latest tip, development where distinct, catalog-named default, payment-generation variants and relevant local files. Other branch tips remain searchable candidates; they are not silently folded into the baseline.

Local snapshots include file hashes and dirty-state metadata. Local generated assets are inventoried but not interpreted as source-equivalent builds. Re-run source drift checks before reusing a mapping.
