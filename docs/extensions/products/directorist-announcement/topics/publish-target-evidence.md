# Directorist Announcement: publish-target

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Announcement post type, recipient selection, expiry and send action control visibility and email.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Create announcement for user A only and another for all users with expiry. Check A/B dashboard, captured email and expiry cleanup.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Entry points and feature coverage

Read the canonical snapshot rows first. Local/default/development rows are available for variant comparison only. Every matching source file is listed with declarations; supporting styles/configuration may have no symbols.

| Snapshot / source | Functions or classes |
| --- | --- |
| announcement--master / [assets/js/admin.js:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/assets/js/admin.js#L1) | `announcement_to` (L6), `announcement_recepents_section` (L7), `toggle_section` (L14), `submit_button` (L22), `form_feedback` (L23), `to` (L35), `recepents` (L36), `subject` (L37), `message` (L38), `expiration` (L39), `send_to_email` (L40), `fields_elm` (L42), `form_data` (L76) |
| announcement--master / [inc/class-content-update.php:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-content-update.php#L1) | `DA_Update` (L16), `__construct` (L20), `instance` (L33), `create_announcement_post_type` (L42), `delete_expired_announcements` (L54), `send_announcement` (L80), `non_legacy_add_dashboard_nav_link` (L210), `close_announcement` (L235), `response_new_announcement_count` (L260), `clear_seen_announcements` (L271) |
| announcement--master / [inc/class-helpers.php:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-helpers.php#L1) | `DA_Helpers` (L13), `get_announcement_label` (L16), `get_announcements` (L25), `get_announcement_query_data` (L52), `get_new_announcement_count` (L78), `non_legacy_add_dashboard_nav_content` (L127), `add_dashboard_nav_link` (L196), `add_dashboard_nav_content` (L219), `get_all_user_emails` (L280) |
| announcement--master / [inc/class-settings.php:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-settings.php#L1) | `DA_Settings` (L10), `__construct` (L15), `instance` (L22), `announcement_menu` (L30), `register_setting_fields` (L49), `setting_fields_tab` (L140) |

## Data readers, writers and lifecycle

Keys/callback expressions below are extracted without stored values. `get` reads; `update/add/delete` writes; scheduled/remote calls are side effects. ORM repositories and schema definitions are linked as source even where literal-key extraction cannot resolve them.

| Snapshot / source | Operation and key |
| --- | --- |
| announcement--master / [inc/class-content-update.php:22](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-content-update.php#L22) | `add_action('init', array( $this, 'create_announcement_post_type' ))` |
| announcement--master / [inc/class-content-update.php:24](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-content-update.php#L24) | `add_action('atbdp_schedule_task', array( $this, 'delete_expired_announcements' ))` |
| announcement--master / [inc/class-content-update.php:27](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-content-update.php#L27) | `add_action('wp_ajax_atbdp_send_announcement', array( $this, 'send_announcement' ))` |
| announcement--master / [inc/class-content-update.php:28](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-content-update.php#L28) | `add_action('wp_ajax_atbdp_close_announcement', array( $this, 'close_announcement' ))` |
| announcement--master / [inc/class-content-update.php:29](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-content-update.php#L29) | `add_action('wp_ajax_atbdp_get_new_announcement_count', array( $this, 'response_new_announcement_count' ))` |
| announcement--master / [inc/class-content-update.php:30](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-content-update.php#L30) | `add_action('wp_ajax_atbdp_clear_seen_announcements', array( $this, 'clear_seen_announcements' ))` |
| announcement--master / [inc/class-content-update.php:43](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-content-update.php#L43) | `register_post_type('listing-announcement')` |
| announcement--master / [inc/class-content-update.php:176](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-content-update.php#L176) | `update_post_meta($announcement, '_recepents')` |
| announcement--master / [inc/class-content-update.php:178](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-content-update.php#L178) | `update_post_meta($announcement, '_recepents')` |
| announcement--master / [inc/class-content-update.php:182](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-content-update.php#L182) | `update_post_meta($announcement, '_to')` |
| announcement--master / [inc/class-content-update.php:183](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-content-update.php#L183) | `update_post_meta($announcement, '_closed')` |
| announcement--master / [inc/class-content-update.php:184](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-content-update.php#L184) | `update_post_meta($announcement, '_seen')` |
| announcement--master / [inc/class-content-update.php:193](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-content-update.php#L193) | `update_post_meta($announcement, '_exp_in_days')` |
| announcement--master / [inc/class-content-update.php:194](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-content-update.php#L194) | `update_post_meta($announcement, '_exp_date')` |
| announcement--master / [inc/class-content-update.php:211](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-content-update.php#L211) | `get_directorist_option('announcement_tab')` |
| announcement--master / [inc/class-content-update.php:212](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-content-update.php#L212) | `get_directorist_option('announcement_tab_text')` |
| announcement--master / [inc/class-content-update.php:251](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-content-update.php#L251) | `update_post_meta($post_id, '_closed')` |
| announcement--master / [inc/class-content-update.php:296](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-content-update.php#L296) | `get_the_author_meta('user_email')` |
| announcement--master / [inc/class-content-update.php:302](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-content-update.php#L302) | `get_post_meta(get_the_ID(), '_recepents')` |
| announcement--master / [inc/class-content-update.php:309](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-content-update.php#L309) | `update_post_meta(get_the_ID(), '_seen')` |
| announcement--master / [inc/class-helpers.php:17](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-helpers.php#L17) | `get_directorist_option('announcement_tab_text')` |
| announcement--master / [inc/class-helpers.php:22](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-helpers.php#L22) | `apply_filters('directorist_announcement_label')` |
| announcement--master / [inc/class-helpers.php:28](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-helpers.php#L28) | `get_the_author_meta('user_email')` |
| announcement--master / [inc/class-helpers.php:33](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-helpers.php#L33) | `get_post_meta($id, '_recepents')` |
| announcement--master / [inc/class-helpers.php:105](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-helpers.php#L105) | `get_the_author_meta('user_email')` |
| announcement--master / [inc/class-helpers.php:111](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-helpers.php#L111) | `get_post_meta(get_the_ID(), '_recepents')` |
| announcement--master / [inc/class-helpers.php:137](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-helpers.php#L137) | `get_the_author_meta('user_email')` |
| announcement--master / [inc/class-helpers.php:149](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-helpers.php#L149) | `get_post_meta(get_the_ID(), '_recepents')` |
| announcement--master / [inc/class-helpers.php:197](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-helpers.php#L197) | `get_directorist_option('announcement_tab')` |
| announcement--master / [inc/class-helpers.php:198](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-helpers.php#L198) | `get_directorist_option('announcement_tab_text')` |
| announcement--master / [inc/class-helpers.php:223](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-helpers.php#L223) | `get_the_author_meta('user_email')` |
| announcement--master / [inc/class-helpers.php:235](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-helpers.php#L235) | `get_post_meta(get_the_ID(), '_recepents')` |
| announcement--master / [inc/class-settings.php:17](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-settings.php#L17) | `add_filter('atbdp_extension_settings_submenu', array( $this, 'announcement_menu' ))` |
| announcement--master / [inc/class-settings.php:18](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-settings.php#L18) | `add_filter('atbdp_listing_type_settings_field_list', array( $this, 'register_setting_fields' ))` |
| announcement--master / [inc/class-settings.php:19](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-settings.php#L19) | `add_filter('atbdp_listing_settings_user_dashboard_sections', array( $this, 'setting_fields_tab' ))` |
| announcement--master / [inc/class-settings.php:34](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-settings.php#L34) | `apply_filters('atbdp_announcement_settings_controls')` |
| announcement--master / [inc/class-settings.php:53](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-settings.php#L53) | `apply_filters('directorist_announcement_user_query_num')` |

## Core and integration context

Load [Core listing/form/query contracts](../../../core/listings.md) for a form/data/query issue, [rendering and builders](../../../core/rendering.md) for display, or [payment lifecycle](../../../core/payments.md) for orders. Do not load all three automatically.

For exact cross-repository hook matches, use `python3 docs/extensions/scripts/query.py hooks HOOK_NAME`; a same-name hook is only a candidate edge. For dynamic calls, inspect the referenced source and actual active callback list.
