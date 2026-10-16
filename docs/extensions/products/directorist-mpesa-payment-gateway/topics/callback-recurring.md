# M-Pesa Payment Gateway: callback-recurring

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Callbacks must resolve the correct order and handle repeat events; pending polling, initial package binding and recurring schedule mapping have separate paths.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Replay success/failure/duplicate callbacks for two orders; test pending query and supported/unsupported plan intervals, cancellation and receipt status. Assert no duplicate payment/package.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Http/Controllers/CheckoutController.php:1](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Http/Controllers/CheckoutController.php#L1) — 2 declarations
- [app/Providers/CronServiceProvider.php:1](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Providers/CronServiceProvider.php#L1) — 5 declarations
- [app/Services/MpesaService.php:1](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L1) — 38 declarations
- [config/app.php:1](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/config/app.php#L1) — 9 declarations
- [routes/rest/api.php:1](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/routes/rest/api.php#L1) — 1 declarations
- [routes/ajax/api.php:1](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/routes/ajax/api.php#L1) — 0 declarations

[Complete topic source/data ledger](callback-recurring-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md) — only if active configuration or source connects it.
