# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [directorist-compare-listing.php:25](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/directorist-compare-listing.php#L25) | defined | `'ABSPATH'` |
| [directorist-compare-listing.php:30](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/directorist-compare-listing.php#L30) | class_exists | `'ATDListingCompare'` |
| [directorist-compare-listing.php:98](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/directorist-compare-listing.php#L98) | defined | `'ATBDP_AUTHOR_URL'` |
| [directorist-compare-listing.php:100](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/directorist-compare-listing.php#L100) | defined | `'ATBDP_ATDLC_POST_ID'` |
| [directorist-compare-listing.php:162](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/directorist-compare-listing.php#L162) | class_exists | `'EDD_SL_Plugin_Updater'` |
| [directorist-compare-listing.php:220](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/directorist-compare-listing.php#L220) | function_exists | `'directorist_is_plugin_active'` |
| [directorist-compare-listing.php:226](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/directorist-compare-listing.php#L226) | function_exists | `'directorist_is_plugin_active_for_network'` |
| [includes.php:6](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/includes.php#L6) | class_exists | `'IncludeClasses'` |
| [Inc/Controller/Base/Activate.php:6](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/Controller/Base/Activate.php#L6) | class_exists | `'Activate'` |
| [Inc/Controller/Base/AjaxHandler.php:6](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/Controller/Base/AjaxHandler.php#L6) | class_exists | `'AjaxHandler'` |
| [Inc/Controller/Base/AjaxHandler.php:147](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/Controller/Base/AjaxHandler.php#L147) | wp_remote_post | `ATBDP_AUTHOR_URL` |
| [Inc/Controller/Base/AjaxHandler.php:149](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/Controller/Base/AjaxHandler.php#L149) | wp_remote_retrieve_response_code | `$response` |
| [Inc/Controller/Base/AjaxHandler.php:154](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/Controller/Base/AjaxHandler.php#L154) | wp_remote_retrieve_body | `$response` |
| [Inc/Controller/Base/CompareButton.php:6](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/Controller/Base/CompareButton.php#L6) | class_exists | `'CompareButton'` |
| [Inc/Controller/Base/Enqueue.php:6](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/Controller/Base/Enqueue.php#L6) | class_exists | `'Enqueue'` |
| [Inc/Controller/Base/ExtensionSettings.php:6](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/Controller/Base/ExtensionSettings.php#L6) | class_exists | `'ExtensionSettings'` |
| [Inc/Controller/Base/HelperFunctions.php:6](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/Controller/Base/HelperFunctions.php#L6) | class_exists | `'HelperFunctions'` |
| [Inc/Controller/Shortcodes/ShortcodeListingCompare.php:6](https://github.com/sovware/directorist-compare-listing/blob/b4a2af84bc8bd56edd5ef76306a9912dcc5aeab2/Inc/Controller/Shortcodes/ShortcodeListingCompare.php#L6) | class_exists | `'ShortcodeListingCompare'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
