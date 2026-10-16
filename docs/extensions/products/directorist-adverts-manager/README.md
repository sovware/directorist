# Directorist Ads Manager

Place ads at configured Directorist page and listing-loop positions.

Official catalog: [Directorist Ads Manager](https://directorist.com/product/directorist-adverts-manager/). Catalog identity checked 2026-09-13.

Canonical investigation baseline: **directorist-adverts-manager / v8-beta / b74a1c0372ec005708ea2bad386b30c3e439d402**. Selected the newest visible implementation branch tip, then retained default/development and local file differences for feature-level reconciliation; no branches were merged.

Observed plugin header: `Directorist - Ads Manager` — `2.3` ([directorist-adverts-manager.php:1](https://github.com/sovware/directorist-adverts-manager/blob/b74a1c0372ec005708ea2bad386b30c3e439d402/directorist-adverts-manager.php#L1)). Header/tag is an identifier, not source authority or release proof.

Read this identity page, then only the matching topic. The evidence indexes are searchable references, never required full reads.

## Topics

| Topic | Symptoms / feature boundary |
| --- | --- |
| [ad-placement](topics/ad-placement.md) | ads manager, advertisement, ad placement |
| [ad-shortcode](topics/ad-shortcode.md) | ad shortcode, ad widget, ad styling |

## Source and compatibility

[Source variants and branch deltas](source-variants.md) — read when installed files differ or changes cross branches.
[Hooks, guards and dependency evidence](contracts.md) — search for the issue hook/class, not every row.
[Dependency packages and runtime guards](dependencies.md) — inspect relevant external API/platform contract.
[Feature coverage classification](coverage.md) — feature-linked source vs infrastructure/support.
[Complete classified file inventory](files.md) — find a missing feature or inspect coverage.

Source inspection only. Functional and browser recipes are **not executed** by this mapping task. No local/client/CI/release compatibility is asserted. Future versions must be checked against actual files.

If the issue spans intake, reproduction, implementation and delivery, use the installed `rabbi-support-delivery-orchestrator` for those generic stages, plus [the focused evidence workflow](../../WORKFLOW.md).
