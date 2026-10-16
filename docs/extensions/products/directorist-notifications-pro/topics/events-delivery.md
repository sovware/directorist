# Directorist Notifications Pro: events-delivery

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Legacy and new order hooks can describe the same event; completion deduplication and endpoint failure handling must avoid duplicate notifications.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Capture test pushes for listing publish/edit/expiry and paid order; replay completion hook, expire an endpoint and check logs/recipient preferences. Do not trigger real client notifications.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [inc/class-admin-log.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-admin-log.php#L1) — 18 declarations
- [inc/class-event-integration.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-event-integration.php#L1) — 35 declarations
- [inc/class-license.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-license.php#L1) — 9 declarations
- [inc/class-sender.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-sender.php#L1) — 14 declarations
- [inc/class-settings.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/inc/class-settings.php#L1) — 15 declarations

[Complete topic source/data ledger](events-delivery-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
