# Feature and infrastructure coverage

Canonical snapshot: `gamipress-integration--master`.

Feature-linked means declarations/file boundaries are connected to an authored user workflow/topic. It does not prove every conditional path was manually audited. Infrastructure is explicitly accounted for rather than presented as another supported feature. Support/generated/vendor-library files are not runtime proof.

Counts: feature-linked=12, infrastructure=3, legacy-candidate=2, support-or-generated=6.

Unresolved feature candidates: 0.

| Source | Classification | Topic or reason |
| --- | --- | --- |
| [.distignore:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/.distignore#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [.gitignore:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/.gitignore#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [changelog.txt:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/changelog.txt#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [directorist-gamipress-integration.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/directorist-gamipress-integration.php#L1) | infrastructure | Plugin bootstrap/header or shared root configuration; inspect include/registration chain. |
| [package-lock.json:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/package-lock.json#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [assets/css/style.css:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/assets/css/style.css#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [assets/js/admin-script.js:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/assets/js/admin-script.js#L1) | feature-linked | [points](topics/points.md) |
| [assets/js/script.js:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/assets/js/script.js#L1) | feature-linked | [redeem-coupon](topics/redeem-coupon.md) |
| [bin/makepot.sh:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/bin/makepot.sh#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [inc/EDD_SL_Plugin_Updater.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/inc/EDD_SL_Plugin_Updater.php#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [inc/directory_type.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/inc/directory_type.php#L1) | legacy-candidate | FAQ-named compatibility/copy files; bootstrap reachability must be checked before treating these as GamiPress features. |
| [inc/helper-functions.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/inc/helper-functions.php#L1) | legacy-candidate | FAQ-named compatibility/copy files; bootstrap reachability must be checked before treating these as GamiPress features. |
| [includes/class-assets.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-assets.php#L1) | feature-linked | [points](topics/points.md) |
| [includes/class-author.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-author.php#L1) | feature-linked | [points](topics/points.md) |
| [includes/class-coupon-manager.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-coupon-manager.php#L1) | feature-linked | [redeem-coupon](topics/redeem-coupon.md) |
| [includes/class-dashboard.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-dashboard.php#L1) | feature-linked | [points](topics/points.md) |
| [includes/class-listeners.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-listeners.php#L1) | feature-linked | [points](topics/points.md) |
| [includes/class-requirements.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-requirements.php#L1) | feature-linked | [points](topics/points.md) |
| [includes/class-rules-engine.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-rules-engine.php#L1) | feature-linked | [points](topics/points.md) |
| [includes/class-settings.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-settings.php#L1) | feature-linked | [redeem-coupon](topics/redeem-coupon.md) |
| [includes/class-triggers.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-triggers.php#L1) | feature-linked | [points](topics/points.md) |
| [includes/class-utils.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-utils.php#L1) | feature-linked | [redeem-coupon](topics/redeem-coupon.md) |
| [languages/directorist-gamipress-integration.pot:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/languages/directorist-gamipress-integration.pot#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
