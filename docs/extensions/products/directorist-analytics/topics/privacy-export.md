# Directorist Analytics: privacy-export

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Tracking respects configured privacy/admin/DNT/IP/referrer settings; raw log retention, aggregation and CSV export are distinct operations. Deleting raw logs does not necessarily delete aggregates.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Use DNT on/off, admin vs author and query-string referrers in a local fixture. Compare raw/aggregate counts, anonymization, date range export and authorized cleanup; do not export client personal data without scope.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Http/Controllers/AnalyticsController.php:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Http/Controllers/AnalyticsController.php#L1) — 19 declarations
- [app/Http/Controllers/ExportController.php:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Http/Controllers/ExportController.php#L1) — 6 declarations
- [app/Http/Controllers/SettingsController.php:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Http/Controllers/SettingsController.php#L1) — 5 declarations
- [app/Services/ActivityService.php:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Services/ActivityService.php#L1) — 5 declarations
- [app/Services/AuthorLoginService.php:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Services/AuthorLoginService.php#L1) — 4 declarations
- [app/Services/PrivacyService.php:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Services/PrivacyService.php#L1) — 10 declarations
- [app/Services/StatsService.php:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Services/StatsService.php#L1) — 9 declarations
- [app/Services/TrackingService.php:1](https://github.com/sovware/directorist-analytics/blob/1cfab8c614df67718fac9065037174acec4370df/app/Services/TrackingService.php#L1) — 18 declarations

[Complete topic source/data ledger](privacy-export-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
