# Job Manager: job-fields

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Salary/type/position/deadline fields and search filters are separate from application submission. Directory builder placement determines visibility.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Create two job types and salary ranges; save/edit, filter and view in native/Divi detail. Check empty and expired deadline presentation.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [includes/class-helper.php:1](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/includes/class-helper.php#L1) — 13 declarations
- [includes/class-scripts.php:1](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/includes/class-scripts.php#L1) — 7 declarations
- [includes/class-settings-manager.php:1](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/includes/class-settings-manager.php#L1) — 11 declarations
- [includes/class-widget-quick-info.php:1](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/includes/class-widget-quick-info.php#L1) — 7 declarations
- [templates/archive/fields/dirjob_deadline.php:1](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/templates/archive/fields/dirjob_deadline.php#L1) — 0 declarations
- [templates/archive/fields/dirjob_job_type.php:1](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/templates/archive/fields/dirjob_job_type.php#L1) — 0 declarations
- [templates/archive/fields/dirjob_open_position.php:1](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/templates/archive/fields/dirjob_open_position.php#L1) — 0 declarations
- [templates/archive/fields/dirjob_salary.php:1](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/templates/archive/fields/dirjob_salary.php#L1) — 0 declarations

[Complete topic source/data ledger](job-fields-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-divi-integration](../../directorist-divi-integration/README.md) — only if active configuration or source connects it.
