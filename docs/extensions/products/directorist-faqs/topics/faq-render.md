# Listing FAQs: faq-render

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Single content widget, shortcode and classic widget are separate render paths.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Compare native detail, shortcode/widget and Divi module for same listing; expand/collapse by keyboard and test no-plan vs allowed plan.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [directorist-faqs.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/directorist-faqs.php#L1) — 28 declarations
- [inc/directory_type.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/inc/directory_type.php#L1) — 7 declarations
- [inc/helper-functions.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/inc/helper-functions.php#L1) — 3 declarations
- [widgets/class-widget.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/widgets/class-widget.php#L1) — 5 declarations
- [templates/add-faq.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/templates/add-faq.php#L1) — 0 declarations
- [templates/ajax/faqs-ajax.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/templates/ajax/faqs-ajax.php#L1) — 0 declarations
- [templates/faqs.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/templates/faqs.php#L1) — 0 declarations
- [templates/view-faqs.php:1](https://github.com/sovware/directorist-faqs/blob/cdcc5031f77ca1333fa39d1ee45dca4ca9937c70/templates/view-faqs.php#L1) — 0 declarations

[Complete topic source/data ledger](faq-render-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md) — only if active configuration or source connects it.
- [directorist-divi-integration](../../directorist-divi-integration/README.md) — only if active configuration or source connects it.
