# Feature and infrastructure coverage

Canonical snapshot: `search-alert--main`.

Feature-linked means declarations/file boundaries are connected to an authored user workflow/topic. It does not prove every conditional path was manually audited. Infrastructure is explicitly accounted for rather than presented as another supported feature. Support/generated/vendor-library files are not runtime proof.

Counts: feature-linked=17, infrastructure=9, support-or-generated=6.

Unresolved feature candidates: 0.

| Source | Classification | Topic or reason |
| --- | --- | --- |
| [.gitignore:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/.gitignore#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [README.md:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/README.md#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [const.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/const.php#L1) | infrastructure | Bootstrap, shared helper, configuration, enqueue or update dependency; linked from identity/contracts rather than a separate user feature. |
| [directorist-search-alert.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/directorist-search-alert.php#L1) | infrastructure | Plugin bootstrap/header or shared root configuration; inspect include/registration chain. |
| [package-lock.json:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/package-lock.json#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [package.json:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/package.json#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [admin/css/style.css:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/admin/css/style.css#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [admin/icon/check.svg:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/admin/icon/check.svg#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [admin/js/admin.js:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/admin/js/admin.js#L1) | feature-linked | [saved-search](topics/saved-search.md) |
| [app/base.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/base.php#L1) | feature-linked | [saved-search](topics/saved-search.md), [alert-delivery](topics/alert-delivery.md) |
| [app/Ajax/Ajax.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Ajax/Ajax.php#L1) | feature-linked | [saved-search](topics/saved-search.md) |
| [app/Dashboard/Admin_Dashboard.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Dashboard/Admin_Dashboard.php#L1) | feature-linked | [saved-search](topics/saved-search.md), [alert-delivery](topics/alert-delivery.md) |
| [app/Dashboard/Dashboard.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Dashboard/Dashboard.php#L1) | feature-linked | [saved-search](topics/saved-search.md) |
| [app/Dashboard/User_Dashboard.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Dashboard/User_Dashboard.php#L1) | feature-linked | [saved-search](topics/saved-search.md) |
| [app/Database/Database.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Database/Database.php#L1) | feature-linked | [saved-search](topics/saved-search.md), [alert-delivery](topics/alert-delivery.md) |
| [app/Email/Email.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L1) | feature-linked | [alert-delivery](topics/alert-delivery.md) |
| [app/Setup/EDD_SL_Plugin_Updater.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Setup/EDD_SL_Plugin_Updater.php#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [app/Setup/Enqueue.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Setup/Enqueue.php#L1) | feature-linked | [saved-search](topics/saved-search.md) |
| [app/Setup/Settings.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Setup/Settings.php#L1) | feature-linked | [alert-delivery](topics/alert-delivery.md) |
| [assets/css/style.css:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/assets/css/style.css#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [assets/css/style.css.map:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/assets/css/style.css.map#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [assets/js/main.js:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/assets/js/main.js#L1) | feature-linked | [saved-search](topics/saved-search.md) |
| [assets/scss/style.scss:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/assets/scss/style.scss#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [assets/scss/admin/admin.scss:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/assets/scss/admin/admin.scss#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [assets/scss/frontend/frontend.scss:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/assets/scss/frontend/frontend.scss#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [helpers/helpers.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/helpers/helpers.php#L1) | feature-linked | [saved-search](topics/saved-search.md) |
| [helpers/trait-search-helper.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/helpers/trait-search-helper.php#L1) | feature-linked | [saved-search](topics/saved-search.md) |
| [languages/directorist-search-alert.pot:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/languages/directorist-search-alert.pot#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [templates/editing-form.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/templates/editing-form.php#L1) | feature-linked | [saved-search](topics/saved-search.md) |
| [templates/search-result.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/templates/search-result.php#L1) | feature-linked | [saved-search](topics/saved-search.md) |
| [templates/dashboard/admin-dashboard.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/templates/dashboard/admin-dashboard.php#L1) | feature-linked | [saved-search](topics/saved-search.md) |
| [templates/dashboard/user-dashboard.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/templates/dashboard/user-dashboard.php#L1) | feature-linked | [saved-search](topics/saved-search.md) |
