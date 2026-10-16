# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [directorist-listing-import.php:17](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/directorist-listing-import.php#L17) | defined | `'ABSPATH'` |
| [directorist-listing-import.php:67](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/directorist-listing-import.php#L67) | class_exists | `'EDD_SL_Plugin_Updater'` |
| [directorist-listing-import.php:93](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/directorist-listing-import.php#L93) | defined | `'ATBDP_VERSION'` |
| [directorist-listing-import.php:132](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/directorist-listing-import.php#L132) | class_exists | `'\DLIG\Installer'` |
| [admin/class-admin-page.php:6](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/admin/class-admin-page.php#L6) | defined | `'ABSPATH'` |
| [admin/class-admin-page.php:92](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/admin/class-admin-page.php#L92) | class_exists | `'\DLIG\Plugin'` |
| [admin/views/admin-page.php:8](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/admin/views/admin-page.php#L8) | defined | `'ABSPATH'` |
| [admin/views/admin-page.php:30](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/admin/views/admin-page.php#L30) | defined | `'ATBDP_DIRECTORY_TYPE'` |
| [admin/views/admin-page.php:128](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/admin/views/admin-page.php#L128) | defined | `'ATBDP_DIRECTORY_TYPE'` |
| [google/includes/class-admin-page.php:23](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-admin-page.php#L23) | defined | `'ABSPATH'` |
| [google/includes/class-admin-page.php:766](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-admin-page.php#L766) | defined | `'ATBDP_DIRECTORY_TYPE'` |
| [google/includes/class-ajax-import.php:27](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-ajax-import.php#L27) | defined | `'ABSPATH'` |
| [google/includes/class-field-mapping.php:14](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-field-mapping.php#L14) | defined | `'ABSPATH'` |
| [google/includes/class-field-mapping.php:170](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-field-mapping.php#L170) | function_exists | `'directorist_get_default_directory'` |
| [google/includes/class-field-mapping.php:174](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-field-mapping.php#L174) | function_exists | `'default_directory_type'` |
| [google/includes/class-field-mapping.php:187](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-field-mapping.php#L187) | defined | `'ATBDP_DIRECTORY_TYPE'` |
| [google/includes/class-field-mapping.php:611](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-field-mapping.php#L611) | function_exists | `'directorist_get_listing_form_fields'` |
| [google/includes/class-field-mapping.php:784](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-field-mapping.php#L784) | class_exists | `'\Directorist\Fields\Fields'` |
| [google/includes/class-field-mapping.php:1259](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-field-mapping.php#L1259) | defined | `'ATBDP_CATEGORY'` |
| [google/includes/class-field-mapping.php:1266](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-field-mapping.php#L1266) | defined | `'ATBDP_LOCATION'` |
| [google/includes/class-field-mapping.php:1273](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-field-mapping.php#L1273) | defined | `'ATBDP_TAGS'` |
| [google/includes/class-google-places-client.php:15](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-google-places-client.php#L15) | defined | `'ABSPATH'` |
| [google/includes/class-google-places-client.php:66](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-google-places-client.php#L66) | wp_remote_post | `self::SEARCH_ENDPOINT` |
| [google/includes/class-google-places-client.php:87](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-google-places-client.php#L87) | wp_remote_retrieve_body | `$response` |
| [google/includes/class-google-places-client.php:199](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-google-places-client.php#L199) | wp_remote_post | `self::SEARCH_ENDPOINT` |
| [google/includes/class-google-places-client.php:217](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-google-places-client.php#L217) | wp_remote_retrieve_response_code | `$response` |
| [google/includes/class-google-places-client.php:218](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-google-places-client.php#L218) | wp_remote_retrieve_body | `$response` |
| [google/includes/class-google-places-client.php:262](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-google-places-client.php#L262) | wp_remote_get | `$url` |
| [google/includes/class-google-places-client.php:278](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-google-places-client.php#L278) | wp_remote_retrieve_body | `$response` |
| [google/includes/class-google-places-client.php:551](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-google-places-client.php#L551) | wp_remote_get | `$url` |
| [google/includes/class-google-places-client.php:568](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-google-places-client.php#L568) | wp_remote_retrieve_response_code | `$response` |
| [google/includes/class-google-places-client.php:569](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-google-places-client.php#L569) | wp_remote_retrieve_body | `$response` |
| [google/includes/class-google-places-client.php:570](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-google-places-client.php#L570) | wp_remote_retrieve_header | `$response` |
| [google/includes/class-google-places-client.php:596](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-google-places-client.php#L596) | defined | `'WP_DEBUG'` |
| [google/includes/class-importer.php:17](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-importer.php#L17) | defined | `'ABSPATH'` |
| [google/includes/class-importer.php:539](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-importer.php#L539) | function_exists | `'directorist_set_listing_directory'` |
| [google/includes/class-importer.php:603](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-importer.php#L603) | function_exists | `'media_sideload_image'` |
| [google/includes/class-importer.php:691](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-importer.php#L691) | defined | `'WP_DEBUG'` |
| [google/includes/class-importer.php:703](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-importer.php#L703) | defined | `'ATBDP_BUSINESS_HOURS'` |
| [google/includes/class-importer.php:704](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-importer.php#L704) | class_exists | `'Directorist_Business_Hour'` |
| [google/includes/class-importer.php:705](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-importer.php#L705) | class_exists | `'ATBDP_Business_Hours'` |
| [google/includes/class-installer.php:10](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-installer.php#L10) | defined | `'ABSPATH'` |
| [google/includes/class-installer.php:24](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-installer.php#L24) | defined | `'ATBDP_VERSION'` |
| [google/includes/class-plugin.php:10](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-plugin.php#L10) | defined | `'ABSPATH'` |
| [google/includes/class-plugin.php:75](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-plugin.php#L75) | function_exists | `'directorist_set_listing_directory'` |
| [google/includes/class-plugin.php:75](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-plugin.php#L75) | function_exists | `'directorist_get_default_directory'` |
| [google/includes/class-plugin.php:84](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-plugin.php#L84) | defined | `'ATBDP_DIRECTORY_TYPE'` |
| [google/includes/class-review-mapper.php:10](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-review-mapper.php#L10) | defined | `'ABSPATH'` |
| [google/includes/class-review-mapper.php:160](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-review-mapper.php#L160) | class_exists | `'\Directorist\Review\Listing_Review_Meta'` |
| [google/includes/class-settings.php:15](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-settings.php#L15) | defined | `'ABSPATH'` |
| [google/includes/class-settings.php:256](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-settings.php#L256) | function_exists | `'openssl_encrypt'` |
| [google/includes/class-settings.php:256](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-settings.php#L256) | function_exists | `'openssl_random_pseudo_bytes'` |
| [google/includes/class-settings.php:295](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-settings.php#L295) | function_exists | `'openssl_decrypt'` |
| [google/includes/class-settings.php:342](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/google/includes/class-settings.php#L342) | defined | `'DGBI_ENCRYPTION_SALT'` |
| [includes/class-feed-discovery.php:6](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/includes/class-feed-discovery.php#L6) | defined | `'ABSPATH'` |
| [includes/class-feed-discovery.php:67](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/includes/class-feed-discovery.php#L67) | function_exists | `'fetch_feed'` |
| [includes/class-feed-discovery.php:91](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/includes/class-feed-discovery.php#L91) | function_exists | `'fetch_feed'` |
| [includes/class-feed-discovery.php:156](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/includes/class-feed-discovery.php#L156) | function_exists | `'fetch_feed'` |
| [includes/class-feed-discovery.php:213](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/includes/class-feed-discovery.php#L213) | wp_remote_get | `$url` |
| [includes/class-feed-discovery.php:228](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/includes/class-feed-discovery.php#L228) | wp_remote_retrieve_response_code | `$response` |
| [includes/class-feed-discovery.php:247](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/includes/class-feed-discovery.php#L247) | wp_remote_retrieve_body | `$response` |
| [includes/class-feed-discovery.php:261](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/includes/class-feed-discovery.php#L261) | class_exists | `'DOMDocument'` |
| [includes/class-feed-manager.php:21](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/includes/class-feed-manager.php#L21) | defined | `'ABSPATH'` |
| [includes/class-importer.php:13](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/includes/class-importer.php#L13) | defined | `'ABSPATH'` |
| [includes/class-importer.php:15](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/includes/class-importer.php#L15) | defined | `'DLI_META_SOURCE_URL'` |
| [includes/class-importer.php:105](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/includes/class-importer.php#L105) | class_exists | `'SimplePie'` |
| [includes/class-importer.php:179](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/includes/class-importer.php#L179) | function_exists | `'directorist_set_listing_directory'` |
| [includes/class-importer.php:181](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/includes/class-importer.php#L181) | function_exists | `'directorist_get_default_directory'` |
| [includes/class-importer.php:181](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/includes/class-importer.php#L181) | function_exists | `'directorist_set_listing_directory'` |
| [includes/class-importer.php:262](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/includes/class-importer.php#L262) | function_exists | `'media_sideload_image'` |
| [includes/class-scheduler.php:6](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/includes/class-scheduler.php#L6) | defined | `'ABSPATH'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
