# Live Chat

Provide listing-linked private conversations and dashboard chat history.

Official catalog: [Live Chat](https://directorist.com/product/directorist-live-chat/). Catalog identity checked 2026-09-13.

Canonical investigation baseline: **directorist-live-chat / fix/live-chat-visibility-socket-fallback / 3a48f59612eb61ad2fc6308063870e59c02fdb34**. Selected the newest visible implementation branch tip, then retained default/development and local file differences for feature-level reconciliation; no branches were merged.

Observed plugin header: `Directorist - Live Chat` — `2.5.0` ([directorist-live-chat.php:1](https://github.com/sovware/directorist-live-chat/blob/3a48f59612eb61ad2fc6308063870e59c02fdb34/directorist-live-chat.php#L1)). Header/tag is an identifier, not source authority or release proof.

Read this identity page, then only the matching topic. The evidence indexes are searchable references, never required full reads.

## Topics

| Topic | Symptoms / feature boundary |
| --- | --- |
| [conversation](topics/conversation.md) | live chat, chat history, message owner |
| [socket-delivery](topics/socket-delivery.md) | io is not defined, socket, chat messages not sending |

## Source and compatibility

[Source variants and branch deltas](source-variants.md) — read when installed files differ or changes cross branches.
[Hooks, guards and dependency evidence](contracts.md) — search for the issue hook/class, not every row.
[Dependency packages and runtime guards](dependencies.md) — inspect relevant external API/platform contract.
[Feature coverage classification](coverage.md) — feature-linked source vs infrastructure/support.
[Complete classified file inventory](files.md) — find a missing feature or inspect coverage.

Source inspection only. Functional and browser recipes are **not executed** by this mapping task. No local/client/CI/release compatibility is asserted. Future versions must be checked against actual files.

If the issue spans intake, reproduction, implementation and delivery, use the installed `rabbi-support-delivery-orchestrator` for those generic stages, plus [the focused evidence workflow](../../WORKFLOW.md).
