# Live Chat: socket-delivery

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Socket dependency/loading and fallback handling vary by branch; history persistence and real-time delivery require separate proof.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Test connected socket, unavailable socket and refreshed history; compare browser errors and stored message identity. Use local test messages only.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [directorist-live-chat.php:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/directorist-live-chat.php#L1) — 33 declarations
- [includes/helper.php:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/includes/helper.php#L1) — 9 declarations
- [assets/public/main.js:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/assets/public/main.js#L1) — 4 declarations
- [assets/admin/main.css:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/assets/admin/main.css#L1) — 0 declarations
- [assets/admin/main.js:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/assets/admin/main.js#L1) — 0 declarations
- [assets/public/style-rtl.css:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/assets/public/style-rtl.css#L1) — 0 declarations
- [assets/public/style.css:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/assets/public/style.css#L1) — 0 declarations

[Complete topic source/data ledger](socket-delivery-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md) — only if active configuration or source connects it.
- [directorist-divi-integration](../../directorist-divi-integration/README.md) — only if active configuration or source connects it.
