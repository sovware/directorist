# Stripe Payment Gateway: webhooks

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Browser success, webhook confirmation and subscription renewal are separate. Verify signature handling and order reference before altering completion logic.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Replay valid, invalid-signature, delayed, out-of-order and duplicate events in sandbox; assert payment, package dates and cancellation once, plus failed renewal.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Http/Controllers/CheckoutController.php:1](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Http/Controllers/CheckoutController.php#L1) — 29 declarations
- [app/Http/Controllers/Controller.php:1](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Http/Controllers/Controller.php#L1) — 1 declarations
- [app/Http/Controllers/LegacyWebhookController.php:1](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Http/Controllers/LegacyWebhookController.php#L1) — 7 declarations
- [app/Http/Controllers/UserController.php:1](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Http/Controllers/UserController.php#L1) — 2 declarations
- [app/Http/Controllers/WebhookController.php:1](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Http/Controllers/WebhookController.php#L1) — 6 declarations
- [app/Providers/Admin/LicenseServiceProvider.php:1](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Providers/Admin/LicenseServiceProvider.php#L1) — 3 declarations
- [app/Providers/Admin/SettingsServiceProvider.php:1](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Providers/Admin/SettingsServiceProvider.php#L1) — 6 declarations
- [app/Providers/Admin/UpdateServiceProvider.php:1](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Providers/Admin/UpdateServiceProvider.php#L1) — 3 declarations

[Complete topic source/data ledger](webhooks-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md) — only if active configuration or source connects it.
- [directorist-coupon](../../directorist-coupon/README.md) — only if active configuration or source connects it.
