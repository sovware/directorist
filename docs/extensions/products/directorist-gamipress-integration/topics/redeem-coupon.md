# Gamipress Integration: redeem-coupon

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Coupon redemption bridges GamiPress points and Directorist Coupon state; balance and coupon uses must stay consistent.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Redeem with sufficient/insufficient points, repeat request and use/expire coupon. Check both point deduction and coupon ownership/usage.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [includes/class-coupon-manager.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-coupon-manager.php#L1) — 6 declarations
- [includes/class-settings.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-settings.php#L1) — 5 declarations
- [includes/class-utils.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-utils.php#L1) — 5 declarations
- [assets/js/script.js:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/assets/js/script.js#L1) — 0 declarations

[Complete topic source/data ledger](redeem-coupon-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-coupon](../../directorist-coupon/README.md) — only if active configuration or source connects it.
