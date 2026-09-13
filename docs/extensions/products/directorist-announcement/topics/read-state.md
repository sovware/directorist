# Directorist Announcement: read-state

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Seen/dismissed state and new-count responses should be scoped to the current user.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Read/close as A, refresh badge and confirm B state unchanged. Test expired and deleted announcements.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [inc/class-content-update.php:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-content-update.php#L1) — 10 declarations
- [inc/class-frontend-view.php:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-frontend-view.php#L1) — 4 declarations
- [inc/class-helpers.php:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-helpers.php#L1) — 9 declarations
- [template-parts/list.php:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/template-parts/list.php#L1) — 0 declarations
- [assets/js/main.js:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/assets/js/main.js#L1) — 0 declarations

[Complete topic source/data ledger](read-state-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
