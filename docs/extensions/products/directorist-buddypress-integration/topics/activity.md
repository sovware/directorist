# BuddyPress Integration: activity

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Publication/share events create activity entries; deletion/visibility and login redirects must respect community context.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Publish/share/delete a listing; compare timeline, group privacy and redirected login route. Ensure unrelated users do not receive private listing access.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [directorist-buddypress-integration.php:1](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/directorist-buddypress-integration.php#L1) — 16 declarations
- [includes/bp-listings-actions.php:1](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/bp-listings-actions.php#L1) — 3 declarations
- [includes/bp-listings-activity.php:1](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/bp-listings-activity.php#L1) — 4 declarations
- [includes/bp-listings-functions.php:1](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/bp-listings-functions.php#L1) — 14 declarations
- [includes/bp-listings-template.php:1](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/bp-listings-template.php#L1) — 5 declarations
- [includes/class-bp-listings-component.php:1](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/class-bp-listings-component.php#L1) — 10 declarations
- [includes/class-builder.php:1](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/class-builder.php#L1) — 4 declarations
- [includes/class-group-listings-extension.php:1](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/class-group-listings-extension.php#L1) — 18 declarations

[Complete topic source/data ledger](activity-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
