# WooCommerce Pricing Plans

Use WooCommerce products, orders and optional subscriptions to sell Directorist listing packages.

Official catalog: [WooCommerce Pricing Plans](https://directorist.com/product/directorist-woocommerce-pricing-plans/). Catalog identity checked 2026-09-13.

Canonical investigation baseline: **directorist-woocommerce-pricing-plans / codex/fix-subscription-plan-repair / c61131cdb1a319bc186bae5da4c3b55b10e42342**. Selected the newest visible implementation branch tip, then retained default/development and local file differences for feature-level reconciliation; no branches were merged.

Observed plugin header: `Directorist - WooCommerce Pricing Plans` — `3.7.1` ([directorist-woocommerce-pricing-plans.php:1](https://github.com/sovware/directorist-woocommerce-pricing-plans/blob/c61131cdb1a319bc186bae5da4c3b55b10e42342/directorist-woocommerce-pricing-plans.php#L1)). Header/tag is an identifier, not source authority or release proof.

Read this identity page, then only the matching topic. The evidence indexes are searchable references, never required full reads.

## Topics

| Topic | Symptoms / feature boundary |
| --- | --- |
| [products-entitlements](topics/products-entitlements.md) | woocommerce plan, wc plan, product plan, wc quota |
| [orders-subscriptions](topics/orders-subscriptions.md) | woocommerce renewal, wc subscription, hpos, woocommerce checkout |

## Source and compatibility

[Source variants and branch deltas](source-variants.md) — read when installed files differ or changes cross branches.
[Hooks, guards and dependency evidence](contracts.md) — search for the issue hook/class, not every row.
[Dependency packages and runtime guards](dependencies.md) — inspect relevant external API/platform contract.
[Feature coverage classification](coverage.md) — feature-linked source vs infrastructure/support.
[Complete classified file inventory](files.md) — find a missing feature or inspect coverage.

Source inspection only. Functional and browser recipes are **not executed** by this mapping task. No local/client/CI/release compatibility is asserted. Future versions must be checked against actual files.

If the issue spans intake, reproduction, implementation and delivery, use the installed `rabbi-support-delivery-orchestrator` for those generic stages, plus [the focused evidence workflow](../../WORKFLOW.md).
