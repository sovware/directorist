# M-Pesa Payment Gateway: callback-recurring

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Callbacks must resolve the correct order and handle repeat events; pending polling, initial package binding and recurring schedule mapping have separate paths.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Replay success/failure/duplicate callbacks for two orders; test pending query and supported/unsupported plan intervals, cancellation and receipt status. Assert no duplicate payment/package.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Entry points and feature coverage

Read the canonical snapshot rows first. Local/default/development rows are available for variant comparison only. Every matching source file is listed with declarations; supporting styles/configuration may have no symbols.

| Snapshot / source | Functions or classes |
| --- | --- |
| mpesa-payment-gateway--main / [app/Http/Controllers/CheckoutController.php:1](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Http/Controllers/CheckoutController.php#L1) | `CheckoutController` (L11), `callback` (L12) |
| mpesa-payment-gateway--main / [app/Providers/CronServiceProvider.php:1](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Providers/CronServiceProvider.php#L1) | `CronServiceProvider` (L10), `boot` (L11), `register_interval` (L17), `schedule_event` (L26), `sync` (L34) |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:1](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L1) | `MpesaService` (L14), `__construct` (L18), `start_payment` (L20), `is_valid_callback_request` (L91), `handle_callback_payload` (L98), `render_checkout_fields` (L119), `validate_checkout_fields` (L189), `render_receipt_notice` (L206), `sync_pending_orders` (L236), `process_recurring_event_queue` (L313), `cancel_subscription` (L411), `standing_order_name` (L450), `plan_schedule` (L454), `handle_stk_callback` (L505), `handle_recurring_callback` (L585), `normalize_callback_metadata` (L647), `upsert_pending_payment` (L661), `mark_order_payment_paid` (L692), `mark_order_failed` (L725), `bind_package_subscription_after_initial_payment` (L744), `payment_transaction_exists` (L772), `amount_from_order` (L779), `subscription_amount_from_order` (L797), `is_recurring_order` (L813), `get_by_order_id` (L822), `is_trial_order` (L827), `get_by_order_id` (L832), `normalize_phone_number` (L837), `mask_phone_number` (L857), `get_order_state` (L867), `update_order_state` (L873), `add_pending_order` (L877), `remove_pending_order` (L893), `find_payload_value` (L912), `map_reference_to_order` (L944), `resolve_order_id_from_references` (L952), `reference_option_key` (L974), `log_error` (L978) |
| mpesa-payment-gateway--main / [config/app.php:1](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/config/app.php#L1) | `CronServiceProvider` (L57), `DirectoristServiceProvider` (L58), `SettingsServiceProvider` (L59), `EnsureIsUserAdmin` (L66), `User` (L76), `Post` (L129), `Comment` (L130), `Term` (L131), `TermTaxonomy` (L132) |
| mpesa-payment-gateway--main / [routes/ajax/api.php:1](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/routes/ajax/api.php#L1) | template / configuration / styling; inspect file |
| mpesa-payment-gateway--main / [routes/rest/api.php:1](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/routes/rest/api.php#L1) | `Route` (L9) |

## Data readers, writers and lifecycle

Keys/callback expressions below are extracted without stored values. `get` reads; `update/add/delete` writes; scheduled/remote calls are side effects. ORM repositories and schema definitions are linked as source even where literal-key extraction cannot resolve them.

| Snapshot / source | Operation and key |
| --- | --- |
| mpesa-payment-gateway--main / [app/Providers/CronServiceProvider.php:12](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Providers/CronServiceProvider.php#L12) | `add_filter('cron_schedules', [ $this, 'register_interval' ])` |
| mpesa-payment-gateway--main / [app/Providers/CronServiceProvider.php:13](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Providers/CronServiceProvider.php#L13) | `add_action(MpesaService::CRON_HOOK, [ $this, 'sync' ])` |
| mpesa-payment-gateway--main / [app/Providers/CronServiceProvider.php:14](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Providers/CronServiceProvider.php#L14) | `add_action('init', [ $this, 'schedule_event' ])` |
| mpesa-payment-gateway--main / [app/Providers/CronServiceProvider.php:27](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Providers/CronServiceProvider.php#L27) | `wp_next_scheduled(MpesaService::CRON_HOOK)` |
| mpesa-payment-gateway--main / [app/Providers/CronServiceProvider.php:31](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Providers/CronServiceProvider.php#L31) | `wp_schedule_event(time() + MINUTE_IN_SECONDS)` |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:237](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L237) | `get_option('directorist_mpesa_pending_orders')` |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:318](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L318) | `get_option('directorist_mpesa_recurring_events')` |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:381](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L381) | `create(write keys: (values omitted; inspect source for dynamic keys))` |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:406](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L406) | `update_option('directorist_mpesa_recurring_events')` |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:500](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L500) | `apply_filters('directorist_mpesa_plan_frequency')` |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:627](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L627) | `get_option('directorist_mpesa_recurring_events')` |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:640](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L640) | `update_option('directorist_mpesa_recurring_events')` |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:668](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L668) | `update(write keys: (values omitted; inspect source for dynamic keys))` |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:681](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L681) | `create(write keys: (values omitted; inspect source for dynamic keys))` |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:699](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L699) | `update(write keys: (values omitted; inspect source for dynamic keys))` |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:707](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L707) | `create(write keys: (values omitted; inspect source for dynamic keys))` |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:718](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L718) | `update(write keys: (values omitted; inspect source for dynamic keys))` |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:730](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L730) | `update(write keys: (values omitted; inspect source for dynamic keys))` |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:737](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L737) | `update(write keys: (values omitted; inspect source for dynamic keys))` |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:769](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L769) | `update(write keys: (values omitted; inspect source for dynamic keys))` |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:775](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L775) | `where('transaction_id')` |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:868](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L868) | `get_option('directorist_mpesa_order_' . $order_id)` |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:874](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L874) | `update_option('directorist_mpesa_order_' . $order_id)` |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:878](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L878) | `get_option('directorist_mpesa_pending_orders')` |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:886](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L886) | `update_option('directorist_mpesa_pending_orders')` |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:894](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L894) | `get_option('directorist_mpesa_pending_orders')` |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:900](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L900) | `update_option('directorist_mpesa_pending_orders')` |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:945](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L945) | `update_option($this->reference_option_key( $reference ))` |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:948](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L948) | `update_option('directorist_mpesa_checkout_' . sanitize_key( $reference ))` |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:960](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L960) | `get_option($this->reference_option_key( $reference ))` |
| mpesa-payment-gateway--main / [app/Services/MpesaService.php:963](https://github.com/sovware/directorist-mpesa-payment-gateway/blob/f304ecfa35049b5cc827b182f54dcbc048dc5bf3/app/Services/MpesaService.php#L963) | `get_option('directorist_mpesa_checkout_' . sanitize_key( $reference ))` |

## Core and integration context

Load [Core listing/form/query contracts](../../../core/listings.md) for a form/data/query issue, [rendering and builders](../../../core/rendering.md) for display, or [payment lifecycle](../../../core/payments.md) for orders. Do not load all three automatically.

Conditional integration candidates (load only when installed configuration or the cited hook/guard connects them):
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md)

For exact cross-repository hook matches, use `python3 docs/extensions/scripts/query.py hooks HOOK_NAME`; a same-name hook is only a candidate edge. For dynamic calls, inspect the referenced source and actual active callback list.
