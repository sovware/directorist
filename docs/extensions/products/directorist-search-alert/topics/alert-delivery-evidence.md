# Directorist Search Alert: alert-delivery

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Listing publication and scheduled delivery select matches on immediate/daily/weekly/monthly paths. Cron execution and email capture must be proved separately.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Create matching and nonmatching listings after the save time. Run only relevant local cron events; assert recipient, schedule, no repeat notifications and expired-search cleanup.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Entry points and feature coverage

Read the canonical snapshot rows first. Local/default/development rows are available for variant comparison only. Every matching source file is listed with declarations; supporting styles/configuration may have no symbols.

| Snapshot / source | Functions or classes |
| --- | --- |
| search-alert--main / [app/Dashboard/Admin_Dashboard.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Dashboard/Admin_Dashboard.php#L1) | `Admin_Dashboard` (L5), `register` (L7), `status_menu` (L11), `get_users_by_email` (L22), `saved_search` (L61) |
| search-alert--main / [app/Database/Database.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Database/Database.php#L1) | `Database` (L3), `insert_saved_search` (L5), `update_saved_search` (L50), `get_search_data` (L73), `get_search_data_by_id` (L103), `deleted_saved_search` (L119), `delete_expired_saved_searches` (L128), `check_combined_request_values_in_db` (L151) |
| search-alert--main / [app/Email/Email.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L1) | `Email` (L10), `register` (L17), `handle_wp_insert_post` (L29), `get_directory_type_slug` (L41), `delete_expired_saved_searches` (L47), `send_email_after_created_listing` (L56), `send_email` (L131), `replace_in_content` (L158), `custom_cron_schedules` (L175), `schedule_email_events` (L186), `send_notification_emails` (L216), `send_daily_emails` (L232), `send_weekly_emails` (L239), `send_monthly_emails` (L246) |
| search-alert--main / [app/Setup/Settings.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Setup/Settings.php#L1) | `Settings` (L4), `register` (L6), `search_alert_menu` (L12), `setting_fields` (L29), `email_template_section` (L66) |
| search-alert--main / [app/base.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/base.php#L1) | `Directorist_Search_Alert` (L17), `instance` (L32), `create_database_table` (L47), `clear_scheduled_hook` (L82), `directorist_listings_header_title` (L95), `load_textdomain` (L127), `__construct` (L144), `includes` (L154), `get_services` (L181), `Enqueue` (L183), `Dashboard` (L184), `User_Dashboard` (L185), `Admin_Dashboard` (L186), `Ajax` (L187), `Email` (L188), `register_services` (L189), `register_services` (L198), `Directorist_Search_Alert` (L220), `directorist_is_plugin_active` (L231), `directorist_is_plugin_active_for_network` (L243) |

## Data readers, writers and lifecycle

Keys/callback expressions below are extracted without stored values. `get` reads; `update/add/delete` writes; scheduled/remote calls are side effects. ORM repositories and schema definitions are linked as source even where literal-key extraction cannot resolve them.

| Snapshot / source | Operation and key |
| --- | --- |
| search-alert--main / [app/Dashboard/Admin_Dashboard.php:8](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Dashboard/Admin_Dashboard.php#L8) | `add_action('admin_menu', array( $this, 'status_menu' ))` |
| search-alert--main / [app/Database/Database.php:41](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Database/Database.php#L41) | `insert(write keys: (values omitted; inspect source for dynamic keys))` |
| search-alert--main / [app/Database/Database.php:70](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Database/Database.php#L70) | `update(write keys: (values omitted; inspect source for dynamic keys))` |
| search-alert--main / [app/Database/Database.php:123](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Database/Database.php#L123) | `delete(write keys: (values omitted; inspect source for dynamic keys))` |
| search-alert--main / [app/Email/Email.php:18](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L18) | `add_filter('cron_schedules', array( $this, 'custom_cron_schedules' ))` |
| search-alert--main / [app/Email/Email.php:19](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L19) | `add_action('wp', array( $this, 'schedule_email_events' ))` |
| search-alert--main / [app/Email/Email.php:20](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L20) | `add_action('directorist_saved_search_daily_emails', array( $this, 'send_daily_emails' ))` |
| search-alert--main / [app/Email/Email.php:21](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L21) | `add_action('directorist_saved_search_weekly_emails', array( $this, 'send_weekly_emails' ))` |
| search-alert--main / [app/Email/Email.php:22](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L22) | `add_action('directorist_saved_search_monthly_emails', array( $this, 'send_monthly_emails' ))` |
| search-alert--main / [app/Email/Email.php:24](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L24) | `add_action('directorist_delete_expired_saved_searches', array( $this, 'delete_expired_saved_searches' ))` |
| search-alert--main / [app/Email/Email.php:26](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L26) | `add_action('wp_insert_post', array( $this, 'handle_wp_insert_post' ))` |
| search-alert--main / [app/Email/Email.php:63](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L63) | `get_post_meta($listing_id, '_directory_type')` |
| search-alert--main / [app/Email/Email.php:65](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L65) | `get_post_meta($listing_id, '_zip')` |
| search-alert--main / [app/Email/Email.php:66](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L66) | `get_post_meta($listing_id, '_address')` |
| search-alert--main / [app/Email/Email.php:67](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L67) | `get_post_meta($listing_id, '_price')` |
| search-alert--main / [app/Email/Email.php:132](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L132) | `get_option('blogname')` |
| search-alert--main / [app/Email/Email.php:133](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L133) | `get_directorist_option('email_from_email')` |
| search-alert--main / [app/Email/Email.php:135](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L135) | `get_directorist_option('search_alert_subject')` |
| search-alert--main / [app/Email/Email.php:136](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L136) | `get_directorist_option('search_alert_body')` |
| search-alert--main / [app/Email/Email.php:159](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L159) | `get_option('blogname')` |
| search-alert--main / [app/Email/Email.php:187](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L187) | `wp_next_scheduled('directorist_saved_search_daily_emails')` |
| search-alert--main / [app/Email/Email.php:188](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L188) | `wp_schedule_event(time())` |
| search-alert--main / [app/Email/Email.php:190](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L190) | `wp_next_scheduled('directorist_saved_search_weekly_emails')` |
| search-alert--main / [app/Email/Email.php:191](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L191) | `wp_schedule_event(time())` |
| search-alert--main / [app/Email/Email.php:193](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L193) | `wp_next_scheduled('directorist_saved_search_monthly_emails')` |
| search-alert--main / [app/Email/Email.php:194](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L194) | `wp_schedule_event(time())` |
| search-alert--main / [app/Email/Email.php:202](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L202) | `wp_next_scheduled('directorist_delete_expired_saved_searches')` |
| search-alert--main / [app/Email/Email.php:203](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L203) | `wp_schedule_event(time())` |
| search-alert--main / [app/Email/Email.php:207](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L207) | `wp_clear_scheduled_hook('directorist_delete_expired_saved_searches')` |
| search-alert--main / [app/Setup/Settings.php:7](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Setup/Settings.php#L7) | `add_filter('atbdp_email_templates_settings_sections', array( $this, 'email_template_section' ))` |
| search-alert--main / [app/Setup/Settings.php:8](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Setup/Settings.php#L8) | `add_filter('atbdp_listing_type_settings_field_list', array( $this, 'setting_fields' ))` |
| search-alert--main / [app/Setup/Settings.php:9](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Setup/Settings.php#L9) | `add_filter('atbdp_extension_settings_submenu', array( $this, 'search_alert_menu' ))` |
| search-alert--main / [app/Setup/Settings.php:16](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Setup/Settings.php#L16) | `apply_filters('atbdp_search_alert_settings_controls')` |
| search-alert--main / [app/base.php:36](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/base.php#L36) | `add_action('init', array( self::$instance, 'load_textdomain' ))` |
| search-alert--main / [app/base.php:37](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/base.php#L37) | `add_filter('directorist_listings_header_title', array( self::$instance, 'directorist_listings_header_title' ))` |
| search-alert--main / [app/base.php:76](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/base.php#L76) | `dbDelta($sql)` |
| search-alert--main / [app/base.php:83](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/base.php#L83) | `wp_clear_scheduled_hook('directorist_saved_search_daily_emails')` |
| search-alert--main / [app/base.php:84](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/base.php#L84) | `wp_clear_scheduled_hook('directorist_saved_search_weekly_emails')` |
| search-alert--main / [app/base.php:85](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/base.php#L85) | `wp_clear_scheduled_hook('directorist_saved_search_monthly_emails')` |
| search-alert--main / [app/base.php:86](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/base.php#L86) | `wp_clear_scheduled_hook('directorist_delete_expired_saved_searches')` |
| search-alert--main / [app/base.php:105](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/base.php#L105) | `get_directorist_option('guest_alert')` |
| search-alert--main / [app/base.php:131](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/base.php#L131) | `apply_filters('plugin_locale')` |
| search-alert--main / [app/base.php:146](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/base.php#L146) | `register_services()` |
| search-alert--main / [app/base.php:232](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/base.php#L232) | `get_option('active_plugins')` |
| search-alert--main / [app/base.php:248](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/base.php#L248) | `get_site_option('active_sitewide_plugins')` |

## Core and integration context

Load [Core listing/form/query contracts](../../../core/listings.md) for a form/data/query issue, [rendering and builders](../../../core/rendering.md) for display, or [payment lifecycle](../../../core/payments.md) for orders. Do not load all three automatically.

For exact cross-repository hook matches, use `python3 docs/extensions/scripts/query.py hooks HOOK_NAME`; a same-name hook is only a candidate edge. For dynamic calls, inspect the referenced source and actual active callback list.
