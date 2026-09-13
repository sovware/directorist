# Digital Marketplace

Sell listing-attached digital files through WooCommerce, with download access and vendor accounting.

Official catalog: [Digital Marketplace](https://directorist.com/product/directorist-digital-marketplace/). Catalog identity checked 2026-09-13.

Canonical investigation baseline: **directorist-digital-marketplace / fix/safari-source-map-errors / dc80ed24ba02b23f9f8157084bc02a5dea7502c7**. Selected the newest visible implementation branch tip, then retained default/development and local file differences for feature-level reconciliation; no branches were merged.

Observed plugin header: `Directorist - Digital Marketplace` — `2.1.2` ([directorist-digital-marketplace.php:1](https://github.com/sovware/directorist-digital-marketplace/blob/dc80ed24ba02b23f9f8157084bc02a5dea7502c7/directorist-digital-marketplace.php#L1)). Header/tag is an identifier, not source authority or release proof.

Read this identity page, then only the matching topic. The evidence indexes are searchable references, never required full reads.

## Topics

| Topic | Symptoms / feature boundary |
| --- | --- |
| [purchase-download](topics/purchase-download.md) | digital marketplace, download file, price tier, buy file |
| [vendor-wallet](topics/vendor-wallet.md) | vendor commission, digital payout, vendor balance |

## Source and compatibility

[Source variants and branch deltas](source-variants.md) — read when installed files differ or changes cross branches.
[Hooks, guards and dependency evidence](contracts.md) — search for the issue hook/class, not every row.
[Dependency packages and runtime guards](dependencies.md) — inspect relevant external API/platform contract.
[Feature coverage classification](coverage.md) — feature-linked source vs infrastructure/support.
[Complete classified file inventory](files.md) — find a missing feature or inspect coverage.

Source inspection only. Functional and browser recipes are **not executed** by this mapping task. No local/client/CI/release compatibility is asserted. Future versions must be checked against actual files.

If the issue spans intake, reproduction, implementation and delivery, use the installed `rabbi-support-delivery-orchestrator` for those generic stages, plus [the focused evidence workflow](../../WORKFLOW.md).
