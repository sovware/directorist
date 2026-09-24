# Claim Listing

Let a business owner request ownership, support approval/payment flows, and display verified status.

Official catalog: [Claim Listing](https://directorist.com/product/directorist-claim-listing/). Catalog identity checked 2026-09-13.

Canonical investigation baseline: **directorist-claim-listing / fix/claimed-badge-tooltip / c0f000fef79d885c69881a38947e22fecd989eba**. Local claimed-badge branch contains later changes than the newest visible Sovware tip; canonical local files are fingerprinted and remote differences retained.

Observed plugin header: `Directorist - Claim Listing` — `2.6.0` ([directorist-claim-listing.php:1](</Users/rabbiislamrony/Local Sites/directorist-core/app/public/wp-content/plugins/directorist-claim-listing/directorist-claim-listing.php:1>)). Header/tag is an identifier, not source authority or release proof.

Read this identity page, then only the matching topic. The evidence indexes are searchable references, never required full reads.

## Topics

| Topic | Symptoms / feature boundary |
| --- | --- |
| [claim-approval](topics/claim-approval.md) | claim listing, claim button, ownership, duplicate claim, claim approval |
| [paid-claim-badge](topics/paid-claim-badge.md) | paid claim, claimed badge, verified badge, claim tooltip |

## Source and compatibility

[Source variants and branch deltas](source-variants.md) — read when installed files differ or changes cross branches.
[Hooks, guards and dependency evidence](contracts.md) — search for the issue hook/class, not every row.
[Dependency packages and runtime guards](dependencies.md) — inspect relevant external API/platform contract.
[Feature coverage classification](coverage.md) — feature-linked source vs infrastructure/support.
[Complete classified file inventory](files.md) — find a missing feature or inspect coverage.

Source inspection only. Functional and browser recipes are **not executed** by this mapping task. No local/client/CI/release compatibility is asserted. Future versions must be checked against actual files.

If the issue spans intake, reproduction, implementation and delivery, use the installed `rabbi-support-delivery-orchestrator` for those generic stages, plus [the focused evidence workflow](../../WORKFLOW.md).
