# Feature and infrastructure coverage

Canonical snapshot: `faqs--development`.

Feature-linked means declarations/file boundaries are connected to an authored user workflow/topic. It does not prove every conditional path was manually audited. Infrastructure is explicitly accounted for rather than presented as another supported feature. Support/generated/vendor-library files are not runtime proof.

Counts: feature-linked=10, infrastructure=6, support-or-generated=11.

Unresolved feature candidates: 0.

| Source | Classification | Topic or reason |
| --- | --- | --- |
| [.gitignore:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/.gitignore#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [composer.json:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/composer.json#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [composer.lock:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/composer.lock#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [config.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/config.php#L1) | infrastructure | Bootstrap, shared helper, configuration, enqueue or update dependency; linked from identity/contracts rather than a separate user feature. |
| [directorist-faqs.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L1) | feature-linked | [faq-edit](topics/faq-edit.md), [faq-render](topics/faq-render.md) |
| [index.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/index.php#L1) | infrastructure | Plugin bootstrap/header or shared root configuration; inspect include/registration chain. |
| [phpcs.xml.dist:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/phpcs.xml.dist#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [readme.txt:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/readme.txt#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [assets/css/daterangepicker.css:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/assets/css/daterangepicker.css#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [assets/css/main-rtl.css:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/assets/css/main-rtl.css#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [assets/css/main.css:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/assets/css/main.css#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [assets/css/map/bh-main.css.map:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/assets/css/map/bh-main.css.map#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [assets/css/map/style.css.map:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/assets/css/map/style.css.map#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [assets/js/admin-main.js:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/assets/js/admin-main.js#L1) | feature-linked | [faq-edit](topics/faq-edit.md) |
| [assets/js/daterangepicker.js:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/assets/js/daterangepicker.js#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [assets/js/main.js:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/assets/js/main.js#L1) | feature-linked | [faq-edit](topics/faq-edit.md) |
| [assets/js/moment.min.js:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/assets/js/moment.min.js#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [inc/EDD_SL_Plugin_Updater.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/inc/EDD_SL_Plugin_Updater.php#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [inc/directory_type.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/inc/directory_type.php#L1) | feature-linked | [faq-edit](topics/faq-edit.md), [faq-render](topics/faq-render.md) |
| [inc/helper-functions.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/inc/helper-functions.php#L1) | feature-linked | [faq-edit](topics/faq-edit.md), [faq-render](topics/faq-render.md) |
| [languages/directorist-faqs.pot:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/languages/directorist-faqs.pot#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [languages/index.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/languages/index.php#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [templates/add-faq.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/templates/add-faq.php#L1) | feature-linked | [faq-render](topics/faq-render.md) |
| [templates/faqs.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/templates/faqs.php#L1) | feature-linked | [faq-edit](topics/faq-edit.md), [faq-render](topics/faq-render.md) |
| [templates/view-faqs.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/templates/view-faqs.php#L1) | feature-linked | [faq-edit](topics/faq-edit.md), [faq-render](topics/faq-render.md) |
| [templates/ajax/faqs-ajax.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/templates/ajax/faqs-ajax.php#L1) | feature-linked | [faq-edit](topics/faq-edit.md), [faq-render](topics/faq-render.md) |
| [widgets/class-widget.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/widgets/class-widget.php#L1) | feature-linked | [faq-edit](topics/faq-edit.md), [faq-render](topics/faq-render.md) |
