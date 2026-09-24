# Booking (Reservation & Appointment): availability

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Availability calculation, slot capacity, guest confirmation and owner status changes are separate paths. Confirm the configured booking type before comparing dates or prices.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Create service slots and event capacity; attempt two guests for the last place, overlapping dates, timezone boundary and invalid confirmation. Inspect stored reservation/status and both customer/owner calendars.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Http/Controllers/BookingController.php:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Http/Controllers/BookingController.php#L1) — 4 declarations
- [app/Providers/Calender.php:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Calender.php#L1) — 16 declarations
- [app/Providers/Commission/Commission.php:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Commission/Commission.php#L1) — 19 declarations
- [app/Providers/Dashboard.php:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Dashboard.php#L1) — 23 declarations
- [app/Providers/Database.php:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Database.php#L1) — 28 declarations
- [app/Providers/FormBuilder.php:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/FormBuilder.php#L1) — 12 declarations
- [app/Providers/GoogleCalendar.php:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/GoogleCalendar.php#L1) — 18 declarations
- [app/Providers/MenuServiceProvider.php:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/MenuServiceProvider.php#L1) — 4 declarations

[Complete topic source/data ledger](availability-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-woocommerce-pricing-plans](../../directorist-woocommerce-pricing-plans/README.md) — only if active configuration or source connects it.
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md) — only if active configuration or source connects it.
