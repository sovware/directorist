# Source variants

Canonical means the investigation starting point selected from actual branch/file evidence, not a released build. A newer commit date identifies a candidate; compare changed files before choosing an issue-specific owner.

| Snapshot | Branch / commit | File delta versus baseline | Evidence |
| --- | --- | --- | --- |
| stripe--alpha | alpha / 6621828d9236c4dc458393ed17fedbdce4056f66 | 289 | [machine index](evidence/stripe--alpha.json) |

## stripe--alpha differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- `app/Helpers/helper.php` (absent in this snapshot)
- `app/Http/Controllers/CheckoutController.php` (absent in this snapshot)
- `app/Http/Controllers/Controller.php` (absent in this snapshot)
- `app/Http/Controllers/LegacyWebhookController.php` (absent in this snapshot)
- `app/Http/Controllers/UserController.php` (absent in this snapshot)
- `app/Http/Controllers/WebhookController.php` (absent in this snapshot)
- `app/Http/Middleware/EnsureIsUserAdmin.php` (absent in this snapshot)
- `app/Models/Post.php` (absent in this snapshot)
- `app/Models/PostMeta.php` (absent in this snapshot)
- `app/Models/User.php` (absent in this snapshot)
- `app/Models/UserMeta.php` (absent in this snapshot)
- `app/Providers/Admin/LicenseServiceProvider.php` (absent in this snapshot)
- `app/Providers/Admin/SettingsServiceProvider.php` (absent in this snapshot)
- `app/Providers/Admin/UpdateServiceProvider.php` (absent in this snapshot)
- `app/Providers/CheckoutServiceProvider.php` (absent in this snapshot)
- `app/Providers/DirectoristServiceProvider.php` (absent in this snapshot)
- `app/Providers/MenuServiceProvider.php` (absent in this snapshot)
- `app/Stripe.php` (absent in this snapshot)
- [assets/admin/main-rtl.css:1](https://github.com/sovware/directorist-stripe/blob/6621828d9236c4dc458393ed17fedbdce4056f66/assets/admin/main-rtl.css#L1)
- [assets/admin/main.css:1](https://github.com/sovware/directorist-stripe/blob/6621828d9236c4dc458393ed17fedbdce4056f66/assets/admin/main.css#L1)
- [assets/admin/main.js:1](https://github.com/sovware/directorist-stripe/blob/6621828d9236c4dc458393ed17fedbdce4056f66/assets/admin/main.js#L1)
- [assets/css/directorist-stripe-rtl.css:1](https://github.com/sovware/directorist-stripe/blob/6621828d9236c4dc458393ed17fedbdce4056f66/assets/css/directorist-stripe-rtl.css#L1)
- [assets/css/directorist-stripe.css:1](https://github.com/sovware/directorist-stripe/blob/6621828d9236c4dc458393ed17fedbdce4056f66/assets/css/directorist-stripe.css#L1)
- [assets/js/common.js:1](https://github.com/sovware/directorist-stripe/blob/6621828d9236c4dc458393ed17fedbdce4056f66/assets/js/common.js#L1)
- [assets/js/directorist-stripe.js:1](https://github.com/sovware/directorist-stripe/blob/6621828d9236c4dc458393ed17fedbdce4056f66/assets/js/directorist-stripe.js#L1)
- [composer.json:1](https://github.com/sovware/directorist-stripe/blob/6621828d9236c4dc458393ed17fedbdce4056f66/composer.json#L1)
- `config/app.php` (absent in this snapshot)
- [const-helper.php:1](https://github.com/sovware/directorist-stripe/blob/6621828d9236c4dc458393ed17fedbdce4056f66/const-helper.php#L1)
- [constants.php:1](https://github.com/sovware/directorist-stripe/blob/6621828d9236c4dc458393ed17fedbdce4056f66/constants.php#L1)
- `database/Migrations/TestMigration.php` (absent in this snapshot)
- [directorist-stripe.php:1](https://github.com/sovware/directorist-stripe/blob/6621828d9236c4dc458393ed17fedbdce4056f66/directorist-stripe.php#L1)
- `enqueues/admin-enqueue.php` (absent in this snapshot)
- `enqueues/frontend-enqueue.php` (absent in this snapshot)
- [helper.php:1](https://github.com/sovware/directorist-stripe/blob/6621828d9236c4dc458393ed17fedbdce4056f66/helper.php#L1)
- [index.php:1](https://github.com/sovware/directorist-stripe/blob/6621828d9236c4dc458393ed17fedbdce4056f66/index.php#L1)
- `package.json` (absent in this snapshot)
- `resources/js/index.js` (absent in this snapshot)
- `resources/sass/app.scss` (absent in this snapshot)
- `resources/views/index.php` (absent in this snapshot)
- `routes/ajax/api.php` (absent in this snapshot)
- `routes/rest/api.php` (absent in this snapshot)
- [stripe-php-sdk/build.php:1](https://github.com/sovware/directorist-stripe/blob/6621828d9236c4dc458393ed17fedbdce4056f66/stripe-php-sdk/build.php#L1)
- [stripe-php-sdk/composer.json:1](https://github.com/sovware/directorist-stripe/blob/6621828d9236c4dc458393ed17fedbdce4056f66/stripe-php-sdk/composer.json#L1)
- [stripe-php-sdk/init.php:1](https://github.com/sovware/directorist-stripe/blob/6621828d9236c4dc458393ed17fedbdce4056f66/stripe-php-sdk/init.php#L1)
- [stripe-php-sdk/update_certs.php:1](https://github.com/sovware/directorist-stripe/blob/6621828d9236c4dc458393ed17fedbdce4056f66/stripe-php-sdk/update_certs.php#L1)
| stripe--feature-sepa-direct-debit-support | feature/sepa-direct-debit-support / a0bd8e43a74c9cc6a2d5ba54a07e3ceb9a4c7c5c | 289 | [machine index](evidence/stripe--feature-sepa-direct-debit-support.json) |

## stripe--feature-sepa-direct-debit-support differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- `app/Helpers/helper.php` (absent in this snapshot)
- `app/Http/Controllers/CheckoutController.php` (absent in this snapshot)
- `app/Http/Controllers/Controller.php` (absent in this snapshot)
- `app/Http/Controllers/LegacyWebhookController.php` (absent in this snapshot)
- `app/Http/Controllers/UserController.php` (absent in this snapshot)
- `app/Http/Controllers/WebhookController.php` (absent in this snapshot)
- `app/Http/Middleware/EnsureIsUserAdmin.php` (absent in this snapshot)
- `app/Models/Post.php` (absent in this snapshot)
- `app/Models/PostMeta.php` (absent in this snapshot)
- `app/Models/User.php` (absent in this snapshot)
- `app/Models/UserMeta.php` (absent in this snapshot)
- `app/Providers/Admin/LicenseServiceProvider.php` (absent in this snapshot)
- `app/Providers/Admin/SettingsServiceProvider.php` (absent in this snapshot)
- `app/Providers/Admin/UpdateServiceProvider.php` (absent in this snapshot)
- `app/Providers/CheckoutServiceProvider.php` (absent in this snapshot)
- `app/Providers/DirectoristServiceProvider.php` (absent in this snapshot)
- `app/Providers/MenuServiceProvider.php` (absent in this snapshot)
- `app/Stripe.php` (absent in this snapshot)
- [assets/admin/main-rtl.css:1](https://github.com/sovware/directorist-stripe/blob/a0bd8e43a74c9cc6a2d5ba54a07e3ceb9a4c7c5c/assets/admin/main-rtl.css#L1)
- [assets/admin/main.css:1](https://github.com/sovware/directorist-stripe/blob/a0bd8e43a74c9cc6a2d5ba54a07e3ceb9a4c7c5c/assets/admin/main.css#L1)
- [assets/admin/main.js:1](https://github.com/sovware/directorist-stripe/blob/a0bd8e43a74c9cc6a2d5ba54a07e3ceb9a4c7c5c/assets/admin/main.js#L1)
- [assets/css/directorist-stripe-rtl.css:1](https://github.com/sovware/directorist-stripe/blob/a0bd8e43a74c9cc6a2d5ba54a07e3ceb9a4c7c5c/assets/css/directorist-stripe-rtl.css#L1)
- [assets/css/directorist-stripe.css:1](https://github.com/sovware/directorist-stripe/blob/a0bd8e43a74c9cc6a2d5ba54a07e3ceb9a4c7c5c/assets/css/directorist-stripe.css#L1)
- [assets/js/common.js:1](https://github.com/sovware/directorist-stripe/blob/a0bd8e43a74c9cc6a2d5ba54a07e3ceb9a4c7c5c/assets/js/common.js#L1)
- [assets/js/directorist-stripe.js:1](https://github.com/sovware/directorist-stripe/blob/a0bd8e43a74c9cc6a2d5ba54a07e3ceb9a4c7c5c/assets/js/directorist-stripe.js#L1)
- [composer.json:1](https://github.com/sovware/directorist-stripe/blob/a0bd8e43a74c9cc6a2d5ba54a07e3ceb9a4c7c5c/composer.json#L1)
- `config/app.php` (absent in this snapshot)
- [const-helper.php:1](https://github.com/sovware/directorist-stripe/blob/a0bd8e43a74c9cc6a2d5ba54a07e3ceb9a4c7c5c/const-helper.php#L1)
- [constants.php:1](https://github.com/sovware/directorist-stripe/blob/a0bd8e43a74c9cc6a2d5ba54a07e3ceb9a4c7c5c/constants.php#L1)
- `database/Migrations/TestMigration.php` (absent in this snapshot)
- [directorist-stripe.php:1](https://github.com/sovware/directorist-stripe/blob/a0bd8e43a74c9cc6a2d5ba54a07e3ceb9a4c7c5c/directorist-stripe.php#L1)
- `enqueues/admin-enqueue.php` (absent in this snapshot)
- `enqueues/frontend-enqueue.php` (absent in this snapshot)
- [helper.php:1](https://github.com/sovware/directorist-stripe/blob/a0bd8e43a74c9cc6a2d5ba54a07e3ceb9a4c7c5c/helper.php#L1)
- [index.php:1](https://github.com/sovware/directorist-stripe/blob/a0bd8e43a74c9cc6a2d5ba54a07e3ceb9a4c7c5c/index.php#L1)
- `package.json` (absent in this snapshot)
- `resources/js/index.js` (absent in this snapshot)
- `resources/sass/app.scss` (absent in this snapshot)
- `resources/views/index.php` (absent in this snapshot)
- `routes/ajax/api.php` (absent in this snapshot)
- `routes/rest/api.php` (absent in this snapshot)
- [stripe-php-sdk/build.php:1](https://github.com/sovware/directorist-stripe/blob/a0bd8e43a74c9cc6a2d5ba54a07e3ceb9a4c7c5c/stripe-php-sdk/build.php#L1)
- [stripe-php-sdk/composer.json:1](https://github.com/sovware/directorist-stripe/blob/a0bd8e43a74c9cc6a2d5ba54a07e3ceb9a4c7c5c/stripe-php-sdk/composer.json#L1)
- [stripe-php-sdk/init.php:1](https://github.com/sovware/directorist-stripe/blob/a0bd8e43a74c9cc6a2d5ba54a07e3ceb9a4c7c5c/stripe-php-sdk/init.php#L1)
- [stripe-php-sdk/update_certs.php:1](https://github.com/sovware/directorist-stripe/blob/a0bd8e43a74c9cc6a2d5ba54a07e3ceb9a4c7c5c/stripe-php-sdk/update_certs.php#L1)
| stripe--local | unversioned / unversioned-local | 37 | [machine index](evidence/stripe--local.json) |

## stripe--local differences

These paths differ from the canonical baseline; direction/semantic impact requires reading the diff.
- [app/Http/Controllers/CheckoutController.php:1](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-stripe/app/Http/Controllers/CheckoutController.php:1>)
- `app/Http/Controllers/LegacyWebhookController.php` (absent in this snapshot)
- [app/Http/Controllers/WebhookController.php:1](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-stripe/app/Http/Controllers/WebhookController.php:1>)
- [app/Providers/CheckoutServiceProvider.php:1](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-stripe/app/Providers/CheckoutServiceProvider.php:1>)
- [app/Stripe.php:1](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-stripe/app/Stripe.php:1>)
- `composer.json` (absent in this snapshot)
- [directorist-stripe.php:1](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-stripe/directorist-stripe.php:1>)
- `package.json` (absent in this snapshot)
- `resources/js/index.js` (absent in this snapshot)
- `resources/sass/app.scss` (absent in this snapshot)
- [routes/rest/api.php:1](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-stripe/routes/rest/api.php:1>)
| stripe-new--master | master / 498c48755f4168fdfdcaa955fb817647287a95e1 | 0 | [machine index](evidence/stripe-new--master.json) |
| stripe-new--development | development / 6dbd9a3edc3b7453fb34f84a31ee6efebe90752d | 0 | [machine index](evidence/stripe-new--development.json) |

## Visible remote branch tips

All returned refs are recorded in [branch inventory](../../branches.json). The selected snapshots cover latest tip, development where distinct, catalog-named default, payment-generation variants and relevant local files. Other branch tips remain searchable candidates; they are not silently folded into the baseline.

Local snapshots include file hashes and dirty-state metadata. Local generated assets are inventoried but not interpreted as source-equivalent builds. Re-run source drift checks before reusing a mapping.
