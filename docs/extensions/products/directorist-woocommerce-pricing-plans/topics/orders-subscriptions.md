# WooCommerce Pricing Plans: orders-subscriptions

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

WooCommerce payment/status transitions and subscription repair belong to WooCommerce integration; do not route every payment issue to Directorist Stripe.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Use sandbox WooCommerce checkout; process completion, cancellation, renewal failure and retry. Compare HPOS setting, product/order IDs, listing expiry and package recovery without double activation.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [inc/classes/class-admin-notices.php:1](https://github.com/sovware/directorist-woocommerce-pricing-plans/blob/c61131cdb1a319bc186bae5da4c3b55b10e42342/inc/classes/class-admin-notices.php#L1) — 10 declarations
- [inc/classes/class-direct-purchase.php:1](https://github.com/sovware/directorist-woocommerce-pricing-plans/blob/c61131cdb1a319bc186bae5da4c3b55b10e42342/inc/classes/class-direct-purchase.php#L1) — 3 declarations
- [inc/classes/class-subscription.php:1](https://github.com/sovware/directorist-woocommerce-pricing-plans/blob/c61131cdb1a319bc186bae5da4c3b55b10e42342/inc/classes/class-subscription.php#L1) — 8 declarations
- [inc/classes/class-wc-controller.php:1](https://github.com/sovware/directorist-woocommerce-pricing-plans/blob/c61131cdb1a319bc186bae5da4c3b55b10e42342/inc/classes/class-wc-controller.php#L1) — 22 declarations
- [inc/helper-functions.php:1](https://github.com/sovware/directorist-woocommerce-pricing-plans/blob/c61131cdb1a319bc186bae5da4c3b55b10e42342/inc/helper-functions.php#L1) — 71 declarations

[Complete topic source/data ledger](orders-subscriptions-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-claim-listing](../../directorist-claim-listing/README.md) — only if active configuration or source connects it.
