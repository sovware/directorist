# HelpGent Integration: contacts-notifications

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Contacts, tags, summary counts, exports and email recipients use integration-specific query scopes.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Compare author A/B contacts/counts, tag edits/export and locally captured response email. Verify no cross-author response or media leakage.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Http/Controllers/ContactController.php:1](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/app/Http/Controllers/ContactController.php#L1) — 7 declarations
- [app/Http/Controllers/FormController.php:1](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/app/Http/Controllers/FormController.php#L1) — 25 declarations
- [app/Http/Controllers/ListController.php:1](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/app/Http/Controllers/ListController.php#L1) — 3 declarations
- [app/Http/Controllers/ResponseController.php:1](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/app/Http/Controllers/ResponseController.php#L1) — 10 declarations
- [app/Http/Controllers/SummaryController.php:1](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/app/Http/Controllers/SummaryController.php#L1) — 4 declarations
- [app/Http/Controllers/TagController.php:1](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/app/Http/Controllers/TagController.php#L1) — 8 declarations
- [app/Providers/EmailNotificationServiceProvider.php:1](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/app/Providers/EmailNotificationServiceProvider.php#L1) — 12 declarations
- [app/Providers/MenuServiceProvider.php:1](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/app/Providers/MenuServiceProvider.php#L1) — 18 declarations

[Complete topic source/data ledger](contacts-notifications-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
