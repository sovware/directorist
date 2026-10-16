# Directorist Notifications Pro: subscription

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Browser permission, service worker scope, authenticated subscription persistence and per-user preference are required before sending.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

On HTTPS localhost/test origin use two users and browsers. Grant/deny permission, subscribe/unsubscribe and disable preference; verify endpoint ownership and service worker scope without publishing credentials.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [directorist-notifications-pro.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/directorist-notifications-pro.php#L1) — 8 declarations
- [inc/class-admin-log.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-admin-log.php#L1) — 18 declarations
- [inc/class-event-integration.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-event-integration.php#L1) — 35 declarations
- [inc/class-frontend.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-frontend.php#L1) — 14 declarations
- [inc/class-license.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-license.php#L1) — 9 declarations
- [inc/class-rest-api.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-rest-api.php#L1) — 10 declarations
- [inc/class-sender.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-sender.php#L1) — 14 declarations
- [inc/class-settings.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-settings.php#L1) — 15 declarations

[Complete topic source/data ledger](subscription-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
