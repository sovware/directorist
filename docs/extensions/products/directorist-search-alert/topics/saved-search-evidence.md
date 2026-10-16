# Directorist Search Alert: saved-search

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Saved searches preserve filter values and ownership; create/edit/delete actions and expiration have different data paths.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Save keyword/category/location/custom filters as user A, edit and delete; ensure user B cannot operate A records. Reload dashboard and verify displayed filters match request.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Entry points and feature coverage

Read the canonical snapshot rows first. Local/default/development rows are available for variant comparison only. Every matching source file is listed with declarations; supporting styles/configuration may have no symbols.

| Snapshot / source | Functions or classes |
| --- | --- |
| search-alert--main / [admin/js/admin.js:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/admin/js/admin.js#L1) | template / configuration / styling; inspect file |
| search-alert--main / [app/Ajax/Ajax.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Ajax/Ajax.php#L1) | `Ajax` (L5), `register` (L7), `edit_save_search` (L25), `edit_modal` (L59), `deleted_search` (L87), `saved_search` (L107) |
| search-alert--main / [app/Dashboard/Admin_Dashboard.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Dashboard/Admin_Dashboard.php#L1) | `Admin_Dashboard` (L5), `register` (L7), `status_menu` (L11), `get_users_by_email` (L22), `saved_search` (L61) |
| search-alert--main / [app/Dashboard/Dashboard.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Dashboard/Dashboard.php#L1) | `Dashboard` (L4), `get_category_name` (L7), `get_location_name` (L20), `get_tag_names` (L32), `get_price_range` (L48), `get_search_filters` (L63), `get_date` (L70), `get_notification_status` (L78) |
| search-alert--main / [app/Dashboard/User_Dashboard.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Dashboard/User_Dashboard.php#L1) | `User_Dashboard` (L4), `register` (L8), `directorist_after_dashboard_navigation` (L13), `directorist_after_dashboard_contents` (L21) |
| search-alert--main / [app/Database/Database.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Database/Database.php#L1) | `Database` (L3), `insert_saved_search` (L5), `update_saved_search` (L50), `get_search_data` (L73), `get_search_data_by_id` (L103), `deleted_saved_search` (L119), `delete_expired_saved_searches` (L128), `check_combined_request_values_in_db` (L151) |
| search-alert--main / [app/Setup/Enqueue.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Setup/Enqueue.php#L1) | `Enqueue` (L4), `register` (L10), `load_frontend_scripts` (L20), `load_admin_scripts` (L42), `get_script_data` (L84), `register_scripts` (L108) |
| search-alert--main / [app/base.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/base.php#L1) | `Directorist_Search_Alert` (L17), `instance` (L32), `create_database_table` (L47), `clear_scheduled_hook` (L82), `directorist_listings_header_title` (L95), `load_textdomain` (L127), `__construct` (L144), `includes` (L154), `get_services` (L181), `Enqueue` (L183), `Dashboard` (L184), `User_Dashboard` (L185), `Admin_Dashboard` (L186), `Ajax` (L187), `Email` (L188), `register_services` (L189), `register_services` (L198), `Directorist_Search_Alert` (L220), `directorist_is_plugin_active` (L231), `directorist_is_plugin_active_for_network` (L243) |
| search-alert--main / [assets/js/main.js:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/assets/js/main.js#L1) | `updateSaveSearchContainers` (L7), `saveSearchContainers` (L8), `observer` (L19), `config` (L26), `getDsaText` (L30), `currentElement` (L39), `saveSearchBtn` (L40), `targetId` (L41), `closeId` (L42), `targetModal` (L45), `openModals` (L55), `targetModal` (L61), `editSearch` (L69), `editModal` (L70), `editModalClose` (L71), `showSuccessMessage` (L85), `openModals` (L94), `notice_message` (L135), `alertFrequencyRadios` (L136), `data` (L143), `data` (L189), `data` (L213), `notice_message` (L250), `data` (L257), `data` (L294) |
| search-alert--main / [helpers/helpers.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/helpers/helpers.php#L1) | `dsa_load_template` (L7), `dsa_get_terms` (L29), `dsa_get_saved_search_expiration_days` (L38), `dsa_get_version_from_content` (L43), `dsa_get_version_from_file_content` (L55) |
| search-alert--main / [helpers/trait-search-helper.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/helpers/trait-search-helper.php#L1) | `Search_Helper` (L5), `new_listings_count` (L6), `get_search_url` (L117) |
| search-alert--main / [templates/dashboard/admin-dashboard.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/templates/dashboard/admin-dashboard.php#L1) | template / configuration / styling; inspect file |
| search-alert--main / [templates/dashboard/user-dashboard.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/templates/dashboard/user-dashboard.php#L1) | template / configuration / styling; inspect file |
| search-alert--main / [templates/editing-form.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/templates/editing-form.php#L1) | template / configuration / styling; inspect file |
| search-alert--main / [templates/search-result.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/templates/search-result.php#L1) | template / configuration / styling; inspect file |

## Data readers, writers and lifecycle

Keys/callback expressions below are extracted without stored values. `get` reads; `update/add/delete` writes; scheduled/remote calls are side effects. ORM repositories and schema definitions are linked as source even where literal-key extraction cannot resolve them.

| Snapshot / source | Operation and key |
| --- | --- |
| search-alert--main / [app/Ajax/Ajax.php:9](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Ajax/Ajax.php#L9) | `add_action('wp_ajax_dsa_saved_search', array( $this, 'saved_search' ))` |
| search-alert--main / [app/Ajax/Ajax.php:10](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Ajax/Ajax.php#L10) | `add_action('wp_ajax_nopriv_dsa_saved_search', array( $this, 'saved_search' ))` |
| search-alert--main / [app/Ajax/Ajax.php:13](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Ajax/Ajax.php#L13) | `add_action('wp_ajax_dsa_deleted_search', array( $this, 'deleted_search' ))` |
| search-alert--main / [app/Ajax/Ajax.php:14](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Ajax/Ajax.php#L14) | `add_action('wp_ajax_nopriv_dsa_deleted_search', array( $this, 'deleted_search' ))` |
| search-alert--main / [app/Ajax/Ajax.php:17](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Ajax/Ajax.php#L17) | `add_action('wp_ajax_dsa_edit_modal', array( $this, 'edit_modal' ))` |
| search-alert--main / [app/Ajax/Ajax.php:18](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Ajax/Ajax.php#L18) | `add_action('wp_ajax_nopriv_dsa_edit_modal', array( $this, 'edit_modal' ))` |
| search-alert--main / [app/Ajax/Ajax.php:21](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Ajax/Ajax.php#L21) | `add_action('wp_ajax_dsa_edit_saved_search', array( $this, 'edit_save_search' ))` |
| search-alert--main / [app/Ajax/Ajax.php:22](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Ajax/Ajax.php#L22) | `add_action('wp_ajax_nopriv_dsa_edit_saved_search', array( $this, 'edit_save_search' ))` |
| search-alert--main / [app/Dashboard/Admin_Dashboard.php:8](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Dashboard/Admin_Dashboard.php#L8) | `add_action('admin_menu', array( $this, 'status_menu' ))` |
| search-alert--main / [app/Dashboard/User_Dashboard.php:9](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Dashboard/User_Dashboard.php#L9) | `add_action("directorist_after_dashboard_navigation", array( $this, "directorist_after_dashboard_navigation" ))` |
| search-alert--main / [app/Dashboard/User_Dashboard.php:10](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Dashboard/User_Dashboard.php#L10) | `add_action("directorist_after_dashboard_contents", array( $this, "directorist_after_dashboard_contents" ))` |
| search-alert--main / [app/Dashboard/User_Dashboard.php:17](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Dashboard/User_Dashboard.php#L17) | `apply_filters('atbdp_user_dashboard_saved_search_tab')` |
| search-alert--main / [app/Database/Database.php:41](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Database/Database.php#L41) | `insert(write keys: (values omitted; inspect source for dynamic keys))` |
| search-alert--main / [app/Database/Database.php:70](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Database/Database.php#L70) | `update(write keys: (values omitted; inspect source for dynamic keys))` |
| search-alert--main / [app/Database/Database.php:123](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Database/Database.php#L123) | `delete(write keys: (values omitted; inspect source for dynamic keys))` |
| search-alert--main / [app/Setup/Enqueue.php:11](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Setup/Enqueue.php#L11) | `add_action('wp_enqueue_scripts', array( $this, 'load_frontend_scripts' ))` |
| search-alert--main / [app/Setup/Enqueue.php:12](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Setup/Enqueue.php#L12) | `add_action('admin_enqueue_scripts', array( $this, 'load_admin_scripts' ))` |
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
| search-alert--main / [helpers/helpers.php:39](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/helpers/helpers.php#L39) | `get_directorist_option('delete_saved_searches_after')` |

## Core and integration context

Load [Core listing/form/query contracts](../../../core/listings.md) for a form/data/query issue, [rendering and builders](../../../core/rendering.md) for display, or [payment lifecycle](../../../core/payments.md) for orders. Do not load all three automatically.

For exact cross-repository hook matches, use `python3 docs/extensions/scripts/query.py hooks HOOK_NAME`; a same-name hook is only a candidate edge. For dynamic calls, inspect the referenced source and actual active callback list.
