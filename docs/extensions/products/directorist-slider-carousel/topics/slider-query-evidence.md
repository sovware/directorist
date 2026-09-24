# Listings Slider & Carousel: slider-query

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Shortcode attributes select/order listings; this extension is different from Core listing-card image slides and client-specific daily sliders.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Create listings with distinct category/date/featured state, set shortcode filters/count and compare returned IDs and ordering.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Entry points and feature coverage

Read the canonical snapshot rows first. Local/default/development rows are available for variant comparison only. Every matching source file is listed with declarations; supporting styles/configuration may have no symbols.

| Snapshot / source | Functions or classes |
| --- | --- |
| slider-carousel--beta / [bd-directorist-slider.php:1](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L1) | `BD_Slider_Carousel` (L18), `instance` (L44), `update_controller` (L63), `__construct` (L81), `atbdp_listing_type_settings_field_list` (L85), `atbdp_extension_settings_submenus` (L363), `load_needed_scripts` (L386), `directorist_slider_carousel` (L395), `__clone` (L1093), `__wakeup` (L1106), `load_textdomain` (L1115), `get_version_from_file_content` (L1120), `get_version_from_content` (L1133), `setup_constants` (L1150), `directorist_is_plugin_active` (L1165), `directorist_is_plugin_active_for_network` (L1171), `BD_Slider_Carousel` (L1198) |

## Data readers, writers and lifecycle

Keys/callback expressions below are extracted without stored values. `get` reads; `update/add/delete` writes; scheduled/remote calls are side effects. ORM repositories and schema definitions are linked as source even where literal-key extraction cannot resolve them.

| Snapshot / source | Operation and key |
| --- | --- |
| slider-carousel--beta / [bd-directorist-slider.php:50](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L50) | `add_action('plugins_loaded', array(self::$instance, 'load_textdomain'))` |
| slider-carousel--beta / [bd-directorist-slider.php:51](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L51) | `add_action('admin_enqueue_scripts', array(self::$instance, 'load_needed_scripts'))` |
| slider-carousel--beta / [bd-directorist-slider.php:54](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L54) | `add_filter('atbdp_listing_type_settings_field_list', array( self::$instance, 'atbdp_listing_type_settings_field_list' ))` |
| slider-carousel--beta / [bd-directorist-slider.php:55](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L55) | `add_filter('atbdp_extension_settings_submenu', array( self::$instance, 'atbdp_extension_settings_submenus' ))` |
| slider-carousel--beta / [bd-directorist-slider.php:57](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L57) | `add_action('admin_init', [ self::$instance, 'update_controller' ])` |
| slider-carousel--beta / [bd-directorist-slider.php:69](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L69) | `get_user_meta(get_current_user_id(), '_plugins_available_in_subscriptions')` |
| slider-carousel--beta / [bd-directorist-slider.php:367](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L367) | `apply_filters('atbdp_booking_settings_controls')` |
| slider-carousel--beta / [bd-directorist-slider.php:399](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L399) | `get_directorist_option('atbdp_legacy_template')` |
| slider-carousel--beta / [bd-directorist-slider.php:417](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L417) | `get_directorist_option('listing_display_by')` |
| slider-carousel--beta / [bd-directorist-slider.php:418](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L418) | `get_directorist_option('listing_type')` |
| slider-carousel--beta / [bd-directorist-slider.php:419](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L419) | `get_directorist_option('slider_cropping')` |
| slider-carousel--beta / [bd-directorist-slider.php:420](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L420) | `get_directorist_option('slider_image_width')` |
| slider-carousel--beta / [bd-directorist-slider.php:421](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L421) | `get_directorist_option('slider_image_height')` |
| slider-carousel--beta / [bd-directorist-slider.php:422](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L422) | `get_directorist_option('display_slider_title')` |
| slider-carousel--beta / [bd-directorist-slider.php:423](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L423) | `get_directorist_option('display_slider_excerpt')` |
| slider-carousel--beta / [bd-directorist-slider.php:424](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L424) | `get_directorist_option('excerpt_words_limit')` |
| slider-carousel--beta / [bd-directorist-slider.php:425](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L425) | `get_directorist_option('display_thumbnail')` |
| slider-carousel--beta / [bd-directorist-slider.php:426](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L426) | `get_directorist_option('slider_thumbnail_columns')` |
| slider-carousel--beta / [bd-directorist-slider.php:427](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L427) | `get_directorist_option('slider_navigation')` |
| slider-carousel--beta / [bd-directorist-slider.php:428](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L428) | `get_directorist_option('thumbnail_navigation')` |
| slider-carousel--beta / [bd-directorist-slider.php:429](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L429) | `get_directorist_option('carousel_cropping')` |
| slider-carousel--beta / [bd-directorist-slider.php:430](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L430) | `get_directorist_option('carousel_image_width')` |
| slider-carousel--beta / [bd-directorist-slider.php:431](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L431) | `get_directorist_option('carousel_image_height')` |
| slider-carousel--beta / [bd-directorist-slider.php:432](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L432) | `get_directorist_option('display_carousel_title')` |
| slider-carousel--beta / [bd-directorist-slider.php:433](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L433) | `get_directorist_option('ls_display_publish_date')` |
| slider-carousel--beta / [bd-directorist-slider.php:434](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L434) | `get_directorist_option('ls_display_category')` |
| slider-carousel--beta / [bd-directorist-slider.php:435](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L435) | `get_directorist_option('carousel_columns_desktop')` |
| slider-carousel--beta / [bd-directorist-slider.php:436](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L436) | `get_directorist_option('carousel_columns_tab')` |
| slider-carousel--beta / [bd-directorist-slider.php:437](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L437) | `get_directorist_option('carousel_columns_mobile')` |
| slider-carousel--beta / [bd-directorist-slider.php:438](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L438) | `get_directorist_option('carousel_navigation')` |
| slider-carousel--beta / [bd-directorist-slider.php:439](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L439) | `get_directorist_option('nav_position')` |
| slider-carousel--beta / [bd-directorist-slider.php:440](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L440) | `get_directorist_option('enable_featured_listing')` |
| slider-carousel--beta / [bd-directorist-slider.php:441](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L441) | `get_directorist_option('number_of_listing')` |
| slider-carousel--beta / [bd-directorist-slider.php:442](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L442) | `get_directorist_option('slider_autoplay')` |
| slider-carousel--beta / [bd-directorist-slider.php:443](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L443) | `get_directorist_option('slider_autoplay_speed')` |
| slider-carousel--beta / [bd-directorist-slider.php:444](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L444) | `get_directorist_option('carousel_autoplay')` |
| slider-carousel--beta / [bd-directorist-slider.php:445](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L445) | `get_directorist_option('carousel_autoplay_speed')` |
| slider-carousel--beta / [bd-directorist-slider.php:804](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L804) | `get_post_meta(get_the_ID(), '_listing_img')` |
| slider-carousel--beta / [bd-directorist-slider.php:805](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L805) | `get_post_meta(get_the_ID(), '_listing_prv_img')` |
| slider-carousel--beta / [bd-directorist-slider.php:806](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L806) | `get_post_meta(get_the_ID(), '_excerpt')` |
| slider-carousel--beta / [bd-directorist-slider.php:807](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L807) | `get_directorist_option('preview_image')` |
| slider-carousel--beta / [bd-directorist-slider.php:817](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L817) | `get_post_meta(get_the_ID(), '_listing_img')` |
| slider-carousel--beta / [bd-directorist-slider.php:818](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L818) | `get_post_meta(get_the_ID(), '_listing_prv_img')` |
| slider-carousel--beta / [bd-directorist-slider.php:819](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L819) | `get_post_meta(get_the_ID(), '_excerpt')` |
| slider-carousel--beta / [bd-directorist-slider.php:875](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L875) | `get_post_meta(get_the_ID(), '_listing_img')` |
| slider-carousel--beta / [bd-directorist-slider.php:876](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L876) | `get_post_meta(get_the_ID(), '_listing_prv_img')` |
| slider-carousel--beta / [bd-directorist-slider.php:961](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L961) | `get_post_meta(get_the_ID(), '_listing_img')` |
| slider-carousel--beta / [bd-directorist-slider.php:962](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L962) | `get_post_meta(get_the_ID(), '_listing_prv_img')` |
| slider-carousel--beta / [bd-directorist-slider.php:1166](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L1166) | `get_option('active_plugins')` |
| slider-carousel--beta / [bd-directorist-slider.php:1176](https://github.com/sovware/directorist-slider-carousel/blob/fa74bc9ece627c2c36bf5c2464dc2f3710fec438/bd-directorist-slider.php#L1176) | `get_site_option('active_sitewide_plugins')` |

## Core and integration context

Load [Core listing/form/query contracts](../../../core/listings.md) for a form/data/query issue, [rendering and builders](../../../core/rendering.md) for display, or [payment lifecycle](../../../core/payments.md) for orders. Do not load all three automatically.

For exact cross-repository hook matches, use `python3 docs/extensions/scripts/query.py hooks HOOK_NAME`; a same-name hook is only a candidate edge. For dynamic calls, inspect the referenced source and actual active callback list.
