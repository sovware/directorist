---
name: directorist-themes-extensions-page-agent
description: 'Use this repo-local skill when redesigning, auditing, planning, testing, or implementing features for the Directorist WordPress admin Themes & Extensions page at edit.php?post_type=at_biz_dir&page=atbdp-extension. Trigger for work on Directorist extension/theme discovery, account connection, subscription/license management, install/update/activate flows, no-reload performance improvements, or designs that touch this admin page. This skill is strict: re-collect dynamic runtime state every time, preserve existing customer compatibility, and never run destructive product actions without explicit confirmation.'
---

# Directorist Themes & Extensions Page Agent

This skill guides safe work on the Directorist admin `Themes & Extensions` page. The page is production-critical because it connects customer accounts, subscriptions, product downloads, plugin/theme activation, updates, and promotional product discovery.

## Core Rule

Re-collect current runtime data on every task. Do not treat previously observed product counts, subscription contents, installed item lists, update counts, user names, screenshots, or local site values as source of truth.

Store and use architecture, data contracts, selectors, endpoint names, and journey rules only.

## Required Context Pass

Before planning or editing, inspect these files:

- `includes/classes/class-extension.php`
- `views/admin-templates/theme-extensions/theme-extension.php`
- `views/admin-templates/theme-extensions/auth/license-auth-section.php`
- `views/admin-templates/theme-extensions/statistics/statistics.php`
- `views/admin-templates/theme-extensions/my-themes-extensions/my-themes-extensions.php`
- `views/admin-templates/theme-extensions/my-themes-extensions/extensions-tab.php`
- `views/admin-templates/theme-extensions/my-themes-extensions/themes-tab.php`
- `views/admin-templates/theme-extensions/all-themes-extensions.php`
- `assets/src/js/admin/components/subscriptionManagement.js`
- `assets/src/js/admin/admin.js`
- `assets/src/scss/layout/admin/admin-style.scss`
- `includes/asset-loader/helper.php`
- `includes/asset-loader/init.php`
- `includes/asset-loader/scripts.php`
- `includes/asset-loader/localized_data.php`

For product docs, copy, labels, or public claims, cross-check:

- `README.md`
- `readme.txt`
- Official Directorist docs: `https://directorist.com/documentation/directorist/`
- Official themes docs: `https://directorist.com/documentation/themes/`
- Official extensions docs: `https://directorist.com/documentation/extensions`

Load reference files as needed:

- Architecture and file map: `references/page-architecture-map.md`
- API/source flow report: `references/api-data-flow-report.md`
- Licensing system PRD: `references/licensing-system-prd.md`
- Runtime state and data shapes: `references/dynamic-data-contract.md`
- Account/license journeys: `references/account-license-journey-map.md`
- Performance and no-reload guidance: `references/performance-improvement-guide.md`
- Full rewrite issue register and fix priorities: `references/rewrite-issue-register.md`
- Verification checklist: `references/qa-checklist.md`

## Live Inspection Requirement

Use Agent Browser for the live admin page when a logged-in session is available:

```bash
agent-browser --session directorist-themes-extensions --profile Default --ignore-https-errors open "https://directorist-core.local/wp-admin/edit.php?post_type=at_biz_dir&page=atbdp-extension"
agent-browser --session directorist-themes-extensions snapshot -c -d 4
agent-browser --session directorist-themes-extensions eval 'JSON.stringify({url:location.href,title:document.title,connected:!!document.querySelector("#purchase-refresh-form"),auth:!!document.querySelector("#atbdp-directorist-license-login-form"),overflow:document.documentElement.scrollWidth>innerWidth})'
```

Treat this as an inspection step only. Do not save the collected runtime values into this skill or its references.

Close the browser session after inspection:

```bash
agent-browser --session directorist-themes-extensions close
```

## Safety Rules

- Never click or automate Install, Update, Activate, Deactivate, Uninstall, Logout, Refresh Purchase, or theme switch actions on a real/client site without explicit confirmation for that exact action.
- Preserve the page slug `atbdp-extension`, parent post type `at_biz_dir`, capability `manage_options`, existing AJAX action names, nonce expectations, response compatibility, settings links, filters, aliases, and legacy user meta keys.
- Preserve legacy misspellings that are part of stored data or public contracts, including `_atbdp_has_subscriptions_sassion` and `atbdp_close_subscriptions_sassion`.
- Do not remove or repurpose old selectors, classes, template paths, filters, or action hooks without a compatibility shim and explicit migration plan.
- After a new UI replaces an old page section, audit old design-specific CSS and remove unused rules after verifying they are not used by the rewritten page, legacy compatibility shims, template overrides, or other Directorist admin screens.
- Treat install, update, download, uninstall, and theme activation as high-risk operations. Use read-only inspection by default.
- Keep this page separate from installed extension settings. Settings-panel work belongs to `docs/agents/directorist-settings-panel/SKILL.md`.
- Treat Figma/design input as design intent. Do not rewrite backend product/install logic only to match a visual design.

## Performance Rule

This page is PHP-rendered admin markup plus legacy jQuery. Improve performance through progressive enhancement first:

1. Keep the current server-rendered page as canonical fallback.
2. Add small, tested no-reload flows around existing AJAX responses.
3. Re-fetch or re-render canonical state after expensive server actions.
4. Keep full page reload fallback when state cannot be safely reconciled.

Preferred rewrite architecture: keep PHP-rendered templates as the compatibility baseline, introduce focused PHP service/resolver classes behind existing AJAX actions, and add a small page-specific JavaScript state adapter for no-reload UI updates.

Do not replace the page with Vue/React or a new persistence/API layer unless the user explicitly approves that architecture change after seeing the compatibility risks. Vue 2 is legacy in the settings panel, and React should only be considered for this page after an explicit full admin UI migration decision.

## Required First Output

Before implementation, produce a compact report:

```markdown
# Themes & Extensions Feasibility Report

## Summary
- Request:
- Recommended approach:
- Risk level:

## Dynamic State Rechecked
- Live page:
- Connected/account state:
- Product state source:
- Docs checked:

## Easy To Achieve
- ...

## Complex Or Risky
- ...

## Affected Areas
- PHP/templates:
- AJAX/server actions:
- JS/state updates:
- SCSS/responsive:
- External Directorist/EDD APIs:

## Compatibility Rules
- ...

## Verification Plan
- Static checks:
- Agent Browser checks:
- No-reload/performance checks:
- Destructive-action policy:
```

If the task is a small docs-only update, the report can be shorter but must still say whether dynamic state was intentionally not stored.

## Maintenance

Update the reference files when a future task confirms a durable architecture fact, compatibility rule, or recurring issue. Do not update them with local runtime counts, purchased product lists, user-specific state, or one-time screenshots.
