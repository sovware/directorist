# Authorize.net Payment Gateway: hosted-checkout

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Legacy source retains 2Checkout-named internals despite the Authorize.net product. The newer processor contains hosted-payment and subscription paths; do not route by class name alone.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Inspect installed generation, sandbox credentials and hosted form request. Test approval, decline, cancel and invalid configuration; compare correct order reference and amount.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Http/Controllers/CheckoutController.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/app/Http/Controllers/CheckoutController.php#L1) — 10 declarations
- [app/Providers/Admin/SettingsServiceProvider.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/app/Providers/Admin/SettingsServiceProvider.php#L1) — 4 declarations
- [app/Providers/CheckoutServiceProvider.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/app/Providers/CheckoutServiceProvider.php#L1) — 5 declarations
- [app/Providers/DirectoristServiceProvider.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/app/Providers/DirectoristServiceProvider.php#L1) — 4 declarations
- [directorist-authorize-net.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/directorist-authorize-net.php#L1) — 3 declarations
- [app/AuthorizeNet.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/app/AuthorizeNet.php#L1) — 8 declarations
- [app/Helpers/helper.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/app/Helpers/helper.php#L1) — 16 declarations
- [config/app.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/config/app.php#L1) — 4 declarations

[Complete topic source/data ledger](hosted-checkout-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md) — only if active configuration or source connects it.
