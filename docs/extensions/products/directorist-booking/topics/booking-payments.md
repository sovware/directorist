# Booking (Reservation & Appointment): booking-payments

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Booking payment uses booking-linked orders and may use WooCommerce. Commission, wallet, refunds and payout records must agree with booking status; an owner approval is not automatically a collected payment.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Test sandbox paid/failed booking, owner approval, cancellation, partial/full refund and commission/payout request. Assert booking/order references and balances; capture email without contacting users.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Providers/Commission/Commission.php:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Commission/Commission.php#L1) — 19 declarations
- [app/Providers/Commission/Wallet.php:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Commission/Wallet.php#L1) — 6 declarations
- [app/Providers/Payment.php:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Payment.php#L1) — 26 declarations
- [app/Providers/Refund/AdminRefund.php:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Refund/AdminRefund.php#L1) — 7 declarations
- [app/Providers/Refund/UserRefund.php:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Refund/UserRefund.php#L1) — 5 declarations
- [app/Providers/WooCommercePayment.php:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/WooCommercePayment.php#L1) — 4 declarations
- [app/Helpers/helper.php:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Helpers/helper.php#L1) — 33 declarations
- [config/app.php:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/config/app.php#L1) — 16 declarations

[Complete topic source/data ledger](booking-payments-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-woocommerce-pricing-plans](../../directorist-woocommerce-pricing-plans/README.md) — only if active configuration or source connects it.
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md) — only if active configuration or source connects it.
