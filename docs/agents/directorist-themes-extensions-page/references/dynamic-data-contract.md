# Dynamic Data Contract

This page is driven by live WordPress state, remote Directorist/EDD state, user meta, filters, and current filesystem/plugin/theme state. Do not store runtime output in this docs agent.

## What Not To Store

Never commit these values into the skill:

- Product counts
- Installed extension lists
- Active/inactive extension lists
- Subscribed extension or theme lists
- Update counts
- Current theme name
- Connected username/account details
- Local screenshots or network logs
- Site-specific AJAX responses

Use those values only as temporary evidence for the current task.

## Product Item Shapes

Extension catalog items commonly include:

- `name`
- `description`
- `link`
- `thumbnail`
- `active`
- `item_id`
- `base` when the plugin base differs from the catalog slug
- optional `badge` object when remote/API support exists

Theme catalog items commonly include:

- `name`
- `description`
- `link`
- `demo_link`
- `thumbnail`
- `active`
- optional `badge` object when remote/API support exists

## Product Copy Source Policy

- Product API data is the preferred source for product name, description, thumbnail, product link, demo link, active promo flag, item ID, and plugin base when valid API values exist.
- Local default product arrays remain the fallback source for product name, description, thumbnail, product link, demo link, item ID, and plugin base.
- Merge API data over local defaults by product key when possible so missing remote fields do not create blank cards.
- If the API is unavailable, empty, malformed, or missing a non-badge field, render the local fallback field.
- Badge/status fields do not use hardcoded local fallback. Render badge/status only when provided by product API data or explicit filters.
- Do not store the current merged catalog output in this skill.

Optional product badge/status shape:

- Preferred API field: `badges`, an array of badge objects.
- Legacy/filter-compatible field: `badge`, a single badge object or scalar label.
- `type`: machine-readable badge type such as `new`, `beta`, `popular`, `sale`, `trending`, or `featured`
- `label`: display label such as `New`
- `expires_at`: optional expiration date/time; expired badges must not render

Badge/status data should come from Directorist.com product API data. EDD product meta, a dedicated product badge setting, or taxonomy can be the upstream source on Directorist.com, but the core plugin should consume the API field. Local product-list filters may add or override badge data for compatibility/testing. Do not infer badges from product order, names, slugs, or local runtime state.

Purchased/subscribed items returned from the account journey may include title, slug/key, item id, license, URL, download/package data, or product type. Re-check the current handler before relying on a field.

## Runtime State Categories

The UI must handle these dynamic states:

- Not connected to Directorist account
- Not connected while Directorist premium products may already be installed locally; the page should still keep the current disconnected UI unless explicitly changed
- Not connected messaging should clarify that already installed extensions keep working, while account connection is required to manage subscriptions, installs, and updates
- Connected account with no purchased products
- Connected account with subscribed plugins
- Connected account with subscribed themes
- Product installed and inactive
- Product installed and active
- Product installed and outdated
- Product installed with expired or missing license/update entitlement
- Product subscribed but not installed
- Product required by another feature
- Product required but not purchased
- Promo-only product
- Promo/subscribed/installed product with optional API-provided badge
- Active theme
- Installed inactive theme
- Subscribed theme not installed
- Theme update available
- Remote API failure
- Filesystem/download failure
- Capability or nonce failure
- Beta package mode

These are categories, not counts. Always collect current values during the task.

## Current State Collection

Use read-only browser checks:

```bash
agent-browser --session directorist-themes-extensions --profile Default --ignore-https-errors open "https://directorist-core.local/wp-admin/edit.php?post_type=at_biz_dir&page=atbdp-extension"
agent-browser --session directorist-themes-extensions snapshot -c -d 4
agent-browser --session directorist-themes-extensions eval 'JSON.stringify({connected:!!document.querySelector("#purchase-refresh-form"),auth:!!document.querySelector("#atbdp-directorist-license-login-form"),hasRequired:!!document.querySelector("#atbdp-required-extensions-form"),overflow:document.documentElement.scrollWidth>innerWidth})'
```

Use source checks:

```bash
rg -n "setup_ajax_actions|show_extension_view|setup_products_list|get_extensions_overview|get_themes_overview" includes/classes/class-extension.php
rg -n "location.reload|atbdp_|file-install-btn|plugin-active-btn|theme-activate-btn" assets/src/js/admin/components/subscriptionManagement.js
rg -n "theme-extensions|atbdp-extension|directorist_required_extensions|directorist_extensions_aliases" includes views assets/src/js assets/src/scss
```

Close the browser session when finished:

```bash
agent-browser --session directorist-themes-extensions close
```

## Compatibility Contracts

Keep these stable unless a migration plan is explicitly approved:

- Page slug: `atbdp-extension`
- Parent post type: `at_biz_dir`
- Capability: `manage_options`
- AJAX actions listed in `page-architecture-map.md`
- User meta keys for connected account and subscriptions
- Filters for product lists, required extensions, and aliases
- Settings links back to the settings panel
- Existing template override expectations for admin templates
- Existing selector/class hooks used by custom CSS or scripts

New no-reload behavior must preserve the same server-side action contracts or provide a compatibility wrapper.
