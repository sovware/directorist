# Booking (Reservation & Appointment): calendar-dashboard

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

The newer branch adds Google Calendar OAuth/settings, event insertion/deletion and conflict checks, plus owner/customer dashboard filters. This is distinct from local slot availability.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Use a test calendar with authorized credentials, connect/disconnect and create/cancel a booking. Check conflict behavior, calendar IDs, event deletion and filtered owner/customer counts. Keep OAuth tokens out of evidence.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Relevant source entry points

Start with these baseline files, then query exact symbols/keys. Full coverage is retained in the linked ledger; no ledger-wide read is required.

- [app/Http/Controllers/BookingController.php:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Http/Controllers/BookingController.php#L1) — 4 declarations
- [app/Providers/BDB_Widget_Template.php:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/BDB_Widget_Template.php#L1) — 5 declarations
- [app/Providers/Calender.php:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Calender.php#L1) — 16 declarations
- [app/Providers/Commission/Wallet.php:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Commission/Wallet.php#L1) — 6 declarations
- [app/Providers/CustomPage.php:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/CustomPage.php#L1) — 3 declarations
- [app/Providers/Dashboard.php:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Dashboard.php#L1) — 23 declarations
- [app/Providers/FormBuilder.php:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/FormBuilder.php#L1) — 12 declarations
- [app/Providers/GoogleCalendar.php:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/GoogleCalendar.php#L1) — 18 declarations

[Complete topic source/data ledger](calendar-dashboard-evidence.md) (search only the relevant symbol/key).

## Expand only when indicated

Form/query → [Core listings](../../../core/listings.md). Rendering → [Core rendering](../../../core/rendering.md). Order/payment → [Core payments](../../../core/payments.md).
- [directorist-woocommerce-pricing-plans](../../directorist-woocommerce-pricing-plans/README.md) — only if active configuration or source connects it.
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md) — only if active configuration or source connects it.
