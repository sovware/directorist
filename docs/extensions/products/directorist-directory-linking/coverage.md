# Feature and infrastructure coverage

Canonical snapshot: `directory-linking--alpha`.

Feature-linked means declarations/file boundaries are connected to an authored user workflow/topic. It does not prove every conditional path was manually audited. Infrastructure is explicitly accounted for rather than presented as another supported feature. Support/generated/vendor-library files are not runtime proof.

Counts: feature-linked=7, infrastructure=10, support-or-generated=5.

Unresolved feature candidates: 0.

| Source | Classification | Topic or reason |
| --- | --- | --- |
| [.gitignore:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/.gitignore#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [composer.json:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/composer.json#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [composer.lock:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/composer.lock#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [const.php:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/const.php#L1) | infrastructure | Bootstrap, shared helper, configuration, enqueue or update dependency; linked from identity/contracts rather than a separate user feature. |
| [directorist-directory-linking.php:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/directorist-directory-linking.php#L1) | infrastructure | Plugin bootstrap/header or shared root configuration; inspect include/registration chain. |
| [package.json:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/package.json#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [phpcs.xml.dist:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/phpcs.xml.dist#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [admin/css/style.css:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/admin/css/style.css#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [admin/js/admin.js:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/admin/js/admin.js#L1) | feature-linked | [link-selection](topics/link-selection.md) |
| [app/base.php:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/base.php#L1) | feature-linked | [link-selection](topics/link-selection.md), [link-search](topics/link-search.md) |
| [app/Builder/Builder.php:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/Builder/Builder.php#L1) | feature-linked | [link-selection](topics/link-selection.md), [link-search](topics/link-search.md) |
| [app/Setup/Enqueue.php:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/Setup/Enqueue.php#L1) | infrastructure | Bootstrap, shared helper, configuration, enqueue or update dependency; linked from identity/contracts rather than a separate user feature. |
| [app/Setup/Settings.php:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/Setup/Settings.php#L1) | feature-linked | [link-search](topics/link-search.md) |
| [assets/css/slick.css:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/assets/css/slick.css#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [assets/css/style.css:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/assets/css/style.css#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [assets/js/main.js:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/assets/js/main.js#L1) | feature-linked | [link-selection](topics/link-selection.md) |
| [assets/js/slick.min.js:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/assets/js/slick.min.js#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [assets/scss/style.scss:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/assets/scss/style.scss#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [helpers/helpers.php:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/helpers/helpers.php#L1) | infrastructure | Bootstrap, shared helper, configuration, enqueue or update dependency; linked from identity/contracts rather than a separate user feature. |
| [inc/EDD_SL_Plugin_Updater.php:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/inc/EDD_SL_Plugin_Updater.php#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [templates/add-listing.php:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/templates/add-listing.php#L1) | feature-linked | [link-selection](topics/link-selection.md) |
| [templates/single-listing.php:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/templates/single-listing.php#L1) | feature-linked | [link-selection](topics/link-selection.md) |
