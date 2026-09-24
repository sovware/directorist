# Feature and infrastructure coverage

Canonical snapshot: `listing-import--main`.

Feature-linked means declarations/file boundaries are connected to an authored user workflow/topic. It does not prove every conditional path was manually audited. Infrastructure is explicitly accounted for rather than presented as another supported feature. Support/generated/vendor-library files are not runtime proof.

Counts: feature-linked=17, infrastructure=2, support-or-generated=2.

Unresolved feature candidates: 0.

| Source | Classification | Topic or reason |
| --- | --- | --- |
| [USAGE-GUIDE.md:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/USAGE-GUIDE.md#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [directorist-listing-import.php:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/directorist-listing-import.php#L1) | infrastructure | Plugin bootstrap/header or shared root configuration; inspect include/registration chain. |
| [admin/class-admin-page.php:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/admin/class-admin-page.php#L1) | feature-linked | [feed-import](topics/feed-import.md) |
| [admin/views/admin-page.php:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/admin/views/admin-page.php#L1) | feature-linked | [feed-import](topics/feed-import.md) |
| [assets/admin.css:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/assets/admin.css#L1) | infrastructure | Declarative configuration or shared presentation asset; no independent runtime feature inferred from file existence. |
| [google/assets/css/admin.css:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/assets/css/admin.css#L1) | feature-linked | [google-import](topics/google-import.md) |
| [google/assets/js/admin.js:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/assets/js/admin.js#L1) | feature-linked | [google-import](topics/google-import.md) |
| [google/includes/class-admin-page.php:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-admin-page.php#L1) | feature-linked | [google-import](topics/google-import.md), [feed-import](topics/feed-import.md) |
| [google/includes/class-ajax-import.php:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-ajax-import.php#L1) | feature-linked | [google-import](topics/google-import.md), [feed-import](topics/feed-import.md) |
| [google/includes/class-field-mapping.php:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-field-mapping.php#L1) | feature-linked | [google-import](topics/google-import.md), [feed-import](topics/feed-import.md) |
| [google/includes/class-google-places-client.php:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-google-places-client.php#L1) | feature-linked | [google-import](topics/google-import.md), [feed-import](topics/feed-import.md) |
| [google/includes/class-importer.php:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-importer.php#L1) | feature-linked | [google-import](topics/google-import.md), [feed-import](topics/feed-import.md) |
| [google/includes/class-installer.php:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-installer.php#L1) | feature-linked | [google-import](topics/google-import.md), [feed-import](topics/feed-import.md) |
| [google/includes/class-plugin.php:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-plugin.php#L1) | feature-linked | [google-import](topics/google-import.md), [feed-import](topics/feed-import.md) |
| [google/includes/class-review-mapper.php:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-review-mapper.php#L1) | feature-linked | [google-import](topics/google-import.md), [feed-import](topics/feed-import.md) |
| [google/includes/class-settings.php:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-settings.php#L1) | feature-linked | [google-import](topics/google-import.md), [feed-import](topics/feed-import.md) |
| [includes/EDD_SL_Plugin_Updater.php:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/includes/EDD_SL_Plugin_Updater.php#L1) | support-or-generated | Fingerprint only; build/library/tooling/assets are not declared first-party runtime features. |
| [includes/class-feed-discovery.php:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/includes/class-feed-discovery.php#L1) | feature-linked | [feed-import](topics/feed-import.md) |
| [includes/class-feed-manager.php:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/includes/class-feed-manager.php#L1) | feature-linked | [feed-import](topics/feed-import.md) |
| [includes/class-importer.php:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/includes/class-importer.php#L1) | feature-linked | [feed-import](topics/feed-import.md) |
| [includes/class-scheduler.php:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/includes/class-scheduler.php#L1) | feature-linked | [feed-import](topics/feed-import.md) |
