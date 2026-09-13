# Google reCAPTCHA

Apply Google challenge/token validation to selected Directorist public forms.

Official catalog: [Google reCAPTCHA](https://directorist.com/product/directorist-google-recaptcha/). Catalog identity checked 2026-09-13.

Canonical investigation baseline: **directorist-google-recaptcha / v3-support / ae5173102e9d6f4a69d52159d610bf45856de762**. Selected the newest visible implementation branch tip, then retained default/development and local file differences for feature-level reconciliation; no branches were merged.

Observed plugin header: `Directorist Google reCAPTCHA` — `2.2.1` ([directorist-google-recaptcha.php:1](https://github.com/sovware/directorist-google-recaptcha/blob/ae5173102e9d6f4a69d52159d610bf45856de762/directorist-google-recaptcha.php#L1)). Header/tag is an identifier, not source authority or release proof.

Read this identity page, then only the matching topic. The evidence indexes are searchable references, never required full reads.

## Topics

| Topic | Symptoms / feature boundary |
| --- | --- |
| [verification](topics/verification.md) | recaptcha, captcha, spam protection, v3 score |
| [captcha-render](topics/captcha-render.md) | captcha missing, captcha badge, captcha key |

## Source and compatibility

[Source variants and branch deltas](source-variants.md) — read when installed files differ or changes cross branches.
[Hooks, guards and dependency evidence](contracts.md) — search for the issue hook/class, not every row.
[Dependency packages and runtime guards](dependencies.md) — inspect relevant external API/platform contract.
[Feature coverage classification](coverage.md) — feature-linked source vs infrastructure/support.
[Complete classified file inventory](files.md) — find a missing feature or inspect coverage.

Source inspection only. Functional and browser recipes are **not executed** by this mapping task. No local/client/CI/release compatibility is asserted. Future versions must be checked against actual files.

If the issue spans intake, reproduction, implementation and delivery, use the installed `rabbi-support-delivery-orchestrator` for those generic stages, plus [the focused evidence workflow](../../WORKFLOW.md).
