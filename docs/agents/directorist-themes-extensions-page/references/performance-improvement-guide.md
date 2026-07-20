# Performance Improvement Guide

This page currently uses PHP-rendered admin templates plus legacy jQuery in `subscriptionManagement.js`. It is not a Vue settings-manager page.

## Current Tech

- Server-rendered PHP templates produce the initial UI.
- jQuery handles form submits, button clicks, bulk selection, install/update/activate calls, tabs, and feedback.
- AJAX requests go through `admin-ajax.php` using `wp_ajax_atbdp_*` handlers.
- The page often reloads after successful AJAX actions to force canonical state.
- CSS is bundled through the legacy admin stylesheet.

## Reload-Heavy Areas

Review `assets/src/js/admin/components/subscriptionManagement.js` for `location.reload()` before planning performance work.

Known reload patterns:

- Account connect success
- Product install success
- Plugin activation success
- Plugin update success
- Theme activation success
- Theme update success
- Refresh purchase success or session reset
- Logout success
- Bulk action success
- Old reload link handler

Do not remove reloads blindly. They currently protect correctness by forcing a full server re-render.

## Recommended Direction

Use progressive enhancement:

1. Preserve the existing PHP-rendered page as the canonical fallback.
2. Add a small client-side state adapter for this page only.
3. Normalize current AJAX response handling into success, error, loading, and stale-state branches.
4. After successful low-risk actions, update affected buttons/cards/counters in place.
5. After high-risk filesystem or theme/plugin actions, re-fetch canonical state or keep reload fallback.
6. Record and compare reload count, network calls, interaction latency, and layout stability.

Avoid a full Vue/React rewrite until the user explicitly approves a larger architecture change.

## Framework Decision

Default rewrite direction: use PHP-rendered templates plus a small page-specific JavaScript adapter, not Vue or React.

The page rewrite is approved as a full UI rewrite, not a staged old/new UI rollout. Do not add a feature flag or rollout toggle by default unless a future task explicitly asks for it. Keep server-rendered compatibility behavior and reload fallbacks for safety; those are not the same as keeping two parallel UIs.

Reasons:

- This page is currently PHP templates plus legacy jQuery, not the Vue settings-manager app.
- Keeping server-rendered markup preserves fallback behavior for existing customer sites.
- A small adapter requires less code than a SPA and can be rolled back safely.
- Existing `wp_ajax_atbdp_*` actions, selectors, filters, user meta keys, and template paths can remain stable.
- Vue 2 is already legacy in the settings panel, so adding new Vue 2 surface area is not future-friendly.
- React is available in WordPress admin, but using it here would require a larger state/API migration and broader compatibility QA.

Preferred implementation shape:

1. Add PHP service/resolver classes behind current handlers for product catalog, account/session state, installed-product state, update state, install/update/download operations, and response formatting.
2. Keep the current PHP templates as the canonical render path and fallback.
3. Add a small JS adapter around existing AJAX calls for loading states, inline notices, card/row replacement, counters, and state-summary refresh.
4. Return structured responses from existing actions while preserving compatibility for older response readers.
5. Use full reload fallback when canonical state cannot be safely reconciled.

Only consider React or a new SPA-style architecture if a future task explicitly approves a full admin UI migration strategy for this page.

## Safer No-Reload Candidates

- Account connect feedback before final state refresh
- Refresh Purchase status messages
- Button loading/error states
- Tab navigation and card visibility
- Non-destructive validation errors
- Promo/product detail modal interactions
- Counter/card updates after a server-confirmed state refresh

## Conservative No-Reload Candidates

- Plugin install
- Plugin update
- Plugin activation
- Theme update
- Theme activation
- Bulk activate/deactivate

For these, show progress without reload, but reconcile with canonical server state before claiming completion. Keep reload fallback.

## High-Risk Or Destructive

- Plugin uninstall
- Theme switch on a live/client site
- Filesystem replacement during update/install
- Remote package download and unzip
- Any flow that deletes an existing plugin/theme folder

Do not automate these on client sites without explicit confirmation.

Theme activation rewrite policy:

- Require an explicit confirmation modal every time before calling the theme activation AJAX action.
- Confirmation must name the theme and warn that the live site's active theme will change and layout, menus, widgets, headers/footers, and theme settings may be affected.
- Do not optimistically mark a theme active before server success and canonical active-theme recheck.
- Keep reload fallback for theme activation because `switch_theme()` changes global site state.

Uninstall rewrite policy:

- Keep uninstall for backward compatibility, but never as a primary one-click action.
- Put uninstall behind a danger/overflow menu and an explicit confirmation modal.
- Confirmation must name the extension and warn that files will be deleted and dependent site features may break.
- Treat uninstall as reload-fallback-first: after server acceptance, re-check canonical plugin state before updating UI.

## Backend Improvement Targets

Before a deeper rewrite, plan a service layer behind existing AJAX actions:

- Product catalog resolver
- Account/session resolver
- Purchased product mapper
- Install/update/download service
- Action response formatter
- State summary endpoint for client-side refresh

Maintain the current AJAX action names and response compatibility while introducing safer internals.

## Page Speed Acceptance

Future performance work should measure:

- Full page reload count per journey
- Number of AJAX calls per action
- Time from click/submit to visible feedback
- Time from server success to stable UI
- Console errors and page errors
- Mobile horizontal overflow
- Layout shift during loading and state changes

Do not report performance success only from code review. Verify in Agent Browser.
