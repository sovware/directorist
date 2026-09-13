# Directorist Analytics: tracking-reports

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Use the selected implementation branch: default main is a scaffold and cannot explain the newer report flow. Track listing identity through aggregation and report loops.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Use at least three listings with different event counts and owners. Generate weekly/monthly reports locally, assert each listing appears once with its own totals and correct period.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Http/Controllers/AnalyticsController.php:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Http/Controllers/AnalyticsController.php#L1) — 19 declarations
- [app/Http/Controllers/Controller.php:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Http/Controllers/Controller.php#L1) — 3 declarations
- [app/Http/Controllers/ExportController.php:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Http/Controllers/ExportController.php#L1) — 6 declarations
- [app/Http/Controllers/SettingsController.php:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Http/Controllers/SettingsController.php#L1) — 5 declarations
- [app/Http/Controllers/TrackingController.php:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Http/Controllers/TrackingController.php#L1) — 5 declarations
- [app/Http/Controllers/UserController.php:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Http/Controllers/UserController.php#L1) — 2 declarations
- [app/Providers/AnalyticsServiceProvider.php:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Providers/AnalyticsServiceProvider.php#L1) — 2 declarations
- [app/Services/ActivityService.php:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Services/ActivityService.php#L1) — 5 declarations

[Complete topic source/data ledger](tracking-reports-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
