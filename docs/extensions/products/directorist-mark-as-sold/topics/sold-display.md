# Mark as Sold: sold-display

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Price replacement, contact handling and author/archive query hooks can differ by setting.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Compare sold and unsold listings under hide/show settings; check author page, search, price and contact action without deleting either listing.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [directorist-mark-as-sold.php:1](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/directorist-mark-as-sold.php#L1) — 33 declarations

[Complete topic source/data ledger](sold-display-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
