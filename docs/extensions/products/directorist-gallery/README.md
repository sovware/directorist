# Image Gallery

Store listing image galleries, display them and import remote gallery attachments.

Official catalog: [Image Gallery](https://directorist.com/product/directorist-gallery/). Catalog identity checked 2026-09-13.

Canonical investigation baseline: **directorist-gallery / fix/gallery-import-cron-scheduling / 34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b**. Selected the newest visible implementation branch tip, then retained default/development and local file differences for feature-level reconciliation; no branches were merged.

Observed plugin header: `Directorist Gallery` — `2.2.2` ([bd-directorist-gallery.php:1](https://github.com/sovware/directorist-gallery/blob/34048acde9eba93c63d2eb7b66f5d5b1ce21ba3b/bd-directorist-gallery.php#L1)). Header/tag is an identifier, not source authority or release proof.

Read this identity page, then only the matching topic. The evidence indexes are searchable references, never required full reads.

## Topics

| Topic | Symptoms / feature boundary |
| --- | --- |
| [gallery-edit](topics/gallery-edit.md) | image gallery, gallery upload, gallery saving |
| [gallery-import](topics/gallery-import.md) | gallery cron, gallery import, gallery slider |

## Source and compatibility

[Source variants and branch deltas](source-variants.md) — read when installed files differ or changes cross branches.
[Hooks, guards and dependency evidence](contracts.md) — search for the issue hook/class, not every row.
[Dependency packages and runtime guards](dependencies.md) — inspect relevant external API/platform contract.
[Feature coverage classification](coverage.md) — feature-linked source vs infrastructure/support.
[Complete classified file inventory](files.md) — find a missing feature or inspect coverage.

Source inspection only. Functional and browser recipes are **not executed** by this mapping task. No local/client/CI/release compatibility is asserted. Future versions must be checked against actual files.

If the issue spans intake, reproduction, implementation and delivery, use the installed `rabbi-support-delivery-orchestrator` for those generic stages, plus [the focused evidence workflow](../../WORKFLOW.md).
