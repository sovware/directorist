# Business Hours: display-import

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Single content widgets, card badges, REST response and CSV integration expose the schedule through separate contracts. A stored schedule alone does not prove any of those paths render.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Compare a no-plan listing and a plan allowing hours. Check native detail, card, REST and a builder template using the queried listing ID. Round-trip CSV on disposable listings and compare normalized schedule.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [bd-business-hour.php:1](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/bd-business-hour.php#L1) — 26 declarations
- [inc/asset-loader.php:1](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/asset-loader.php#L1) — 9 declarations
- [inc/class-rest-response.php:1](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/class-rest-response.php#L1) — 10 declarations
- [inc/csv_manager.php:1](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/csv_manager.php#L1) — 7 declarations
- [inc/directory_types_manager.php:1](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/directory_types_manager.php#L1) — 12 declarations
- [inc/helper-functions.php:1](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/inc/helper-functions.php#L1) — 29 declarations
- [widgets/class-widget.php:1](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/widgets/class-widget.php#L1) — 5 declarations
- [templates/badge.php:1](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/templates/badge.php#L1) — 0 declarations

[Complete topic source/data ledger](display-import-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md) — only if active configuration or source connects it.
- [directorist-divi-integration](../../directorist-divi-integration/README.md) — only if active configuration or source connects it.
