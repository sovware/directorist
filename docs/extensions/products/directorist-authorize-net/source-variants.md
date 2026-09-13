# Source variants

Canonical means the investigation starting point selected from actual branch/file evidence, not a released build. A newer commit date identifies a candidate; compare changed files before choosing an issue-specific owner.

| Snapshot | Branch / commit | File delta versus baseline | Evidence |
| --- | --- | --- | --- |
| authorize-net--alpha | alpha / cc402159fa1d088d0acdbbd0eabc37a57845c59f | 612 | [machine index](evidence/authorize-net--alpha.json) |

## authorize-net--alpha differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- [app/API/TwoCheckoutAPI.php:1](https://github.com/sovware/directorist-authorize-net/blob/cc402159fa1d088d0acdbbd0eabc37a57845c59f/app/API/TwoCheckoutAPI.php#L1)
- `app/AuthorizeNet.php` (absent in this snapshot)
- `app/Helpers/helper.php` (absent in this snapshot)
- [app/Hooks/Hooks.php:1](https://github.com/sovware/directorist-authorize-net/blob/cc402159fa1d088d0acdbbd0eabc37a57845c59f/app/Hooks/Hooks.php#L1)
- `app/Http/Controllers/CheckoutController.php` (absent in this snapshot)
- `app/Http/Controllers/Controller.php` (absent in this snapshot)
- `app/Http/Controllers/UserController.php` (absent in this snapshot)
- `app/Http/Middleware/EnsureIsUserAdmin.php` (absent in this snapshot)
- `app/Models/Post.php` (absent in this snapshot)
- `app/Models/PostMeta.php` (absent in this snapshot)
- `app/Models/User.php` (absent in this snapshot)
- `app/Models/UserMeta.php` (absent in this snapshot)
- `app/Providers/Admin/SettingsServiceProvider.php` (absent in this snapshot)
- `app/Providers/CheckoutServiceProvider.php` (absent in this snapshot)
- `app/Providers/DirectoristServiceProvider.php` (absent in this snapshot)
- `app/Providers/MenuServiceProvider.php` (absent in this snapshot)
- [app/Setup/APP_Settings.php:1](https://github.com/sovware/directorist-authorize-net/blob/cc402159fa1d088d0acdbbd0eabc37a57845c59f/app/Setup/APP_Settings.php#L1)
- [app/Setup/Enqueue.php:1](https://github.com/sovware/directorist-authorize-net/blob/cc402159fa1d088d0acdbbd0eabc37a57845c59f/app/Setup/Enqueue.php#L1)
- [app/Shortcodes/Authorize_Gateway_Checkout.php:1](https://github.com/sovware/directorist-authorize-net/blob/cc402159fa1d088d0acdbbd0eabc37a57845c59f/app/Shortcodes/Authorize_Gateway_Checkout.php#L1)
- [app/Shortcodes/Shortcodes.php:1](https://github.com/sovware/directorist-authorize-net/blob/cc402159fa1d088d0acdbbd0eabc37a57845c59f/app/Shortcodes/Shortcodes.php#L1)
- [app/base.php:1](https://github.com/sovware/directorist-authorize-net/blob/cc402159fa1d088d0acdbbd0eabc37a57845c59f/app/base.php#L1)
- [assets/css/card.css:1](https://github.com/sovware/directorist-authorize-net/blob/cc402159fa1d088d0acdbbd0eabc37a57845c59f/assets/css/card.css#L1)
- [assets/css/main.css:1](https://github.com/sovware/directorist-authorize-net/blob/cc402159fa1d088d0acdbbd0eabc37a57845c59f/assets/css/main.css#L1)
- [assets/js/card-alt.js:1](https://github.com/sovware/directorist-authorize-net/blob/cc402159fa1d088d0acdbbd0eabc37a57845c59f/assets/js/card-alt.js#L1)
- [assets/js/card.js:1](https://github.com/sovware/directorist-authorize-net/blob/cc402159fa1d088d0acdbbd0eabc37a57845c59f/assets/js/card.js#L1)
- [assets/js/main.js:1](https://github.com/sovware/directorist-authorize-net/blob/cc402159fa1d088d0acdbbd0eabc37a57845c59f/assets/js/main.js#L1)
- `composer.json` (absent in this snapshot)
- `config/app.php` (absent in this snapshot)
- [const.php:1](https://github.com/sovware/directorist-authorize-net/blob/cc402159fa1d088d0acdbbd0eabc37a57845c59f/const.php#L1)
- `database/Migrations/TestMigration.php` (absent in this snapshot)
- [directorist-authorize-net.php:1](https://github.com/sovware/directorist-authorize-net/blob/cc402159fa1d088d0acdbbd0eabc37a57845c59f/directorist-authorize-net.php#L1)
- `enqueues/admin-enqueue.php` (absent in this snapshot)
- `enqueues/frontend-enqueue.php` (absent in this snapshot)
- [helpers/const-helper.php:1](https://github.com/sovware/directorist-authorize-net/blob/cc402159fa1d088d0acdbbd0eabc37a57845c59f/helpers/const-helper.php#L1)
- [helpers/helpers.php:1](https://github.com/sovware/directorist-authorize-net/blob/cc402159fa1d088d0acdbbd0eabc37a57845c59f/helpers/helpers.php#L1)
- [package-lock.json:1](https://github.com/sovware/directorist-authorize-net/blob/cc402159fa1d088d0acdbbd0eabc37a57845c59f/package-lock.json#L1)
- `package.json` (absent in this snapshot)
- `resources/js/app.js` (absent in this snapshot)
- `resources/sass/app.scss` (absent in this snapshot)
- `resources/views/index.php` (absent in this snapshot)
- `routes/ajax/api.php` (absent in this snapshot)
- `routes/rest/api.php` (absent in this snapshot)
- [templates/directorist-authorize-gateway.php:1](https://github.com/sovware/directorist-authorize-net/blob/cc402159fa1d088d0acdbbd0eabc37a57845c59f/templates/directorist-authorize-gateway.php#L1)
| authorize-net-new--master | master / 8ed560d5ddebb4d72e7f4a4237c6324dc1f873dd | 16 | [machine index](evidence/authorize-net-new--master.json) |

## authorize-net-new--master differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- [app/AuthorizeNet.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/8ed560d5ddebb4d72e7f4a4237c6324dc1f873dd/app/AuthorizeNet.php#L1)
- [app/Helpers/helper.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/8ed560d5ddebb4d72e7f4a4237c6324dc1f873dd/app/Helpers/helper.php#L1)
- [app/Http/Controllers/CheckoutController.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/8ed560d5ddebb4d72e7f4a4237c6324dc1f873dd/app/Http/Controllers/CheckoutController.php#L1)
- [app/Http/Controllers/PaymentController.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/8ed560d5ddebb4d72e7f4a4237c6324dc1f873dd/app/Http/Controllers/PaymentController.php#L1)
- [app/Providers/Admin/DirectoristServiceProvider.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/8ed560d5ddebb4d72e7f4a4237c6324dc1f873dd/app/Providers/Admin/DirectoristServiceProvider.php#L1)
- [app/Providers/Admin/LicenseServiceProvider.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/8ed560d5ddebb4d72e7f4a4237c6324dc1f873dd/app/Providers/Admin/LicenseServiceProvider.php#L1)
- [app/Providers/Admin/SettingsServiceProvider.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/8ed560d5ddebb4d72e7f4a4237c6324dc1f873dd/app/Providers/Admin/SettingsServiceProvider.php#L1)
- [app/Providers/CheckoutServiceProvider.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/8ed560d5ddebb4d72e7f4a4237c6324dc1f873dd/app/Providers/CheckoutServiceProvider.php#L1)
- `app/Providers/DirectoristServiceProvider.php` (absent in this snapshot)
- [app/Router/PaymentRouter.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/8ed560d5ddebb4d72e7f4a4237c6324dc1f873dd/app/Router/PaymentRouter.php#L1)
- [composer.json:1](https://github.com/sovware/directorist-authorize-net-new/blob/8ed560d5ddebb4d72e7f4a4237c6324dc1f873dd/composer.json#L1)
- [config/app.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/8ed560d5ddebb4d72e7f4a4237c6324dc1f873dd/config/app.php#L1)
- [directorist-authorize-net.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/8ed560d5ddebb4d72e7f4a4237c6324dc1f873dd/directorist-authorize-net.php#L1)
- [enqueues/admin-enqueue.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/8ed560d5ddebb4d72e7f4a4237c6324dc1f873dd/enqueues/admin-enqueue.php#L1)
- [routes/rest/api.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/8ed560d5ddebb4d72e7f4a4237c6324dc1f873dd/routes/rest/api.php#L1)
| authorize-net-new--development | development / ab7986313aa349c4cea2d9956169ae2d07da4fd0 | 0 | [machine index](evidence/authorize-net-new--development.json) |

## Visible remote branch tips

All returned refs are recorded in [branch inventory](../../branches.json). The selected snapshots cover latest tip, development where distinct, catalog-named default, payment-generation variants and relevant local files. Other branch tips remain searchable candidates; they are not silently folded into the baseline.

Local snapshots include file hashes and dirty-state metadata. Local generated assets are inventoried but not interpreted as source-equivalent builds. Re-run source drift checks before reusing a mapping.
