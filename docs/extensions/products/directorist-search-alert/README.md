# Directorist Search Alert

Save a user search and send matching-listing notifications on configured schedules.

Official catalog: [Directorist Search Alert](https://directorist.com/product/directorist-search-alert/). Catalog identity checked 2026-09-13.

Canonical investigation baseline: **directorist-search-alert / main / 4b62ac4632520a7e5ea2494ac30ca2b3756899df**. Selected the newest visible implementation branch tip, then retained default/development and local file differences for feature-level reconciliation; no branches were merged.

Observed plugin header: `Directorist - Search Alert` — `1.0.3` ([directorist-search-alert.php:1](https://github.com/sovware/directorist-search-alert/blob/4b62ac4632520a7e5ea2494ac30ca2b3756899df/directorist-search-alert.php#L1)). Header/tag is an identifier, not source authority or release proof.

Read this identity page, then only the matching topic. The evidence indexes are searchable references, never required full reads.

## Topics

| Topic | Symptoms / feature boundary |
| --- | --- |
| [saved-search](topics/saved-search.md) | search alert, saved search, alert preferences |
| [alert-delivery](topics/alert-delivery.md) | search alert email, daily alert, weekly alert, monthly alert |

## Source and compatibility

[Source variants and branch deltas](source-variants.md) — read when installed files differ or changes cross branches.
[Hooks, guards and dependency evidence](contracts.md) — search for the issue hook/class, not every row.
[Dependency packages and runtime guards](dependencies.md) — inspect relevant external API/platform contract.
[Feature coverage classification](coverage.md) — feature-linked source vs infrastructure/support.
[Complete classified file inventory](files.md) — find a missing feature or inspect coverage.

Source inspection only. Functional and browser recipes are **not executed** by this mapping task. No local/client/CI/release compatibility is asserted. Future versions must be checked against actual files.

If the issue spans intake, reproduction, implementation and delivery, use the installed `rabbi-support-delivery-orchestrator` for those generic stages, plus [the focused evidence workflow](../../WORKFLOW.md).
