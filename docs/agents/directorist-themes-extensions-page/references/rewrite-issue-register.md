# Rewrite Issue Register

Use this register when planning or implementing a full rewrite of the Directorist admin `Themes & Extensions` page. These are durable issue patterns and fix priorities, not runtime data snapshots. Always re-check the current page, source, and Directorist docs before acting.

## Rewrite Principle

The page is an account-connected product management surface. It is not only a marketing catalog. A rewrite must preserve account connection, subscription discovery, install, activate, update, required-extension, settings-link, theme, and promo-product journeys while improving layout, speed, and failure handling.

Keep existing page slug, AJAX action names, nonce contracts, filters, aliases, user meta keys, selectors needed for compatibility, and fallback server-rendered behavior unless an explicit migration plan is approved.

The approved direction is a full UI rewrite of this page, not a staged old/new UI rollout. Do not plan a separate feature flag or rollout toggle by default unless a future task explicitly asks for one. Full UI rewrite still means preserving backend contracts, server-rendered compatibility behavior, reload fallbacks, and high-risk action safeguards.

## Preferred Rewrite Architecture

- Do not default to a Vue or React rewrite for this page.
- Keep PHP-rendered templates as the compatibility baseline and canonical fallback.
- Reduce complexity by adding focused PHP service/resolver classes behind the existing `wp_ajax_atbdp_*` actions.
- Add a small page-specific JavaScript state adapter for no-reload behavior instead of a full SPA.
- The adapter should own request lifecycle, button loading states, inline notices, row/card replacement, counters, and canonical state refresh.
- Existing actions should gradually return structured responses with `success`, `message`, `item_key`, `next_state`, `requires_reload`, and optional rendered HTML/state-summary data while preserving backward compatibility.
- Vue 2 should not be expanded just because the settings panel uses Vue; it is legacy surface area.
- React should only be considered after an explicit larger admin UI migration decision, because it requires a new state/API layer and broader QA.
- This architecture is the lowest-risk default for the 20k customer base: fewer moving parts, easier rollback, existing server-rendered fallback, and stable public contracts.

## Disconnected Account Policy

- Preserve current disconnected-account behavior by default.
- When `_atbdp_has_subscriptions_sassion` is empty, the page should render the account-connect form and the marketplace/promo discovery section.
- Do not expose installed premium product management in the disconnected state by default.
- Do not show Settings, Active status, Activate, Deactivate, Update, or local management actions for installed premium plugins/themes while disconnected unless a future task explicitly changes this policy.
- Existing installed/active premium extensions can continue working elsewhere in WordPress, but the Themes & Extensions page should not manage them while disconnected.
- License-backed actions must remain gated behind connected account/subscription data: new premium install, premium package download, premium update, refresh purchase, and any server-side license validation flow.
- Improve disconnected-state messaging without changing behavior. Recommended helper copy: `Already installed extensions will keep working. Connect your Directorist account to manage subscriptions, installs, and updates.`
- The intent is to keep a clear account-first product-management surface, avoid license/update confusion, and reduce support risk for existing customer sites.
- Connected-state page model can still group products as:
  - `Installed`: local WordPress plugin/theme state rendered after account connection.
  - `Available in your account`: subscription/license data rendered after account connection.
  - `Marketplace`: external product discovery, visible regardless of connection state.

## Remote Product Badge Policy

- New, beta, popular, sale, or similar product badges/status should be driven by the product API data contract, not hardcoded in plugin templates.
- The current product catalog contract may not include badge data. Re-check `Directorist\Core\API::get_products()` and the remote `v1/get-remote-products` response before implementation.
- Preferred remote API shape is a structured optional field:

```json
"badge": {
  "type": "new",
  "label": "New",
  "expires_at": "2026-09-01"
}
```

- `type` should be a machine-readable enum such as `new`, `beta`, `popular`, `sale`, or `featured`.
- `label` should be the display text from the API and escaped/translated safely where applicable.
- `expires_at` should be optional and handled server-side or client-side so expired badges do not render.
- The core plugin UI should read badge/status values from the product API. EDD product meta, custom taxonomy, or a dedicated badge setting can be the upstream source on Directorist.com, but the Themes & Extensions page should depend on the API field, not direct EDD assumptions.
- Do not depend on normal WordPress/EDD `post_status`; `publish` and `draft` indicate product availability, not UI badge state.
- Badge rendering must be optional and backward compatible. If no `badge` field exists, render no badge.
- Local filters `atbdp_extension_list` and `atbdp_theme_list` should still be able to add or override badge data for compatibility and testing.
- Do not infer `New` from product order, product name, local install date, or a hardcoded slug list.
- Because the product catalog is cached, badge changes from the API must account for cache invalidation or acceptable cache delay.

## Product Copy Source Policy

- Product API data is preferred for product names, descriptions, thumbnails, product links, demo links, active promo flags, item IDs, and plugin bases when valid fields exist.
- Local default product arrays must remain a safe fallback for names, descriptions, thumbnails, links, demo links, item IDs, and plugin bases.
- Merge API product data over local defaults by product key where possible; do not allow missing remote fields to create blank cards or broken product links.
- If the API is unavailable, empty, malformed, or missing a non-badge field, use the local fallback field.
- Badge/status behavior remains stricter: badge/status must come from product API data or explicit filters only. Do not create hardcoded badge/status fallback from local product names, slugs, order, or descriptions.
- Cross-check user-facing product claims, labels, and descriptions against local `README.md`/`readme.txt` and official Directorist docs before changing copy.

## High Priority Issues To Fix

### Responsive Layout

- Current extension management UI is table-first and uses fixed/minimum widths in the extension name and action areas.
- This can create page-level horizontal overflow on mobile and narrow admin layouts.
- Full rewrite should use a responsive management layout: table on wide screens only if needed, card/list rows on narrow screens, stable action menus, and no page-level overflow.
- Always re-check with Agent Browser desktop and mobile viewports.

Primary areas:

- `views/admin-templates/theme-extensions/my-themes-extensions/extensions-tab.php`
- `views/admin-templates/theme-extensions/my-themes-extensions/themes-tab.php`
- `assets/src/scss/layout/admin/admin-style.scss`

### Reload-Heavy UX

- `assets/src/js/admin/components/subscriptionManagement.js` relies heavily on `location.reload()` after successful account connect, install, activate, update, refresh purchase, logout, uninstall, and bulk actions.
- Do not remove reloads blindly. They currently force canonical server-rendered state.
- Add progressive no-reload behavior behind existing AJAX actions. Use a small page-state adapter, loading states, normalized error handling, and canonical state refresh before claiming completion.
- Keep full reload fallback for high-risk actions and unreconciled states.

### Dead Login Continuation Flow

- The account-connect success path reloads immediately, leaving older checklist/download continuation code unreachable.
- During rewrite, either remove the dead path or intentionally rebuild it as a supported flow.
- Do not revive old checklist behavior without checking current account, subscription, and install contracts.

### Registered Download Handler Type Bug

- `ATBDP_Extensions::handle_file_download_request()` currently has invalid product type validation: valid `plugin` and `theme` values cannot pass the condition.
- Do not reuse this handler for new flows until the condition and response contract are fixed.
- Prefer `atbdp_install_file_from_subscriptions` for current subscription installs unless this legacy handler is intentionally repaired and tested.

### Plugin Activation Error Handling

- Single plugin activation calls WordPress `activate_plugin()` without checking for `WP_Error`.
- A rewrite must surface activation errors, avoid false-success UI, and keep the button recoverable on failure.
- Bulk activation already checks `WP_Error`; align single activation response behavior with that pattern.

### Filesystem Install And Update Safety

- Plugin/theme download, install, and update flows write to `wp-content/plugins` and `wp-content/themes`.
- Existing code can delete an existing destination directory before fully validating package extraction and copy success.
- Full rewrite should introduce a safer install/update service behind existing AJAX actions:
  - validate download host and URL before download;
  - validate `download_url()`, `unzip_file()`, and `copy_dir()` results;
  - verify expected package structure before replacing an existing directory;
  - clean temp directories reliably;
  - restore temporary error handlers;
  - return structured failures with recovery instructions;
  - never mark UI complete until server confirms final state.

### Plugin Uninstall Safety

- Keep uninstall available for compatibility with the existing page, but make it a protected danger action.
- Do not remove the behavior unless a future migration explicitly chooses to delegate uninstall fully to the WordPress Plugins screen.
- Do not expose uninstall as a primary one-click action.
- Place uninstall under a danger/overflow menu.
- Require a confirmation modal that names the extension and explains that plugin files will be deleted and dependent site features may break.
- The server should confirm capability, nonce, plugin target validity, and `delete_plugins()` result before returning success.
- The UI must not mark uninstall complete until canonical WordPress plugin state has been re-checked, or a reload fallback has completed.
- Failed uninstall must restore the action UI and show inline failure feedback.

### Theme Update State Defensive Checks

- Theme update code assumes `get_site_transient( 'update_themes' )` is an object with a `response` property.
- Rewrite code must handle missing, false, malformed, or empty update transient state without PHP warnings.
- Theme activation calls `switch_theme()` and must remain a high-risk action.
- Locked recommendation: every theme activation/switch requires an explicit confirmation modal before the AJAX request is sent.
- The confirmation modal must name the theme and warn that activating it changes the live site's active theme and may affect layout, menus, widgets, headers/footers, and theme settings.
- Theme activation must not be triggered by a single accidental click. Do not run it from Agent Browser or automated QA on a real/client site without explicit confirmation.
- Do not show active-theme success UI until server success and canonical active-theme state has been re-checked, or reload fallback has completed.

### Stale License/Purchase Helpers

- `handle_license_activation_request()` exists but is not registered by `setup_ajax_actions()`.
- `get_customers_purchased()` and older purchased/download helpers contain stale variable usage and duplicated fields.
- Do not base new logic on these helpers without a focused audit. Prefer a new service layer behind existing public contracts.

### JavaScript State And Error Handling

- Current JS uses repeated state flags, undeclared assignments in some bulk paths, inconsistent error resets, browser `alert()` for several failures, and success paths that reload without inspecting response status.
- Rewrite should centralize request state, disable and restore controls consistently, show inline feedback, and keep action buttons usable after errors.
- Bulk flows should aggregate per-item results and continue safely where appropriate.

### Accessibility, Semantics, And I18n

- Current templates include hardcoded English strings, duplicate IDs, empty or `#` action links, external `target="_blank"` links without `rel`, placeholder-only fields, and action controls implemented as links.
- Rewrite should use buttons for local actions, unique IDs, visible labels or accessible labels, `rel="noopener noreferrer"` for external blank-target links, translatable strings, and clear keyboard/focus behavior.

### Theme Changelog Modal

- The theme "What's new" modal contains dummy hardcoded version and changelog content.
- Do not ship this content in a redesign. Either connect it to real update/changelog data or remove/hide the feature.

### Remote API And Credential Handling

- Browser JS should continue talking to local WordPress AJAX only. PHP owns Directorist.com and EDD remote calls.
- Existing remote flows mix Directorist REST endpoints and EDD action endpoints; keep response normalization server-side.
- Review `sslverify => false` usages carefully. Harden only with a compatibility plan and useful error handling for customer sites.
- Never log or store account passwords beyond the current request. Keep account connection and refresh purchase flows careful around credentials.
- Before changing API behavior, read `api-data-flow-report.md#future-api-improvement-feedback` and apply its guidance for normalized responses, state-summary refresh, stable error codes, product catalog versioning, cache freshness, credential handling, and mutation safety.

## Rewrite Fix Order

1. Preserve contracts first: page slug, capability, AJAX action names, nonces, filters, aliases, meta keys, and existing settings/product links.
2. Preserve current disconnected behavior: account-connect form plus marketplace only, with no installed premium product management while disconnected.
3. Implement product copy merging with API data as primary and local product arrays as fallback for non-badge fields.
4. Add optional product badge/status support through product API contracts, with no hardcoded badge fallback.
5. Add focused PHP service/resolver classes behind existing AJAX actions instead of introducing Vue/React as the first rewrite step.
6. Add server-side state summary or render-partial capability before removing reloads.
7. Build a response formatter for all page AJAX handlers: success, error, message, action, item key, canonical state needed, reload fallback.
8. Add a small page-specific JavaScript state adapter around existing AJAX calls.
9. Replace fragile table layout with responsive rows/cards while preserving old selectors or compatibility wrappers.
10. Fix low-risk no-reload flows first: account-connect feedback, refresh purchase errors, tabs, filtering/search, inline errors, and button states.
11. Harden single plugin activation and bulk result handling.
12. Harden install/update/download services before changing their UI behavior.
13. Keep uninstall for compatibility, but move it behind a protected danger action with confirmation and canonical plugin-state revalidation.
14. Require confirmation modal for every theme switch, then revalidate canonical active-theme state or use reload fallback.
15. Treat plugin/theme update and filesystem replacement as conservative flows with server revalidation and reload fallback.
16. Remove or rebuild stale/dead code only after verifying no custom integration depends on old selectors or action names.

## Regression Checklist For Full Rewrite

- Disconnected account state renders and validates without page errors.
- Disconnected account state keeps the current behavior: account-connect form plus marketplace, without installed premium product management actions.
- Connected account state renders statistics, extensions, themes, required items, and promo items from dynamic server state.
- Premium install/update/download actions remain gated behind connected account/subscription/license state.
- Product names, descriptions, thumbnails, and links use API data when present and local fallback when API fields are missing.
- Product badges render only from API/filter-provided badge data and disappear safely when absent or expired.
- Existing installed active, installed inactive, subscribed-not-installed, outdated, required, promo-only, active theme, inactive theme, and update-available states are all represented.
- No page-level horizontal overflow on mobile admin widths.
- No action leaves buttons permanently disabled after failure.
- No local action uses `alert()` as the only feedback.
- Uninstall is not exposed as a one-click primary action; it requires explicit confirmation and recovers cleanly on failure.
- Theme activation requires explicit confirmation every time and does not show success before active-theme state is confirmed.
- No success UI is shown before server acceptance.
- High-risk actions re-check canonical WordPress state before final UI update.
- Console and page errors stay clean after load, tab changes, and non-destructive interactions.
- Official Directorist docs and local `README.md`/`readme.txt` are checked before changing public claims, product labels, or descriptions.
