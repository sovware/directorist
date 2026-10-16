# PayPal Payment Gateway: checkout

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Legacy IPN and newer registered gateway implementations differ; inspect actual processor/service availability rather than assuming the newer header implies full support.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Use authorized sandbox, verify merchant/mode/currency/order return URL, abandon payment and return. Compare displayed receipt with actual stored order status.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Http/Controllers/CheckoutController.php:1](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Http/Controllers/CheckoutController.php#L1) — 37 declarations
- [app/Providers/Admin/SettingsServiceProvider.php:1](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Providers/Admin/SettingsServiceProvider.php#L1) — 6 declarations
- [app/Providers/CheckoutServiceProvider.php:1](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Providers/CheckoutServiceProvider.php#L1) — 5 declarations
- [app/Providers/DirectoristServiceProvider.php:1](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Providers/DirectoristServiceProvider.php#L1) — 4 declarations
- [app/Providers/MenuServiceProvider.php:1](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Providers/MenuServiceProvider.php#L1) — 5 declarations
- [directorist-paypal.php:1](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/directorist-paypal.php#L1) — 4 declarations
- [app/Helpers/helper.php:1](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/Helpers/helper.php#L1) — 19 declarations
- [app/PayPal.php:1](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/app/PayPal.php#L1) — 21 declarations

[Complete topic source/data ledger](checkout-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md) — only if active configuration or source connects it.
