# Authorize.net Payment Gateway: recurring-webhook

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Newer webhook/subscription API code is separate from the legacy completion path. Branch header 999.0.0 is not a released-version assertion.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Use stubs/sandbox for initial payment, recurring transaction, duplicate event and cancel; check authenticated callback and subscription schedule without charging clients.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Http/Controllers/CheckoutController.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/app/Http/Controllers/CheckoutController.php#L1) — 10 declarations
- [app/Http/Controllers/Controller.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/app/Http/Controllers/Controller.php#L1) — 1 declarations
- [app/Http/Controllers/UserController.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/app/Http/Controllers/UserController.php#L1) — 2 declarations
- [app/Providers/Admin/SettingsServiceProvider.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/app/Providers/Admin/SettingsServiceProvider.php#L1) — 4 declarations
- [app/AuthorizeNet.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/app/AuthorizeNet.php#L1) — 8 declarations
- [config/app.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/config/app.php#L1) — 4 declarations
- [routes/ajax/api.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/routes/ajax/api.php#L1) — 0 declarations
- [routes/rest/api.php:1](https://github.com/sovware/directorist-authorize-net-new/blob/ab7986313aa349c4cea2d9956169ae2d07da4fd0/routes/rest/api.php#L1) — 0 declarations

[Complete topic source/data ledger](recurring-webhook-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md) — only if active configuration or source connects it.
