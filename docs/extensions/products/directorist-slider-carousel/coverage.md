# Feature and infrastructure coverage

Canonical snapshot: `slider-carousel--beta`.

Feature-linked means declarations/file boundaries are connected to an authored user workflow/topic. It does not prove every conditional path was manually audited. Infrastructure is explicitly accounted for rather than presented as another supported feature. Support/generated/vendor-library files are not runtime proof.

Counts: feature-linked=6, infrastructure=3, support-or-generated=8.

Unresolved feature candidates: 0.

| Source | Classification | Topic or reason |
| --- | --- | --- |
| [.gitignore:1](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/.gitignore#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [bd-directorist-slider.php:1](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L1) | feature-linked | [slider-query](topics/slider-query.md), [slider-motion](topics/slider-motion.md) |
| [composer.json:1](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/composer.json#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [composer.lock:1](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/composer.lock#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [config.php:1](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/config.php#L1) | infrastructure | Bootstrap, shared helper, configuration, enqueue or update dependency; linked from identity/contracts rather than a separate user feature. |
| [index.php:1](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/index.php#L1) | infrastructure | Plugin bootstrap/header or shared root configuration; inspect include/registration chain. |
| [phpcs.xml.dist:1](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/phpcs.xml.dist#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [readme.txt:1](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/readme.txt#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [assets/admin/main.js:1](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/assets/admin/main.js#L1) | feature-linked | [slider-motion](topics/slider-motion.md) |
| [assets/admin/slick.min.css:1](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/assets/admin/slick.min.css#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [assets/admin/slick.min.js:1](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/assets/admin/slick.min.js#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [assets/admin/style-rtl.css:1](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/assets/admin/style-rtl.css#L1) | feature-linked | [slider-motion](topics/slider-motion.md) |
| [assets/admin/style.css:1](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/assets/admin/style.css#L1) | feature-linked | [slider-motion](topics/slider-motion.md) |
| [assets/css/style-rtl.css:1](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/assets/css/style-rtl.css#L1) | feature-linked | [slider-motion](topics/slider-motion.md) |
| [assets/css/style.css:1](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/assets/css/style.css#L1) | feature-linked | [slider-motion](topics/slider-motion.md) |
| [inc/EDD_SL_Plugin_Updater.php:1](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/inc/EDD_SL_Plugin_Updater.php#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [languages/directorist-slider-carousel.pot:1](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/languages/directorist-slider-carousel.pot#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
