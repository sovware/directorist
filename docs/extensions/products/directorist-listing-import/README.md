# Directorist Listing Importer

Import listings from Google Places and feeds with mapping, duplicate checks and scheduled runs.

Official catalog: [Directorist Listing Importer](https://directorist.com/product/directorist-listing-importer/). Catalog identity checked 2026-09-13.

Canonical investigation baseline: **directorist-listing-import / main / 81e621c5d45a938a1a21d9e0185f3f7528f9a660**. Selected the newest visible implementation branch tip, then retained default/development and local file differences for feature-level reconciliation; no branches were merged.

Observed plugin header: `Listing Importer` — `1.1.0` ([directorist-listing-import.php:1](https://github.com/sovware/directorist-listing-import/blob/81e621c5d45a938a1a21d9e0185f3f7528f9a660/directorist-listing-import.php#L1)). Header/tag is an identifier, not source authority or release proof.

Read this identity page, then only the matching topic. The evidence indexes are searchable references, never required full reads.

## Topics

| Topic | Symptoms / feature boundary |
| --- | --- |
| [google-import](topics/google-import.md) | google import, listing importer, place id, field mapping, import hours |
| [feed-import](topics/feed-import.md) | rss import, feed import, scheduled import, duplicate feed |

## Source and compatibility

[Source variants and branch deltas](source-variants.md) — read when installed files differ or changes cross branches.
[Hooks, guards and dependency evidence](contracts.md) — search for the issue hook/class, not every row.
[Dependency packages and runtime guards](dependencies.md) — inspect relevant external API/platform contract.
[Feature coverage classification](coverage.md) — feature-linked source vs infrastructure/support.
[Complete classified file inventory](files.md) — find a missing feature or inspect coverage.

Source inspection only. Functional and browser recipes are **not executed** by this mapping task. No local/client/CI/release compatibility is asserted. Future versions must be checked against actual files.

If the issue spans intake, reproduction, implementation and delivery, use the installed `rabbi-support-delivery-orchestrator` for those generic stages, plus [the focused evidence workflow](../../WORKFLOW.md).
