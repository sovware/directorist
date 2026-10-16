# Digital Marketplace: vendor-wallet

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Order/refund status updates vendor balances and payout records. Do not confuse a payout request with paid settlement.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Compare completed and refunded sales for two vendors, commission rates, repeated order-status events and payout request/admin decision; assert no cross-vendor data exposure.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Module/Core/Ajax/Init.php:1](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/app/Module/Core/Ajax/Init.php#L1) — 4 declarations
- [app/Module/Core/Ajax/Payout.php:1](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/app/Module/Core/Ajax/Payout.php#L1) — 3 declarations
- [app/Module/Dashboard/Init.php:1](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/app/Module/Dashboard/Init.php#L1) — 5 declarations
- [app/Module/Dashboard/Payouts.php:1](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/app/Module/Dashboard/Payouts.php#L1) — 18 declarations
- [app/Module/Dashboard/Tabs_Wallet.php:1](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/app/Module/Dashboard/Tabs_Wallet.php#L1) — 4 declarations
- [app/Module/WooCommerce/Init.php:1](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/app/Module/WooCommerce/Init.php#L1) — 5 declarations
- [app/Module/WooCommerce/Order.php:1](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/app/Module/WooCommerce/Order.php#L1) — 11 declarations
- [app/Module/WooCommerce/WC_Helper.php:1](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/app/Module/WooCommerce/WC_Helper.php#L1) — 10 declarations

[Complete topic source/data ledger](vendor-wallet-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
