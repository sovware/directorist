# Directorist Advanced Review: moderation-media

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Replies, upload deletion, reactions and abuse reports have distinct endpoints and authorization.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Use owner, reviewer, guest and unrelated account. Test permitted/forbidden edits, attachment type/size, duplicate reaction and report submission; compare API and rendered replies.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Http/Controllers/AttachmentController.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Http/Controllers/AttachmentController.php#L1) — 3 declarations
- [app/Http/Controllers/ReactionController.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Http/Controllers/ReactionController.php#L1) — 3 declarations
- [app/Http/Controllers/ReplyController.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Http/Controllers/ReplyController.php#L1) — 7 declarations
- [app/Http/Controllers/ReportController.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Http/Controllers/ReportController.php#L1) — 2 declarations
- [app/DTO/ReactionDTO.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/DTO/ReactionDTO.php#L1) — 9 declarations
- [app/DTO/ReviewDTO.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/DTO/ReviewDTO.php#L1) — 23 declarations
- [app/Http/Middleware/Auth.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Http/Middleware/Auth.php#L1) — 2 declarations
- [app/Http/Middleware/EnsureIsUserAdmin.php:1](https://github.com/sovware/directorist-advanced-review/blob/38089538e124bb99123f0db34860992247bb6506/app/Http/Middleware/EnsureIsUserAdmin.php#L1) — 2 declarations

[Complete topic source/data ledger](moderation-media-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
