# HelpGent Integration: forms-responses

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

HelpGent owns form/response entities; this integration scopes queries, quotas and dashboard permissions. Pro-only settings depend on the actual HelpGent edition.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Create forms as two listing authors, submit test response/media, then edit/read/archive as each user. Check quotas and unauthorized attachment access.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Http/Controllers/FormController.php:1](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/app/Http/Controllers/FormController.php#L1) — 25 declarations
- [app/Http/Controllers/ResponseController.php:1](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/app/Http/Controllers/ResponseController.php#L1) — 10 declarations
- [app/Providers/Admin/DirectoristSettingsServiceProvider.php:1](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/app/Providers/Admin/DirectoristSettingsServiceProvider.php#L1) — 4 declarations
- [app/Providers/MenuServiceProvider.php:1](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/app/Providers/MenuServiceProvider.php#L1) — 18 declarations
- [app/Helpers/helper.php:1](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/app/Helpers/helper.php#L1) — 17 declarations
- [app/Http/Middleware/Auth.php:1](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/app/Http/Middleware/Auth.php#L1) — 2 declarations
- [app/Http/Middleware/EnsureIsUserAdmin.php:1](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/app/Http/Middleware/EnsureIsUserAdmin.php#L1) — 2 declarations
- [config/app.php:1](https://github.com/sovware/directorist-helpgent-integration/blob/681495a66d0f8072924937b8ae00e6f514ac68ab/config/app.php#L1) — 4 declarations

[Complete topic source/data ledger](forms-responses-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
