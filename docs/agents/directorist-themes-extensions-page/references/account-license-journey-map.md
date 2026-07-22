# Account And License Journey Map

This map covers the Directorist.com account, license, subscription, and product-management journey inside the admin `Themes & Extensions` page. It does not cover the frontend `[directorist_user_dashboard]`.

## Page Load Journey

1. WordPress loads the admin submenu page `atbdp-extension`.
2. `ATBDP_Extensions::initial_setup()` prepares aliases, required extensions, product lists, and update data.
3. `show_extension_view()` reads current user meta to decide whether the account is connected.
4. The root template renders either the account connection form or connected account product-management UI.
5. The promo catalog is rendered as product discovery regardless of account state.

All values are dynamic and must be re-collected on each task.

## Logged-Out Account Journey

1. Template `auth/license-auth-section.php` renders `#atbdp-directorist-license-login-form`.
2. `subscriptionManagement.js` intercepts submit.
3. AJAX action `atbdp_authenticate_the_customer` sends username, password, and nonce.
4. `authenticate_the_customer()` calls the Directorist remote account/licensing endpoint.
5. On success, user meta is updated with connected state and available subscription data.
6. Current JS reloads the page after success.

Performance opportunity: replace unconditional reload with a state refresh/render step, but keep reload fallback when canonical state cannot be reconstructed safely.

## Disconnected Account Recommendation

When the account is not connected, preserve the current page behavior: render the Directorist account-connect form and the promo marketplace/discovery section only.

- Locked as of 2026-07-22: the disconnected view is complete and should be treated as out of scope for future Themes & Extensions work unless the user explicitly asks for disconnected-view changes in that task.
- Do not alter disconnected-view layout, account-connect copy, resource links, search/tabs/count placement, product row/card actions, CSS/responsive behavior, or page-specific JS behavior by accident while working on connected-state, badge, API, licensing, install/update, or theme-management features.
- Do not expose installed premium product management in the disconnected page state by default.
- Do not show Settings, Active status, Activate, Deactivate, Update, or local management actions for installed premium products while disconnected unless a future task explicitly changes this policy.
- Already installed premium products can continue working in WordPress, but this page should not manage them while the Directorist account session is disconnected.
- Premium install, package download, update, refresh purchase, and license validation must require connected account/subscription data.
- The disconnected state should include a clear account-connect CTA for managing subscriptions, installs, and updates.
- Recommended helper copy: `Already installed extensions will keep working. Connect your Directorist account to manage subscriptions, installs, and updates.`
- This keeps the safest behavior for existing customer sites while reducing confusion about whether installed premium products stop working when the account is disconnected.

## Connected Account Journey

1. Statistics section renders current extension/theme availability and update status from server-side overview data.
2. `my-themes-extensions.php` renders tabs for extension and theme management.
3. Installed products, subscribed products, required products, active theme, and available subscription themes are all generated from live data.
4. Refresh Purchase opens a password confirmation flow and calls `atbdp_refresh_purchase_status`.
5. Logout calls `atbdp_close_subscriptions_sassion` and clears connected account state.

Do not store the resulting product or account values in docs.

## Product Action Journeys

### Install From Subscription

- UI selector family: `.file-install-btn`
- AJAX action: `atbdp_install_file_from_subscriptions`
- Server path: `handle_file_install_request_from_subscriptions()` -> `install_file_from_subscriptions()`
- Risk: filesystem download, unzip/copy, license activation, package host validation, plugin/theme destination changes.

### Activate Plugin

- UI selector family: `.plugin-active-btn`
- AJAX action: `atbdp_activate_plugin`
- Server path: `activate_plugin()`
- Risk: activation can trigger extension code, dependencies, fatal errors, or `WP_Error`.

### Update Plugin

- UI selector family: `.ext-update-btn`
- AJAX action: `atbdp_update_plugins`
- Server path: `handle_plugins_update_request()` -> update/download helpers
- Risk: remote version checks, download package, filesystem replacement.

### Bulk Plugin Action

- Form: `#atbdp-my-extensions-form`
- AJAX action: `atbdp_plugins_bulk_action`
- Supported tasks include activate, deactivate, and uninstall.
- Risk: uninstall is destructive and must not be automated without explicit confirmation.

### Plugin Uninstall Recommendation

- Keep uninstall available for compatibility with the existing page, but make it a protected danger action in the rewrite.
- Do not expose uninstall as an easy primary action.
- Place uninstall under a danger/overflow menu with clear destructive styling.
- Require an explicit confirmation modal that shows the extension name and explains that plugin files will be deleted and site features may break.
- Do not run uninstall from Agent Browser or tests on a real/client site without explicit confirmation for that exact action.
- After server success, re-check canonical WordPress plugin state before updating the UI; keep full reload fallback when state cannot be reconciled.

### Required Extensions

- Form: `#atbdp-required-extensions-form`
- UI can show install, activate, or external get-now paths depending on ownership and install state.
- Required extension data comes from `directorist_required_extensions` and current product/install state.

### Theme Activate/Update

- Theme activation action: `atbdp_activate_theme`
- Theme update action: `atbdp_update_theme`
- Risk: `switch_theme()` changes the live site theme, and update/download touches theme files.
- Locked recommendation: every theme activation/switch must require an explicit confirmation modal before calling `atbdp_activate_theme`.
- The confirmation must name the theme and warn that activating it changes the live site's active theme and may affect layout, menus, widgets, headers/footers, and theme settings.
- Theme activation should not be treated like a normal button status change. It is a live-site change and must not run from accidental click, hover, keyboard focus, or Agent Browser automation without explicit confirmation.
- After server success, re-check canonical active theme state before updating UI; keep full reload fallback when state cannot be safely reconciled.

## External Handoff Points

The page can link outside wp-admin for:

- Product details
- Get Now / purchase flows
- Demo links
- Directorist.com dashboard/account/support
- Official docs

External links must open intentionally and should not be mistaken for local AJAX state.

## Journey Safety

- Inspect forms and selectors read-only by default.
- Use mocked AJAX, local throwaway sites, or explicit approval for action testing.
- For no-reload improvements, update the local UI only after server success and then reconcile with canonical server state.
- Keep full reload fallback available for remote/API/filesystem failures.
