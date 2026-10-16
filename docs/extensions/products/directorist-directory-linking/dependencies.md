# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |
| [package.json:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/package.json#L1) | node-sass | ^6.0.1 |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [const.php:3](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/const.php#L3) | defined | `'SWBDP_DIRLINK_LANG_PATH'` |
| [const.php:6](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/const.php#L6) | defined | `'SWBDP_DIRLINK_PLUGIN_PATH'` |
| [const.php:9](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/const.php#L9) | defined | `'SWBDP_DIRLINK_PLUGIN_DIR_URI'` |
| [const.php:12](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/const.php#L12) | defined | `'SWBDP_DIRLINK_ASSET_URI'` |
| [const.php:15](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/const.php#L15) | defined | `'SWBDP_DIRLINK_CSS_URI'` |
| [const.php:18](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/const.php#L18) | defined | `'SWBDP_DIRLINK_JS_URI'` |
| [const.php:21](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/const.php#L21) | defined | `'SWBDP_DIRLINK_DIR'` |
| [const.php:24](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/const.php#L24) | defined | `'SWBDP_DIRLINK_VERSION'` |
| [directorist-directory-linking.php:25](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/directorist-directory-linking.php#L25) | defined | `'ABSPATH'` |
| [directorist-directory-linking.php:28](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/directorist-directory-linking.php#L28) | defined | `'SWBDP_DIRLINK_PLUGIN_FILE'` |
| [directorist-directory-linking.php:33](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/directorist-directory-linking.php#L33) | defined | `'ATBDP_AUTHOR_URL'` |
| [directorist-directory-linking.php:38](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/directorist-directory-linking.php#L38) | defined | `'ATBDP_DIR_DIRLINK_POST_ID'` |
| [directorist-directory-linking.php:51](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/directorist-directory-linking.php#L51) | class_exists | `'Directorist_Directory_Linking'` |
| [directorist-directory-linking.php:55](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/directorist-directory-linking.php#L55) | class_exists | `'EDD_SL_Plugin_Updater'` |
| [app/base.php:2](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/base.php#L2) | defined | `'ABSPATH'` |
| [app/base.php:111](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/base.php#L111) | class_exists | `$class_name` |
| [app/base.php:125](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/base.php#L125) | function_exists | `'directorist_is_plugin_active'` |
| [app/base.php:131](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/base.php#L131) | function_exists | `'directorist_is_plugin_active_for_network'` |
| [templates/add-listing.php:8](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/templates/add-listing.php#L8) | defined | `'ABSPATH'` |
| [templates/single-listing.php:8](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/templates/single-listing.php#L8) | defined | `'ABSPATH'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
