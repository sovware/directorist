# QA Checklist

Use this checklist for Themes & Extensions page work. Current runtime data must be collected fresh and not stored in this docs agent.

## Static Checks

- Confirm `ATBDP_Extensions::setup_ajax_actions()` still registers expected `wp_ajax_atbdp_*` actions.
- Confirm `show_extension_view()` still passes required data to the root template.
- Confirm `subscriptionManagement.js` action names match server handlers.
- Confirm new code preserves existing nonce usage or provides compatibility.
- Confirm new copy is escaped and translatable in PHP.
- Confirm changed selectors/classes do not remove legacy hooks without shims.
- Confirm page styles are scoped to the Themes & Extensions page root/classes and do not introduce broad global admin or shared Directorist selector changes.
- Confirm docs/copy claims against `README.md`, `readme.txt`, and official Directorist docs.
- Confirm product names, descriptions, thumbnails, and links use API values when present and local defaults when API fields are missing or invalid.
- Confirm product badges/status render only from product API/filter-provided fields and do not rely on hardcoded product slugs or product order.
- Confirm no old/new UI feature flag or rollout toggle was added unless explicitly requested.
- Confirm disconnected-view files/selectors/styles were not changed unless the current task explicitly requested disconnected-view changes.
- Confirm connected Dashboard recommendations show at most three cards, rotate through real directory types, and never expose the full catalog under an unknown/general type.
- Confirm recommendation autoplay pauses on hover, keyboard focus, hidden browser tabs, and reduced-motion preference without rendering a Pause control.
- Confirm previous, next, and native directory selection work without triggering product AJAX actions.
- Confirm Quick Actions re-collect current directory terms and do not rely on stored documentation/runtime snapshots.
- Confirm zero-directory state shows Create directory and Email notifications without dead directory-specific links.
- Confirm the setup checklist remains visible before the first directory exists, uses the current first directory type's `_created_date` for its 30-day window, and is absent for missing, invalid, future, or expired dates.
- Confirm checklist dismissal persists only in `localStorage` under a blog-and-user-scoped key, sends no network request, and creates no option, user meta, table, migration, or licensing data.
- Confirm one-directory state hides the selector and binds Add Listing, Categories, Listing Layout, and Submission Form to that directory.
- Confirm multiple-directory selection updates all four directory-specific links, descriptions, and accessible labels without changing the global Email Notifications link.
- Confirm the selected Quick Actions directory is selected in the new-listing admin metabox and loads that directory's `submission_form_fields`.
- Confirm an existing listing's saved directory overrides any `directory_type` query parameter.
- Confirm a valid remembered Quick Actions directory is restored for the browser session and a deleted/stale directory falls back to the current default.
- Confirm `#submission_form` and `#single_page_layout__contents` override saved Builder tab state and open the requested layout/submenu.
- Confirm a connected clean sidebar URL opens Dashboard with All as the default product type.
- Confirm Add-ons with Extensions or Themes updates `te_view`/`te_type` without a reload and restores the same state after reload.
- Confirm switching to Dashboard retains `te_type`, and returning to Add-ons restores the same All/Extensions/Themes selection.
- Confirm invalid `te_view` and `te_type` values fall back to Dashboard and All and are removed from the canonical URL.
- Confirm `#atbdp-required-extensions-form` still forces Add-ons, Extensions, and Required regardless of initial URL state.
- Confirm disconnected rendering ignores connected `te_view`/`te_type` parameters.
- Confirm disconnected-state username is first in tab order but is not autofocused on normal page load.
- Confirm disconnected-state resource links are plain Docs, Tutorials, and Support links with external-link safety attributes.
- Confirm disconnected-state account copy changes based on local official Directorist product detection: normal subscription copy when none are installed, installed-product copy when local Directorist extensions/themes are present.
- Confirm legacy theme links to `#atbdp-required-extensions-form` select Add-ons, Extensions, and Required without submitting or triggering a product action.
- Confirm required products render once and keep the correct Install, Activate, or Get It Now action for current ownership/install state.
- Confirm the connected header has no search control and the Add-ons toolbar remains the only catalog search.
- Confirm the notification count and items derive from current extension updates, theme updates, and required-extension state.
- Confirm clicking an extension/theme update notification opens Add-ons, selects the matching product type and Updates, focuses the Updates filter, and sends no update request.
- Confirm clicking a required-extension notification opens Add-ons, selects Extensions and Required, focuses the Required filter, and sends no install request.
- Confirm the notification empty state, Escape close, outside-click close, account-menu mutual exclusion, keyboard focus restoration, and 390px dropdown containment.
- Confirm connected WordPress sidebar Dashboard is the first Directorist submenu and the clean route opens Dashboard.
- Confirm connected Themes & Extensions sidebar opens `te_view=addons` and selects Add-ons in both the header and WordPress sidebar.
- Confirm header Dashboard/Add-ons switches update the matching WordPress submenu current state without reload.
- Confirm disconnected users still receive one Themes & Extensions submenu and no connected Dashboard.
- Confirm the Recent Activity card renders no more than five current dynamic items and contains no reference-design names, dates, amounts, or listing titles.
- Confirm opening View all makes one lazy `directorist_te_get_activity` request, returns focus on close, traps Tab while open, closes on Escape/backdrop, and has no mobile overflow.
- Confirm activity filters reset pagination, Load more appends without duplicates, and empty/error states remain usable.
- Confirm the activity endpoint rejects missing/invalid nonce and non-admin capability requests.

## Agent Browser Checks

Open the local admin page:

```bash
agent-browser --session directorist-themes-extensions --profile Default --ignore-https-errors open "https://directorist-core.local/wp-admin/edit.php?post_type=at_biz_dir&page=atbdp-extension"
```

Inspect without destructive actions:

```bash
agent-browser --session directorist-themes-extensions snapshot -c -d 4
agent-browser --session directorist-themes-extensions console --clear
agent-browser --session directorist-themes-extensions errors --clear
```

Mobile overflow check:

```bash
agent-browser --session directorist-themes-extensions set viewport 390 844
agent-browser --session directorist-themes-extensions eval 'JSON.stringify({scrollWidth:document.documentElement.scrollWidth,innerWidth:innerWidth,overflow:document.documentElement.scrollWidth>innerWidth})'
```

Close the session:

```bash
agent-browser --session directorist-themes-extensions close
```

## Dynamic State Matrix

Verify behavior against current site state for:

- Not connected account
- Not connected account while premium products may already be installed locally; verify the page still shows the auth/connect state and does not expose installed-product management actions
- Connected account
- Subscribed product not installed
- Installed product inactive
- Installed product active
- Installed product outdated
- Required extension
- Promo-only product
- Product with API/filter-provided badge
- Product without badge data
- Product with expired badge data
- Product with missing API name, description, thumbnail, or link to verify local fallback fields
- Active theme
- Installed inactive theme
- Theme update available
- Remote API failure
- Nonce failure
- Capability failure
- Filesystem failure

Use categories only in docs. Do not preserve observed counts or product lists.

## Performance Checks

- Count full page reloads before and after the change.
- Confirm loading states appear immediately after click/submit.
- Confirm server errors are shown without leaving disabled buttons stuck.
- Confirm no-reload UI updates are reconciled with canonical server state.
- Confirm full reload fallback still works.
- Compare desktop and mobile interaction latency.
- Check console and page errors after each tested journey.

Product-action queue QA:

- Confirm every request includes exactly one `plugin_key` or `theme_stylesheet`; no unscoped update-all request is sent.
- Confirm install requests include exactly one `item_key` and activate/deactivate/delete requests include exactly one `plugin_items` entry.
- Simulate more than twenty install/update candidates and confirm request concurrency never exceeds one.
- Confirm visible progress advances from the first candidate through the total without disabling unrelated navigation.
- Confirm row states advance through Waiting, action progress, success/failure, and Skipped where applicable.
- Confirm later legacy direct-control handlers cannot cause duplicate Install, Update, or Activate requests.
- Confirm an explicit product-level failure is recorded and later products are still attempted.
- Confirm a transport/server interruption stops new requests, marks untouched candidates skipped, and does not retry the uncertain product automatically.
- Confirm malformed and duplicate candidates do not create requests.
- Confirm completion reloads canonical state and shows an action/failed/skipped summary without storing licenses, credentials, download URLs, or runtime catalog snapshots.
- Confirm the result summary is readable without overflow on desktop and mobile.
- Confirm plugin activation errors and `delete_plugins()` errors are returned as failures rather than false success.
- Confirm invalid archives, unzip failure, stage-copy failure, and final-move failure leave the previous installed product directory recoverable.

## Disconnected Accessibility Checks

- Confirm the disconnected page remains browse-first: account connect form plus marketplace catalog are both reachable without forced focus.
- Confirm Account login remains the selected default and username/password fields have visible labels.
- Confirm Access key is an explicit secondary tab, and only the selected method's controls are enabled or reachable by keyboard.
- Confirm Left/Right/Home/End keys change the selected authentication tab and move focus with it.
- Confirm pressing Enter inside username/password or access key submits the account-connect form exactly once.
- Confirm password and access-key visibility toggles change the input type, icon, `aria-label`, and `aria-pressed`.
- Confirm connect loading state disables controls only during the active request and restores them after failure.
- Confirm empty username, empty password, empty/invalid access key, wrong account credentials, API unavailable, nonce failure, capability failure, and unexpected errors render inline form feedback.
- Confirm a submitted access key never appears in the URL, local/session storage, AJAX response, user meta, options, logs, or documentation.
- Confirm an existing account-login connection with no `_atbdp_subscription_connection_method` value still refreshes with a password.
- Confirm an access-key connection stores only `_atbdp_subscription_connection_method=access_key` and asks for the key again during Refresh Purchases.
- Confirm disconnected search no-result state shows an inline empty state and a clear/reset affordance.
- Confirm mobile disconnected view uses a one-column connect form, does not open the keyboard on load, and has no horizontal page overflow.

## Destructive-Action Safeguards

Do not execute these on a real/client site without explicit confirmation:

- Install plugin/theme
- Update plugin/theme
- Activate plugin
- Deactivate plugin
- Uninstall plugin
- Activate/switch theme
- Logout connected account
- Bulk action
- Refresh purchase if it requires real credentials

When these need QA, use a disposable local site, mocks, or a server-side test harness.

Uninstall-specific QA for rewrite:

- Confirm uninstall is in a danger/overflow area, not a primary action.
- Confirm confirmation modal names the extension and warns that files will be deleted and site features may break.
- Confirm cancellation makes no AJAX request.
- Confirm success is shown only after server success and canonical plugin-state recheck or reload fallback.
- Confirm failure restores the UI and shows inline feedback.

Theme-activation-specific QA for rewrite:

- Confirm clicking a theme Activate button opens a confirmation modal instead of sending AJAX immediately.
- Confirm the modal names the theme and warns that the live site's active theme will change.
- Confirm cancellation makes no AJAX request.
- Confirm confirmation sends the existing `atbdp_activate_theme` action only after explicit user approval.
- Confirm active-theme UI updates only after server success and canonical active-theme recheck or reload fallback.
- Confirm failure restores the UI and shows inline feedback.
