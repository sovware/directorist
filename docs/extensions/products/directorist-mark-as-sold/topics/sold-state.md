# Mark as Sold: sold-state

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Dashboard action, stored sold state, badge and query exclusion are separate contracts; sold is not inherently WordPress trash/unpublish.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Toggle owned listing sold/available, attempt as another author; reload dashboard/detail/archive and verify expected visibility and stored state.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [directorist-mark-as-sold.php:1](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/directorist-mark-as-sold.php#L1) — 33 declarations
- [assets/admin/js/main.js:1](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/assets/admin/js/main.js#L1) — 0 declarations
- [assets/public/js/main.js:1](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/assets/public/js/main.js#L1) — 0 declarations

[Complete topic source/data ledger](sold-state-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
