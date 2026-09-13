# Claim Listing: paid-claim-badge

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Claim pricing can use plan allowances or a manual charge; paid completion must reach the correct claim/listing. Badge content may be overridden by a builder/theme.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Compare free claim, plan-supported claim and manual fee; test failed/completed payment, card/detail tooltip and Divi badge. Preserve author ownership when only tooltip styling changes.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [config-helper.php:1](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/config-helper.php:1>) — 2 declarations
- [directorist-claim-listing.php:1](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/directorist-claim-listing.php:1>) — 59 declarations
- [inc/class-claim-now.php:1](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/class-claim-now.php:1>) — 5 declarations
- [inc/directory_type.php:1](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/directory_type.php:1>) — 9 declarations
- [inc/helper-functions.php:1](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/inc/helper-functions.php:1>) — 20 declarations
- [templates/claim-listing-template.php:1](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/templates/claim-listing-template.php:1>) — 0 declarations
- [templates/partials/plan-selector.php:1](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/templates/partials/plan-selector.php:1>) — 0 declarations
- [templates/verified-badge.php:1](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/templates/verified-badge.php:1>) — 0 declarations

[Complete topic source/data ledger](paid-claim-badge-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md) — only if active configuration or source connects it.
- [directorist-woocommerce-pricing-plans](../../directorist-woocommerce-pricing-plans/README.md) — only if active configuration or source connects it.
- [directorist-divi-integration](../../directorist-divi-integration/README.md) — only if active configuration or source connects it.
