# Directorist Coupon: discount-rules

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Admin rules, AJAX eligibility, checkout calculation and usage count are separate. Stripe checkout adaptation does not establish discounts for every future renewal.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Create fixed/percentage coupons with expiry, usage limit and plan restrictions. Test eligible/ineligible plans, final allowed use, invalid code, tax and total floor.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [directorist-coupon.php:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/directorist-coupon.php#L1) — 14 declarations
- [Inc/Controller/Admin/CustomWpListTable.php:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Admin/CustomWpListTable.php#L1) — 5 declarations
- [Inc/Controller/Admin/SWBDPCouponCPT.php:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Admin/SWBDPCouponCPT.php#L1) — 8 declarations
- [Inc/Controller/Base/CouponHandler.php:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/CouponHandler.php#L1) — 10 declarations
- [Inc/Controller/Base/ExtensionSettings.php:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/ExtensionSettings.php#L1) — 8 declarations
- [Inc/Controller/Base/HelperFunctions.php:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/HelperFunctions.php#L1) — 8 declarations
- [Inc/Controller/Base/ShortcodeHandler.php:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/ShortcodeHandler.php#L1) — 4 declarations
- [Inc/View/AdminCallbacks.php:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/View/AdminCallbacks.php#L1) — 3 declarations

[Complete topic source/data ledger](discount-rules-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-stripe](../../directorist-stripe/README.md) — only if active configuration or source connects it.
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md) — only if active configuration or source connects it.
- [directorist-gamipress-integration](../../directorist-gamipress-integration/README.md) — only if active configuration or source connects it.
