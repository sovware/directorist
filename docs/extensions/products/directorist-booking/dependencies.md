# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |
| [composer.json:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/composer.json#L1) | php | >=7.4 |
| [composer.json:1](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/composer.json#L1) | wpmvc/framework | 1.2.01 |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [directorist-booking.php:3](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/directorist-booking.php#L3) | defined | `'ABSPATH'` |
| [directorist-booking.php:60](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/directorist-booking.php#L60) | defined | `'BDB_VERSION'` |
| [directorist-booking.php:65](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/directorist-booking.php#L65) | defined | `'BDB_DIR'` |
| [directorist-booking.php:68](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/directorist-booking.php#L68) | defined | `'BDB_URL'` |
| [directorist-booking.php:71](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/directorist-booking.php#L71) | defined | `'BDB_FILE'` |
| [directorist-booking.php:73](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/directorist-booking.php#L73) | defined | `'BDB_BASE'` |
| [directorist-booking.php:76](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/directorist-booking.php#L76) | defined | `'BDB_TEXTDOMAIN'` |
| [directorist-booking.php:79](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/directorist-booking.php#L79) | defined | `'BDB_LANG_DIR'` |
| [directorist-booking.php:82](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/directorist-booking.php#L82) | defined | `'BDB_TEMPLATES_DIR'` |
| [app/Helpers/helper.php:3](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Helpers/helper.php#L3) | defined | `'ABSPATH'` |
| [app/Helpers/helper.php:45](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Helpers/helper.php#L45) | function_exists | `'bdb_date_time_wp_format'` |
| [app/Helpers/helper.php:87](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Helpers/helper.php#L87) | function_exists | `'bdb_day_fields'` |
| [app/Helpers/helper.php:114](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Helpers/helper.php#L114) | function_exists | `'bdb_create_required_pages'` |
| [app/Helpers/helper.php:143](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Helpers/helper.php#L143) | function_exists | `'bdb_booking_confirmation_page'` |
| [app/Helpers/helper.php:156](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Helpers/helper.php#L156) | function_exists | `'bdb_count_my_bookings'` |
| [app/Helpers/helper.php:165](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Helpers/helper.php#L165) | function_exists | `'bdb_count_my_bookings_by_status'` |
| [app/Helpers/helper.php:187](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Helpers/helper.php#L187) | function_exists | `'bdb_count_bookings'` |
| [app/Helpers/helper.php:282](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Helpers/helper.php#L282) | function_exists | `'directorist_get_order_by_id'` |
| [app/Helpers/helper.php:483](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Helpers/helper.php#L483) | function_exists | `'atbdp_check_booking_restriction'` |
| [app/Helpers/helper.php:492](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Helpers/helper.php#L492) | function_exists | `'searchForId'` |
| [app/Helpers/helper.php:538](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Helpers/helper.php#L538) | function_exists | `'dashboardPrice'` |
| [app/Helpers/helper.php:565](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Helpers/helper.php#L565) | function_exists | `'required'` |
| [app/Helpers/helper.php:662](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Helpers/helper.php#L662) | function_exists | `'bdb_get_payouts'` |
| [app/Helpers/helper.php:692](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Helpers/helper.php#L692) | function_exists | `'bdb_render_listing_rating'` |
| [app/Helpers/helper.php:698](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Helpers/helper.php#L698) | function_exists | `'directorist_get_listing_rating'` |
| [app/Helpers/helper.php:702](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Helpers/helper.php#L702) | function_exists | `'directorist_get_listing_review_count'` |
| [app/Helpers/helper.php:706](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Helpers/helper.php#L706) | class_exists | `'\Directorist\Review\Markup'` |
| [app/Helpers/helper.php:726](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Helpers/helper.php#L726) | function_exists | `'bdb_render_refund_policy'` |
| [app/Helpers/helper.php:766](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Helpers/helper.php#L766) | function_exists | `'directorist_enable_booking'` |
| [app/Helpers/helper.php:802](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Helpers/helper.php#L802) | function_exists | `'atbdp_check_booking_restriction'` |
| [app/Helpers/helper.php:810](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Helpers/helper.php#L810) | class_exists | `'\Directorist_Booking\WpMVC\View\View'` |
| [app/Providers/Calender.php:5](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Calender.php#L5) | defined | `"ABSPATH"` |
| [app/Providers/CustomPage.php:5](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/CustomPage.php#L5) | defined | `"ABSPATH"` |
| [app/Providers/Dashboard.php:5](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Dashboard.php#L5) | defined | `"ABSPATH"` |
| [app/Providers/Database.php:5](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Database.php#L5) | defined | `"ABSPATH"` |
| [app/Providers/Database.php:649](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Database.php#L649) | function_exists | `'directorist_get_user_contact'` |
| [app/Providers/FormBuilder.php:5](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/FormBuilder.php#L5) | defined | `"ABSPATH"` |
| [app/Providers/GoogleCalendar.php:5](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/GoogleCalendar.php#L5) | defined | `"ABSPATH"` |
| [app/Providers/GoogleCalendar.php:186](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/GoogleCalendar.php#L186) | wp_remote_post | `self::OAUTH_TOKEN_URL` |
| [app/Providers/GoogleCalendar.php:202](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/GoogleCalendar.php#L202) | wp_remote_retrieve_response_code | `$response` |
| [app/Providers/GoogleCalendar.php:207](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/GoogleCalendar.php#L207) | wp_remote_retrieve_body | `$response` |
| [app/Providers/GoogleCalendar.php:330](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/GoogleCalendar.php#L330) | wp_remote_post | `self::OAUTH_TOKEN_URL` |
| [app/Providers/GoogleCalendar.php:346](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/GoogleCalendar.php#L346) | wp_remote_retrieve_response_code | `$response` |
| [app/Providers/GoogleCalendar.php:352](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/GoogleCalendar.php#L352) | wp_remote_retrieve_body | `$response` |
| [app/Providers/GoogleCalendar.php:380](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/GoogleCalendar.php#L380) | wp_remote_get | `self::CALENDAR_LIST_API_URL` |
| [app/Providers/GoogleCalendar.php:393](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/GoogleCalendar.php#L393) | wp_remote_retrieve_response_code | `$response` |
| [app/Providers/GoogleCalendar.php:398](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/GoogleCalendar.php#L398) | wp_remote_retrieve_body | `$response` |
| [app/Providers/GoogleCalendar.php:460](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/GoogleCalendar.php#L460) | wp_remote_post | `self::CALENDAR_API_URL . '/freeBusy'` |
| [app/Providers/GoogleCalendar.php:489](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/GoogleCalendar.php#L489) | wp_remote_retrieve_body | `$response` |
| [app/Providers/GoogleCalendar.php:667](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/GoogleCalendar.php#L667) | wp_remote_post | `$url` |
| [app/Providers/GoogleCalendar.php:690](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/GoogleCalendar.php#L690) | wp_remote_retrieve_response_code | `$response` |
| [app/Providers/GoogleCalendar.php:702](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/GoogleCalendar.php#L702) | wp_remote_retrieve_body | `$response` |
| [app/Providers/GoogleCalendar.php:725](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/GoogleCalendar.php#L725) | wp_remote_request | `$url` |
| [app/Providers/MetaSubmission.php:5](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/MetaSubmission.php#L5) | defined | `"ABSPATH"` |
| [app/Providers/Payment.php:7](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Payment.php#L7) | defined | `"ABSPATH"` |
| [app/Providers/Payment.php:1002](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Payment.php#L1002) | function_exists | `'bdb_commission_system'` |
| [app/Providers/Settings.php:5](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Settings.php#L5) | defined | `"ABSPATH"` |
| [app/Providers/Widget.php:5](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Widget.php#L5) | defined | `"ABSPATH"` |
| [app/Providers/WooCommercePayment.php:5](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/WooCommercePayment.php#L5) | defined | `"ABSPATH"` |
| [app/Providers/Admin/Test.php:5](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Admin/Test.php#L5) | defined | `"ABSPATH"` |
| [app/Providers/Admin/TestProvider.php:5](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Admin/TestProvider.php#L5) | defined | `"ABSPATH"` |
| [app/Providers/Admin/UpdateServiceProvider.php:5](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Admin/UpdateServiceProvider.php#L5) | defined | `'ABSPATH'` |
| [app/Providers/Commission/Commission.php:5](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Commission/Commission.php#L5) | defined | `"ABSPATH"` |
| [app/Providers/Commission/Wallet.php:5](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Commission/Wallet.php#L5) | defined | `"ABSPATH"` |
| [app/Providers/Refund/AdminRefund.php:5](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Refund/AdminRefund.php#L5) | defined | `"ABSPATH"` |
| [app/Providers/Refund/UserRefund.php:5](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/app/Providers/Refund/UserRefund.php#L5) | defined | `"ABSPATH"` |
| [config/app.php:3](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/config/app.php#L3) | defined | `'ABSPATH'` |
| [enqueues/admin-enqueue.php:5](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/enqueues/admin-enqueue.php#L5) | defined | `'ABSPATH'` |
| [enqueues/frontend-enqueue.php:5](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/enqueues/frontend-enqueue.php#L5) | defined | `'ABSPATH'` |
| [resources/views/booking-confirmation.php:21](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/resources/views/booking-confirmation.php#L21) | function_exists | `'directorist_get_checkout_page_url'` |
| [resources/views/check-in-out.php:8](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/resources/views/check-in-out.php#L8) | defined | `'ABSPATH'` |
| [resources/views/index.php:3](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/resources/views/index.php#L3) | defined | `'ABSPATH'` |
| [resources/views/number-of-guest.php:8](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/resources/views/number-of-guest.php#L8) | defined | `'ABSPATH'` |
| [resources/views/user-refund-form.php:3](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/resources/views/user-refund-form.php#L3) | defined | `'ABSPATH'` |
| [resources/views/refund-policy/refund-policy.php:8](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/resources/views/refund-policy/refund-policy.php#L8) | defined | `'ABSPATH'` |
| [resources/views/refund-policy/refund-request.php:11](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/resources/views/refund-policy/refund-request.php#L11) | defined | `'ABSPATH'` |
| [resources/views/refund-policy/requested-listings.php:11](https://github.com/sovware/directorist-booking/blob/3dac671d62a54fb22220d790d5b9fbad990717d9/resources/views/refund-policy/requested-listings.php#L11) | defined | `'ABSPATH'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
