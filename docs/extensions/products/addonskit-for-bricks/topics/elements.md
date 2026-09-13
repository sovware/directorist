# AddonsKit for Bricks: elements

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Element controls convert to Directorist shortcode/query settings; Core owns resulting listing workflows. Dependency checks require actual Bricks and Directorist.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Build search, listings, add-listing and checkout elements in a local Bricks page; save/reopen and exercise native search/submission.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [addonskit-for-bricks.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/addonskit-for-bricks.php#L1) — 7 declarations
- [src/Elements/AddListing.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/AddListing.php#L1) — 13 declarations
- [src/Elements/AuthorProfile.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/AuthorProfile.php#L1) — 6 declarations
- [src/Elements/Authors.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Authors.php#L1) — 15 declarations
- [src/Elements/BaseElement.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/BaseElement.php#L1) — 2 declarations
- [src/Elements/Categories.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Categories.php#L1) — 6 declarations
- [src/Elements/Checkout.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Checkout.php#L1) — 7 declarations
- [src/Elements/Components/Account.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Elements/Components/Account.php#L1) — 7 declarations

[Complete topic source/data ledger](elements-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
