# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |
| [composer.json:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/composer.json#L1) | php | >=7.3 |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [directorist-announcement.php:11](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/directorist-announcement.php#L11) | defined | `'ABSPATH'` |
| [directorist-announcement.php:13](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/directorist-announcement.php#L13) | defined | `'DIRECTORIST_ANNOUNCEMENT_VERSION'` |
| [directorist-announcement.php:18](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/directorist-announcement.php#L18) | defined | `'DIRECTORIST_ANNOUNCEMENT_BASE_DIR'` |
| [directorist-announcement.php:23](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/directorist-announcement.php#L23) | defined | `'ATBDP_AUTHOR_URL'` |
| [directorist-announcement.php:28](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/directorist-announcement.php#L28) | defined | `'ATBDP_DIR_ANNOUNCE_POST_ID'` |
| [directorist-announcement.php:32](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/directorist-announcement.php#L32) | class_exists | `'Directorist_Announcement'` |
| [directorist-announcement.php:74](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/directorist-announcement.php#L74) | is_plugin_active | `'directorist/directorist-base.php'` |
| [directorist-announcement.php:86](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/directorist-announcement.php#L86) | class_exists | `'EDD_SL_Plugin_Updater'` |
| [inc/class-content-update.php:12](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-content-update.php#L12) | class_exists | `'DA_Update'` |
| [inc/class-warning-notice.php:8](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-warning-notice.php#L8) | defined | `'ABSPATH'` |
| [template-parts/list.php:11](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/template-parts/list.php#L11) | defined | `'ABSPATH'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
