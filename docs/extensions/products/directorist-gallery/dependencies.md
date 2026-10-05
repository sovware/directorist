# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [bd-directorist-gallery.php:15](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/bd-directorist-gallery.php#L15) | defined | `'ABSPATH'` |
| [bd-directorist-gallery.php:16](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/bd-directorist-gallery.php#L16) | class_exists | `'BD_Gallery'` |
| [bd-directorist-gallery.php:194](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/bd-directorist-gallery.php#L194) | function_exists | `'atbdp_image_cropping'` |
| [bd-directorist-gallery.php:241](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/bd-directorist-gallery.php#L241) | wp_remote_post | `ATBDP_AUTHOR_URL` |
| [bd-directorist-gallery.php:243](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/bd-directorist-gallery.php#L243) | wp_remote_retrieve_response_code | `$response` |
| [bd-directorist-gallery.php:250](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/bd-directorist-gallery.php#L250) | wp_remote_retrieve_body | `$response` |
| [bd-directorist-gallery.php:335](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/bd-directorist-gallery.php#L335) | wp_remote_post | `ATBDP_AUTHOR_URL` |
| [bd-directorist-gallery.php:337](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/bd-directorist-gallery.php#L337) | wp_remote_retrieve_response_code | `$response` |
| [bd-directorist-gallery.php:344](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/bd-directorist-gallery.php#L344) | wp_remote_retrieve_body | `$response` |
| [bd-directorist-gallery.php:620](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/bd-directorist-gallery.php#L620) | defined | `'ATBDP_VERSION'` |
| [bd-directorist-gallery.php:943](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/bd-directorist-gallery.php#L943) | class_exists | `'EDD_SL_Plugin_Updater'` |
| [bd-directorist-gallery.php:1001](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/bd-directorist-gallery.php#L1001) | defined | `'BDG_FILE'` |
| [bd-directorist-gallery.php:1011](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/bd-directorist-gallery.php#L1011) | function_exists | `'directorist_is_plugin_active'` |
| [bd-directorist-gallery.php:1017](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/bd-directorist-gallery.php#L1017) | function_exists | `'directorist_is_plugin_active_for_network'` |
| [config.php:3](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/config.php#L3) | defined | `'BDG_VERSION'` |
| [config.php:5](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/config.php#L5) | defined | `'BDG_DIR'` |
| [config.php:7](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/config.php#L7) | defined | `'BDG_URL'` |
| [config.php:9](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/config.php#L9) | defined | `'BDG_BASE'` |
| [config.php:11](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/config.php#L11) | defined | `'BDG_TEXTDOMAIN'` |
| [config.php:13](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/config.php#L13) | defined | `'BDG_INC_DIR'` |
| [config.php:15](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/config.php#L15) | defined | `'BDG_ASSETS'` |
| [config.php:16](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/config.php#L16) | defined | `'BDG_ADMIN_ASSETS'` |
| [config.php:17](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/config.php#L17) | defined | `'BDG_FONT_ASSETS'` |
| [config.php:19](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/config.php#L19) | defined | `'BDG_TEMPLATES_DIR'` |
| [config.php:21](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/config.php#L21) | defined | `'BDG_LANG_DIR'` |
| [config.php:23](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/config.php#L23) | defined | `'ATBDP_AUTHOR_URL'` |
| [config.php:27](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/config.php#L27) | defined | `'ATBDP_GALLERY_POST_ID'` |
| [inc/directory_type.php:5](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/inc/directory_type.php#L5) | class_exists | `'Gallery_Post_Type_Manager'` |
| [inc/importer.php:3](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/inc/importer.php#L3) | class_exists | `'Directorist_Gallery_Data_Importer'` |
| [inc/importer.php:266](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/inc/importer.php#L266) | function_exists | `'download_url'` |
| [inc/importer.php:270](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/inc/importer.php#L270) | function_exists | `'media_handle_sideload'` |
| [inc/importer.php:274](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/inc/importer.php#L274) | function_exists | `'wp_read_image_metadata'` |
| [templates/gallery_image_upload.php:3](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/templates/gallery_image_upload.php#L3) | defined | `'ABSPATH'` |
| [templates/view_gallery.php:3](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/templates/view_gallery.php#L3) | defined | `'ABSPATH'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
