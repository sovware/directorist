# Business Hours

Authors maintain business schedules; visitors see opening status and can filter open businesses.

Official catalog: [Business Hours](https://directorist.com/product/directorist-business-hours/). Catalog identity checked 2026-09-13.

Canonical investigation baseline: **directorist-business-hours / development / f4ce722e68bff3f85b8b845042c904c7bfd52ef4**. Selected the newest visible implementation branch tip, then retained default/development and local file differences for feature-level reconciliation; no branches were merged.

Observed plugin header: `Directorist - Business Hour` — `3.8.0` ([bd-business-hour.php:1](https://github.com/sovware/directorist-business-hours/blob/f4ce722e68bff3f85b8b845042c904c7bfd52ef4/bd-business-hour.php#L1)). Header/tag is an identifier, not source authority or release proof.

Read this identity page, then only the matching topic. The evidence indexes are searchable references, never required full reads.

## Topics

| Topic | Symptoms / feature boundary |
| --- | --- |
| [schedule](topics/schedule.md) | hours saving, overnight, timezone, open now, closed now, 24 hours, business hour |
| [display-import](topics/display-import.md) | hours empty, hours not showing, hours missing, hours csv, hours badge, schedule import |

## Source and compatibility

[Source variants and branch deltas](source-variants.md) — read when installed files differ or changes cross branches.
[Hooks, guards and dependency evidence](contracts.md) — search for the issue hook/class, not every row.
[Dependency packages and runtime guards](dependencies.md) — inspect relevant external API/platform contract.
[Feature coverage classification](coverage.md) — feature-linked source vs infrastructure/support.
[Complete classified file inventory](files.md) — find a missing feature or inspect coverage.

Source inspection only. Functional and browser recipes are **not executed** by this mapping task. No local/client/CI/release compatibility is asserted. Future versions must be checked against actual files.

If the issue spans intake, reproduction, implementation and delivery, use the installed `rabbi-support-delivery-orchestrator` for those generic stages, plus [the focused evidence workflow](../../WORKFLOW.md).
