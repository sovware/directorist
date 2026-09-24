# Directorist Analytics: dashboard

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Dashboard query ranges, ownership and frontend charts are separate from report email formatting. Source features absent from a variant must be treated as absent, not assumed available.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Compare author and admin views, date ranges with no data and boundary events, timezone and listing filter; check network responses and rendered chart totals.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Providers/AnalyticsServiceProvider.php:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Providers/AnalyticsServiceProvider.php#L1) — 2 declarations
- [app/Providers/MenuServiceProvider.php:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Providers/MenuServiceProvider.php#L1) — 4 declarations
- [app/Models/Activity.php:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Models/Activity.php#L1) — 3 declarations
- [app/Models/Click.php:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Models/Click.php#L1) — 3 declarations
- [app/Models/CountryStatDaily.php:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Models/CountryStatDaily.php#L1) — 3 declarations
- [app/Models/DailyStat.php:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Models/DailyStat.php#L1) — 3 declarations
- [app/Models/Post.php:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Models/Post.php#L1) — 5 declarations
- [app/Models/PostMeta.php:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Models/PostMeta.php#L1) — 3 declarations

[Complete topic source/data ledger](dashboard-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
