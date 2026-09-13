# Digital Marketplace: purchase-download

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Listing files/tiers generate WooCommerce products/orders; authorized download access follows purchase state, not just a visible download button.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Create two file tiers and a nonbuyer. Buy one tier in sandbox, test authorized download, unpaid/cancelled order, changed file and refund revocation.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Helper/Listing.php:1](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/app/Helper/Listing.php#L1) — 13 declarations
- [app/Module/Core/Admin/Builder.php:1](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/app/Module/Core/Admin/Builder.php#L1) — 4 declarations
- [app/Module/Core/Admin/Notice.php:1](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/app/Module/Core/Admin/Notice.php#L1) — 5 declarations
- [app/Module/Core/Admin/Settings.php:1](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/app/Module/Core/Admin/Settings.php#L1) — 6 declarations
- [app/Module/Core/Ajax/Purchase.php:1](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/app/Module/Core/Ajax/Purchase.php#L1) — 6 declarations
- [app/Module/Core/Asset/AdminAsset.php:1](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/app/Module/Core/Asset/AdminAsset.php#L1) — 5 declarations
- [app/Module/Core/Asset/AssetEnqueuer.php:1](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/app/Module/Core/Asset/AssetEnqueuer.php#L1) — 6 declarations
- [app/Module/Core/Asset/Init.php:1](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/app/Module/Core/Asset/Init.php#L1) — 4 declarations

[Complete topic source/data ledger](purchase-download-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
