# Social Login: provider-auth

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Provider token verification precedes user registration/login. Callback/origin configuration and account mapping must be inspected; browser button appearance is not authentication proof.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Use test provider apps and accounts; verify valid/expired/forged token, existing email vs new account and redirect. Never log tokens or mutate production provider apps.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Entry points and feature coverage

Read the canonical snapshot rows first. Local/default/development rows are available for variant comparison only. Every matching source file is listed with declarations; supporting styles/configuration may have no symbols.

| Snapshot / source | Functions or classes |
| --- | --- |
| social-login--master / [assets/public/js/social-login.js:1](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/assets/public/js/social-login.js#L1) | `initGAPI` (L246) |
| social-login--master / [directorist-social-login.php:1](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L1) | `Directorist_Social_Login` (L19), `__construct` (L29), `instance` (L49), `google_signin` (L81), `g_register_user` (L148), `g_login_user` (L206), `atbdp_social_login_license_deactivation` (L229), `atbdp_social_login_license_activation` (L322), `load_needed_scripts_admin` (L415), `social_login` (L425), `verify_facebook_token` (L478), `register_user` (L509), `login_user` (L544), `genarate_username` (L560), `get_random_string` (L576), `__clone` (L595), `__wakeup` (L608), `atbdp_social_login_html` (L614), `load_cnd_for_media` (L654), `load_needed_scripts_public` (L660), `add_settings_fields_for_social_submenu` (L686), `add_settings_for_social_submenu` (L711), `add_settings_for_social_submenue` (L740), `add_settings_to_ext_general_fields` (L791), `load_template` (L811), `license_settings_controls` (L817), `load_textdomain` (L860), `includes` (L877), `setup_constants` (L912), `directorist_is_plugin_active` (L922), `directorist_is_plugin_active_for_network` (L928), `Directorist_Social_Login` (L955) |
| social-login--master / [inc/class-compatibility.php:1](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/inc/class-compatibility.php#L1) | `Directorist_Social_Login_Compatibility` (L8), `__construct` (L10) |

## Data readers, writers and lifecycle

Keys/callback expressions below are extracted without stored values. `get` reads; `update/add/delete` writes; scheduled/remote calls are side effects. ORM repositories and schema definitions are linked as source even where literal-key extraction cannot resolve them.

| Snapshot / source | Operation and key |
| --- | --- |
| social-login--master / [directorist-social-login.php:55](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L55) | `add_action('init', array(self::$instance, 'load_textdomain'))` |
| social-login--master / [directorist-social-login.php:56](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L56) | `add_filter('atbdp_license_settings_controls', array(self::$instance, 'license_settings_controls'))` |
| social-login--master / [directorist-social-login.php:57](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L57) | `add_action('wp_enqueue_scripts', array(self::$instance, 'load_needed_scripts_public'))` |
| social-login--master / [directorist-social-login.php:58](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L58) | `add_action('admin_enqueue_scripts', array(self::$instance, 'load_needed_scripts_admin'))` |
| social-login--master / [directorist-social-login.php:59](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L59) | `add_action('wp_footer', array(self::$instance, 'load_cnd_for_media'))` |
| social-login--master / [directorist-social-login.php:61](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L61) | `add_filter('atbdp_listing_type_settings_field_list', array(self::$instance, 'add_settings_fields_for_social_submenu'))` |
| social-login--master / [directorist-social-login.php:62](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L62) | `add_filter('atbdp_extension_settings_submenu', array(self::$instance, 'add_settings_for_social_submenu'))` |
| social-login--master / [directorist-social-login.php:64](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L64) | `add_filter('atbdp_extension_settings_fields', array(self::$instance, 'add_settings_to_ext_general_fields'))` |
| social-login--master / [directorist-social-login.php:65](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L65) | `add_filter('atbdp_extension_settings_submenus', array(self::$instance, 'add_settings_for_social_submenue'))` |
| social-login--master / [directorist-social-login.php:67](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L67) | `add_action('atbdp_before_login_form_end', array(self::$instance, 'atbdp_social_login_html'))` |
| social-login--master / [directorist-social-login.php:69](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L69) | `add_action('wp_ajax_atbdp_social_login', array(self::$instance, 'social_login'))` |
| social-login--master / [directorist-social-login.php:70](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L70) | `add_action('wp_ajax_nopriv_atbdp_social_login', array(self::$instance, 'social_login'))` |
| social-login--master / [directorist-social-login.php:74](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L74) | `add_action('init', array(self::$instance, 'google_signin'))` |
| social-login--master / [directorist-social-login.php:115](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L115) | `get_directorist_option('google_api')` |
| social-login--master / [directorist-social-login.php:176](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L176) | `register_new_user(sanitize_user($username))` |
| social-login--master / [directorist-social-login.php:217](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L217) | `update_user_meta($id, 'nickname')` |
| social-login--master / [directorist-social-login.php:231](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L231) | `get_option('atbdp_option')` |
| social-login--master / [directorist-social-login.php:233](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L233) | `update_option('atbdp_option')` |
| social-login--master / [directorist-social-login.php:234](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L234) | `update_option('directorist_social_login_license')` |
| social-login--master / [directorist-social-login.php:245](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L245) | `wp_remote_post(ATBDP_AUTHOR_URL)` |
| social-login--master / [directorist-social-login.php:247](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L247) | `wp_remote_retrieve_response_code($response)` |
| social-login--master / [directorist-social-login.php:254](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L254) | `wp_remote_retrieve_body($response)` |
| social-login--master / [directorist-social-login.php:261](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L261) | `update_option('directorist_social_login_license_status')` |
| social-login--master / [directorist-social-login.php:267](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L267) | `get_option('date_format')` |
| social-login--master / [directorist-social-login.php:324](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L324) | `get_option('atbdp_option')` |
| social-login--master / [directorist-social-login.php:326](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L326) | `update_option('atbdp_option')` |
| social-login--master / [directorist-social-login.php:327](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L327) | `update_option('directorist_social_login_license')` |
| social-login--master / [directorist-social-login.php:338](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L338) | `wp_remote_post(ATBDP_AUTHOR_URL)` |
| social-login--master / [directorist-social-login.php:340](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L340) | `wp_remote_retrieve_response_code($response)` |
| social-login--master / [directorist-social-login.php:347](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L347) | `wp_remote_retrieve_body($response)` |
| social-login--master / [directorist-social-login.php:354](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L354) | `update_option('directorist_social_login_license_status')` |
| social-login--master / [directorist-social-login.php:360](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L360) | `get_option('date_format')` |
| social-login--master / [directorist-social-login.php:460](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L460) | `update_user_meta($wp_user->ID, '_atbdp_social_id')` |
| social-login--master / [directorist-social-login.php:470](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L470) | `register_user($user_meta)` |
| social-login--master / [directorist-social-login.php:487](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L487) | `wp_remote_get($url)` |
| social-login--master / [directorist-social-login.php:489](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L489) | `wp_remote_retrieve_response_code($response)` |
| social-login--master / [directorist-social-login.php:493](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L493) | `wp_remote_retrieve_body($response)` |
| social-login--master / [directorist-social-login.php:527](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L527) | `update_user_meta($user_id, '_atbdp_generated_password')` |
| social-login--master / [directorist-social-login.php:528](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L528) | `update_user_meta($user_id, '_atbdp_social_id')` |
| social-login--master / [directorist-social-login.php:615](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L615) | `get_directorist_option('enable_social_login')` |
| social-login--master / [directorist-social-login.php:621](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L621) | `get_directorist_option('google_api')` |
| social-login--master / [directorist-social-login.php:663](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L663) | `get_directorist_option('atbdp_fb_app_id')` |
| social-login--master / [directorist-social-login.php:664](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L664) | `get_directorist_option('google_api')` |
| social-login--master / [directorist-social-login.php:665](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L665) | `get_directorist_option('atbdp_social_login_debug')` |
| social-login--master / [directorist-social-login.php:819](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L819) | `get_option('directorist_social_login_license_status')` |
| social-login--master / [directorist-social-login.php:835](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L835) | `apply_filters('atbdp_social_login_license_controls')` |
| social-login--master / [directorist-social-login.php:839](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L839) | `apply_filters('atbdp_social_login_license_settings_field')` |
| social-login--master / [directorist-social-login.php:850](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L850) | `apply_filters('atbdp_licence_menu_for_social_login')` |
| social-login--master / [directorist-social-login.php:865](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L865) | `apply_filters('plugin_locale')` |
| social-login--master / [directorist-social-login.php:893](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L893) | `get_option('directorist_social_login_license')` |
| social-login--master / [directorist-social-login.php:923](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L923) | `get_option('active_plugins')` |
| social-login--master / [directorist-social-login.php:933](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L933) | `get_site_option('active_sitewide_plugins')` |
| social-login--master / [inc/class-compatibility.php:11](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/inc/class-compatibility.php#L11) | `add_filter('wordfence_ls_require_captcha', '__return_false')` |

## Core and integration context

Load [Core listing/form/query contracts](../../../core/listings.md) for a form/data/query issue, [rendering and builders](../../../core/rendering.md) for display, or [payment lifecycle](../../../core/payments.md) for orders. Do not load all three automatically.

For exact cross-repository hook matches, use `python3 docs/extensions/scripts/query.py hooks HOOK_NAME`; a same-name hook is only a candidate edge. For dynamic calls, inspect the referenced source and actual active callback list.
