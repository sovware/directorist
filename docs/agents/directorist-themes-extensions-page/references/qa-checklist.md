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
- Confirm disconnected-state username is first in tab order but is not autofocused on normal page load.
- Confirm disconnected-state resource links are plain Docs, Tutorials, and Support links with external-link safety attributes.
- Confirm disconnected-state account copy changes based on local official Directorist product detection: normal subscription copy when none are installed, installed-product copy when local Directorist extensions/themes are present.

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

## Disconnected Accessibility Checks

- Confirm the disconnected page remains browse-first: account connect form plus marketplace catalog are both reachable without forced focus.
- Confirm username and password fields have visible labels.
- Confirm pressing Enter inside username/password submits the account-connect form.
- Confirm password visibility toggle changes the input type, icon, `aria-label`, and `aria-pressed`.
- Confirm connect loading state disables controls only during the active request and restores them after failure.
- Confirm empty username, empty password, wrong credentials, API unavailable, nonce failure, and unexpected errors render inline form feedback.
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
