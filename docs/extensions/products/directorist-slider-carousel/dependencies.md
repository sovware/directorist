# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [bd-directorist-slider.php:16](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L16) | defined | `'ABSPATH'` |
| [bd-directorist-slider.php:17](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L17) | class_exists | `'BD_Slider_Carousel'` |
| [bd-directorist-slider.php:64](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L64) | class_exists | `'EDD_SL_Plugin_Updater'` |
| [bd-directorist-slider.php:398](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L398) | class_exists | `'Directorist_Base'` |
| [bd-directorist-slider.php:1152](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L1152) | defined | `'BDSC_FILE'` |
| [bd-directorist-slider.php:1164](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L1164) | function_exists | `'directorist_is_plugin_active'` |
| [bd-directorist-slider.php:1170](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L1170) | function_exists | `'directorist_is_plugin_active_for_network'` |
| [config.php:3](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/config.php#L3) | defined | `'BDSC_VERSION'` |
| [config.php:5](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/config.php#L5) | defined | `'BDSC_DIR'` |
| [config.php:7](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/config.php#L7) | defined | `'BDSC_URL'` |
| [config.php:9](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/config.php#L9) | defined | `'BDSC_BASE'` |
| [config.php:11](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/config.php#L11) | defined | `'BDSC_TEXTDOMAIN'` |
| [config.php:13](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/config.php#L13) | defined | `'BDSC_ASSETS'` |
| [config.php:15](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/config.php#L15) | defined | `'BDSC_LANG_DIR'` |
| [config.php:17](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/config.php#L17) | defined | `'BDSC_NAME'` |
| [config.php:19](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/config.php#L19) | defined | `'ATBDP_AUTHOR_URL'` |
| [config.php:23](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/config.php#L23) | defined | `'ATBDP_SLIDER_POST_ID'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
