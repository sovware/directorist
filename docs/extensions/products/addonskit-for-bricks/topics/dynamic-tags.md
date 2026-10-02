# AddonsKit for Bricks: dynamic-tags

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Dynamic field/tag values depend on listing/directory/taxonomy context; editor sample is not final queried object.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Use two directories with same custom field type, category images and empty values. Compare loop/detail/editor output and escaped field values.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [addonskit-for-bricks.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/addonskit-for-bricks.php#L1) — 7 declarations
- [src/DynamicTags/DataProvider.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/DynamicTags/DataProvider.php#L1) — 2 declarations
- [src/DynamicTags/Provider.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/DynamicTags/Provider.php#L1) — 11 declarations
- [src/Support/Utils.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Support/Utils.php#L1) — 15 declarations

[Complete topic source/data ledger](dynamic-tags-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
