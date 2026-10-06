# M-Pesa Payment Gateway: checkout

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Phone normalization, KES amount rules, credentials and processor compatibility gate the request. A successful OAuth test does not prove checkout collection.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Use API stubs or authorized sandbox; test 01/07 phone formats, fractional amount rejection, missing credentials and unsupported currency; verify submitted amount/reference and checkout feedback.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Http/Controllers/OAuthController.php:1](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Http/Controllers/OAuthController.php#L1) — 4 declarations
- [app/Providers/Admin/SettingsServiceProvider.php:1](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Providers/Admin/SettingsServiceProvider.php#L1) — 9 declarations
- [app/Providers/CheckoutServiceProvider.php:1](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Providers/CheckoutServiceProvider.php#L1) — 8 declarations
- [app/Providers/DirectoristServiceProvider.php:1](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Providers/DirectoristServiceProvider.php#L1) — 4 declarations
- [app/Services/MpesaApi.php:1](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaApi.php#L1) — 16 declarations
- [app/Services/MpesaService.php:1](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L1) — 38 declarations
- [directorist-mpesa.php:1](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/directorist-mpesa.php#L1) — 4 declarations
- [app/Helpers/helper.php:1](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Helpers/helper.php#L1) — 33 declarations

[Complete topic source/data ledger](checkout-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md) — only if active configuration or source connects it.
