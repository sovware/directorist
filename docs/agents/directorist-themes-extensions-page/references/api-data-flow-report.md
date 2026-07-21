# API And Data Flow Report

This report documents durable data sources and API routes for the Directorist admin `Themes & Extensions` page. It must not contain runtime product counts, installed product lists, subscribed product lists, user names, screenshots, or one-time AJAX payloads from a specific site.

## Summary

The page data does not come from one API. It is assembled from:

- Local Directorist product definitions in `ATBDP_Extensions`.
- Optional remote product catalog data from `app.directorist.com`.
- Directorist.com account/license data fetched through WordPress AJAX handlers.
- Local WordPress plugin, theme, update, option, transient, and user-meta state.
- Existing `admin-ajax.php` actions that connect the jQuery admin UI to PHP handlers.

Frontend JavaScript should not call Directorist.com directly. Keep the browser talking to local WordPress AJAX, then let PHP own remote API calls, WordPress state reads, filesystem work, and compatibility behavior.

## Step-By-Step Page Load Flow

1. WordPress loads `wp-admin/edit.php?post_type=at_biz_dir&page=atbdp-extension`.
2. `ATBDP_Extensions::__construct()` registers the admin menu, AJAX actions, menu styling, and `initial_setup()` only for this page.
3. `setup_ajax_actions()` registers the `wp_ajax_atbdp_*` action handlers when the current admin can `manage_options`.
4. `initial_setup()` prepares extension aliases, calls `wp_update_plugins()`, reads required extensions from `directorist_required_extensions`, and calls `setup_products_list()`.
5. `setup_products_list()` builds `$this->extensions` and `$this->themes`.
6. `show_extension_view()` reads connected-account state from user meta, builds extension/theme overview arrays, and loads `admin-templates/theme-extensions/theme-extension`.
7. The root template renders the account-connect form when disconnected, or statistics plus managed products when connected, and always renders the promo marketplace section.

## Product Catalog Source

Default behavior is local-first:

- `ATBDP_Extensions::$load_from_api` is `false`.
- Extensions come from `ATBDP_Extensions::get_default_extensions()`.
- Themes come from `ATBDP_Extensions::get_default_themes()`.
- Filters can modify these lists:
  - `atbdp_extension_list`
  - `atbdp_theme_list`

Optional remote catalog path:

- `Directorist\Core\API::get_products()`
- Base URL: `https://app.directorist.com/wp-json/directorist/`
- Endpoint: `v1/get-remote-products`
- Effective URL: `https://app.directorist.com/wp-json/directorist/v1/get-remote-products`
- Cache key: `directorist_products`
- Cache duration: `30 * DAY_IN_SECONDS`
- Empty remote response falls back to local defaults.

Use the product catalog for product names, descriptions, thumbnails, product links, demo links, active promo flags, item IDs, optional plugin base overrides, and future optional product badge/status metadata. Do not store fetched catalog output in docs.

Product copy/source policy for rewrite:

- Prefer product API data for product name, description, thumbnail, product link, demo link, active promo flag, item ID, and plugin base when the API provides usable values.
- Keep local product arrays as safe fallback for product name, description, thumbnail, product link, demo link, item ID, and plugin base when the API is unavailable, empty, malformed, missing a field, or disabled.
- Do not render blank cards only because remote copy is missing; merge API data over local defaults by product key where possible.
- Cross-check public product claims against local `README.md`/`readme.txt` and official Directorist docs before changing visible copy.
- Badge/status values are different: render them only from product API or explicit local filters. Do not invent hardcoded badge/status fallback from local copy, product order, slug, or name.

Future badge/status support should be added to this catalog contract as optional API data. EDD product meta or a dedicated product badge setting on Directorist.com can feed the API, but the core plugin UI should consume the product API field. Do not use normal EDD/WordPress `post_status` as the badge source because it represents product availability, not display labels such as `New`.

Recommended optional badge object:

```json
{
  "type": "new",
  "label": "New",
  "expires_at": "2026-09-01"
}
```

Render no badge when this field is absent, expired, malformed, or disabled by filters.

## Account Connection API

Browser action:

- Form: `#atbdp-directorist-license-login-form`
- JavaScript file: `assets/src/js/admin/components/subscriptionManagement.js`
- Local AJAX URL: `directorist_admin.ajaxurl`
- AJAX action: `atbdp_authenticate_the_customer`
- Request fields: `username`, `password`, `nonce`

Server handler:

- `ATBDP_Extensions::authenticate_the_customer()`
- Remote method: `ATBDP_Extensions::remote_authenticate_user()`
- Remote endpoint: `https://directorist.com/wp-json/directorist/v1/licencing`
- Method in code: `wp_remote_get()`
- Request body: `user`, `password`
- Headers include a Directorist user-agent and `Accept: application/json`.

Successful response is normalized into `license_data`, then stored under current user meta:

- `_atbdp_subscribed_username`
- `_atbdp_has_subscriptions_sassion`
- `_themes_available_in_subscriptions`
- `_plugins_available_in_subscriptions`

Keep the `sassion` spelling because it is part of the existing data/action contract.

## Subscription Reads

After connection, the page usually reads subscription data from local user meta instead of calling Directorist.com on every page load.

Extension subscription reader:

- `ATBDP_Extensions::get_purchased_extension_list()`
- Meta key: `_plugins_available_in_subscriptions`
- Filter: `directorist_purchased_extension_list`

Theme subscription reader:

- `ATBDP_Extensions::get_purchased_theme_list()`
- Meta key: `_themes_available_in_subscriptions`
- Filter: `directorist_purchased_theme_list`

The subscription arrays are keyed from product permalinks by stripping:

- `http://directorist.com/product/`
- `https://directorist.com/product/`
- `/`

## Refresh Purchase Flow

Browser action:

- Form/action area: purchase refresh form in connected account UI
- AJAX action: `atbdp_refresh_purchase_status`
- Request fields: `password`, `nonce`

Server flow:

1. `handle_refresh_purchase_status_request()` validates capability and nonce.
2. `refresh_purchase_status()` reads `_atbdp_subscribed_username`.
3. It calls `remote_authenticate_user()` again against `https://directorist.com/wp-json/directorist/v1/licencing`.
4. It rewrites `_themes_available_in_subscriptions` and `_plugins_available_in_subscriptions`.
5. If the username/session is missing, it deletes `_atbdp_has_subscriptions_sassion` and returns a reload-required status.

Current JavaScript reloads after success. A future no-reload version should refresh canonical state through a server response or state-summary endpoint.

## Logout Flow

Browser action:

- Button selector family: `.subscriptions-logout-btn`
- AJAX action: `atbdp_close_subscriptions_sassion`
- Request fields: `hard_logout`, `nonce`

Server flow:

- Always deletes `_atbdp_has_subscriptions_sassion`.
- If hard logout is enabled, also deletes:
  - `_atbdp_subscribed_username`
  - `_themes_available_in_subscriptions`
  - `_plugins_available_in_subscriptions`

Current JavaScript reloads after success.

## Installed Extension Data

Installed extension state is local WordPress data:

- `get_plugins()`
- `is_plugin_active()`
- official extension keys from `$this->extensions`
- alias map from `directorist_extensions_aliases`

`get_extensions_overview()` builds:

- installed extension list
- active extension total
- outdated extension list
- available-in-subscription list
- promo extension list
- required extension list

Use this server-side overview as canonical for rendered extension sections.

## Extension Update API

Browser action:

- Button selector family: `.ext-update-btn`
- AJAX action: `atbdp_update_plugins`
- Request fields: optional `plugin_key`, `nonce`

Server flow:

1. `handle_plugins_update_request()` validates capability and nonce.
2. `update_plugins()` calls `get_outdated_extensions_via_api()`.
3. `get_outdated_extensions_via_api()` reads installed plugins via `get_plugins()`.
4. It reads purchased extensions from `_plugins_available_in_subscriptions`.
5. It checks each eligible installed product against EDD version data.

Remote EDD version check:

- Method: `wp_remote_post()`
- Endpoint: `https://directorist.com`
- Body fields:
  - `edd_action=get_version`
  - `license`
  - `item_id`
  - `version`
  - `slug`
  - `author=AazzTech`
  - `url=home_url()`
  - `beta=false`
- SSL verification is controlled by `edd_sl_api_request_verify_ssl`.
- Cache key prefix: `directorist_ext_version_`
- Cache duration: `3 * HOUR_IN_SECONDS`

If update is requested, download URL is fetched through the product-data API before filesystem update.

## Theme Data And Update Flow

Theme state is local WordPress theme data:

- `wp_get_theme()`
- `wp_get_themes()`
- `get_option('stylesheet')`
- `get_site_transient('update_themes')`
- WordPress theme screenshot/customizer APIs

Browser actions:

- Theme activation: `atbdp_activate_theme`
- Theme update: `atbdp_update_theme`

Theme activation calls `switch_theme()`, so it must be treated as live-site destructive behavior.

Theme update flow:

1. `handle_theme_update_request()` validates capability and nonce.
2. `update_the_themes()` checks `get_site_transient('update_themes')`.
3. It reads purchased themes from `_themes_available_in_subscriptions`.
4. It calls `get_file_download_link()` or falls back to the WordPress theme update package when available.
5. It downloads and installs through `download_theme()`.

## Download Link API

Used for plugin and theme update/install flows when a subscription item has a license and item ID.

- Method: `wp_remote_get()`
- Endpoint: `https://directorist.com/wp-json/directorist/v1/get-product-data/`
- Request body:
  - `product_type`
  - `license`
  - `item_id`
  - `get_info=download_link`
  - optional `beta=true`
- Returns: download URL in `response['data']`
- Current code sets `sslverify` to `false`.

Downloaded packages are then passed to `download_plugin()` or `download_theme()`. Current host verification allows `directorist.com` only in `is_varified_host()`.

## License Activation API

Used before install/download flows and when adding products to `_atbdp_purchased_products`.

- Method: `wp_remote_get()`
- Endpoint: `https://directorist.com`
- Request body:
  - `edd_action=activate_license`
  - `url=home_url()`
  - `item_id`
  - `license`
- Current code sets `sslverify` to `false`.
- `item_name_mismatch` can still be treated as success when returned `item_id` matches the requested item ID.

Successful activation can update `_atbdp_purchased_products`.

## Install From Subscription Flow

Browser action:

- Button selector family: `.file-install-btn`
- AJAX action: `atbdp_install_file_from_subscriptions`
- Request fields: `item_key`, `type`, `nonce`

Server flow:

1. `handle_file_install_request_from_subscriptions()` validates capability and nonce.
2. `install_file_from_subscriptions()` validates item key and product type.
3. It selects subscription source:
   - `plugin` -> `_plugins_available_in_subscriptions`
   - `theme` -> `_themes_available_in_subscriptions`
4. It confirms the item exists in the current subscription list.
5. It activates the license through EDD.
6. It selects beta or normal download link.
7. It calls `download_plugin()` or `download_theme()`.

This is high-risk because it can write plugin/theme files.

## Plugin Activation And Bulk Actions

Plugin activation:

- Browser selector family: `.plugin-active-btn`
- AJAX action: `atbdp_activate_plugin`
- Server action: `activate_plugin($plugin_key)`

Bulk installed-extension form:

- Form: `#atbdp-my-extensions-form`
- AJAX action: `atbdp_plugins_bulk_action`
- Tasks: `activate`, `deactivate`, `uninstall`
- Bulk nonce field in JS: `directorist_nonce`

Uninstall calls `delete_plugins()`, so it must not be automated on real/client sites without explicit confirmation.

## Required Extensions

Required extensions do not come directly from a remote endpoint. They are composed from:

- `directorist_required_extensions` filter
- current official extension catalog
- extension alias map
- purchased extension user meta
- plugin folder existence under the plugins directory
- active plugin option state

`prepare_the_final_requred_extension_list()` outputs required products with:

- recommending references
- base plugin file
- purchased state
- installed state

The UI then decides whether to show install, activate, or external get-now actions.

## Promo Product Rendering

Promo cards are rendered from `$this->extensions` and `$this->themes`, after filtering out installed/subscribed products for connected users.

Promo links are passed through `ATBDP_Upgrade::promo_link()`.

These links can leave wp-admin for Directorist product, purchase, demo, account, or support pages. Treat them as external handoff points, not local state transitions.

## Rendered Sections

Root template:

- Disconnected: account connect form.
- Connected: statistics plus managed themes/extensions.
- Always: all themes/extensions promo marketplace.

Statistics section uses:

- active extension total
- available extension total
- available theme total
- extension update total
- theme update total

Installed extensions section uses:

- `installed_extension_list`
- `outdated_plugin_list`
- `extension_list`
- `settings_url`

Subscribed extensions section uses:

- `extensions_available_in_subscriptions`
- `extension_list`
- alias map

Themes tab uses:

- `current_active_theme_info`
- `themes_available_in_subscriptions`
- installed theme data
- update state

## Known API/Data Issues

- `handle_file_download_request()` has `if ( 'plugin' !== $type || 'theme' !== $type )`, which is always invalid for both valid types. Prefer `atbdp_install_file_from_subscriptions` path unless this is intentionally fixed.
- `activate_plugin()` currently calls `activate_plugin()` without checking `WP_Error`.
- `handle_license_activation_request()` exists but is not registered in `setup_ajax_actions()`.
- `get_customers_purchased()` references stale/undefined variables and should not be reused blindly.
- Many successful AJAX paths immediately call `location.reload()`.
- Remote calls mix REST-style Directorist endpoints and EDD action endpoints.
- Some remote calls set `sslverify` to `false`; future hardening must be planned carefully for existing customers.

## Future API Improvement Feedback

Use these notes when changing the Themes & Extensions API layer. They are design constraints and improvement targets, not current runtime data.

### Browser Boundary

- Keep browser JavaScript calling local WordPress AJAX only.
- Do not call Directorist.com, EDD, or package download URLs directly from the browser.
- Let PHP own remote authentication, license activation, product catalog reads, package URL resolution, filesystem work, and canonical WordPress state checks.
- Preserve existing `wp_ajax_atbdp_*` action names and request fields; add new response fields in a backward-compatible way.

### Response Contract

Create one normalized response formatter for all page actions while preserving legacy fields that current JavaScript or third-party code may expect.

Recommended response fields:

```json
{
  "success": true,
  "code": "plugin_activated",
  "message": "Plugin activated.",
  "action": "activate_plugin",
  "item_key": "directorist-extension-slug",
  "type": "plugin",
  "next_state": "active",
  "requires_reload": false,
  "state": {},
  "html": {}
}
```

Rules:

- `success` must be the canonical boolean for new code.
- `code` must be machine-readable and stable for UI handling, logs, and tests.
- `message` must be safe for display and translatable when generated locally.
- `requires_reload` must be explicit, especially for filesystem, update, theme switch, and unreconciled remote failures.
- `state` may include a fresh canonical state summary, but docs must never store example site-specific values from it.
- `html` may include rendered row/card partials when that is safer than duplicating template logic in JavaScript.

### State Summary Endpoint

Add a read-only state endpoint before removing reloads from mutation flows.

Recommended local action:

- `atbdp_get_themes_extensions_state`

Recommended state groups:

- `account`: connected/disconnected capability and refresh/logout availability.
- `statistics`: counts and update flags generated at request time only.
- `extensions`: installed, active, inactive, outdated, subscribed, required, promo, and action availability.
- `themes`: active, installed, inactive, subscribed, update availability, and action availability.
- `notices`: non-sensitive connection, license, update, and remote API messages.
- `html`: optional server-rendered partials for rows/cards/counters.

Rules:

- Keep it capability and nonce protected.
- Return no passwords, raw licenses, subscription secrets, or user-identifying account data.
- Generate state from canonical server reads each time; do not let the browser invent final install/update/activation state.
- Use this endpoint after successful mutations and after recoverable API failures that may leave the UI stale.

### Error Codes And Failure Shape

Map mixed Directorist REST, EDD, WordPress, and filesystem failures into stable local error codes.

Recommended code categories:

- `capability_denied`
- `nonce_invalid`
- `account_disconnected`
- `auth_failed`
- `subscription_missing`
- `license_missing`
- `license_activation_failed`
- `remote_unreachable`
- `remote_invalid_response`
- `download_unavailable`
- `download_host_invalid`
- `filesystem_unavailable`
- `package_invalid`
- `install_failed`
- `update_failed`
- `activation_failed`
- `theme_switch_failed`
- `requires_reload`

Rules:

- Never show false success when WordPress returns `WP_Error`.
- Include developer-safe diagnostic context only when it does not expose passwords, raw licenses, or private account data.
- Prefer inline recoverable errors in the UI over browser `alert()` calls.

### Product Catalog API

Future product catalog API changes should be versioned and optional-field friendly.

Recommended additions:

- `schema_version`
- `catalog_version` or another cache-busting marker.
- `badge` object for `new`, `beta`, `popular`, `sale`, or similar labels.
- `status` or `availability` only when it has a clear UI meaning separate from EDD/WordPress `post_status`.
- stable product key/slug, item ID, product type, link, thumbnail, description, plugin base, demo link, and active promo flag.

Rules:

- Merge API product fields over local defaults by product key.
- Keep local fallback for non-badge fields when API data is unavailable or incomplete.
- Render badge/status only from API data or explicit filters.
- Do not infer badges from product name, slug, order, install date, or local hardcoded lists.
- Account for product catalog cache delay when adding time-sensitive badge behavior.

### Cache And Freshness

- Keep product catalog caching separate from account/subscription state.
- Do not cache user subscription/license state as if it were public catalog data.
- Prefer shorter or version-busted cache behavior for badge/status metadata than for long-lived product copy when product marketing needs faster updates.
- Add explicit cache invalidation or refresh behavior for support/debug flows if remote product data appears stale.
- Keep EDD version-check caching scoped enough to avoid cross-product contamination.

### Credential And Transport Safety

- Do not store account passwords after a request finishes.
- Do not log passwords, raw licenses, or raw subscription payloads.
- Prefer remote account authentication over `POST` for future API work; keep compatibility with current behavior until Directorist.com supports the safer contract.
- Review every `sslverify => false` path. Harden with compatibility filters and actionable error messages rather than silently breaking older customer sites.
- Use reasonable remote timeouts and map timeout failures to recoverable UI messages.

### Mutation Safety

- Add per-item action locking or request IDs for install, update, activate, uninstall, refresh, and theme switch to reduce duplicate-click and concurrent-request risk.
- Do not mark UI state final until the server accepts the action and a canonical state recheck confirms the result.
- For install/update, validate download URL, host, package structure, unzip result, copy result, and final plugin/theme presence before returning success.
- For theme switch, require explicit UI confirmation before AJAX and re-check `get_option( 'stylesheet' )` after success.
- For uninstall, require explicit UI confirmation and re-check plugin filesystem/active state after success.

## Redesign Guidance

For no-reload or page-speed improvements:

1. Keep browser calls pointed at local `admin-ajax.php`.
2. Keep PHP as the remote API and WordPress state boundary.
3. Add a stable server-side state summary before removing reloads.
4. Let expensive actions show progress in-place, then reconcile with canonical server state.
5. Keep full page reload as fallback for install, update, activation, theme switch, remote API failure, and filesystem failure.
