# Gamipress Integration: points

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Listing/review/favorite/order events feed GamiPress rules; event occurrence and rule eligibility are separate.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Configure one rule each for publish/review/favorite, trigger and repeat, inspect points/log and unrelated user balance.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [includes/class-assets.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-assets.php#L1) — 4 declarations
- [includes/class-author.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-author.php#L1) — 3 declarations
- [includes/class-dashboard.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-dashboard.php#L1) — 5 declarations
- [includes/class-listeners.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-listeners.php#L1) — 10 declarations
- [includes/class-requirements.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-requirements.php#L1) — 5 declarations
- [includes/class-rules-engine.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-rules-engine.php#L1) — 3 declarations
- [includes/class-triggers.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-triggers.php#L1) — 5 declarations
- [assets/js/admin-script.js:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/assets/js/admin-script.js#L1) — 0 declarations

[Complete topic source/data ledger](points-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-coupon](../../directorist-coupon/README.md) — only if active configuration or source connects it.
