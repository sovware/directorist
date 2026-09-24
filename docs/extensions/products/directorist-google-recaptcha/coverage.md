# Feature and infrastructure coverage

Canonical snapshot: `google-recaptcha--v3-support`.

Feature-linked means declarations/file boundaries are connected to an authored user workflow/topic. It does not prove every conditional path was manually audited. Infrastructure is explicitly accounted for rather than presented as another supported feature. Support/generated/vendor-library files are not runtime proof.

Counts: feature-linked=6, infrastructure=4, support-or-generated=7.

Unresolved feature candidates: 0.

| Source | Classification | Topic or reason |
| --- | --- | --- |
| [.gitignore:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/.gitignore#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [composer.json:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/composer.json#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [composer.lock:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/composer.lock#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [config.php:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/config.php#L1) | infrastructure | Bootstrap, shared helper, configuration, enqueue or update dependency; linked from identity/contracts rather than a separate user feature. |
| [directorist-google-recaptcha.php:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/directorist-google-recaptcha.php#L1) | feature-linked | [verification](topics/verification.md), [captcha-render](topics/captcha-render.md) |
| [index.php:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/index.php#L1) | infrastructure | Plugin bootstrap/header or shared root configuration; inspect include/registration chain. |
| [phpcs.xml.dist:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/phpcs.xml.dist#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [recaptchalib.php:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/recaptchalib.php#L1) | feature-linked | [verification](topics/verification.md), [captcha-render](topics/captcha-render.md) |
| [assets/.DS_Store:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/assets/.DS_Store#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [assets/css/main.css:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/assets/css/main.css#L1) | feature-linked | [captcha-render](topics/captcha-render.md) |
| [assets/js/main.js:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/assets/js/main.js#L1) | feature-linked | [captcha-render](topics/captcha-render.md) |
| [inc/EDD_SL_Plugin_Updater.php:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/inc/EDD_SL_Plugin_Updater.php#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [inc/directory_type.php:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/inc/directory_type.php#L1) | feature-linked | [verification](topics/verification.md), [captcha-render](topics/captcha-render.md) |
| [inc/helper-functions.php:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/inc/helper-functions.php#L1) | infrastructure | Inspected scaffold/empty guard, shared helper, licensing/configuration or updater; not an independent user feature. |
| [languages/directorist-google-recaptcha.pot:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/languages/directorist-google-recaptcha.pot#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [languages/index.php:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/languages/index.php#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [templates/google-recaptcha-fields.php:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/templates/google-recaptcha-fields.php#L1) | feature-linked | [verification](topics/verification.md), [captcha-render](topics/captcha-render.md) |
