# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [directorist-ai-search.php:19](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/directorist-ai-search.php#L19) | defined | `'ABSPATH'` |
| [directorist-ai-search.php:21](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/directorist-ai-search.php#L21) | defined | `'DIRECTORIST_AI_SEARCH_FILE'` |
| [directorist-ai-search.php:25](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/directorist-ai-search.php#L25) | defined | `'DIRECTORIST_AI_SEARCH_PATH'` |
| [directorist-ai-search.php:29](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/directorist-ai-search.php#L29) | defined | `'DIRECTORIST_AI_SEARCH_URL'` |
| [directorist-ai-search.php:33](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/directorist-ai-search.php#L33) | defined | `'DIRECTORIST_AI_SEARCH_BASENAME'` |
| [inc/Admin/AdminMenu.php:15](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Admin/AdminMenu.php#L15) | defined | `'ABSPATH'` |
| [inc/Admin/AdminMenu.php:1020](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Admin/AdminMenu.php#L1020) | defined | `'ATBDP_POST_TYPE'` |
| [inc/Admin/AdminRestController.php:15](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Admin/AdminRestController.php#L15) | defined | `'ABSPATH'` |
| [inc/Core/Activator.php:10](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Core/Activator.php#L10) | defined | `'ABSPATH'` |
| [inc/Core/Deactivator.php:10](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Core/Deactivator.php#L10) | defined | `'ABSPATH'` |
| [inc/Core/Plugin.php:21](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Core/Plugin.php#L21) | defined | `'ABSPATH'` |
| [inc/Core/Plugin.php:43](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Core/Plugin.php#L43) | defined | `'DIRECTORIST_AI_SEARCH_VERSION'` |
| [inc/Core/Plugin.php:129](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Core/Plugin.php#L129) | defined | `'ATBDP_POST_TYPE'` |
| [inc/Services/ApiClient.php:12](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/ApiClient.php#L12) | defined | `'ABSPATH'` |
| [inc/Services/ApiClient.php:101](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/ApiClient.php#L101) | wp_remote_request | `$url` |
| [inc/Services/ApiClient.php:113](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/ApiClient.php#L113) | wp_remote_retrieve_response_code | `$response` |
| [inc/Services/ApiClient.php:114](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/ApiClient.php#L114) | wp_remote_retrieve_body | `$response` |
| [inc/Services/ApiKeyProvisioningService.php:12](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/ApiKeyProvisioningService.php#L12) | defined | `'ABSPATH'` |
| [inc/Services/ApiKeyProvisioningService.php:95](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/ApiKeyProvisioningService.php#L95) | wp_remote_post | `$url` |
| [inc/Services/ApiKeyProvisioningService.php:124](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/ApiKeyProvisioningService.php#L124) | wp_remote_retrieve_response_code | `$response` |
| [inc/Services/ApiKeyProvisioningService.php:125](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/ApiKeyProvisioningService.php#L125) | wp_remote_retrieve_body | `$response` |
| [inc/Services/AutomaticIndexingService.php:12](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/AutomaticIndexingService.php#L12) | defined | `'ABSPATH'` |
| [inc/Services/AutomaticIndexingService.php:225](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/AutomaticIndexingService.php#L225) | defined | `'DOING_AUTOSAVE'` |
| [inc/Services/AutomaticIndexingService.php:303](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/AutomaticIndexingService.php#L303) | defined | `'ATBDP_POST_TYPE'` |
| [inc/Services/DirectoristAccountService.php:12](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/DirectoristAccountService.php#L12) | defined | `'ABSPATH'` |
| [inc/Services/DirectoristDependencyService.php:12](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/DirectoristDependencyService.php#L12) | defined | `'ABSPATH'` |
| [inc/Services/DirectoristDependencyService.php:32](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/DirectoristDependencyService.php#L32) | class_exists | `'\Directorist_Base'` |
| [inc/Services/DirectoristDependencyService.php:32](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/DirectoristDependencyService.php#L32) | defined | `'ATBDP_VERSION'` |
| [inc/Services/DirectoristDependencyService.php:32](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/DirectoristDependencyService.php#L32) | defined | `'ATBDP_POST_TYPE'` |
| [inc/Services/DirectoristQueryInterceptor.php:12](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/DirectoristQueryInterceptor.php#L12) | defined | `'ABSPATH'` |
| [inc/Services/DirectoristQueryInterceptor.php:129](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/DirectoristQueryInterceptor.php#L129) | defined | `'ATBDP_POST_TYPE'` |
| [inc/Services/DirectoristQueryInterceptor.php:305](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/DirectoristQueryInterceptor.php#L305) | function_exists | `'directorist_get_directories'` |
| [inc/Services/DirectoristQueryInterceptor.php:371](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/DirectoristQueryInterceptor.php#L371) | function_exists | `'directorist_get_default_directory'` |
| [inc/Services/FrontendAssetsService.php:12](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendAssetsService.php#L12) | defined | `'ABSPATH'` |
| [inc/Services/FrontendAssetsService.php:67](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendAssetsService.php#L67) | function_exists | `'directorist_require_style'` |
| [inc/Services/FrontendRestController.php:13](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendRestController.php#L13) | defined | `'ABSPATH'` |
| [inc/Services/FrontendResultsService.php:14](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendResultsService.php#L14) | defined | `'ABSPATH'` |
| [inc/Services/FrontendResultsService.php:130](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendResultsService.php#L130) | function_exists | `'directorist_is_multi_directory_enabled'` |
| [inc/Services/FrontendResultsService.php:130](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendResultsService.php#L130) | function_exists | `'directorist_get_default_directory'` |
| [inc/Services/FrontendResultsService.php:155](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendResultsService.php#L155) | function_exists | `'directorist_get_directory_meta'` |
| [inc/Services/FrontendResultsService.php:156](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendResultsService.php#L156) | function_exists | `'directorist_icon'` |
| [inc/Services/FrontendResultsService.php:157](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendResultsService.php#L157) | class_exists | `Helper::class` |
| [inc/Services/FrontendResultsService.php:160](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendResultsService.php#L160) | function_exists | `'directorist_get_listing_preview_image'` |
| [inc/Services/FrontendResultsService.php:162](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendResultsService.php#L162) | function_exists | `'atbdp_get_image_source'` |
| [inc/Services/FrontendResultsService.php:167](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendResultsService.php#L167) | function_exists | `'get_the_post_thumbnail_url'` |
| [inc/Services/FrontendResultsService.php:177](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendResultsService.php#L177) | defined | `'ATBDP_DIRECTORY_TYPE'` |
| [inc/Services/FrontendResultsService.php:177](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/FrontendResultsService.php#L177) | defined | `'ATBDP_TYPE'` |
| [inc/Services/ListingPayloadService.php:12](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/ListingPayloadService.php#L12) | defined | `'ABSPATH'` |
| [inc/Services/ListingPayloadService.php:206](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/ListingPayloadService.php#L206) | defined | `'ATBDP_POST_TYPE'` |
| [inc/Services/ListingService.php:12](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/ListingService.php#L12) | defined | `'ABSPATH'` |
| [inc/Services/ListingService.php:688](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/ListingService.php#L688) | defined | `'ATBDP_POST_TYPE'` |
| [inc/Services/SearchContextService.php:12](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/SearchContextService.php#L12) | defined | `'ABSPATH'` |
| [inc/Services/SearchContextService.php:435](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/SearchContextService.php#L435) | function_exists | `'directorist_get_distance_range'` |
| [inc/Services/SearchContextService.php:449](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/SearchContextService.php#L449) | function_exists | `'directorist_get_distance_range'` |
| [inc/Services/SearchContextService.php:471](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/SearchContextService.php#L471) | function_exists | `'directorist_get_rating_field_meta_key'` |
| [inc/Services/SearchContextService.php:550](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/SearchContextService.php#L550) | defined | `'ATBDP_POST_TYPE'` |
| [inc/Services/SearchContextService.php:554](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/SearchContextService.php#L554) | defined | `'ATBDP_DIRECTORY_TYPE'` |
| [inc/Services/SearchContextService.php:554](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/SearchContextService.php#L554) | defined | `'ATBDP_TYPE'` |
| [inc/Services/SearchContextService.php:558](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/SearchContextService.php#L558) | defined | `'ATBDP_CATEGORY'` |
| [inc/Services/SearchContextService.php:562](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/SearchContextService.php#L562) | defined | `'ATBDP_LOCATION'` |
| [inc/Services/SearchContextService.php:566](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/SearchContextService.php#L566) | defined | `'ATBDP_TAGS'` |
| [inc/Services/SemanticCandidateService.php:12](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/SemanticCandidateService.php#L12) | defined | `'ABSPATH'` |
| [inc/Services/SettingsService.php:12](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/SettingsService.php#L12) | defined | `'ABSPATH'` |
| [inc/Services/SettingsService.php:195](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/SettingsService.php#L195) | defined | `'DIRECTORIST_AI_SEARCH_SITE_API_KEY'` |
| [inc/Services/SettingsService.php:207](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/SettingsService.php#L207) | defined | `'DIRECTORIST_AI_SEARCH_PROVISIONING_KEY'` |
| [inc/Services/SettingsService.php:491](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/SettingsService.php#L491) | defined | `$name` |
| [inc/Services/SettingsService.php:547](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/SettingsService.php#L547) | function_exists | `'wp_generate_uuid4'` |
| [inc/Services/SettingsService.php:660](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/SettingsService.php#L660) | function_exists | `'update_directorist_option'` |
| [inc/Services/SettingsService.php:676](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/SettingsService.php#L676) | function_exists | `'get_directorist_option'` |
| [inc/Services/UniversalSearchInterceptor.php:13](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Services/UniversalSearchInterceptor.php#L13) | defined | `'ABSPATH'` |
| [inc/Traits/Singleton.php:10](https://github.com/sovware/directorist-ai-search/blob/07f49c7e6c2ba400e756f69b815b4febaf4d886f/inc/Traits/Singleton.php#L10) | defined | `'ABSPATH'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
