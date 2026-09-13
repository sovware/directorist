# Pricing Plans

Sell per-listing or package entitlements and control listing features, renewal, expiry and subscription state.

Official catalog: [Pricing Plans](https://directorist.com/product/directorist-pricing-plans/). Catalog identity checked 2026-09-13.

Canonical investigation baseline: **directorist-pricing-plans-new / development / 99f9cf0be143f40c4422be87ccd6e9ea028cc2df**. Selected the newest visible implementation branch tip, then retained default/development and local file differences for feature-level reconciliation; no branches were merged.

Observed plugin header: `Directorist - Pricing Plans` — `4.1.1` ([directorist-pricing-plans.php:1](https://github.com/sovware/directorist-pricing-plans-new/blob/99f9cf0be143f40c4422be87ccd6e9ea028cc2df/directorist-pricing-plans.php#L1)). Header/tag is an identifier, not source authority or release proof.

Read this identity page, then only the matching topic. The evidence indexes are searchable references, never required full reads.

## Topics

| Topic | Symptoms / feature boundary |
| --- | --- |
| [entitlements](topics/entitlements.md) | no plan, missing fields, plan allowance, quota, plan assignment, pricing plans |
| [checkout-renewal](topics/checkout-renewal.md) | renewal, expired listing, subscription, proration, cancel, trial, paid plan, package |
| [migration-admin](topics/migration-admin.md) | plan migration, legacy plan, plan sorting, missing package, pricing admin |
| [plan-presentation](topics/plan-presentation.md) | pricing table, plan card, plan tabs, plan button, plan display |

## Source and compatibility

[Source variants and branch deltas](source-variants.md) — read when installed files differ or changes cross branches.
[Hooks, guards and dependency evidence](contracts.md) — search for the issue hook/class, not every row.
[Dependency packages and runtime guards](dependencies.md) — inspect relevant external API/platform contract.
[Feature coverage classification](coverage.md) — feature-linked source vs infrastructure/support.
[Complete classified file inventory](files.md) — find a missing feature or inspect coverage.

Source inspection only. Functional and browser recipes are **not executed** by this mapping task. No local/client/CI/release compatibility is asserted. Future versions must be checked against actual files.

If the issue spans intake, reproduction, implementation and delivery, use the installed `rabbi-support-delivery-orchestrator` for those generic stages, plus [the focused evidence workflow](../../WORKFLOW.md).
