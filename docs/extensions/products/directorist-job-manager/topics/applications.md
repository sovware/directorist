# Job Manager: applications

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Application form generation, upload restrictions, cleanup and email construction form the submission pipeline.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Submit valid/invalid attachments and missing required fields as guest/user; capture mail locally, verify intended recipient, validation feedback and temporary file cleanup.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [includes/class-ajax-handler.php:1](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/includes/class-ajax-handler.php#L1) — 5 declarations
- [includes/class-file-handler.php:1](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/includes/class-file-handler.php#L1) — 13 declarations
- [includes/class-form-generator.php:1](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/includes/class-form-generator.php#L1) — 12 declarations
- [includes/class-form-handler.php:1](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/includes/class-form-handler.php#L1) — 4 declarations
- [includes/class-general.php:1](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/includes/class-general.php#L1) — 4 declarations
- [includes/class-generate-pages.php:1](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/includes/class-generate-pages.php#L1) — 4 declarations
- [includes/class-helper.php:1](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/includes/class-helper.php#L1) — 13 declarations
- [includes/class-mail-processor.php:1](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/includes/class-mail-processor.php#L1) — 12 declarations

[Complete topic source/data ledger](applications-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-divi-integration](../../directorist-divi-integration/README.md) — only if active configuration or source connects it.
