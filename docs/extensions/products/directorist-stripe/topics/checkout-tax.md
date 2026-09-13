# Stripe Payment Gateway: checkout-tax

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Legacy and processor generations differ. Amount currency conversion, tax, discounts and trial calculation must be traced to the installed generation; SEPA belongs to a specific branch.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Test sandbox zero-decimal and normal currencies, tax-inclusive/exclusive prices, coupon and trial. Inspect checkout total, order amount, redirect and delayed-payment state.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Http/Controllers/CheckoutController.php:1](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Http/Controllers/CheckoutController.php#L1) — 29 declarations
- [app/Http/Controllers/WebhookController.php:1](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Http/Controllers/WebhookController.php#L1) — 6 declarations
- [app/Providers/Admin/LicenseServiceProvider.php:1](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Providers/Admin/LicenseServiceProvider.php#L1) — 3 declarations
- [app/Providers/Admin/SettingsServiceProvider.php:1](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Providers/Admin/SettingsServiceProvider.php#L1) — 6 declarations
- [app/Providers/CheckoutServiceProvider.php:1](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Providers/CheckoutServiceProvider.php#L1) — 6 declarations
- [directorist-stripe.php:1](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/directorist-stripe.php#L1) — 6 declarations
- [app/Helpers/helper.php:1](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Helpers/helper.php#L1) — 4 declarations
- [app/Stripe.php:1](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/app/Stripe.php#L1) — 15 declarations

[Complete topic source/data ledger](checkout-tax-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md) — only if active configuration or source connects it.
- [directorist-coupon](../../directorist-coupon/README.md) — only if active configuration or source connects it.
