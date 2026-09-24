# Feature and infrastructure coverage

Canonical snapshot: `compare-listing--alpha`.

Feature-linked means declarations/file boundaries are connected to an authored user workflow/topic. It does not prove every conditional path was manually audited. Infrastructure is explicitly accounted for rather than presented as another supported feature. Support/generated/vendor-library files are not runtime proof.

Counts: feature-linked=14, infrastructure=6, support-or-generated=5.

Unresolved feature candidates: 0.

| Source | Classification | Topic or reason |
| --- | --- | --- |
| [.gitignore:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/.gitignore#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [composer.json:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/composer.json#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [composer.lock:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/composer.lock#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [directorist-compare-listing.php:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/directorist-compare-listing.php#L1) | feature-linked | [comparison](topics/comparison.md) |
| [includes.php:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/includes.php#L1) | infrastructure | Bootstrap, shared helper, configuration, enqueue or update dependency; linked from identity/contracts rather than a separate user feature. |
| [index.php:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/index.php#L1) | infrastructure | Plugin bootstrap/header or shared root configuration; inspect include/registration chain. |
| [phpcs.xml.dist:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/phpcs.xml.dist#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [Inc/Controller/Base/Activate.php:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/Controller/Base/Activate.php#L1) | feature-linked | [comparison](topics/comparison.md) |
| [Inc/Controller/Base/AjaxHandler.php:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/Controller/Base/AjaxHandler.php#L1) | feature-linked | [selection](topics/selection.md) |
| [Inc/Controller/Base/CompareButton.php:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/Controller/Base/CompareButton.php#L1) | feature-linked | [selection](topics/selection.md) |
| [Inc/Controller/Base/EDD_SL_Plugin_Updater.php:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/Controller/Base/EDD_SL_Plugin_Updater.php#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [Inc/Controller/Base/Enqueue.php:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/Controller/Base/Enqueue.php#L1) | infrastructure | Bootstrap, shared helper, configuration, enqueue or update dependency; linked from identity/contracts rather than a separate user feature. |
| [Inc/Controller/Base/ExtensionSettings.php:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/Controller/Base/ExtensionSettings.php#L1) | feature-linked | [comparison](topics/comparison.md) |
| [Inc/Controller/Base/HelperFunctions.php:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/Controller/Base/HelperFunctions.php#L1) | feature-linked | [selection](topics/selection.md) |
| [Inc/Controller/Shortcodes/ShortcodeListingCompare.php:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/Controller/Shortcodes/ShortcodeListingCompare.php#L1) | feature-linked | [comparison](topics/comparison.md) |
| [Inc/View/ajax-select-window.php:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/View/ajax-select-window.php#L1) | feature-linked | [selection](topics/selection.md) |
| [Inc/View/compare-page.php:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/View/compare-page.php#L1) | feature-linked | [comparison](topics/comparison.md) |
| [Inc/View/select-window.php:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/View/select-window.php#L1) | feature-linked | [selection](topics/selection.md) |
| [Inc/View/selected-listings-sidebar.php:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/View/selected-listings-sidebar.php#L1) | feature-linked | [selection](topics/selection.md) |
| [assets/admin/css/atdlc-admin.css:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/assets/admin/css/atdlc-admin.css#L1) | feature-linked | [selection](topics/selection.md) |
| [assets/admin/js/atdlc-admin.js:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/assets/admin/js/atdlc-admin.js#L1) | feature-linked | [selection](topics/selection.md) |
| [assets/frontend/css/atdlc-frontend-rtl.css:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/assets/frontend/css/atdlc-frontend-rtl.css#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [assets/frontend/css/atdlc-frontend.css:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/assets/frontend/css/atdlc-frontend.css#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [assets/frontend/js/atdlc-frontend-ajax.js:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/assets/frontend/js/atdlc-frontend-ajax.js#L1) | feature-linked | [selection](topics/selection.md) |
| [languages/directorist-compare-listing.pot:1](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/languages/directorist-compare-listing.pot#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
