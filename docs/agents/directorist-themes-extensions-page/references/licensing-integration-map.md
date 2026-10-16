# Licensing Integration Map

This reference maps the systems connected to licensing for the Directorist admin `Themes & Extensions` page. It records durable contracts and ownership boundaries only. Never store credentials, access keys, license keys, customer names, entitlement lists, product counts, or one-time API responses in this file.

## System Boundaries

| System | Responsibility | Canonical data |
| --- | --- | --- |
| Directorist core plugin | Renders the local admin UI, sends local AJAX requests, normalizes remote account responses, caches entitlements in user meta, and performs WordPress install/update/activation work | Local WordPress plugin/theme state and the current admin user's cached connection state |
| Directorist License Manager | Authenticates a Directorist.com account and assembles EDD customer, entitlement, license, plan, and account data | Directorist.com account and EDD-backed licensing data |
| Directorist.com WordPress users | Own the account identity | User ID, login/email, display name, avatar |
| Easy Digital Downloads | Owns purchases, downloadable products, customers, and license records | Product IDs, files, purchases, licenses, customer records |
| EDD All Access | Supplies account-level pass name, status, expiration, lifetime, and all-access state when available | All Access pass objects |
| EDD Software Licensing | Activates licenses and supplies version/update eligibility | License activation and version responses |
| Legacy Directorist licensing API | Compatibility fallback for older deployments | Existing `license_data` response |
| Directorist product API | Supplies catalog copy and optional badges/recommendation metadata; it is not the entitlement authority | Remote product catalog |

## Active Account Login Flow

1. The browser submits the page form to local WordPress `admin-ajax.php`.
2. Existing AJAX action `atbdp_authenticate_the_customer` calls `ATBDP_Extensions::authenticate_the_customer()`.
3. Core sends a server-side `POST` request to:
   - `https://directorist.com/wp-json/directorist-license-manager/user-login`
   - Fields: `email`, `pass`, `domain`
4. License Manager accepts either username or email through `AccountRepository::authenticate_user_login()`.
5. License Manager's `EddRepository::get_customer_data()` assembles downloads, license state, purchase history, All Access state, and optional `account_summary`.
6. Core accepts the preferred response only when `plan_data.downloads.templates` and `plan_data.downloads.extensions` are arrays.
7. Core maps those arrays into the legacy-compatible `license_data.themes` and `license_data.plugins` shape.
8. Core stores sanitized connection and entitlement state in the current local WordPress admin user's meta.
9. The submitted password exists only for the remote request and must never be stored locally or returned to browser state.

If the preferred route is unavailable, non-successful in a fallback-safe way, malformed, or incomplete, account login falls back to:

- `GET https://directorist.com/wp-json/directorist/v1/licencing`
- Legacy fields: `user`, `password`

The fallback exists for the installed customer base and must not be removed until the old endpoint is formally retired with a compatibility plan.

## Legacy Access-Key Session Migration

Core no longer offers access-key login or calls the License Manager `user-connect` endpoint. A stored `_atbdp_subscription_connection_method=access_key` marker is migration-only. Core rejects that session immediately and, on the next admin request, clears its connected marker, account identity/summary, and cached extension/theme entitlements. The customer must connect again with Directorist.com username/email and password.

This migration removes Core-managed licensing access. It does not remotely revoke an EDD license that an already installed extension manages itself, nor deactivate that extension.

## Remote Response Contract

Preferred License Manager response:

```json
{
  "method": "user_login",
  "account_data": {
    "user_id": 0,
    "user_email": "",
    "display_name": ""
  },
  "plan_data": {
    "downloads": {
      "templates": [],
      "extensions": []
    },
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

Rules:

- `account_summary` and every field inside it are optional.
- Login success is not enough to replace local entitlements unless both required download arrays are valid.
- Extra remote fields are untrusted. Core whitelists accepted account identity fields.
- Missing account summary must fall back to generic connected-account copy without changing entitlement behavior.
- Dates must be ISO 8601 from the API and formatted using the client site's WordPress date settings.

## Local Client-Site Cache

Core stores the following on the current WordPress admin user:

| User meta key | Purpose | Secret |
| --- | --- | --- |
| `_atbdp_has_subscriptions_sassion` | Connected/disconnected page state | No |
| `_atbdp_subscribed_username` | Connected account identifier | Treat as private account data |
| `_plugins_available_in_subscriptions` | Normalized extension entitlements | Treat as private entitlement data |
| `_themes_available_in_subscriptions` | Normalized theme entitlements | Treat as private entitlement data |
| `_atbdp_account_summary` | Sanitized optional plan/avatar/expiry summary | Treat as private account data |
| `_atbdp_subscription_connection_method` | `account`; legacy `access_key` is cleared | No |

Keep the `sassion` misspelling because it is a shipped compatibility contract. These values are a local cache, not permanent licensing truth and not documentation truth.

## Refresh Purchases

1. UI reuses `atbdp_refresh_purchase_status`.
2. Core requires a supported account session and requests the Directorist.com password again.
3. Core re-authenticates through the account License Manager route, with the legacy account fallback when needed.
4. Only a valid complete response replaces local extension/theme entitlement meta and account summary.
5. The password is discarded after the request.

Refresh Purchases is remote revalidation. It is not a product-catalog refresh, WordPress plugin update check, or license-key rotation.

## Disconnect

`atbdp_close_subscriptions_sassion` disconnects the local WordPress page session.

- It clears connected state, account summary, and connection-method meta.
- Hard disconnect may also clear the cached account identifier and entitlement arrays.
- It does not cancel a subscription.
- It does not deactivate EDD licenses remotely.
- It does not deactivate or uninstall already installed plugins or themes.
- Already installed products may continue running; account connection is required for page-managed subscription installs, downloads, refreshes, and updates.

## Install, Update, And License Calls

The current core product-management path is not fully routed through License Manager:

| Operation | Current remote contract |
| --- | --- |
| Account login | License Manager `user-login` |
| License activation | `https://directorist.com` with EDD `activate_license` |
| Extension version check | Directorist.com EDD `get_version` request |
| Package URL | `https://directorist.com/wp-json/directorist/v1/get-product-data/` |
| Product catalog | `https://app.directorist.com/wp-json/directorist/v1/get-remote-products` |

Local WordPress state remains canonical after every install, update, activation, or theme switch. Remote success alone must not cause the UI to claim a final local state.

## Account Summary And Avatar

License Manager builds optional account summary data from:

- Directorist.com WordPress user display name and avatar.
- EDD customer identity.
- EDD All Access pass objects.

Core sanitizes and stores only the supported summary fields. The connected Dashboard greeting and account control use the licensing owner, not the local site name or local WordPress administrator identity. When avatar data is unavailable, the UI uses a safe fallback such as account initials.

## Security Requirements

The public skill records required protections, not private service implementation details or an exploit checklist:

- Credential routes require verified HTTPS, redacted logs, throttling, failed-attempt controls, generic authentication errors, and monitoring.
- License Manager responses should omit submitted credentials and unnecessary secret fields.
- Core must continue whitelisting accepted identity/summary fields instead of persisting arbitrary remote response data.
- Legacy account fallback is compatibility-only and should be retired only through a separately reviewed migration.
- New EDD activation and package-download work must use verified HTTPS and strict package-host validation.
- Never log, document, or persist submitted passwords, raw license keys, or complete remote entitlement payloads.

## Cross-Repository Change Checklist

When changing licensing behavior, inspect all affected repositories:

1. **Directorist core**
   - AJAX names, nonce/capability checks, fallback behavior, user-meta compatibility, response normalization, UI states.
2. **Directorist License Manager**
   - Account route validation, lookup, EDD data shape, optional fields, error status codes.
3. **Directorist.com theme/site code**
   - Legacy `/directorist/v1/licencing` endpoint and product/catalog endpoints when still active.
4. **EDD dependencies**
   - Core EDD, Software Licensing, and All Access behavior on the live site.

For backward-compatible API additions:

- Add optional fields; do not rename or remove established fields.
- Keep old core versions able to ignore new data.
- Keep new core versions able to fall back when new routes are absent.
- Never replace cached entitlements from a partial or malformed success response.
- Verify account login, legacy access-key invalidation, refresh, disconnect, install, update, and failed-remote states separately.
