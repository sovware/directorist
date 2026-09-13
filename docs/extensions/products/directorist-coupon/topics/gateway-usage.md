# Directorist Coupon: gateway-usage

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Coupon application to Stripe session and local order items must agree; update usage only according to the actual hook lifecycle.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Complete and abandon sandbox checkout, replay completion and compare usage count/order receipt. Verify first invoice vs renewal behavior explicitly before promising recurring discounts.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [directorist-coupon.php:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/directorist-coupon.php#L1) — 14 declarations
- [Inc/Controller/Base/CouponHandler.php:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/CouponHandler.php#L1) — 10 declarations

[Complete topic source/data ledger](gateway-usage-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-stripe](../../directorist-stripe/README.md) — only if active configuration or source connects it.
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md) — only if active configuration or source connects it.
- [directorist-gamipress-integration](../../directorist-gamipress-integration/README.md) — only if active configuration or source connects it.
