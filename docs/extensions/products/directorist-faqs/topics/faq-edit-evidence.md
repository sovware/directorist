# Listing FAQs: faq-edit

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Frontend/admin FAQ submission and dynamic row actions must preserve question/answer pairing; plan restrictions can hide the field independently.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Create three distinct FAQs, delete middle row, reorder if supported, save/reopen and inspect values. Test author ownership, empty answer and markup escaping.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Entry points and feature coverage

Read the canonical snapshot rows first. Local/default/development rows are available for variant comparison only. Every matching source file is listed with declarations; supporting styles/configuration may have no symbols.

| Snapshot / source | Functions or classes |
| --- | --- |
| faqs--development / [assets/js/admin-main.js:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/assets/js/admin-main.js#L1) | `handles` (L3), `atbdp_do_ajax` (L4) |
| faqs--development / [assets/js/main.js:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/assets/js/main.js#L1) | `handles` (L3), `atbdp_do_ajax` (L4) |
| faqs--development / [directorist-faqs.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L1) | `Listings_fAQs` (L18), `instance` (L45), `__construct` (L85), `atbdp_extension_fields` (L91), `atbdp_listing_type_settings_field_list` (L96), `atbdp_extension_settings_submenus` (L122), `__clone` (L147), `__wakeup` (L160), `update_controller` (L166), `atbdp_faqs_license_deactivation` (L180), `atbdp_faqs_license_activation` (L274), `atbdp_show_faqs` (L368), `atbdp_shortcode_faqa` (L383), `atbdp_new_metabox` (L403), `add_new_faq_admin` (L413), `atbdp_faqs_ajax_handler` (L428), `load_needed_scripts` (L435), `load_needed_scripts_admin` (L452), `register_widget` (L495), `load_template` (L506), `load_textdomain` (L514), `includes` (L531), `get_version_from_file_content` (L544), `get_version_from_content` (L557), `setup_constants` (L575), `directorist_is_plugin_active` (L585), `directorist_is_plugin_active_for_network` (L591), `Listings_fAQs` (L619) |
| faqs--development / [inc/directory_type.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/inc/directory_type.php#L1) | `FAQS_Post_Type_Manager` (L6), `__construct` (L8), `save` (L19), `directorist_single_item_template` (L28), `directorist_field_template` (L44), `atbdp_single_listing_content_widgets` (L59), `atbdp_form_builder_widgets` (L72) |
| faqs--development / [inc/helper-functions.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/inc/helper-functions.php#L1) | `atbdp_get_option` (L15), `atbdp_sanitize_array` (L42), `dfaqs_get_template` (L66) |
| faqs--development / [templates/ajax/faqs-ajax.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/templates/ajax/faqs-ajax.php#L1) | template / configuration / styling; inspect file |
| faqs--development / [templates/faqs.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/templates/faqs.php#L1) | template / configuration / styling; inspect file |
| faqs--development / [templates/view-faqs.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/templates/view-faqs.php#L1) | template / configuration / styling; inspect file |
| faqs--development / [widgets/class-widget.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/widgets/class-widget.php#L1) | `FAQs_Widget` (L4), `__construct` (L9), `widget` (L29), `form` (L83), `update` (L103) |
| faqs--main / [directorist-faqs.php:1](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L1) | `Listings_fAQs` (L18), `instance` (L45), `__construct` (L85), `atbdp_extension_fields` (L91), `atbdp_listing_type_settings_field_list` (L96), `atbdp_extension_settings_submenus` (L122), `__clone` (L147), `__wakeup` (L160), `update_controller` (L166), `atbdp_faqs_license_deactivation` (L180), `atbdp_faqs_license_activation` (L274), `atbdp_show_faqs` (L368), `atbdp_shortcode_faqa` (L383), `atbdp_new_metabox` (L403), `add_new_faq_admin` (L413), `atbdp_faqs_ajax_handler` (L428), `load_needed_scripts` (L435), `load_needed_scripts_admin` (L452), `register_widget` (L495), `load_template` (L506), `load_textdomain` (L514), `includes` (L531), `get_version_from_file_content` (L544), `get_version_from_content` (L557), `setup_constants` (L575), `directorist_is_plugin_active` (L585), `directorist_is_plugin_active_for_network` (L591), `Listings_fAQs` (L619) |
| faqs--main / [widgets/class-widget.php:1](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/widgets/class-widget.php#L1) | `FAQs_Widget` (L4), `__construct` (L9), `widget` (L29), `form` (L80), `update` (L100) |

## Data readers, writers and lifecycle

Keys/callback expressions below are extracted without stored values. `get` reads; `update/add/delete` writes; scheduled/remote calls are side effects. ORM repositories and schema definitions are linked as source even where literal-key extraction cannot resolve them.

| Snapshot / source | Operation and key |
| --- | --- |
| faqs--development / [directorist-faqs.php:51](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L51) | `add_action('init', array(self::$instance, 'load_textdomain'))` |
| faqs--development / [directorist-faqs.php:52](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L52) | `add_action('admin_enqueue_scripts', array(self::$instance, 'load_needed_scripts_admin'))` |
| faqs--development / [directorist-faqs.php:53](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L53) | `add_action('wp_enqueue_scripts', array(self::$instance, 'load_needed_scripts'))` |
| faqs--development / [directorist-faqs.php:54](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L54) | `add_action('admin_init', [ self::$instance, 'update_controller' ])` |
| faqs--development / [directorist-faqs.php:63](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L63) | `add_action('widgets_init', array(self::$instance, 'register_widget'))` |
| faqs--development / [directorist-faqs.php:66](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L66) | `add_filter('atbdp_listing_type_settings_field_list', array( self::$instance, 'atbdp_listing_type_settings_field_list' ))` |
| faqs--development / [directorist-faqs.php:67](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L67) | `add_filter('atbdp_extension_fields', array( self::$instance, 'atbdp_extension_fields' ))` |
| faqs--development / [directorist-faqs.php:68](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L68) | `add_filter('atbdp_extension_settings_submenu', array( self::$instance, 'atbdp_extension_settings_submenus' ))` |
| faqs--development / [directorist-faqs.php:70](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L70) | `add_action('atbdp_after_video_metabox_backend_add_listing', array(self::$instance, 'atbdp_new_metabox'))` |
| faqs--development / [directorist-faqs.php:71](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L71) | `add_action('wp_ajax_atbdp_faqs_handler', array(self::$instance, 'atbdp_faqs_ajax_handler'))` |
| faqs--development / [directorist-faqs.php:72](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L72) | `add_action('wp_ajax_nopriv_atbdp_faqs_handler', array(self::$instance, 'atbdp_faqs_ajax_handler'))` |
| faqs--development / [directorist-faqs.php:73](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L73) | `add_action('atbdp_listing_faqs', array(self::$instance, 'atbdp_show_faqs'))` |
| faqs--development / [directorist-faqs.php:78](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L78) | `add_action('wp_ajax_atbdp_faqs_license_activation', array(self::$instance, 'atbdp_faqs_license_activation'))` |
| faqs--development / [directorist-faqs.php:80](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L80) | `add_action('wp_ajax_atbdp_faqs_license_deactivation', array(self::$instance, 'atbdp_faqs_license_deactivation'))` |
| faqs--development / [directorist-faqs.php:126](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L126) | `apply_filters('atbdp_faq_settings_controls')` |
| faqs--development / [directorist-faqs.php:167](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L167) | `get_user_meta(get_current_user_id(), '_plugins_available_in_subscriptions')` |
| faqs--development / [directorist-faqs.php:183](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L183) | `get_option('atbdp_option')` |
| faqs--development / [directorist-faqs.php:185](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L185) | `update_option('atbdp_option')` |
| faqs--development / [directorist-faqs.php:186](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L186) | `update_option('directorist_faqs_license')` |
| faqs--development / [directorist-faqs.php:197](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L197) | `wp_remote_post(ATBDP_AUTHOR_URL)` |
| faqs--development / [directorist-faqs.php:199](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L199) | `wp_remote_retrieve_response_code($response)` |
| faqs--development / [directorist-faqs.php:206](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L206) | `wp_remote_retrieve_body($response)` |
| faqs--development / [directorist-faqs.php:213](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L213) | `update_option('directorist_faqs_license_status')` |
| faqs--development / [directorist-faqs.php:219](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L219) | `get_option('date_format')` |
| faqs--development / [directorist-faqs.php:277](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L277) | `get_option('atbdp_option')` |
| faqs--development / [directorist-faqs.php:279](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L279) | `update_option('atbdp_option')` |
| faqs--development / [directorist-faqs.php:280](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L280) | `update_option('directorist_faqs_license')` |
| faqs--development / [directorist-faqs.php:291](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L291) | `wp_remote_post(ATBDP_AUTHOR_URL)` |
| faqs--development / [directorist-faqs.php:293](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L293) | `wp_remote_retrieve_response_code($response)` |
| faqs--development / [directorist-faqs.php:300](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L300) | `wp_remote_retrieve_body($response)` |
| faqs--development / [directorist-faqs.php:307](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L307) | `update_option('directorist_faqs_license_status')` |
| faqs--development / [directorist-faqs.php:313](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L313) | `get_option('date_format')` |
| faqs--development / [directorist-faqs.php:372](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L372) | `get_post_meta(get_the_ID(), '_fm_plans')` |
| faqs--development / [directorist-faqs.php:386](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L386) | `get_post_meta($post->ID, '_faqs')` |
| faqs--development / [directorist-faqs.php:392](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L392) | `get_post_meta($post->ID, '_fm_plans')` |
| faqs--development / [directorist-faqs.php:396](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L396) | `do_action('atbdp_listing_faqs')` |
| faqs--development / [directorist-faqs.php:415](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L415) | `get_directorist_option('enable_faqs')` |
| faqs--development / [directorist-faqs.php:419](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L419) | `get_post_meta($post->ID, '_faqs')` |
| faqs--development / [directorist-faqs.php:443](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L443) | `get_directorist_option('faqs_ans_box')` |
| faqs--development / [directorist-faqs.php:486](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L486) | `get_directorist_option('faqs_ans_box')` |
| faqs--development / [directorist-faqs.php:498](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L498) | `register_widget('FAQs_Widget')` |
| faqs--development / [directorist-faqs.php:519](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L519) | `apply_filters('plugin_locale')` |
| faqs--development / [directorist-faqs.php:586](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L586) | `get_option('active_plugins')` |
| faqs--development / [directorist-faqs.php:596](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L596) | `get_site_option('active_sitewide_plugins')` |
| faqs--development / [inc/directory_type.php:11](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/inc/directory_type.php#L11) | `add_filter('atbdp_form_preset_widgets', array($this, 'atbdp_form_builder_widgets'))` |
| faqs--development / [inc/directory_type.php:12](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/inc/directory_type.php#L12) | `add_filter('atbdp_single_listing_content_widgets', array($this, 'atbdp_single_listing_content_widgets'))` |
| faqs--development / [inc/directory_type.php:13](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/inc/directory_type.php#L13) | `add_filter('directorist_field_template', array( $this, 'directorist_field_template' ))` |
| faqs--development / [inc/directory_type.php:14](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/inc/directory_type.php#L14) | `add_filter('directorist_single_item_template', array( $this, 'directorist_single_item_template' ))` |
| faqs--development / [inc/directory_type.php:15](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/inc/directory_type.php#L15) | `add_filter('atbdp_ultimate_listing_meta_user_submission', array( $this, 'save' ))` |
| faqs--development / [inc/directory_type.php:35](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/inc/directory_type.php#L35) | `apply_filters('directorist_faqs_single_field_templete_on_demand')` |
| faqs--development / [inc/directory_type.php:50](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/inc/directory_type.php#L50) | `apply_filters('directorist_faqs_form_field_templete_on_demand')` |
| faqs--development / [inc/helper-functions.php:23](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/inc/helper-functions.php#L23) | `get_option($group)` |
| faqs--development / [templates/ajax/faqs-ajax.php:19](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/templates/ajax/faqs-ajax.php#L19) | `get_directorist_option('faqs_ans_box')` |
| faqs--development / [templates/faqs.php:12](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/templates/faqs.php#L12) | `get_directorist_option('enable_faqs')` |
| faqs--development / [widgets/class-widget.php:38](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/widgets/class-widget.php#L38) | `get_post_meta($listing_id, '_fm_plans')` |
| faqs--development / [widgets/class-widget.php:51](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/widgets/class-widget.php#L51) | `apply_filters('widget_title')` |
| faqs--development / [widgets/class-widget.php:57](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/widgets/class-widget.php#L57) | `get_post_meta($listing_id, '_faqs')` |
| faqs--main / [directorist-faqs.php:51](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L51) | `add_action('init', array(self::$instance, 'load_textdomain'))` |
| faqs--main / [directorist-faqs.php:52](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L52) | `add_action('admin_enqueue_scripts', array(self::$instance, 'load_needed_scripts_admin'))` |
| faqs--main / [directorist-faqs.php:53](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L53) | `add_action('wp_enqueue_scripts', array(self::$instance, 'load_needed_scripts'))` |
| faqs--main / [directorist-faqs.php:54](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L54) | `add_action('admin_init', [ self::$instance, 'update_controller' ])` |
| faqs--main / [directorist-faqs.php:63](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L63) | `add_action('widgets_init', array(self::$instance, 'register_widget'))` |
| faqs--main / [directorist-faqs.php:66](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L66) | `add_filter('atbdp_listing_type_settings_field_list', array( self::$instance, 'atbdp_listing_type_settings_field_list' ))` |
| faqs--main / [directorist-faqs.php:67](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L67) | `add_filter('atbdp_extension_fields', array( self::$instance, 'atbdp_extension_fields' ))` |
| faqs--main / [directorist-faqs.php:68](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L68) | `add_filter('atbdp_extension_settings_submenu', array( self::$instance, 'atbdp_extension_settings_submenus' ))` |
| faqs--main / [directorist-faqs.php:70](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L70) | `add_action('atbdp_after_video_metabox_backend_add_listing', array(self::$instance, 'atbdp_new_metabox'))` |
| faqs--main / [directorist-faqs.php:71](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L71) | `add_action('wp_ajax_atbdp_faqs_handler', array(self::$instance, 'atbdp_faqs_ajax_handler'))` |
| faqs--main / [directorist-faqs.php:72](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L72) | `add_action('wp_ajax_nopriv_atbdp_faqs_handler', array(self::$instance, 'atbdp_faqs_ajax_handler'))` |
| faqs--main / [directorist-faqs.php:73](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L73) | `add_action('atbdp_listing_faqs', array(self::$instance, 'atbdp_show_faqs'))` |
| faqs--main / [directorist-faqs.php:78](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L78) | `add_action('wp_ajax_atbdp_faqs_license_activation', array(self::$instance, 'atbdp_faqs_license_activation'))` |
| faqs--main / [directorist-faqs.php:80](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L80) | `add_action('wp_ajax_atbdp_faqs_license_deactivation', array(self::$instance, 'atbdp_faqs_license_deactivation'))` |
| faqs--main / [directorist-faqs.php:126](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L126) | `apply_filters('atbdp_faq_settings_controls')` |
| faqs--main / [directorist-faqs.php:167](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L167) | `get_user_meta(get_current_user_id(), '_plugins_available_in_subscriptions')` |
| faqs--main / [directorist-faqs.php:183](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L183) | `get_option('atbdp_option')` |
| faqs--main / [directorist-faqs.php:185](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L185) | `update_option('atbdp_option')` |
| faqs--main / [directorist-faqs.php:186](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L186) | `update_option('directorist_faqs_license')` |
| faqs--main / [directorist-faqs.php:197](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L197) | `wp_remote_post(ATBDP_AUTHOR_URL)` |
| faqs--main / [directorist-faqs.php:199](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L199) | `wp_remote_retrieve_response_code($response)` |
| faqs--main / [directorist-faqs.php:206](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L206) | `wp_remote_retrieve_body($response)` |
| faqs--main / [directorist-faqs.php:213](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L213) | `update_option('directorist_faqs_license_status')` |
| faqs--main / [directorist-faqs.php:219](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L219) | `get_option('date_format')` |
| faqs--main / [directorist-faqs.php:277](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L277) | `get_option('atbdp_option')` |
| faqs--main / [directorist-faqs.php:279](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L279) | `update_option('atbdp_option')` |
| faqs--main / [directorist-faqs.php:280](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L280) | `update_option('directorist_faqs_license')` |
| faqs--main / [directorist-faqs.php:291](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L291) | `wp_remote_post(ATBDP_AUTHOR_URL)` |
| faqs--main / [directorist-faqs.php:293](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L293) | `wp_remote_retrieve_response_code($response)` |
| faqs--main / [directorist-faqs.php:300](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L300) | `wp_remote_retrieve_body($response)` |
| faqs--main / [directorist-faqs.php:307](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L307) | `update_option('directorist_faqs_license_status')` |
| faqs--main / [directorist-faqs.php:313](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L313) | `get_option('date_format')` |
| faqs--main / [directorist-faqs.php:372](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L372) | `get_post_meta(get_the_ID(), '_fm_plans')` |
| faqs--main / [directorist-faqs.php:386](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L386) | `get_post_meta($post->ID, '_faqs')` |
| faqs--main / [directorist-faqs.php:392](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L392) | `get_post_meta($post->ID, '_fm_plans')` |
| faqs--main / [directorist-faqs.php:396](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L396) | `do_action('atbdp_listing_faqs')` |
| faqs--main / [directorist-faqs.php:415](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L415) | `get_directorist_option('enable_faqs')` |
| faqs--main / [directorist-faqs.php:419](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L419) | `get_post_meta($post->ID, '_faqs')` |
| faqs--main / [directorist-faqs.php:443](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L443) | `get_directorist_option('faqs_ans_box')` |
| faqs--main / [directorist-faqs.php:486](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L486) | `get_directorist_option('faqs_ans_box')` |
| faqs--main / [directorist-faqs.php:498](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L498) | `register_widget('FAQs_Widget')` |
| faqs--main / [directorist-faqs.php:519](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L519) | `apply_filters('plugin_locale')` |
| faqs--main / [directorist-faqs.php:586](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L586) | `get_option('active_plugins')` |
| faqs--main / [directorist-faqs.php:596](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/directorist-faqs.php#L596) | `get_site_option('active_sitewide_plugins')` |
| faqs--main / [widgets/class-widget.php:38](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/widgets/class-widget.php#L38) | `get_post_meta($listing_id, '_fm_plans')` |
| faqs--main / [widgets/class-widget.php:48](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/widgets/class-widget.php#L48) | `apply_filters('widget_title')` |
| faqs--main / [widgets/class-widget.php:54](https://github.com/sovware/directorist-faqs/blob/b83f4f571f845c19024f099fdc40438276c30563/widgets/class-widget.php#L54) | `get_post_meta($listing_id, '_faqs')` |

## Core and integration context

Load [Core listing/form/query contracts](../../../core/listings.md) for a form/data/query issue, [rendering and builders](../../../core/rendering.md) for display, or [payment lifecycle](../../../core/payments.md) for orders. Do not load all three automatically.

Conditional integration candidates (load only when installed configuration or the cited hook/guard connects them):
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md)
- [directorist-divi-integration](../../directorist-divi-integration/README.md)

For exact cross-repository hook matches, use `python3 docs/extensions/scripts/query.py hooks HOOK_NAME`; a same-name hook is only a candidate edge. For dynamic calls, inspect the referenced source and actual active callback list.
