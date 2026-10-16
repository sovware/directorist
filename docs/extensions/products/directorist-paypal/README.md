# PayPal Payment Gateway

Collect PayPal payments and reconcile external confirmation with local orders.

Official catalog: [PayPal Payment Gateway](https://directorist.com/product/directorist-paypal/). Catalog identity checked 2026-09-13.

Canonical investigation baseline: **directorist-paypal-new / development / 84de33e01f7a8aae9ee2ee801916403ec6537703**. Selected the newest visible implementation branch tip, then retained default/development and local file differences for feature-level reconciliation; no branches were merged.

Observed plugin header: `Directorist - Paypal Payment Gateway` — `3.0.0` ([directorist-paypal.php:1](https://github.com/sovware/directorist-paypal-new/blob/84de33e01f7a8aae9ee2ee801916403ec6537703/directorist-paypal.php#L1)). Header/tag is an identifier, not source authority or release proof.

Read this identity page, then only the matching topic. The evidence indexes are searchable references, never required full reads.

## Topics

| Topic | Symptoms / feature boundary |
| --- | --- |
| [checkout](topics/checkout.md) | paypal, paypal checkout, paypal sandbox |
| [confirmation](topics/confirmation.md) | paypal ipn, paypal webhook, paypal pending |

## Source and compatibility

[Source variants and branch deltas](source-variants.md) — read when installed files differ or changes cross branches.
[Hooks, guards and dependency evidence](contracts.md) — search for the issue hook/class, not every row.
[Dependency packages and runtime guards](dependencies.md) — inspect relevant external API/platform contract.
[Feature coverage classification](coverage.md) — feature-linked source vs infrastructure/support.
[Complete classified file inventory](files.md) — find a missing feature or inspect coverage.

Source inspection only. Functional and browser recipes are **not executed** by this mapping task. No local/client/CI/release compatibility is asserted. Future versions must be checked against actual files.

If the issue spans intake, reproduction, implementation and delivery, use the installed `rabbi-support-delivery-orchestrator` for those generic stages, plus [the focused evidence workflow](../../WORKFLOW.md).
