# Mailchimp Integration: registration

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Registration consent and audience configuration control subscription; successful WordPress registration does not guarantee Mailchimp success.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Use stub/test audience; register with/without consent, existing subscribed email and API failure. Check requested list and subscription state.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [directorist-mailchimp-integration.php:1](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/directorist-mailchimp-integration.php#L1) — 11 declarations
- [includes/class-mailchimp.php:1](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/includes/class-mailchimp.php#L1) — 3 declarations
- [includes/class-settings-manager.php:1](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/includes/class-settings-manager.php#L1) — 4 declarations
- [includes/class-subscribe-after-registration.php:1](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/includes/class-subscribe-after-registration.php#L1) — 6 declarations
- [templates/before-registration-checkbox.php:1](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/templates/before-registration-checkbox.php#L1) — 0 declarations

[Complete topic source/data ledger](registration-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
