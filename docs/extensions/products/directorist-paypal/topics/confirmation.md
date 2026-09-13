# PayPal Payment Gateway: confirmation

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

IPN verification/transaction handling in the legacy implementation must bind to the correct order; a return URL alone is not payment proof.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Replay sandbox verified, forged and duplicate notifications, return before notification and failed notification. Check order amount, transaction identity and one-time completion.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Http/Controllers/CheckoutController.php:1](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Http/Controllers/CheckoutController.php#L1) — 37 declarations
- [app/Http/Controllers/Controller.php:1](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Http/Controllers/Controller.php#L1) — 2 declarations
- [app/Http/Controllers/UserController.php:1](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Http/Controllers/UserController.php#L1) — 2 declarations
- [app/Http/Controllers/WebhookController.php:1](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Http/Controllers/WebhookController.php#L1) — 5 declarations
- [app/Providers/DirectoristServiceProvider.php:1](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Providers/DirectoristServiceProvider.php#L1) — 4 declarations
- [app/Providers/MenuServiceProvider.php:1](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Providers/MenuServiceProvider.php#L1) — 5 declarations
- [directorist-paypal.php:1](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/directorist-paypal.php#L1) — 4 declarations
- [app/Helpers/helper.php:1](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Helpers/helper.php#L1) — 19 declarations

[Complete topic source/data ledger](confirmation-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md) — only if active configuration or source connects it.
