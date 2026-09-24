# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |
| [composer.json:1](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/composer.json#L1) | mailchimp/marketing | ^3.0 |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [directorist-mailchimp-integration.php:15](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/directorist-mailchimp-integration.php#L15) | defined | `'ABSPATH'` |
| [directorist-mailchimp-integration.php:17](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/directorist-mailchimp-integration.php#L17) | class_exists | `'Directorist_Mailchimp_Integration'` |
| [directorist-mailchimp-integration.php:74](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/directorist-mailchimp-integration.php#L74) | class_exists | `'EDD_SL_Plugin_Updater'` |
| [directorist-mailchimp-integration.php:83](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/directorist-mailchimp-integration.php#L83) | defined | `'DM_VERSION'` |
| [directorist-mailchimp-integration.php:87](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/directorist-mailchimp-integration.php#L87) | defined | `'DM_POST_ID'` |
| [directorist-mailchimp-integration.php:92](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/directorist-mailchimp-integration.php#L92) | defined | `'ATBDP_AUTHOR_URL'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
