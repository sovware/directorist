# Themes & Extensions Page Architecture Map

Use this map for source orientation. Re-check files before each task because product catalogs, active plugins, subscriptions, themes, and update availability are dynamic.

## Admin Entry

- Page URL: `wp-admin/edit.php?post_type=at_biz_dir&page=atbdp-extension`
- Admin screen id: `at_biz_dir_page_atbdp-extension`
- Main class: `ATBDP_Extensions` in `includes/classes/class-extension.php`
- Menu registration: `admin_menu()` adds the submenu under `edit.php?post_type=at_biz_dir`
- Capability: `manage_options`
- Render callback: `show_extension_view()`

## Server Lifecycle

- Constructor registers `setup_ajax_actions()` on `admin_init`.
- When `$_GET['page']` is `atbdp-extension`, constructor also registers `initial_setup()` on `admin_init`.
- `initial_setup()` prepares extension aliases, calls WordPress plugin update checks, loads required extensions through `directorist_required_extensions`, and prepares products through `setup_products_list()`.
- `show_extension_view()` builds render data and loads `admin-templates/theme-extensions/theme-extension`.

## Product Sources

- Default extension list: `ATBDP_Extensions::get_default_extensions_list()`
- Default theme list: `ATBDP_Extensions::get_default_themes_list()`
- Local filters:
  - `atbdp_extension_list`
  - `atbdp_theme_list`
  - `directorist_required_extensions`
  - `directorist_extensions_aliases`
- Optional remote catalog: `Directorist\Core\API::get_products()` in `includes/classes/class-directorist-api.php`
- Remote catalog endpoint: `https://app.directorist.com/wp-json/directorist/v1/get-remote-products`
- Remote catalog cache: transient `directorist_products`

Do not commit fetched product payloads into docs. Store only the source and contract.

## Account And Subscription State

- Connected-account flag user meta: `_atbdp_has_subscriptions_sassion`
- Connected username user meta: `_atbdp_subscribed_username`
- Subscribed plugins user meta: `_plugins_available_in_subscriptions`
- Subscribed themes user meta: `_themes_available_in_subscriptions`
- Refresh and logout behavior are controlled by class methods in `class-extension.php` and jQuery handlers in `subscriptionManagement.js`.

Preserve the misspelled `sassion` keys/actions because they are compatibility contracts.

## Templates

- Root wrapper: `views/admin-templates/theme-extensions/theme-extension.php`
- Logged-out account form: `theme-extensions/auth/license-auth-section.php`
- Connected statistics: `theme-extensions/statistics/statistics.php`
- Connected product area: `theme-extensions/my-themes-extensions/my-themes-extensions.php`
- Extensions tab: `theme-extensions/my-themes-extensions/extensions-tab.php`
- Themes tab: `theme-extensions/my-themes-extensions/themes-tab.php`
- Promo marketplace: `theme-extensions/all-themes-extensions.php`

Template output is server-rendered PHP and must remain usable without new JavaScript state management.

## JavaScript And Assets

- Admin entry: `assets/src/js/admin/admin.js`
- Page behavior: `assets/src/js/admin/components/subscriptionManagement.js`
- Enqueued admin script: `directorist-admin-script`
- Enqueued admin CSS: `directorist-admin-style`
- Localized object: `directorist_admin`, localized to `jquery` in `includes/asset-loader/localized_data.php`
- Screen detection: `includes/asset-loader/helper.php`
- Enqueue rules: `includes/asset-loader/init.php`
- Script handles: `includes/asset-loader/scripts.php`
- Main styles are currently in `assets/src/scss/layout/admin/admin-style.scss`

## AJAX Actions

Registered by `ATBDP_Extensions::setup_ajax_actions()`:

- `atbdp_authenticate_the_customer`
- `atbdp_download_file`
- `atbdp_install_file_from_subscriptions`
- `atbdp_plugins_bulk_action`
- `atbdp_activate_theme`
- `atbdp_activate_plugin`
- `atbdp_update_plugins`
- `atbdp_update_theme`
- `atbdp_refresh_purchase_status`
- `atbdp_close_subscriptions_sassion`

Most actions use `directorist_admin.nonce`; bulk plugin actions use `directorist_admin.directorist_nonce`. Re-check each handler before changing request shape.

## Remote Dependencies

- Directorist account authentication: `https://directorist.com/wp-json/directorist/v1/licencing`
- Product data/download links: `https://directorist.com/wp-json/directorist/v1/get-product-data/`
- EDD software licensing version checks: `https://directorist.com` with `edd_action=get_version`
- Optional remote product catalog: `https://app.directorist.com/wp-json/directorist/v1/get-remote-products`

Network failures must leave the page usable with local defaults and clear error states.

## Settings Page Connection

The settings panel links admins back to this page, but it is a separate domain:

- Settings URL pattern: `edit.php?post_type=at_biz_dir&page=atbdp-settings#extension_settings__extensions_general`
- Settings-panel skill: `docs/agents/directorist-settings-panel/SKILL.md`
- Do not document installed extension settings as core marketplace/discovery behavior.

## Existing Issue Patterns

- The current UI uses tables and fixed widths that can become fragile on narrow screens.
- `subscriptionManagement.js` uses many `location.reload()` calls after AJAX success.
- Old login continuation/download checklist code exists after an immediate reload and should be treated as stale unless revived intentionally.
- `handle_file_download_request()` must be reviewed carefully before reuse because the type validation path is known-risk.
- Plugin/theme install and update code touches the filesystem and must be treated as high risk.
- The theme "What's new" modal must not ship hardcoded dummy changelog content in a redesign.

For full rewrite fix priorities, read `references/rewrite-issue-register.md` before planning implementation.
