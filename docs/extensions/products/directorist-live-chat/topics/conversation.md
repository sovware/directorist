# Live Chat: conversation

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Listing restriction, participant identity and conversation lookup precede display. Native Live Chat is separate from HelpGent messaging.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Use listing owner, buyer and unrelated user. Start a conversation, refresh history, attempt another conversation ID and verify authorized visibility.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [directorist-live-chat.php:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/directorist-live-chat.php#L1) — 33 declarations
- [class/admin-chat.php:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/class/admin-chat.php#L1) — 4 declarations
- [includes/directory_type.php:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/includes/directory_type.php#L1) — 5 declarations
- [includes/helper.php:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/includes/helper.php#L1) — 9 declarations
- [templates/chat.php:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/templates/chat.php#L1) — 0 declarations
- [templates/live_chat.php:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/templates/live_chat.php#L1) — 0 declarations

[Complete topic source/data ledger](conversation-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md) — only if active configuration or source connects it.
- [directorist-divi-integration](../../directorist-divi-integration/README.md) — only if active configuration or source connects it.
