# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [directorist-mark-as-sold.php:14](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/directorist-mark-as-sold.php#L14) | defined | `'ABSPATH'` |
| [directorist-mark-as-sold.php:15](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/directorist-mark-as-sold.php#L15) | class_exists | `'Directorist_Mark_as_Sold'` |
| [directorist-mark-as-sold.php:98](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/directorist-mark-as-sold.php#L98) | function_exists | `'directorist_is_listing_feature_available'` |
| [directorist-mark-as-sold.php:122](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/directorist-mark-as-sold.php#L122) | function_exists | `'directorist_is_listing_feature_available'` |
| [directorist-mark-as-sold.php:682](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/directorist-mark-as-sold.php#L682) | defined | `'MAS_VERSION'` |
| [directorist-mark-as-sold.php:686](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/directorist-mark-as-sold.php#L686) | defined | `'ATBDP_AUTHOR_URL'` |
| [directorist-mark-as-sold.php:690](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/directorist-mark-as-sold.php#L690) | defined | `'ATBDP_MAS_POST_ID'` |
| [directorist-mark-as-sold.php:705](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/directorist-mark-as-sold.php#L705) | class_exists | `'EDD_SL_Plugin_Updater'` |
| [directorist-mark-as-sold.php:739](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/directorist-mark-as-sold.php#L739) | function_exists | `'directorist_is_plugin_active'` |
| [directorist-mark-as-sold.php:745](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/directorist-mark-as-sold.php#L745) | function_exists | `'directorist_is_plugin_active_for_network'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
