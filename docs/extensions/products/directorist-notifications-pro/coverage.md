# Feature and infrastructure coverage

Canonical snapshot: `notifications-pro--main`.

Feature-linked means declarations/file boundaries are connected to an authored user workflow/topic. It does not prove every conditional path was manually audited. Infrastructure is explicitly accounted for rather than presented as another supported feature. Support/generated/vendor-library files are not runtime proof.

Counts: feature-linked=12, infrastructure=3, support-or-generated=5.

Unresolved feature candidates: 0.

| Source | Classification | Topic or reason |
| --- | --- | --- |
| [.gitignore:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/.gitignore#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [composer.json:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/composer.json#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [composer.lock:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/composer.lock#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [directorist-notifications-pro.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/directorist-notifications-pro.php#L1) | feature-linked | [subscription](topics/subscription.md) |
| [index.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/index.php#L1) | infrastructure | Plugin bootstrap/header or shared root configuration; inspect include/registration chain. |
| [assets/css/frontend-web-push.css:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/assets/css/frontend-web-push.css#L1) | feature-linked | [subscription](topics/subscription.md) |
| [assets/js/frontend-web-push.js:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/assets/js/frontend-web-push.js#L1) | feature-linked | [subscription](topics/subscription.md) |
| [assets/js/service-worker.js:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/assets/js/service-worker.js#L1) | feature-linked | [subscription](topics/subscription.md) |
| [dev-tools/build.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/dev-tools/build.php#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [inc/EDD_SL_Plugin_Updater.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/EDD_SL_Plugin_Updater.php#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [inc/class-admin-log.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-admin-log.php#L1) | feature-linked | [subscription](topics/subscription.md), [events-delivery](topics/events-delivery.md) |
| [inc/class-event-integration.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-event-integration.php#L1) | feature-linked | [subscription](topics/subscription.md), [events-delivery](topics/events-delivery.md) |
| [inc/class-frontend.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-frontend.php#L1) | feature-linked | [subscription](topics/subscription.md) |
| [inc/class-license.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-license.php#L1) | feature-linked | [subscription](topics/subscription.md), [events-delivery](topics/events-delivery.md) |
| [inc/class-rest-api.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-rest-api.php#L1) | feature-linked | [subscription](topics/subscription.md) |
| [inc/class-sender.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-sender.php#L1) | feature-linked | [subscription](topics/subscription.md), [events-delivery](topics/events-delivery.md) |
| [inc/class-settings.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-settings.php#L1) | feature-linked | [subscription](topics/subscription.md), [events-delivery](topics/events-delivery.md) |
| [inc/class-web-push.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-web-push.php#L1) | feature-linked | [subscription](topics/subscription.md) |
| [inc/index.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/index.php#L1) | infrastructure | Inspected scaffold/empty guard, shared helper, licensing/configuration or updater; not an independent user feature. |
| [languages/index.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/languages/index.php#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
