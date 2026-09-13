# Public contracts and dependency evidence

Literal registrations, emissions and guards extracted with PHP tokenization. Dynamic hooks/callbacks need source evaluation; matching a hook name is a candidate relationship, not proof that a callback executes. Values of options/credentials are not collected.

| Snapshot / source | Call / key / callback |
| --- | --- |
| slider-carousel--beta / [bd-directorist-slider.php:16](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L16) | `defined('ABSPATH')` |
| slider-carousel--beta / [bd-directorist-slider.php:17](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L17) | `class_exists('BD_Slider_Carousel')` |
| slider-carousel--beta / [bd-directorist-slider.php:50](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L50) | `add_action('plugins_loaded', array(self::$instance, 'load_textdomain'))` |
| slider-carousel--beta / [bd-directorist-slider.php:51](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L51) | `add_action('admin_enqueue_scripts', array(self::$instance, 'load_needed_scripts'))` |
| slider-carousel--beta / [bd-directorist-slider.php:54](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L54) | `add_filter('atbdp_listing_type_settings_field_list', array( self::$instance, 'atbdp_listing_type_settings_field_list' ))` |
| slider-carousel--beta / [bd-directorist-slider.php:55](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L55) | `add_filter('atbdp_extension_settings_submenu', array( self::$instance, 'atbdp_extension_settings_submenus' ))` |
| slider-carousel--beta / [bd-directorist-slider.php:57](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L57) | `add_action('admin_init', [ self::$instance, 'update_controller' ])` |
| slider-carousel--beta / [bd-directorist-slider.php:64](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L64) | `class_exists('EDD_SL_Plugin_Updater')` |
| slider-carousel--beta / [bd-directorist-slider.php:367](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L367) | `apply_filters('atbdp_booking_settings_controls')` |
| slider-carousel--beta / [bd-directorist-slider.php:398](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L398) | `class_exists('Directorist_Base')` |
| slider-carousel--beta / [bd-directorist-slider.php:1152](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L1152) | `defined('BDSC_FILE')` |
| slider-carousel--beta / [bd-directorist-slider.php:1164](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L1164) | `function_exists('directorist_is_plugin_active')` |
| slider-carousel--beta / [bd-directorist-slider.php:1170](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L1170) | `function_exists('directorist_is_plugin_active_for_network')` |
| slider-carousel--beta / [config.php:3](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/config.php#L3) | `defined('BDSC_VERSION')` |
| slider-carousel--beta / [config.php:5](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/config.php#L5) | `defined('BDSC_DIR')` |
| slider-carousel--beta / [config.php:7](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/config.php#L7) | `defined('BDSC_URL')` |
| slider-carousel--beta / [config.php:9](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/config.php#L9) | `defined('BDSC_BASE')` |
| slider-carousel--beta / [config.php:11](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/config.php#L11) | `defined('BDSC_TEXTDOMAIN')` |
| slider-carousel--beta / [config.php:13](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/config.php#L13) | `defined('BDSC_ASSETS')` |
| slider-carousel--beta / [config.php:15](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/config.php#L15) | `defined('BDSC_LANG_DIR')` |
| slider-carousel--beta / [config.php:17](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/config.php#L17) | `defined('BDSC_NAME')` |
| slider-carousel--beta / [config.php:19](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/config.php#L19) | `defined('ATBDP_AUTHOR_URL')` |
| slider-carousel--beta / [config.php:23](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/config.php#L23) | `defined('ATBDP_SLIDER_POST_ID')` |
