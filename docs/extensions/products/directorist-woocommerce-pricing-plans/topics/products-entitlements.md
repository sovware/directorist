# WooCommerce Pricing Plans: products-entitlements

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

WooCommerce order/product identity drives listing entitlement. The plugin guards against simultaneous native Pricing Plans classes; verify which plan engine actually loaded.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Create a package product and two directory types; buy as a new author and assert listing quota, permitted fields and dashboard package. Test no purchase and exhausted quota.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [const-helper.php:1](https://github.com/sovware/directorist-woocommerce-pricing-plans/blob/c61131cdb1a319bc186bae5da4c3b55b10e42342/const-helper.php#L1) — 2 declarations
- [directorist-woocommerce-pricing-plans.php:1](https://github.com/sovware/directorist-woocommerce-pricing-plans/blob/c61131cdb1a319bc186bae5da4c3b55b10e42342/directorist-woocommerce-pricing-plans.php#L1) — 17 declarations
- [inc/class-woo-admin.php:1](https://github.com/sovware/directorist-woocommerce-pricing-plans/blob/c61131cdb1a319bc186bae5da4c3b55b10e42342/inc/class-woo-admin.php#L1) — 2 declarations
- [inc/classes/class-ajax-handler.php:1](https://github.com/sovware/directorist-woocommerce-pricing-plans/blob/c61131cdb1a319bc186bae5da4c3b55b10e42342/inc/classes/class-ajax-handler.php#L1) — 14 declarations
- [inc/classes/class-data-controller.php:1](https://github.com/sovware/directorist-woocommerce-pricing-plans/blob/c61131cdb1a319bc186bae5da4c3b55b10e42342/inc/classes/class-data-controller.php#L1) — 15 declarations
- [inc/classes/class-listing_type_manager.php:1](https://github.com/sovware/directorist-woocommerce-pricing-plans/blob/c61131cdb1a319bc186bae5da4c3b55b10e42342/inc/classes/class-listing_type_manager.php#L1) — 14 declarations
- [inc/classes/class-restrictions.php:1](https://github.com/sovware/directorist-woocommerce-pricing-plans/blob/c61131cdb1a319bc186bae5da4c3b55b10e42342/inc/classes/class-restrictions.php#L1) — 10 declarations
- [inc/classes/class-settings.php:1](https://github.com/sovware/directorist-woocommerce-pricing-plans/blob/c61131cdb1a319bc186bae5da4c3b55b10e42342/inc/classes/class-settings.php#L1) — 10 declarations

[Complete topic source/data ledger](products-entitlements-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-claim-listing](../../directorist-claim-listing/README.md) — only if active configuration or source connects it.
