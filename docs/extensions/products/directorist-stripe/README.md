# Stripe Payment Gateway

Collect card/Stripe payments and synchronize checkout, webhooks and subscriptions with Directorist orders.

Official catalog: [Stripe Payment Gateway](https://directorist.com/product/directorist-stripe/). Catalog identity checked 2026-09-13.

Canonical investigation baseline: **directorist-stripe-new / master / 498c48755f4168fdfdcaa955fb817647287a95e1**. Selected the newest visible implementation branch tip, then retained default/development and local file differences for feature-level reconciliation; no branches were merged.

Observed plugin header: `Directorist - Stripe Payment Gateway` — `3.0.1` ([directorist-stripe.php:1](https://github.com/sovware/directorist-stripe-new/blob/498c48755f4168fdfdcaa955fb817647287a95e1/directorist-stripe.php#L1)). Header/tag is an identifier, not source authority or release proof.

Read this identity page, then only the matching topic. The evidence indexes are searchable references, never required full reads.

## Topics

| Topic | Symptoms / feature boundary |
| --- | --- |
| [checkout-tax](topics/checkout-tax.md) | stripe, card payment, sepa, stripe tax, stripe coupon |
| [webhooks](topics/webhooks.md) | stripe webhook, stripe renewal, stripe cancellation |

## Source and compatibility

[Source variants and branch deltas](source-variants.md) — read when installed files differ or changes cross branches.
[Hooks, guards and dependency evidence](contracts.md) — search for the issue hook/class, not every row.
[Dependency packages and runtime guards](dependencies.md) — inspect relevant external API/platform contract.
[Feature coverage classification](coverage.md) — feature-linked source vs infrastructure/support.
[Complete classified file inventory](files.md) — find a missing feature or inspect coverage.

Source inspection only. Functional and browser recipes are **not executed** by this mapping task. No local/client/CI/release compatibility is asserted. Future versions must be checked against actual files.

If the issue spans intake, reproduction, implementation and delivery, use the installed `rabbi-support-delivery-orchestrator` for those generic stages, plus [the focused evidence workflow](../../WORKFLOW.md).
