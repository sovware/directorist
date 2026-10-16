# Feature and infrastructure coverage

Canonical snapshot: `announcement--master`.

Feature-linked means declarations/file boundaries are connected to an authored user workflow/topic. It does not prove every conditional path was manually audited. Infrastructure is explicitly accounted for rather than presented as another supported feature. Support/generated/vendor-library files are not runtime proof.

Counts: feature-linked=7, infrastructure=5, support-or-generated=7.

Unresolved feature candidates: 0.

| Source | Classification | Topic or reason |
| --- | --- | --- |
| [.gitattributes:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/.gitattributes#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [.gitignore:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/.gitignore#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [composer.json:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/composer.json#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [composer.lock:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/composer.lock#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [directorist-announcement.php:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/directorist-announcement.php#L1) | infrastructure | Plugin bootstrap/header or shared root configuration; inspect include/registration chain. |
| [gulpfile.js:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/gulpfile.js#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [package.json:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/package.json#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [phpcs.xml.dist:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/phpcs.xml.dist#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [assets/css/announcement-main.css:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/assets/css/announcement-main.css#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [assets/js/admin.js:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/assets/js/admin.js#L1) | feature-linked | [publish-target](topics/publish-target.md) |
| [assets/js/main.js:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/assets/js/main.js#L1) | feature-linked | [read-state](topics/read-state.md) |
| [inc/EDD_SL_Plugin_Updater.php:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/EDD_SL_Plugin_Updater.php#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [inc/class-content-update.php:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-content-update.php#L1) | feature-linked | [publish-target](topics/publish-target.md), [read-state](topics/read-state.md) |
| [inc/class-frontend-view.php:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-frontend-view.php#L1) | feature-linked | [read-state](topics/read-state.md) |
| [inc/class-helpers.php:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-helpers.php#L1) | feature-linked | [publish-target](topics/publish-target.md), [read-state](topics/read-state.md) |
| [inc/class-settings.php:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-settings.php#L1) | feature-linked | [publish-target](topics/publish-target.md) |
| [inc/class-warning-notice.php:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-warning-notice.php#L1) | infrastructure | Bootstrap, shared helper, configuration, enqueue or update dependency; linked from identity/contracts rather than a separate user feature. |
| [languages/directorist-announcement.pot:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/languages/directorist-announcement.pot#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [template-parts/list.php:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/template-parts/list.php#L1) | feature-linked | [read-state](topics/read-state.md) |
