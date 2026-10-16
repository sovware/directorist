# Directorist Search Alert: saved-search

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Saved searches preserve filter values and ownership; create/edit/delete actions and expiration have different data paths.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Save keyword/category/location/custom filters as user A, edit and delete; ensure user B cannot operate A records. Reload dashboard and verify displayed filters match request.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Ajax/Ajax.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Ajax/Ajax.php#L1) — 6 declarations
- [app/Dashboard/Admin_Dashboard.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Dashboard/Admin_Dashboard.php#L1) — 5 declarations
- [app/Dashboard/Dashboard.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Dashboard/Dashboard.php#L1) — 8 declarations
- [app/Dashboard/User_Dashboard.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Dashboard/User_Dashboard.php#L1) — 4 declarations
- [app/Database/Database.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Database/Database.php#L1) — 8 declarations
- [app/Setup/Enqueue.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Setup/Enqueue.php#L1) — 6 declarations
- [app/base.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/base.php#L1) — 20 declarations
- [helpers/helpers.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/helpers/helpers.php#L1) — 5 declarations

[Complete topic source/data ledger](saved-search-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
