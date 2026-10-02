# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [directorist-adverts-manager.php:25](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/directorist-adverts-manager.php#L25) | defined | `'ABSPATH'` |
| [directorist-adverts-manager.php:31](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/directorist-adverts-manager.php#L31) | class_exists | `'SWBDPAdsManager'` |
| [directorist-adverts-manager.php:113](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/directorist-adverts-manager.php#L113) | defined | `'ATBDP_POST_TYPE'` |
| [directorist-adverts-manager.php:115](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/directorist-adverts-manager.php#L115) | defined | `'ATBDP_AUTHOR_URL'` |
| [directorist-adverts-manager.php:117](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/directorist-adverts-manager.php#L117) | defined | `'ATBDP_ADS_POST_ID'` |
| [directorist-adverts-manager.php:139](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/directorist-adverts-manager.php#L139) | class_exists | `'EDD_SL_Plugin_Updater'` |
| [directorist-adverts-manager.php:181](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/directorist-adverts-manager.php#L181) | function_exists | `'get_directorist_option'` |
| [directorist-adverts-manager.php:272](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/directorist-adverts-manager.php#L272) | function_exists | `'directorist_is_plugin_active'` |
| [directorist-adverts-manager.php:278](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/directorist-adverts-manager.php#L278) | function_exists | `'directorist_is_plugin_active_for_network'` |
| [includes.php:6](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/includes.php#L6) | class_exists | `'SWBDPAMIncludeClasses'` |
| [Inc/Controller/AdDisplayPages/PageAddListing.php:7](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/AdDisplayPages/PageAddListing.php#L7) | class_exists | `'SWBDPAMPAddListing'` |
| [Inc/Controller/AdDisplayPages/PageAllCategories.php:7](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/AdDisplayPages/PageAllCategories.php#L7) | class_exists | `'SWBDPAMPAllCategories'` |
| [Inc/Controller/AdDisplayPages/PageAllListings.php:7](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/AdDisplayPages/PageAllListings.php#L7) | class_exists | `'SWBDPAMPAllListings'` |
| [Inc/Controller/AdDisplayPages/PageAllLocations.php:7](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/AdDisplayPages/PageAllLocations.php#L7) | class_exists | `'SWBDPAMPAllLocations'` |
| [Inc/Controller/AdDisplayPages/PageAuthorListing.php:7](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/AdDisplayPages/PageAuthorListing.php#L7) | class_exists | `'SWBDPAMPAuthorListing'` |
| [Inc/Controller/AdDisplayPages/PageDashboard.php:7](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/AdDisplayPages/PageDashboard.php#L7) | class_exists | `'SWBDPAMPDashboard'` |
| [Inc/Controller/AdDisplayPages/PageSearchHome.php:8](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/AdDisplayPages/PageSearchHome.php#L8) | class_exists | `'SWBDPAMPSearchHome'` |
| [Inc/Controller/AdDisplayPages/PageSearchResult.php:7](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/AdDisplayPages/PageSearchResult.php#L7) | class_exists | `'SWBDPAMPSearchResult'` |
| [Inc/Controller/AdDisplayPages/PageSingleCategory.php:8](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/AdDisplayPages/PageSingleCategory.php#L8) | class_exists | `'SWBDPAMPSingleCategory'` |
| [Inc/Controller/AdDisplayPages/PageSingleListing.php:8](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/AdDisplayPages/PageSingleListing.php#L8) | class_exists | `'SWBDPAMPSingleListing'` |
| [Inc/Controller/AdDisplayPages/PageSingleLocation.php:7](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/AdDisplayPages/PageSingleLocation.php#L7) | class_exists | `'SWBDPAMPSingleLocation'` |
| [Inc/Controller/Admin/AdsManagerCPT.php:7](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/Admin/AdsManagerCPT.php#L7) | class_exists | `'SWBDPAdsManagerCPT'` |
| [Inc/Controller/Admin/CustomWpListTable.php:7](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/Admin/CustomWpListTable.php#L7) | class_exists | `'SWBDPAMCustomWpListTable'` |
| [Inc/Controller/Base/Activate.php:7](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/Base/Activate.php#L7) | class_exists | `'SWBDPAMActivate'` |
| [Inc/Controller/Base/DynamicStyle.php:6](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/Base/DynamicStyle.php#L6) | class_exists | `'SWBDPAMDynamicStyle'` |
| [Inc/Controller/Base/Enqueue.php:7](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/Base/Enqueue.php#L7) | class_exists | `'SWBDPAMEnqueue'` |
| [Inc/Controller/Base/ExtensionSettings.php:7](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/Base/ExtensionSettings.php#L7) | class_exists | `'SWBDPAMExtnSettings'` |
| [Inc/Controller/Base/ExtensionSettings.php:66](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/Base/ExtensionSettings.php#L66) | wp_remote_post | `ATBDP_AUTHOR_URL` |
| [Inc/Controller/Base/ExtensionSettings.php:69](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/Base/ExtensionSettings.php#L69) | wp_remote_retrieve_response_code | `$response` |
| [Inc/Controller/Base/ExtensionSettings.php:75](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/Base/ExtensionSettings.php#L75) | wp_remote_retrieve_body | `$response` |
| [Inc/Controller/Base/ExtensionSettings.php:161](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/Base/ExtensionSettings.php#L161) | wp_remote_post | `ATBDP_AUTHOR_URL` |
| [Inc/Controller/Base/ExtensionSettings.php:164](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/Base/ExtensionSettings.php#L164) | wp_remote_retrieve_response_code | `$response` |
| [Inc/Controller/Base/ExtensionSettings.php:171](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/Base/ExtensionSettings.php#L171) | wp_remote_retrieve_body | `$response` |
| [Inc/Controller/Base/HelperFunctions.php:7](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/Base/HelperFunctions.php#L7) | class_exists | `'SWBDPAMHelperFunctions'` |
| [Inc/Controller/Base/ShortcodeHandler.php:6](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/Base/ShortcodeHandler.php#L6) | class_exists | `'SWBDPAMShortcodeHandler'` |
| [Inc/Controller/Base/WidgetsHandler.php:7](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/Base/WidgetsHandler.php#L7) | class_exists | `'SWBDPAMWidgetsHandler'` |
| [Inc/Controller/Widgets/WidgetAdsManager.php:7](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/Controller/Widgets/WidgetAdsManager.php#L7) | class_exists | `'SWBDPWidgetAdsManager'` |
| [Inc/View/AdminCallbacks.php:7](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/Inc/View/AdminCallbacks.php#L7) | class_exists | `'SWBDPAMAdminCallbacks'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
