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

## Account Summary Availability

The connected dashboard can safely resolve these values without a new remote API contract:

- Current WordPress user's first name or display name
- Directorist account connected state
- Whether subscribed extension or theme entitlements are present
- Installed, active, and outdated plugin/theme state
- Local directory type count, names, and slugs
- Configured All Listings and Add Listing page URLs

The legacy licensing flow does not expose these account-level fields through a normalized, reliable contract:

- Global Directorist plan name
- Account-level subscription status such as active, expired, or cancelled
- Global plan expiration or renewal date
- Authoritative all-access/every-product-unlocked boolean
- Remote account display name intended for UI greetings

The newer Directorist License Manager API provides an optional normalized `plan_data.account_summary` from EDD All Access pass data. Core stores the sanitized summary in `_atbdp_account_summary` during account connection/refresh and removes it on disconnect. When EDD All Access data is unavailable, no matching pass exists, the field is malformed, or the legacy API is used, fields remain null/unknown and core renders generic connected-account copy.

The current connection method is stored separately in `_atbdp_subscription_connection_method` as `account` or `access_key`. This is non-secret UI/refresh state. Never store the submitted access key as runtime truth or persistent credential; request it again when an access-key-connected customer refreshes purchases.

Do not infer missing account fields from product counts, individual product licenses, installed products, or the connected username/email.

The backward-compatible account summary uses nullable fields where the server cannot determine a value:

```json
{
  "plan_data": {
    "account_summary": {
      "display_name": null,
      "avatar_url": null,
      "plan_name": null,
      "subscription_status": "unknown",
      "expires_at": null,
      "all_access": false,
      "is_lifetime": false
    }
  }
}
```

`subscription_status` accepts `active`, `expired`, `cancelled`, or `unknown`. The API date is ISO 8601; core formats it using the customer site's WordPress date settings. `avatar_url` is optional, sanitized, and generated by Directorist.com for the authenticated licensing owner. Core uses licensing-owner initials when it is missing.

Still unavailable when the customer has no usable EDD All Access pass:

- Authoritative non-All-Access membership plan name
- Renewal date distinct from access expiration
- Cancellation-at-period-end state
- Billing interval, renewal price, and payment method

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

## Directory Recommendation Contract

Connected Dashboard recommendations are derived at request time from:

- Current `atbdp_listing_types` terms: term ID, name, slug, and default-directory state
- The current extension catalog after API/local fallback/filter merging
- Current installed plugin bases and `is_plugin_active()` state
- Current uninstalled subscription entitlements
- Optional product API recommendation metadata

Do not store observed directory names, term counts, selected terms, recommendation cards, or installation results in this reference as truth.

The local profile registry accepts ordered product slugs and remains the compatibility fallback. It is filterable through `directorist_extension_recommendation_profiles`.

Optional product API metadata uses this shape:

```json
{
  "recommendations": [
    {
      "profile": "restaurant",
      "priority": 100,
      "reason": "Accept reservations from listing pages."
    }
  ]
}
```

Compatibility semantics:

- Field absent: retain the local placement for that product
- Empty array: remove the product from every recommendation profile
- Valid array: replace local placement with validated API placements
- Malformed non-empty data: ignore the override and retain local placement
- Unknown profile keys: ignore that placement
- Priority: integer clamped to `0..100`
- Reason: optional sanitized plain text; product description remains fallback

Recommendation state labels/actions are resolved locally and are never trusted from profile metadata:

- Active: show `Active`, no recommendation CTA
- Installed inactive: show `Installed`, reuse Activate
- Entitled and uninstalled: show `Not installed`, reuse Install
- Not entitled: show `Available`, link to product details
- Missing catalog product: omit and continue to the next configured candidate

Presentation rules:

- Show no more than three recommendation cards at once.
- Multiple real directory types rotate in stable term order; do not randomize directory order.
- A directory with more than three candidates advances through deterministic three-card windows when it returns.
- Unknown/custom directory types use the generic candidate pool, not the complete catalog.
- Automatic rotation pauses on hover, focus, tab inactivity, explicit user pause, and reduced-motion preference.
- Directory-profile mappings are `Recommended`; only the established theme requirement contract may label a product `Required`.

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

## Connected Dashboard Activity Contract

The Recent Activity card and drawer derive display data from existing local sources:

- Listing activity: `at_biz_dir` posts with published, pending, or draft status
- Review activity: WordPress comments with `comment_type=review`
- Payment activity: modern `directorist_orders` rows with `status=paid` and a positive amount, plus unmigrated `atbdp_orders` posts with `_payment_status=completed` and a positive `_amount`
- User activity: WordPress users with Directorist `_user_type` metadata
- Upcoming expirations: published listings with a finite `_expiry_date`

The UI must not infer remote licensing events, subscription renewals, failed payments, or product update events from these local sources.

The AJAX response is normalized:

```json
{
  "success": true,
  "data": {
    "items": [
      {
        "id": "listing-123",
        "type": "listing",
        "title": "Listing published",
        "subject": "Example listing",
        "context": "by Example owner",
        "timestamp": 1780000000,
        "icon": "la la-plus",
        "tone": "blue",
        "action_label": "Edit",
        "action_url": "https://example.test/wp-admin/post.php?post=123&action=edit",
        "upcoming": false,
        "group": "today",
        "group_label": "Today",
        "time_label": "5 minutes ago"
      }
    ],
    "has_more": false,
    "next_page": null,
    "page": 1,
    "type": "all"
  }
}
```

The example describes shape only. Never store an observed activity item, site URL, count, title, user, amount, or timestamp as documentation truth.

Connected Dashboard metric values are also collected at request time:

- Published and pending counts: `at_biz_dir` post statuses
- Listing views: `_atbdp_post_views_count` summed for published listings
- Upcoming expiration count: finite `_expiry_date` values within the configured display window
- Revenue and paid-order count: paid modern orders plus completed unmigrated legacy order posts

Modern orders with a `legacy_id` suppress the corresponding legacy post from both activity and metric totals. No trend percentage or sparkline should be presented as real data until a time-series analytics contract exists.

Connected Dashboard setup progress is also request-scoped:

- Directory step: at least one current `atbdp_listing_types` term
- Category step: at least one current `at_biz_dir-category` term
- Gateway step: at least one value returned by `ATBDP_Gateway::get_active_gateways()`
- Listing step: at least one published `at_biz_dir` post

The setup section may link to the established admin screens, but it must not mutate any of these states. Never store observed setup progress or completion values in this document.
