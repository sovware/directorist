# Social Login

Authenticate/register users through configured Google/Facebook providers.

Official catalog: [Social Login](https://directorist.com/product/directorist-social-login/). Catalog identity checked 2026-09-13.

Canonical investigation baseline: **directorist-social-login / master / 94b2eacbd6be5dc019141202abd1a32bf14bf515**. Selected the newest visible implementation branch tip, then retained default/development and local file differences for feature-level reconciliation; no branches were merged.

Observed plugin header: `Directorist - Social Login` — `2.1.4` ([directorist-social-login.php:1](https://github.com/sovware/directorist-social-login/blob/94b2eacbd6be5dc019141202abd1a32bf14bf515/directorist-social-login.php#L1)). Header/tag is an identifier, not source authority or release proof.

Read this identity page, then only the matching topic. The evidence indexes are searchable references, never required full reads.

## Topics

| Topic | Symptoms / feature boundary |
| --- | --- |
| [provider-auth](topics/provider-auth.md) | social login, google login, facebook login, oauth |
| [login-ui](topics/login-ui.md) | social button, google button missing, facebook button |

## Source and compatibility

[Source variants and branch deltas](source-variants.md) — read when installed files differ or changes cross branches.
[Hooks, guards and dependency evidence](contracts.md) — search for the issue hook/class, not every row.
[Dependency packages and runtime guards](dependencies.md) — inspect relevant external API/platform contract.
[Feature coverage classification](coverage.md) — feature-linked source vs infrastructure/support.
[Complete classified file inventory](files.md) — find a missing feature or inspect coverage.

Source inspection only. Functional and browser recipes are **not executed** by this mapping task. No local/client/CI/release compatibility is asserted. Future versions must be checked against actual files.

If the issue spans intake, reproduction, implementation and delivery, use the installed `rabbi-support-delivery-orchestrator` for those generic stages, plus [the focused evidence workflow](../../WORKFLOW.md).
