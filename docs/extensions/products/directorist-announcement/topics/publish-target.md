# Directorist Announcement: publish-target

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Announcement post type, recipient selection, expiry and send action control visibility and email.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Create announcement for user A only and another for all users with expiry. Check A/B dashboard, captured email and expiry cleanup.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [inc/class-content-update.php:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-content-update.php#L1) — 10 declarations
- [inc/class-helpers.php:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-helpers.php#L1) — 9 declarations
- [inc/class-settings.php:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/inc/class-settings.php#L1) — 6 declarations
- [assets/js/admin.js:1](https://github.com/sovware/directorist-announcement/blob/92c16c32b1f6610316b2250ebf68c5f6c2abd717/assets/js/admin.js#L1) — 13 declarations

[Complete topic source/data ledger](publish-target-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
