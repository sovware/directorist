# Directorist Search Alert: alert-delivery

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Listing publication and scheduled delivery select matches on immediate/daily/weekly/monthly paths. Cron execution and email capture must be proved separately.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Create matching and nonmatching listings after the save time. Run only relevant local cron events; assert recipient, schedule, no repeat notifications and expired-search cleanup.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Dashboard/Admin_Dashboard.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Dashboard/Admin_Dashboard.php#L1) — 5 declarations
- [app/Database/Database.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Database/Database.php#L1) — 8 declarations
- [app/Email/Email.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Email/Email.php#L1) — 14 declarations
- [app/Setup/Settings.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/Setup/Settings.php#L1) — 5 declarations
- [app/base.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/app/base.php#L1) — 20 declarations

[Complete topic source/data ledger](alert-delivery-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
