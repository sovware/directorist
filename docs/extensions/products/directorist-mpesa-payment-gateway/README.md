# M-Pesa Payment Gateway

Collect M-Pesa payments with STK push and supported recurring schedules.

Official catalog: [M-Pesa Payment Gateway](https://directorist.com/product/m-pesa-payment-gateway/). Catalog identity checked 2026-09-13.

Canonical investigation baseline: **directorist-mpesa-payment-gateway / main / f304ecfa35049b5cc827b182f54dcbc048dc5bf3**. Selected the newest visible implementation branch tip, then retained default/development and local file differences for feature-level reconciliation; no branches were merged.

Observed plugin header: `Directorist - M-Pesa Payment Gateway` — `1.0.0` ([directorist-mpesa.php:1](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/directorist-mpesa.php#L1)). Header/tag is an identifier, not source authority or release proof.

Read this identity page, then only the matching topic. The evidence indexes are searchable references, never required full reads.

## Topics

| Topic | Symptoms / feature boundary |
| --- | --- |
| [checkout](topics/checkout.md) | mpesa, m-pesa, stk push, kenyan phone, kes |
| [callback-recurring](topics/callback-recurring.md) | ratiba, mpesa callback, mpesa renewal |

## Source and compatibility

[Source variants and branch deltas](source-variants.md) — read when installed files differ or changes cross branches.
[Hooks, guards and dependency evidence](contracts.md) — search for the issue hook/class, not every row.
[Dependency packages and runtime guards](dependencies.md) — inspect relevant external API/platform contract.
[Feature coverage classification](coverage.md) — feature-linked source vs infrastructure/support.
[Complete classified file inventory](files.md) — find a missing feature or inspect coverage.

Source inspection only. Functional and browser recipes are **not executed** by this mapping task. No local/client/CI/release compatibility is asserted. Future versions must be checked against actual files.

If the issue spans intake, reproduction, implementation and delivery, use the installed `rabbi-support-delivery-orchestrator` for those generic stages, plus [the focused evidence workflow](../../WORKFLOW.md).
