# Business Hours: schedule

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Schedule submission, timezone selection, disabled/24-hour states, overnight ranges, badges and open-now filtering are different paths. Compare the saved listing ID and timezone with the renderer and query clock.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Create schedules spanning midnight, a closed day and 24-hour day in two timezones. Save/reload in frontend and admin; compare detail badge and open-now results immediately before/after boundaries. Capture actual time and cache state.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [bd-business-hour.php:1](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/bd-business-hour.php#L1) — 26 declarations
- [inc/csv_manager.php:1](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/csv_manager.php#L1) — 7 declarations
- [inc/directory_types_manager.php:1](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/directory_types_manager.php#L1) — 12 declarations
- [inc/helper-functions.php:1](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/helper-functions.php#L1) — 29 declarations
- [widgets/class-widget.php:1](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/widgets/class-widget.php#L1) — 5 declarations
- [templates/business-hour-fields.php:1](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/templates/business-hour-fields.php#L1) — 0 declarations
- [templates/show_hours.php:1](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/templates/show_hours.php#L1) — 0 declarations
- [assets/js/main.js:1](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/assets/js/main.js#L1) — 132 declarations

[Complete topic source/data ledger](schedule-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md) — only if active configuration or source connects it.
- [directorist-divi-integration](../../directorist-divi-integration/README.md) — only if active configuration or source connects it.
