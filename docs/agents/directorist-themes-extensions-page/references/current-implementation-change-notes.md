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
- The page focuses the username field for disconnected users.
- Messaging clarifies that already installed Directorist products keep working, but account connection is required for subscription installs, updates, and license-backed management.
- Disconnected product rows use marketplace/details-oriented actions instead of install/update/activate/deactivate/delete controls.

## Connected Header And Account Menu

- The large connected account stats/action card was removed from the body.
- Connected header navigation now uses `Dashboard` and `Add-ons` as internal page views.
- The existing Themes & Extensions catalog is labeled `Add-ons` in the header nav, but keeps the original page slug, selectors, forms, rows, and AJAX contracts.
- `Docs`, `Tutorials`, and `Support` remain external resource links, not dashboard tabs, and are grouped on the right side of the connected header like the disconnected view.
- The `Dashboard` view is currently a static design-preview surface based on the supplied reference HTML. It must be replaced with dynamic Directorist data before production release decisions are made.
- The static Dashboard preview inner cards were aligned to the supplied reference HTML pattern: metric sparkline decoration, soft recent-activity icons, the five-item activity list, icon/title recommendation cards, and soft recommendation install buttons.
- Account summary moved into the avatar dropdown so the product list starts closer to the page title/update banner.
- Avatar dropdown is click-only for opening. Hover and focus alone do not open it.
- Dropdown closes on outside click, Escape, and focus leaving the menu.
- Dropdown contains dynamic account summary values, `Refresh purchases`, and `Disconnect`.
- `Refresh purchases` keeps the existing `#purchase-refresh-form` and `atbdp_refresh_purchase_status` AJAX action.
- `Disconnect` keeps `.subscriptions-logout-btn` and `atbdp_close_subscriptions_sassion` compatibility.
- Refresh purchase password inputs are hidden from tab order until the user opens that panel.
- Connected header search is intentionally still static for now.

## Product Catalog And Toolbar

- Search was moved into the toolbar near the catalog count.
- Primary tabs keep dynamic All, Extensions, and Themes counts.
- Status segmented filter keeps All, Installed, Not installed, and Updates.
- Updates count renders as a small warning pill.
- Update status labels render the target version when available, for example `vX.Y.Z available`; fallback remains `Update available`.
- Theme update metadata now carries `new_version` from the WordPress theme update transient into active theme row state.

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

## Compatibility Rules Preserved

- Page slug remains `atbdp-extension`.
- Parent post type remains `at_biz_dir`.
- Existing AJAX action names and nonce patterns are preserved.
- Existing selectors/classes needed by legacy JS are preserved where actions still depend on them.
- Legacy user meta key `_atbdp_has_subscriptions_sassion` and AJAX action `atbdp_close_subscriptions_sassion` are preserved, including misspellings.
- Product filters and local fallback data remain part of the product merge path.

## Known Follow-Up Notes

- Many expensive actions still use full reload after server success. Future no-reload work should progressively enhance around existing AJAX responses and re-fetch canonical state.
- Connected header search remains static by decision and should be handled in a separate task.
- API badge source should move to Directorist.com/EDD product metadata so local fallback badges can eventually be removed.
- Build assets were not regenerated during these source edits unless explicitly stated in the task.
