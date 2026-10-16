# Licensing System PRD

This PRD defines how the Directorist admin `Themes & Extensions` page should handle account connection, licenses, subscriptions, extension setup, and theme setup during the full UI rewrite.

It is intentionally product/system focused. It must not store runtime account data, current product counts, subscribed product lists, installed plugin lists, usernames, screenshots, or one-time API payloads.

## Objective

Build a safer, faster, account-connected product management system for Directorist themes and extensions while preserving existing customer compatibility.

The page should let a site admin:

- Connect a Directorist.com account.
- See which extensions and themes are installed, active, available in the account, required, outdated, or marketplace-only.
- Install entitled premium extensions/themes.
- Activate installed extensions.
- Update entitled premium extensions/themes.
- Switch Directorist themes only after explicit confirmation.
- Refresh purchase/subscription state.
- Disconnect the Directorist account without breaking already installed products.

## Non-Goals

- Do not redesign the frontend `[directorist_user_dashboard]`.
- Do not replace the whole page with Vue/React unless a future explicit architecture decision approves it.
- Do not create a new payment/subscription platform inside the plugin.
- Do not expose raw license keys, passwords, or private subscription payloads in browser state.
- Do not add an old/new UI rollout toggle by default.
- Do not store runtime product/account values in docs.

## Core Product Rule

The Directorist account/license connection controls product management on this page. It should not control whether already installed premium extensions keep running elsewhere in WordPress.

Disconnected behavior stays as currently approved:

- Show the Directorist account connection form.
- Show marketplace/promo product discovery.
- Do not show local premium product management actions while disconnected.
- Already installed premium products can keep working in WordPress.
- Show helper copy: `Already installed extensions will keep working. Connect your Directorist account to manage subscriptions, installs, and updates.`

## System Actors

- WordPress admin user: must have `manage_options`.
- Local WordPress site: source of installed plugin/theme state.
- Directorist plugin PHP: owns local AJAX, remote calls, entitlement normalization, install/update logic, and canonical state.
- Browser JavaScript: calls local WordPress AJAX only and updates UI from server-confirmed state.
- Directorist.com account/licensing API: verifies account credentials and returns subscription/license data.
- Directorist.com EDD license API: activates/checks licenses for package access and updates.
- app.directorist.com product API: optional product catalog/copy/badge source.

## Main Data Sources

### Product Catalog

Purpose: define the official extension/theme catalog and marketplace cards.

Sources:

- Local fallback arrays from `ATBDP_Extensions::get_default_extensions()`.
- Local fallback arrays from `ATBDP_Extensions::get_default_themes()`.
- Optional remote catalog through `Directorist\Core\API::get_products()`.
- Filters: `atbdp_extension_list`, `atbdp_theme_list`.

Rules:

- API product fields are preferred when valid.
- Local product arrays remain fallback for non-badge fields.
- Merge API over local defaults by product key.
- Badge/status fields must come from product API data or explicit filters only.
- Do not infer badge/status from product name, slug, order, local install date, or hardcoded lists.

### Account And Subscription State

Purpose: determine whether the admin account is connected and which products are entitled.

Stored local user meta:

- `_atbdp_subscribed_username`
- `_atbdp_has_subscriptions_sassion`
- `_themes_available_in_subscriptions`
- `_plugins_available_in_subscriptions`

Rules:

- Preserve the `sassion` misspelling because it is a legacy contract.
- Treat this user meta as a local cache of account state, not public catalog truth.
- Refresh purchase should revalidate against Directorist.com before changing entitlement state.
- Do not expose raw license keys or private subscription payloads to browser JavaScript.

### Local WordPress Product State

Purpose: determine what is installed, active, outdated, required, or currently active theme.

Sources:

- `get_plugins()`
- `is_plugin_active()`
- `wp_get_theme()`
- `wp_get_themes()`
- `get_option( 'stylesheet' )`
- `get_site_transient( 'update_plugins' )`
- `get_site_transient( 'update_themes' )`
- `directorist_required_extensions`
- `directorist_extensions_aliases`

Rules:

- Local WordPress state is canonical for installed/active/final action result.
- Browser UI must not invent final install/update/activation state.
- After high-risk actions, re-check canonical server state or use reload fallback.

## Required Backend Components

### ProductCatalogResolver

Builds the extension/theme catalog.

Responsibilities:

- Load local fallback catalog.
- Optionally merge remote API catalog.
- Apply existing filters.
- Normalize product keys, item IDs, links, thumbnails, descriptions, plugin base, demo links, active flags, and optional badge data.
- Avoid blank cards when remote fields are missing.

### AccountConnectionService

Handles connected/disconnected account state.

Responsibilities:

- Authenticate Directorist.com credentials through the existing local AJAX action.
- Store connected account state in existing user meta.
- Refresh purchase/subscription state.
- Logout/disconnect by clearing the same legacy meta keys.
- Never persist account password after the request.

### EntitlementResolver

Converts subscription payloads into product action permissions.

Responsibilities:

- Read `_plugins_available_in_subscriptions` and `_themes_available_in_subscriptions`.
- Match entitlement records to product catalog keys.
- Determine whether install/update/download/license actions are available.
- Hide premium management actions when the account is disconnected.
- Return safe action availability data, not raw secrets.

### ProductStateResolver

Builds canonical current state for the page.

Responsibilities:

- Determine installed, active, inactive, outdated, required, subscribed-not-installed, promo-only, active theme, inactive theme, and update-available states.
- Resolve alias/deprecated extension keys.
- Create state groups for extensions, themes, required items, and statistics.
- Produce server-rendered partials when needed for no-reload UI updates.

### LicenseService

Owns EDD license activation/version/package eligibility.

Responsibilities:

- Activate a product license before install/download when required.
- Check EDD version data for update eligibility.
- Normalize EDD errors into stable local error codes.
- Treat missing, invalid, expired, or remote-failed licenses as recoverable UI states.
- Never return raw license data to the browser unless already exposed by legacy behavior and required for compatibility.

### PackageInstallService

Owns plugin/theme download, validation, install, update, and cleanup.

Responsibilities:

- Request package URLs from Directorist.com only from PHP.
- Validate package host and URL.
- Validate `download_url()`, `unzip_file()`, extracted package structure, and copy result.
- Avoid deleting/replacing existing folders before validating the new package.
- Clean temporary files/directories.
- Return structured errors and keep reload fallback for unreconciled filesystem state.

### PageStateEndpoint

New recommended read-only AJAX action:

- `atbdp_get_themes_extensions_state`

Responsibilities:

- Return canonical account, extension, theme, required-product, statistics, notice, and optional HTML partial state.
- Be capability and nonce protected.
- Return no passwords, raw licenses, usernames, or private account payloads.
- Support no-reload UI refresh after successful actions.

### ResponseFormatter

Normalizes all page action responses.

Recommended shape:

```json
{
  "success": true,
  "code": "plugin_activated",
  "message": "Plugin activated.",
  "action": "activate_plugin",
  "item_key": "product-key",
  "type": "plugin",
  "next_state": "active",
  "requires_reload": false,
  "state": {},
  "html": {}
}
```

Rules:

- Preserve legacy response fields where existing JS or third-party code may depend on them.
- New JS should use `success`, `code`, `message`, `requires_reload`, and canonical `state`.
- Errors should be inline and recoverable where possible.

## Core User Journeys

### 1. Page Load

1. Admin opens `edit.php?post_type=at_biz_dir&page=atbdp-extension`.
2. PHP validates capability and prepares aliases, required extensions, product catalog, account state, and local WP state.
3. If account is disconnected, render connect form plus marketplace.
4. If account is connected, render statistics, installed/subscribed extension management, theme management, required products, and marketplace.
5. Browser JS enhances interactions without replacing server-rendered fallback.

### 2. Connect Account

1. Admin submits Directorist.com username/password.
2. Browser calls local `atbdp_authenticate_the_customer`.
3. PHP calls Directorist.com account/licensing API.
4. PHP stores connected account and subscription state in legacy user meta.
5. UI refreshes via state endpoint or reload fallback.
6. Password is discarded after the request.

Success state:

- Connected account UI becomes available.
- Subscribed products can be shown from canonical server state.

Failure state:

- Show inline authentication/remote/API error.
- Keep connect form usable.
- Do not store partial/unsafe credential state.

### 3. Refresh Purchase

1. Admin confirms password for refresh.
2. Browser calls local `atbdp_refresh_purchase_status`.
3. PHP re-authenticates against Directorist.com and rewrites subscription meta.
4. UI refreshes canonical state.

Failure state:

- Existing visible state remains stable.
- Show inline error.
- If session is invalid, return `requires_reload` or disconnected state.

### 4. Disconnect Account

1. Admin clicks logout/disconnect.
2. Browser calls local `atbdp_close_subscriptions_sassion`.
3. PHP clears connected session meta.
4. UI returns to disconnected state.

Rules:

- Installed premium products keep running elsewhere in WordPress.
- This page no longer shows premium management actions while disconnected.

### 5. Install Extension From Subscription

1. Product must be in connected account entitlement state.
2. Browser calls local `atbdp_install_file_from_subscriptions`.
3. PHP validates capability, nonce, entitlement, license, download URL, package, and filesystem operation.
4. PHP installs the extension package.
5. UI re-checks canonical plugin state.

Rules:

- Do not mark installed until server confirms installed state.
- Keep full reload fallback for filesystem ambiguity.
- Show recoverable errors for invalid license, missing entitlement, failed download, invalid package, and filesystem failure.

### 6. Activate Extension

1. Product must be installed and inactive.
2. Browser calls local `atbdp_activate_plugin`.
3. PHP calls WordPress `activate_plugin()`.
4. PHP must check `WP_Error`.
5. UI re-checks canonical active plugin state.

Rules:

- Do not show false success on `WP_Error`.
- If activation fails, restore button and show inline error.

### 7. Update Extension

1. Product must be installed, entitled, and update-available.
2. PHP checks EDD version data and download eligibility.
3. PHP validates package and safely replaces plugin files.
4. UI re-checks installed version/update state.

Rules:

- Treat update as high-risk filesystem mutation.
- Prefer progress UI plus reload fallback over optimistic final state.

### 8. Required Extension

1. Required items come from `directorist_required_extensions`.
2. PHP matches required product to catalog, aliases, subscription state, install state, and active state.
3. UI shows the next safe action: install, activate, or get-now/external handoff.

Rules:

- Required status is not a remote endpoint by itself.
- Required-product install/update still follows the same entitlement and license rules.

### 9. Install Theme From Subscription

1. Product must be in connected account entitlement state.
2. Browser calls existing install action with `type=theme`.
3. PHP validates entitlement, license, package URL, package structure, and theme install result.
4. UI re-checks `wp_get_themes()` and theme update state.

Rules:

- Installing a theme is filesystem mutation but does not switch the live theme by itself.
- Keep reload fallback if theme state cannot be reconciled.

### 10. Activate Theme

1. Theme must be installed and inactive.
2. UI shows explicit confirmation modal every time.
3. After confirmation, browser calls local `atbdp_activate_theme`.
4. PHP calls `switch_theme()`.
5. UI re-checks `get_option( 'stylesheet' )`.

Confirmation copy must communicate:

- This changes the live site's active theme.
- Layout, menus, widgets, headers/footers, and theme settings may be affected.

Rules:

- Never trigger theme switch from a single accidental click.
- Do not automate theme switch on real/client sites without explicit confirmation.
- Do not show success before canonical active-theme state is confirmed.

### 11. Update Theme

1. Theme must be installed, entitled, and update-available.
2. PHP checks theme update transient and entitlement.
3. PHP validates package/download and updates theme files.
4. UI re-checks canonical theme version/update state.

Rules:

- Handle missing or malformed `update_themes` transient defensively.
- Keep reload fallback for filesystem or state uncertainty.

### 12. Marketplace / Get Now

1. Promo-only product cards are visible as product discovery.
2. Links may go to Directorist product, pricing, account, demo, support, or docs pages.
3. External links are handoff points, not local state changes.

Rules:

- Use API product copy when available and local fallback when not.
- Product claims should be cross-checked with local readme files and official docs before copy changes.

## License States

The UI and API should support these states as categories:

- `connected`: account session exists locally.
- `disconnected`: no local account session.
- `entitled`: current account has subscription/license access for product.
- `not_entitled`: product is marketplace-only for this account.
- `license_active`: remote license activation succeeded for the requested product.
- `license_missing`: entitlement record lacks a usable license.
- `license_invalid`: EDD activation/check failed.
- `license_expired`: remote license indicates renewal required.
- `license_remote_failed`: Directorist.com/EDD could not be reached or returned invalid response.
- `installed`: product exists locally.
- `active`: plugin active or theme is current stylesheet.
- `inactive`: installed but not active.
- `update_available`: local version is behind canonical update state.
- `requires_reload`: server cannot safely reconcile state in-place.

Already installed products should not be presented as broken only because the account is disconnected. The page should say connection is required for subscription management, installs, downloads, and updates.

## API Requirements

Existing local AJAX actions must remain compatible:

- `atbdp_authenticate_the_customer`
- `atbdp_install_file_from_subscriptions`
- `atbdp_plugins_bulk_action`
- `atbdp_activate_theme`
- `atbdp_activate_plugin`
- `atbdp_update_plugins`
- `atbdp_update_theme`
- `atbdp_refresh_purchase_status`
- `atbdp_close_subscriptions_sassion`

Recommended new local AJAX action:

- `atbdp_get_themes_extensions_state`

Remote API calls stay server-side:

- Account/license auth: `https://directorist.com/wp-json/directorist/v1/licencing`
- Product data/download link: `https://directorist.com/wp-json/directorist/v1/get-product-data/`
- EDD license activation/version: `https://directorist.com`
- Optional product catalog: `https://app.directorist.com/wp-json/directorist/v1/get-remote-products`

## UI Requirements

- Keep disconnected UI simple: connect form plus marketplace.
- Connected UI should group products by clear state: installed, available in account, required, themes, marketplace.
- Show action buttons only when the server says the action is available.
- Use inline notices instead of browser `alert()` as the primary feedback.
- Use button loading states and disable duplicate clicks while a request is running.
- Require confirmation for uninstall and theme activation.
- Keep external product/docs/account links visually distinct from local AJAX actions.
- Mobile layout must avoid horizontal overflow.

## Performance Requirements

- Keep PHP-rendered templates as canonical fallback.
- Add no-reload behavior through a small page-specific JS state adapter.
- Use the state endpoint after safe or successful actions.
- Avoid full reload for simple UI state changes and recoverable errors.
- Keep reload fallback for install, update, theme switch, uninstall, remote API failure, and filesystem uncertainty.

## Security And Privacy Requirements

- Require `manage_options` for all local actions.
- Verify nonce for all local actions.
- Sanitize request fields and escape rendered output.
- Never store passwords after request completion.
- Never log passwords, raw licenses, or private subscription payloads.
- Do not expose raw subscription/license data to the browser.
- Review `sslverify => false` paths with compatibility-safe hardening and clear error handling.

## Compatibility Requirements

Preserve:

- Page slug: `atbdp-extension`
- Parent post type: `at_biz_dir`
- Capability: `manage_options`
- Legacy AJAX action names
- Legacy user meta keys
- `directorist_extensions_aliases`
- `directorist_required_extensions`
- `atbdp_extension_list`
- `atbdp_theme_list`
- Existing settings/product/external links
- Existing template/selector compatibility where practical

## Error Handling Requirements

Use stable local error codes such as:

- `capability_denied`
- `nonce_invalid`
- `account_disconnected`
- `auth_failed`
- `subscription_missing`
- `license_missing`
- `license_activation_failed`
- `license_expired`
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

## Acceptance Criteria

- A disconnected admin sees the account-connect form and marketplace only.
- A connected admin sees product-management sections built from current server state.
- Installed premium products are not implied to stop working when disconnected.
- Premium install/update/download requires connected entitlement/license state.
- Extension activation checks `WP_Error` and never shows false success.
- Theme activation always requires confirmation and re-checks active theme after success.
- Uninstall is available only as a protected danger action with confirmation.
- Product badges render only from API/filter data.
- Product copy uses API data with local fallback for missing non-badge fields.
- No high-risk action shows final success before canonical server state confirms it.
- No page-level horizontal overflow on mobile admin widths.
- All local action failures leave controls recoverable.
- Official Directorist docs and local readme files are checked before changing public product claims.

## Documentation References

- Local API map: `api-data-flow-report.md`
- Runtime data contract: `dynamic-data-contract.md`
- Account journey map: `account-license-journey-map.md`
- Rewrite issue register: `rewrite-issue-register.md`
- Official Directorist docs: `https://directorist.com/documentation/directorist/`
- Official themes docs: `https://directorist.com/documentation/themes/`
- Official extensions docs: `https://directorist.com/documentation/extensions/`
