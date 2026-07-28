# Current Implementation Change Notes

These notes capture durable Themes & Extensions page changes made during the current redesign pass. They are implementation context for future rewrite work, not runtime truth.

Do not store runtime product counts, installed product lists, update counts, account names, subscription contents, or local screenshots here. Re-check live state at task time.

## Scope

- Page: `edit.php?post_type=at_biz_dir&page=atbdp-extension`.
- Template: `views/admin-templates/theme-extensions/theme-extension.php`.
- Page JS: `assets/js/directorist-themes-extensions.js`.
- Page CSS: `assets/css/directorist-themes-extensions.css`.
- Product data source class: `includes/classes/class-extension.php`.
- Disconnected view remains locked unless a future task explicitly asks to change it.

## Disconnected View

- Disconnected view is treated as complete and locked.
- Header only keeps Directorist branding and resource links for Docs, Tutorials, and Support.
- Connected-state navigation, account menu, settings, active/update controls, and local product management are not shown while disconnected.
- Account form uses `Username or email address` with an email-style placeholder.
- Password field has a show/hide toggle.
- Account login remains the default connection method for backward compatibility. A compact, keyboard-accessible Access key tab uses the License Manager `user-connect` endpoint through the existing `atbdp_authenticate_the_customer` AJAX action.
- Access-key and account-login responses normalize into the same legacy session and entitlement user meta. The access key is request-only and is not persisted; `_atbdp_subscription_connection_method` stores only the non-secret method name.
- Access-key help links to the Directorist.com account dashboard where the current theme exposes the key. Official Directorist installation/license docs still describe account email/password only, so those docs must be updated before publicly announcing Access Key login.
- The disconnected page does not force focus on load, so the account form and marketplace remain browse-first; switching authentication methods focuses the first field in the selected panel.
- Messaging clarifies that already installed Directorist products keep working, but account connection is required for subscription installs, updates, and license-backed management.
- Disconnected product rows use marketplace/details-oriented actions instead of install/update/activate/deactivate/delete controls.

## Connected Header And Account Menu

- The large connected account stats/action card was removed from the body.
- Connected header navigation now uses `Dashboard` and `Add-ons` as internal page views.
- The existing Themes & Extensions catalog is labeled `Add-ons` in the header nav, but keeps the original page slug, selectors, forms, rows, and AJAX contracts.
- `Docs`, `Tutorials`, and `Support` remain external resource links, not dashboard tabs, and are grouped on the right side of the connected header like the disconnected view.
- The Dashboard welcome section uses the connected Directorist licensing account owner's display name. It falls back to a non-email licensing username and then generic `Welcome back`; it does not expose the licensing email or use the local WordPress administrator identity.
- The connected header account control uses the optional authoritative licensing-account `avatar_url`, with a Gravatar-derived URL or licensing-owner initials as safe client fallbacks. The account dropdown identifies the licensing owner instead of the local WordPress user.
- The Dashboard consumes the optional `_atbdp_account_summary` user meta when a compatible licensing API provides authoritative plan status, expiry, All Access, and lifetime fields.
- Active All Access plans can show the dynamic expiration/unlocked message; lifetime, expired, cancelled, limited, missing, and malformed states use dedicated safe copy or the generic connected fallback.
- Legacy licensing responses remain compatible because absent account-summary data does not alter existing plugin/theme subscription state.
- Account authentication now prefers the Directorist License Manager POST endpoint, maps its product response into the existing `license_data` shape, and falls back to the legacy licensing GET endpoint when the new route is unavailable or malformed.
- The Directorist.com License Manager accepts either username or email, returns optional `plan_data.account_summary`, and leaves existing response fields intact.
- Until the username-capable License Manager reaches Directorist.com, a username rejected by the older email-only POST route falls back to the legacy licensing endpoint instead of breaking existing connections.
- Optional account-summary generation is fail-open on Directorist.com; an EDD summary exception returns the established licensing payload with `account_summary: null`.
- Core accepts the new authentication response only when both theme and extension entitlement arrays have the expected shape, and credential-bearing POST requests do not follow redirects.
- `View listings` uses the configured Directorist All Listings page and is hidden when no directory type exists.
- `Add listing` uses the configured frontend Directorist Add Listing page, allowing the existing form to handle one or multiple directory types. With no directory type, it becomes `Create directory` and links to Directory Builder.
- The Dashboard footer uses the runtime `ATBDP_VERSION`, the API-backed account-summary plan name/status with legacy-safe fallbacks, and a filterable official changelog URL. It must not contain a hardcoded plugin version or plan name.
- Dashboard summary metrics now use current local Directorist data for published listings, listing views, pending listings, upcoming expirations, paid-order revenue, and paid-order count.
- Fake metric percentages and decorative trend lines were removed because there is no historical analytics contract for those comparisons.
- Account summary moved into the avatar dropdown so the product list starts closer to the page title/update banner.
- Avatar dropdown is click-only for opening. Hover and focus alone do not open it.
- Dropdown closes on outside click, Escape, and focus leaving the menu.
- Dropdown contains a labeled connected state, compact dynamic account summary tiles, touch-sized `Refresh purchases`, and a visually separated `Disconnect account` action.
- `Refresh purchases` keeps the existing `#purchase-refresh-form` and `atbdp_refresh_purchase_status` AJAX action. It requests a Directorist password for account-login connections and an access key for access-key connections.
- `Disconnect` keeps `.subscriptions-logout-btn` and `atbdp_close_subscriptions_sassion` compatibility.
- Returning-customer reconnect reuses the first validated authentication response to replace saved theme/extension entitlements and account summary. It no longer calls `refresh_purchase_status()` automatically and therefore does not issue a duplicate remote authentication request.
- The reconnect response still preserves `has_previous_subscriptions`, and the existing page reload remains in place. Manual Refresh Purchases behavior is unchanged.
- Refresh purchase password inputs are hidden from tab order until the user opens that panel. The revealed form uses a visible label, password visibility toggle, text submit action, live feedback region, and an explicit non-submit close button.
- Closing the inner refresh-purchase form keeps the account dropdown open, clears the password and feedback, restores the default Refresh Purchases row, and returns focus to that row.
- Closing the account dropdown itself still resets any open refresh form, so reopening the dropdown always starts from the default account-summary state.
- The page-specific refresh controls suppress the legacy width/display animation handlers only inside the connected account dropdown and clear stale animation styles. Legacy refresh layouts outside this dropdown keep their established behavior.
- Connected header search was removed. Catalog search remains in the Add-ons toolbar as the single supported search control.

## Directory-Aware Dashboard Quick Actions

- Quick Actions re-collect current `atbdp_listing_types` terms on each connected Dashboard request; runtime directory names, IDs, and counts are not stored as documentation truth.
- With multiple directories, one compact Directory selector controls Add Listing, Categories, Listing Layout, and Submission Form links without reloading the Dashboard.
- The selector starts from the valid default directory, restores a still-valid session selection, and falls back safely when a stored directory no longer exists.
- Quick Actions `Add a listing` opens the WordPress admin editor with `post_type=at_biz_dir&directory_type={term_id}`. The admin metabox accepts that sanitized, valid directory only when the new listing has no saved directory; saved listing metadata remains authoritative. The existing admin listing loader then uses that directory's `submission_form_fields`; Builder data is not copied into the Dashboard.
- `Manage categories` opens the Directorist category admin scoped with the selected `directory_type`.
- `Customize listing layout` opens the selected Directory Builder at `#single_page_layout__contents`.
- `Submission form settings` opens the selected Directory Builder at `#submission_form`.
- `Email notifications` remains global and opens Directorist Settings at the email notification channel.
- With one directory the selector is hidden and links are pre-bound. With no directories, the card shows Create directory and Email notifications only.
- Directory Builder navigation resolves valid layout/submenu hashes before its saved localStorage tab state. Missing or invalid hashes keep the established saved/default fallback behavior.
- The connected welcome CTA remains the configured frontend Add Listing journey; only the Quick Actions row uses the directory-scoped WordPress admin editor.

## Connected View And Product-Type State

- A connected clean page URL renders Dashboard by default. The WordPress Themes & Extensions sidebar link remains the clean entry point.
- Header and product-type state use optional, whitelisted URL parameters: `te_view=addons` and `te_type=extension|theme`. Missing or invalid values fall back to Dashboard and All.
- Dashboard is the implicit URL default, Add-ons uses `te_view=addons`, and All is the implicit product-type default. Default parameters are removed from the URL.
- Selecting Extensions or Themes remains remembered while switching Dashboard and Add-ons, and the current state survives reloads and full-page action fallbacks.
- State changes use `history.replaceState()` and do not reload the page or add tab-by-tab browser history entries.
- The legacy `#atbdp-required-extensions-form` route remains authoritative and forces Add-ons, Extensions, and Required.
- Disconnected rendering ignores these connected-view parameters and keeps the locked account-connect/marketplace experience.

## Product Catalog And Toolbar

- Disconnected users always receive the complete current extension and theme catalogs. Installed/subscription promo exclusions apply only to connected users and can no longer reduce the disconnected marketplace to a partial list.
- Search was moved into the toolbar near the catalog count.
- Primary tabs keep dynamic All, Extensions, and Themes counts.
- Status segmented filter keeps All, Installed, Not installed, Required, and Updates. Required is rendered only when the current theme has outstanding Directorist requirements.
- Installed and Not installed render compact row-derived count badges. Counts update for the selected All, Extensions, or Themes scope without a remote request.
- Existing theme links to `#atbdp-required-extensions-form` remain compatible. The hash opens Add-ons, selects Extensions and Required, clears catalog search, and focuses the Required filter.
- Required subscription, installed-inactive, and marketplace metadata is merged into one canonical product row. Purchased products keep Install/Activate actions, unowned products keep Get It Now, and duplicate subscription/required/promo rows are suppressed.
- The disconnected fallback preserves the locked design and directs the legacy hash to the existing account-connect area without exposing product-management actions.
- Updates count renders as a small warning pill.
- Update status labels render the target version when available, for example `vX.Y.Z available`; fallback remains `Update available`.
- Theme update metadata now carries `new_version` from the WordPress theme update transient into active theme row state.

## Connected Header Notifications

- The connected header bell uses existing server-rendered page state only. It does not add a polling endpoint, persistence layer, or separate update engine.
- Current notification sources are extension updates, theme updates, and outstanding extensions declared through the existing required-extension contract.
- With no actionable state, the dropdown renders an all-caught-up message and no count badge.
- Clicking a notification never installs or updates a product. It opens Add-ons, selects Extensions or Themes, selects Updates or Required, scrolls to the catalog toolbar, focuses the status filter, and briefly highlights the selected controls.
- The notification and account dropdowns are mutually exclusive, close on outside click or Escape, and restore focus to their trigger.
- Do not add license expiry, renewal, remote API failure, or deprecated-product notifications until those states have a reliable normalized data contract and a safe destination.

## Badges

- Badge rendering supports scalar badges such as `New` and structured badges such as `{ type, label, expires_at }`.
- Empty, malformed, duplicate, or expired badges are ignored.
- Badge class names are generated from badge type, for example `directorist-te-badge--new`, `--popular`, and `--trending`.
- Local badge fallback was added only for existing local product entries. API/filter-provided badge data remains the desired source of truth.
- Badge terms are searchable.
- Search highlights matching text in product titles and descriptions.
- Badge styling was reduced to keep row height compact.

## Local Product Fallback Additions

- Added local fallback entries for Directorist Notifications Pro and Directorist Divi Integration because the product API can return these products while the local fallback list did not previously include them.
- Added local images for those fallback products.
- These entries should remain display/catalog fallbacks only; live product metadata should still come from API/filter data when available.

## Active Theme Handling

- The active site theme row remains visible because it explains what theme controls the live WordPress site and provides access to Customize.
- If the active theme is a Directorist theme, type label is `Directorist theme`.
- If the active theme is not in the Directorist theme catalog, type label is `WordPress theme`.
- Active theme badge is `Active site theme`.
- Default WordPress themes are not presented as Directorist products.
- Active theme row keeps a single visible `Customize` action. Duplicate overflow `Customize` was removed.
- Theme activation remains high risk and should require explicit confirmation before calling `atbdp_activate_theme`.

## Row Actions

- Product detail actions are exposed directly instead of hiding essential discovery behind overflow.
- Demo actions are exposed directly where applicable.
- Active plugin rows use `Settings` as the primary action when no update is available.
- Active installed plugin overflow contains `Deactivate` only when applicable.
- Inactive installed plugin overflow uses `Delete plugin` as the destructive file-removal action.
- `Delete` remains protected by confirmation and should never be exposed as an easy primary action.
- Duplicate Settings/Customize-style actions should be avoided.

## Selection And Bulk Actions

- Rows with no safe bulk action render a disabled checkbox instead of silently omitting the selection cell.
- Master checkbox selects visible selectable rows only.
- Bulk bar no longer requires every selected item to share the same action.
- Bulk action visibility is now based on the union of eligible selected actions.
- Each visible bulk action shows a dynamic eligible-item count.
- Clicking a bulk action runs only the selected items that support that action; unsupported selected items are skipped.
- Button title/ARIA text explains how many selected items will run and how many will be skipped.
- Delete bulk action remains destructive and requires confirmation.
- Delete is limited to items marked eligible for uninstall/delete, not active plugins.
- Existing AJAX contracts are preserved:
  - `atbdp_install_file_from_subscriptions`
  - `atbdp_update_plugins`
  - `atbdp_update_theme`
  - `atbdp_plugins_bulk_action`

## Connected View Responsive Fixes

- Connected header is not sticky, preventing overlap with product rows.
- Connected nav can wrap/scroll on smaller screens without clipping the active label.
- Product titles and badges are allowed to wrap on narrow screens.
- Toolbar/search/count and bulk bar were checked for no horizontal overflow.

## Connected Sidebar Routing And Activity

- Connected users get a `Dashboard` Directorist submenu at the top. Its registered page slug remains `atbdp-extension` and the clean URL renders Dashboard.
- The existing `Themes & Extensions` submenu remains available and adds `te_view=addons`, so it opens Add-ons directly.
- Header Dashboard/Add-ons switching updates the clean/add-ons URL and the matching WordPress submenu current state without reloading.
- Disconnected users keep one `Themes & Extensions` submenu and the locked Add-ons/connect view; Dashboard is not exposed while disconnected.
- Recent Activity no longer contains reference-design names, amounts, dates, or listings. `ATBDP_Extension_Activity` builds normalized items from current listings, Directorist reviews, paid modern table-based orders, completed legacy order posts, Directorist user registrations, and upcoming listing expirations.
- Modern orders from `directorist_orders` are preferred. Legacy `atbdp_orders` posts remain supported, and migrated legacy IDs are excluded to prevent duplicate payment activity or revenue.
- The Dashboard card renders at most five current items. `View all` opens an accessible side drawer and lazily calls `directorist_te_get_activity`.
- Activity drawer filters are All, Listings, Reviews, Payments, and Users. Results load ten at a time with an explicit Load more action; no infinite scroll or background polling is used.
- The activity endpoint requires `manage_options` and the established `atbdp_nonce_action_js` nonce. It returns display-only action URLs and never installs, updates, activates, disconnects, or modifies product state.
- Final activity data is filterable through `directorist_themes_extensions_activity_data`. Runtime activity items and counts must not be stored in docs.
- Dashboard summary cards now use current published/pending listing counts, listing-view meta, upcoming expirations, and paid-order totals instead of reference-design numbers.
- The setup checklist now evaluates current directory types, categories, active gateways, and published listings. Its progress, copy, completion state, and links are generated on each request.
- The setup checklist is server-eligible for 30 days from the current first directory type's existing `_created_date`. Sites without a directory keep the checklist until one is created; a missing, invalid, future, or expired first-directory date hides it without creating fallback database state.
- Checklist dismissal is browser-local and scoped by WordPress blog ID plus administrator ID. It uses `localStorage`, sends no AJAX request, and never creates an option, user meta, table, migration, or licensing field.
- The directory setup link follows the registered mode: `atbdp-layout-builder` for single-directory sites and the `atbdp-directory-types` overview for multi-directory sites.
- Multi-directory mode intentionally does not deep-link to a default directory or the add-new screen. The overview lets administrators see all directory types before deciding which one to edit or create.
- The payment gateway shortcut uses the settings route `#monetization_settings__gateway`.
- Completed setup items remain readable, clickable maintenance shortcuts; do not style them as disabled or crossed-out text.
- Setup completion is informational only. Rendering the connected Dashboard must never create demo content, enable a gateway, publish a listing, or change settings automatically.

## Directory-Aware Dashboard Recommendations

- The connected Dashboard recommendation section is dynamic; the disconnected view remains locked and unchanged.
- `ATBDP_Extension_Recommendations` owns the centralized profile registry, directory classification, API override merge, product-state resolution, and final card data.
- The active recommendation group comes from real `atbdp_listing_types` terms and starts from the current default directory.
- Recommendations rotate every six seconds through real directory terms. When a directory returns, its next deterministic three-card window is shown; the implementation does not use unpredictable random ordering.
- Hover, keyboard focus, browser-tab inactivity, the explicit Pause control, and `prefers-reduced-motion` stop automatic rotation. Previous, next, and the compact native directory selector remain available without reload.
- The recommendation directory chooser mirrors the Quick Actions control: a visible `Directory` label and the same select height, typography, border, radius, focus treatment, and responsive sizing. Previous, next, and pause remain grouped as secondary carousel controls.
- Known directory profiles render their ordered recommendation candidates three at a time. Unknown/custom directory types use the generic candidate pool three at a time and never expose the complete extension catalog as a recommendation group.
- Directory mappings are recommendations, not hard dependencies. The `Required` product state remains reserved for extensions declared through the existing theme-required-extension contract.
- Recommendation cards keep installed products visible:
  - Active extensions show `Active` with no management CTA.
  - Installed inactive extensions show `Installed` with the existing Activate action.
  - Entitled uninstalled extensions show `Not installed` with the existing Install action.
  - Unowned catalog products show `Available` with an external View details action.
- Product actions reuse `.plugin-active-btn`, `.file-install-btn`, existing data keys, AJAX actions, and nonce handling.
- Profile data is filterable through `directorist_extension_recommendation_profiles`; final prepared data is filterable through `directorist_extension_recommendation_data`.
- Optional per-product API `recommendations` metadata can override local profile placement. Missing metadata preserves fallback placement, an empty array removes that product from all profiles, and malformed non-empty metadata is ignored.
- Product copy, thumbnail, link, installation state, activity state, and entitlement still come from the existing dynamic catalog and WordPress state. The template does not hardcode product cards.
- Post Your Need is no longer a local catalog product or recommendation candidate because it was removed from the remote product API.
- The predefined Post Your Need directory profile remains supported and recommends other available products. Legacy detection for already-installed Post Your Need extension copies remains untouched for customer compatibility.
- Automatic rotation, manual switching, card-window changes, and pause/resume are page-local interactions and make no remote request.

## Compatibility Rules Preserved

- Page slug remains `atbdp-extension`.
- Parent post type remains `at_biz_dir`.
- Existing AJAX action names and nonce patterns are preserved.
- Existing selectors/classes needed by legacy JS are preserved where actions still depend on them.
- Legacy user meta key `_atbdp_has_subscriptions_sassion` and AJAX action `atbdp_close_subscriptions_sassion` are preserved, including misspellings.
- Product filters and local fallback data remain part of the product merge path.

## Known Follow-Up Notes

- Many expensive actions still use full reload after server success. Future no-reload work should progressively enhance around existing AJAX responses and re-fetch canonical state.
- API badge source should move to Directorist.com/EDD product metadata so local fallback badges can eventually be removed.
- Build assets were not regenerated during these source edits unless explicitly stated in the task.
