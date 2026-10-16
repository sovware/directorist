# Mark as Sold

Let listing authors mark listings sold and alter badges, price/contact visibility and queries.

Official catalog: [Mark as Sold](https://directorist.com/product/directorist-mark-as-sold/). Catalog identity checked 2026-09-13.

Canonical investigation baseline: **directorist-mark-as-sold / details-update / ce63a5af0e80c619d48fe9ba490acbf05b66fab2**. Selected the newest visible implementation branch tip, then retained default/development and local file differences for feature-level reconciliation; no branches were merged.

Observed plugin header: `Directorist - Mark as Sold` — `2.3.0` ([directorist-mark-as-sold.php:1](https://github.com/sovware/directorist-mark-as-sold/blob/ce63a5af0e80c619d48fe9ba490acbf05b66fab2/directorist-mark-as-sold.php#L1)). Header/tag is an identifier, not source authority or release proof.

Read this identity page, then only the matching topic. The evidence indexes are searchable references, never required full reads.

## Topics

| Topic | Symptoms / feature boundary |
| --- | --- |
| [sold-state](topics/sold-state.md) | mark as sold, sold button, sold badge |
| [sold-display](topics/sold-display.md) | sold price, sold contact, hide sold |

## Source and compatibility

[Source variants and branch deltas](source-variants.md) — read when installed files differ or changes cross branches.
[Hooks, guards and dependency evidence](contracts.md) — search for the issue hook/class, not every row.
[Dependency packages and runtime guards](dependencies.md) — inspect relevant external API/platform contract.
[Feature coverage classification](coverage.md) — feature-linked source vs infrastructure/support.
[Complete classified file inventory](files.md) — find a missing feature or inspect coverage.

Source inspection only. Functional and browser recipes are **not executed** by this mapping task. No local/client/CI/release compatibility is asserted. Future versions must be checked against actual files.

If the issue spans intake, reproduction, implementation and delivery, use the installed `rabbi-support-delivery-orchestrator` for those generic stages, plus [the focused evidence workflow](../../WORKFLOW.md).
