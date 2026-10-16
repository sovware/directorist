# BuddyPress Integration: profile-groups

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Profile listing/favorite/add screens and group-connected IDs depend on the community platform and active components.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Use two users and public/private groups. Connect/disconnect listings, view profile tabs and attempt unauthorized group edits; check counts/pagination.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [includes/bp-listings-functions.php:1](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/bp-listings-functions.php#L1) — 14 declarations
- [includes/bp-listings-screens.php:1](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/bp-listings-screens.php#L1) — 5 declarations
- [includes/bp-listings-template.php:1](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/bp-listings-template.php#L1) — 5 declarations
- [includes/class-bp-listings-component.php:1](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/class-bp-listings-component.php#L1) — 10 declarations
- [includes/class-bp-listings-loader.php:1](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/class-bp-listings-loader.php#L1) — 1 declarations
- [includes/class-group-listings-extension.php:1](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/class-group-listings-extension.php#L1) — 18 declarations
- [includes/class-settings.php:1](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/class-settings.php#L1) — 7 declarations
- [includes/listings-common-functions.php:1](https://github.com/sovware/directorist-buddypress-integration/blob/e63ae3d41639d5ddf4df772f1dbd12136792c8db/includes/listings-common-functions.php#L1) — 8 declarations

[Complete topic source/data ledger](profile-groups-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
