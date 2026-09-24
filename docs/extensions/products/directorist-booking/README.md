# Booking (Reservation & Appointment)

Accept listing reservations, service appointments and events, with calendars, payments and owner management.

Official catalog: [Booking (Reservation & Appointment)](https://directorist.com/product/directorist-booking/). Catalog identity checked 2026-09-13.

Canonical investigation baseline: **directorist-booking / fix/client-issue-3257-guest-confirmation / 3dac671d62a54fb22220d790d5b9fbad990717d9**. Selected the newest visible implementation branch tip, then retained default/development and local file differences for feature-level reconciliation; no branches were merged.

Observed plugin header: `Directorist - Booking` — `3.1.0` ([directorist-booking.php:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/directorist-booking.php#L1)). Header/tag is an identifier, not source authority or release proof.

Read this identity page, then only the matching topic. The evidence indexes are searchable references, never required full reads.

## Topics

| Topic | Symptoms / feature boundary |
| --- | --- |
| [availability](topics/availability.md) | booking, reservation, appointment, time slots, guest confirmation, calendar |
| [booking-payments](topics/booking-payments.md) | booking payment, booking refund, booking commission, wallet payout |
| [calendar-dashboard](topics/calendar-dashboard.md) | google calendar, booking oauth, calendar conflict, booking dashboard |

## Source and compatibility

[Source variants and branch deltas](source-variants.md) — read when installed files differ or changes cross branches.
[Hooks, guards and dependency evidence](contracts.md) — search for the issue hook/class, not every row.
[Dependency packages and runtime guards](dependencies.md) — inspect relevant external API/platform contract.
[Feature coverage classification](coverage.md) — feature-linked source vs infrastructure/support.
[Complete classified file inventory](files.md) — find a missing feature or inspect coverage.

Source inspection only. Functional and browser recipes are **not executed** by this mapping task. No local/client/CI/release compatibility is asserted. Future versions must be checked against actual files.

If the issue spans intake, reproduction, implementation and delivery, use the installed `rabbi-support-delivery-orchestrator` for those generic stages, plus [the focused evidence workflow](../../WORKFLOW.md).
