# Feature and infrastructure coverage

Canonical snapshot: `mark-as-sold--details-update`.

Feature-linked means declarations/file boundaries are connected to an authored user workflow/topic. It does not prove every conditional path was manually audited. Infrastructure is explicitly accounted for rather than presented as another supported feature. Support/generated/vendor-library files are not runtime proof.

Counts: feature-linked=3, infrastructure=5, support-or-generated=5.

Unresolved feature candidates: 0.

| Source | Classification | Topic or reason |
| --- | --- | --- |
| [.gitignore:1](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/.gitignore#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [composer.json:1](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/composer.json#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [composer.lock:1](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/composer.lock#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [directorist-mark-as-sold.php:1](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/directorist-mark-as-sold.php#L1) | feature-linked | [sold-state](topics/sold-state.md), [sold-display](topics/sold-display.md) |
| [index.php:1](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/index.php#L1) | infrastructure | Plugin bootstrap/header or shared root configuration; inspect include/registration chain. |
| [phpcs.xml.dist:1](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/phpcs.xml.dist#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [assets/admin/css/main.css:1](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/assets/admin/css/main.css#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [assets/admin/js/main.js:1](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/assets/admin/js/main.js#L1) | feature-linked | [sold-state](topics/sold-state.md) |
| [assets/public/css/main-rtl.css:1](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/assets/public/css/main-rtl.css#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [assets/public/css/main.css:1](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/assets/public/css/main.css#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [assets/public/js/main.js:1](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/assets/public/js/main.js#L1) | feature-linked | [sold-state](topics/sold-state.md) |
| [includes/EDD_SL_Plugin_Updater.php:1](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/includes/EDD_SL_Plugin_Updater.php#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [languages/directorist-mark-as-sold.pot:1](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/languages/directorist-mark-as-sold.pot#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
