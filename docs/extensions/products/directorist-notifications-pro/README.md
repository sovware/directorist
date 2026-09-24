# Directorist Notifications Pro

Deliver browser push notifications for listing/order events and user preferences.

Official catalog: [Directorist Notifications Pro](https://directorist.com/product/directorist-notifications-pro/). Catalog identity checked 2026-09-13.

Canonical investigation baseline: **directorist-notifications-pro / main / b9b5c721c27d55082f25cf0ea00b74c44008020a**. Selected the newest visible implementation branch tip, then retained default/development and local file differences for feature-level reconciliation; no branches were merged.

Observed plugin header: `Directorist Notifications Pro` — `1.0.0` ([directorist-notifications-pro.php:1](https://github.com/sovware/directorist-notifications-pro/blob/b9b5c721c27d55082f25cf0ea00b74c44008020a/directorist-notifications-pro.php#L1)). Header/tag is an identifier, not source authority or release proof.

Read this identity page, then only the matching topic. The evidence indexes are searchable references, never required full reads.

## Topics

| Topic | Symptoms / feature boundary |
| --- | --- |
| [subscription](topics/subscription.md) | push notification, browser permission, service worker, vapid |
| [events-delivery](topics/events-delivery.md) | push duplicate, push missing, notification log |

## Source and compatibility

[Source variants and branch deltas](source-variants.md) — read when installed files differ or changes cross branches.
[Hooks, guards and dependency evidence](contracts.md) — search for the issue hook/class, not every row.
[Dependency packages and runtime guards](dependencies.md) — inspect relevant external API/platform contract.
[Feature coverage classification](coverage.md) — feature-linked source vs infrastructure/support.
[Complete classified file inventory](files.md) — find a missing feature or inspect coverage.

Source inspection only. Functional and browser recipes are **not executed** by this mapping task. No local/client/CI/release compatibility is asserted. Future versions must be checked against actual files.

If the issue spans intake, reproduction, implementation and delivery, use the installed `rabbi-support-delivery-orchestrator` for those generic stages, plus [the focused evidence workflow](../../WORKFLOW.md).
