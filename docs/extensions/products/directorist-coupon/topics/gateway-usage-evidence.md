# Directorist Coupon: gateway-usage

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Coupon application to Stripe session and local order items must agree; update usage only according to the actual hook lifecycle.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Complete and abandon sandbox checkout, replay completion and compare usage count/order receipt. Verify first invoice vs renewal behavior explicitly before promising recurring discounts.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Entry points and feature coverage

Read the canonical snapshot rows first. Local/default/development rows are available for variant comparison only. Every matching source file is listed with declarations; supporting styles/configuration may have no symbols.

| Snapshot / source | Functions or classes |
| --- | --- |
| coupon--development / [Inc/Controller/Base/CouponHandler.php:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/CouponHandler.php#L1) | `SWBDPCouponHandler` (L12), `register` (L13), `register_rest_routes` (L19), `rest_permission_check` (L40), `validate_coupon_rest` (L44), `swbdpc_apply_discount_new_checkout` (L71), `swbdpc_coupon_code_input_field` (L104), `validate_coupon_from_request` (L113), `get_checkout_subtotal` (L122), `format_rate` (L130) |
| coupon--development / [directorist-coupon.php:1](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/directorist-coupon.php#L1) | `swbdpc_get_version_from_file_content` (L19), `swbdpc_define_plugin_constants` (L37), `SWBDPCoupon` (L83), `instance` (L91), `update_controller` (L104), `get_version_from_file_content` (L119), `get_version_from_content` (L123), `define_constant` (L138), `instance_plugin_classes` (L147), `SWBDPCoupon` (L186), `swbdp_coupon_plugin_activate` (L195), `directorist_coupon_incompatibility_notice` (L217), `directorist_coupon_core_version_requirment` (L236), `directorist_coupon_is_compaitable` (L243) |
| coupon--main / [Inc/Controller/Base/CouponHandler.php:1](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/Inc/Controller/Base/CouponHandler.php#L1) | `SWBDPCouponHandler` (L11), `register` (L17), `directorist_stripe_gateway_total` (L36), `add_coupon_to_stripe_checkout_session` (L54), `swbdpc_coupon_code_input_field` (L91), `swbdpc_calculate_after_discount_total_amount` (L107), `update_coupon_uses` (L141) |
| coupon--main / [directorist-coupon.php:1](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/directorist-coupon.php#L1) | `SWBDPCoupon` (L36), `instance` (L51), `update_controller` (L67), `order_items` (L82), `get_version_from_file_content` (L86), `get_version_from_content` (L99), `define_constant` (L114), `instance_plugin_classes` (L141), `SWBDPCoupon` (L184), `swbdp_coupon_plugin_activate` (L195), `swbdp_coupon_plugin_deactivate` (L219), `directorist_is_plugin_active` (L226), `directorist_is_plugin_active_for_network` (L232) |

## Data readers, writers and lifecycle

Keys/callback expressions below are extracted without stored values. `get` reads; `update/add/delete` writes; scheduled/remote calls are side effects. ORM repositories and schema definitions are linked as source even where literal-key extraction cannot resolve them.

| Snapshot / source | Operation and key |
| --- | --- |
| coupon--development / [Inc/Controller/Base/CouponHandler.php:14](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/CouponHandler.php#L14) | `add_action('atbdp_before_checkout_table', array( $this, 'swbdpc_coupon_code_input_field' ))` |
| coupon--development / [Inc/Controller/Base/CouponHandler.php:15](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/CouponHandler.php#L15) | `add_action('rest_api_init', array( $this, 'register_rest_routes' ))` |
| coupon--development / [Inc/Controller/Base/CouponHandler.php:16](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/CouponHandler.php#L16) | `add_action('directorist_checkout_create_order', array( $this, 'swbdpc_apply_discount_new_checkout' ))` |
| coupon--development / [Inc/Controller/Base/CouponHandler.php:20](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/CouponHandler.php#L20) | `register_rest_route('directorist-coupon/v1', '/coupons/validate')` |
| coupon--development / [Inc/Controller/Base/CouponHandler.php:127](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/Inc/Controller/Base/CouponHandler.php#L127) | `apply_filters('directorist_checkout_subtotal')` |
| coupon--development / [directorist-coupon.php:98](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/directorist-coupon.php#L98) | `add_action('admin_init', [ self::$instance, 'update_controller' ])` |
| coupon--development / [directorist-coupon.php:105](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/directorist-coupon.php#L105) | `get_user_meta(get_current_user_id(), '_plugins_available_in_subscriptions')` |
| coupon--development / [directorist-coupon.php:205](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/directorist-coupon.php#L205) | `get_option('swbdp_coupon_installed')` |
| coupon--development / [directorist-coupon.php:208](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/directorist-coupon.php#L208) | `update_option('swbdp_coupon_installed')` |
| coupon--development / [directorist-coupon.php:212](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/directorist-coupon.php#L212) | `update_option('swbdp_coupon_version')` |
| coupon--development / [directorist-coupon.php:215](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/directorist-coupon.php#L215) | `register_activation_hook(__FILE__)` |
| coupon--development / [directorist-coupon.php:218](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/directorist-coupon.php#L218) | `add_action('admin_notices', function () use ( $required_plugin, $required_version, $current_version ): void { printf( '<div class="notice notice-error"><p>%s</p></div>', wp_kses_post( sprintf( __( '<strong>Directorist - Coupon</strong> requires %1$s version <strong>%2)` |
| coupon--development / [directorist-coupon.php:247](https://github.com/sovware/directorist-coupon/blob/d98770d6b7dd98122f1be8fd0cd2038c25abe687/directorist-coupon.php#L247) | `add_action('plugins_loaded', function() { $core_version_requirment = directorist_coupon_core_version_requirment(); $current_core_version = $core_version_requirment['current_version']; $required_core_version = $core_version_requirment['required_version']; if ( ! directo)` |
| coupon--main / [Inc/Controller/Base/CouponHandler.php:20](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/Inc/Controller/Base/CouponHandler.php#L20) | `add_action('atbdp_before_checkout_table', array( $this, 'swbdpc_coupon_code_input_field' ))` |
| coupon--main / [Inc/Controller/Base/CouponHandler.php:22](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/Inc/Controller/Base/CouponHandler.php#L22) | `add_filter('atbdp_order_amount', array( $this, 'swbdpc_calculate_after_discount_total_amount' ))` |
| coupon--main / [Inc/Controller/Base/CouponHandler.php:24](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/Inc/Controller/Base/CouponHandler.php#L24) | `add_filter('directorist_stripe_checkout_session_args', array( $this, 'add_coupon_to_stripe_checkout_session' ))` |
| coupon--main / [Inc/Controller/Base/CouponHandler.php:25](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/Inc/Controller/Base/CouponHandler.php#L25) | `add_filter('directorist_stripe_gateway_total', array( $this, 'directorist_stripe_gateway_total' ))` |
| coupon--main / [Inc/Controller/Base/CouponHandler.php:59](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/Inc/Controller/Base/CouponHandler.php#L59) | `get_directorist_option('payment_currency')` |
| coupon--main / [Inc/Controller/Base/CouponHandler.php:59](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/Inc/Controller/Base/CouponHandler.php#L59) | `get_directorist_option('g_currency')` |
| coupon--main / [Inc/Controller/Base/CouponHandler.php:63](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/Inc/Controller/Base/CouponHandler.php#L63) | `create(write keys: duration, amount_off, currency (values omitted; inspect source for dynamic keys))` |
| coupon--main / [Inc/Controller/Base/CouponHandler.php:114](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/Inc/Controller/Base/CouponHandler.php#L114) | `get_post_meta($order_id, '_listing_id')` |
| coupon--main / [Inc/Controller/Base/CouponHandler.php:115](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/Inc/Controller/Base/CouponHandler.php#L115) | `get_post_meta($order_id, '_fm_plan_ordered')` |
| coupon--main / [Inc/Controller/Base/CouponHandler.php:116](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/Inc/Controller/Base/CouponHandler.php#L116) | `get_post_meta($listing_id, '_fm_plans')` |
| coupon--main / [Inc/Controller/Base/CouponHandler.php:127](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/Inc/Controller/Base/CouponHandler.php#L127) | `update_post_meta($order_id, '_discount')` |
| coupon--main / [Inc/Controller/Base/CouponHandler.php:128](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/Inc/Controller/Base/CouponHandler.php#L128) | `update_post_meta($order_id, '_coupon')` |
| coupon--main / [Inc/Controller/Base/CouponHandler.php:142](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/Inc/Controller/Base/CouponHandler.php#L142) | `get_user_meta($user_id, 'swbdpc_coupon_usages')` |
| coupon--main / [Inc/Controller/Base/CouponHandler.php:143](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/Inc/Controller/Base/CouponHandler.php#L143) | `get_post_meta($user_id, 'swbdpc_current_user_count')` |
| coupon--main / [Inc/Controller/Base/CouponHandler.php:170](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/Inc/Controller/Base/CouponHandler.php#L170) | `update_user_meta($user_id, 'swbdpc_coupon_usages')` |
| coupon--main / [Inc/Controller/Base/CouponHandler.php:171](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/Inc/Controller/Base/CouponHandler.php#L171) | `update_post_meta($coupon_id, 'swbdpc_current_user_count')` |
| coupon--main / [directorist-coupon.php:59](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/directorist-coupon.php#L59) | `add_filter('atpp_order_items', [self::$instance, 'order_items'])` |
| coupon--main / [directorist-coupon.php:60](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/directorist-coupon.php#L60) | `add_action('admin_init', [ self::$instance, 'update_controller' ])` |
| coupon--main / [directorist-coupon.php:68](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/directorist-coupon.php#L68) | `get_user_meta(get_current_user_id(), '_plugins_available_in_subscriptions')` |
| coupon--main / [directorist-coupon.php:198](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/directorist-coupon.php#L198) | `get_option('active_plugins')` |
| coupon--main / [directorist-coupon.php:201](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/directorist-coupon.php#L201) | `get_option('swbdp_coupon_installed')` |
| coupon--main / [directorist-coupon.php:203](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/directorist-coupon.php#L203) | `update_option('swbdp_coupon_installed')` |
| coupon--main / [directorist-coupon.php:206](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/directorist-coupon.php#L206) | `update_option('swbdp_coupon_version')` |
| coupon--main / [directorist-coupon.php:210](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/directorist-coupon.php#L210) | `register_activation_hook(__FILE__)` |
| coupon--main / [directorist-coupon.php:223](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/directorist-coupon.php#L223) | `register_deactivation_hook(__FILE__)` |
| coupon--main / [directorist-coupon.php:227](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/directorist-coupon.php#L227) | `get_option('active_plugins')` |
| coupon--main / [directorist-coupon.php:237](https://github.com/sovware/directorist-coupon/blob/c98c9236247e4f3075732cc345a729cade8f0103/directorist-coupon.php#L237) | `get_site_option('active_sitewide_plugins')` |

## Core and integration context

Load [Core listing/form/query contracts](../../../core/listings.md) for a form/data/query issue, [rendering and builders](../../../core/rendering.md) for display, or [payment lifecycle](../../../core/payments.md) for orders. Do not load all three automatically.

Conditional integration candidates (load only when installed configuration or the cited hook/guard connects them):
- [directorist-stripe](../../directorist-stripe/README.md)
- [directorist-pricing-plans](../../directorist-pricing-plans/README.md)
- [directorist-gamipress-integration](../../directorist-gamipress-integration/README.md)

For exact cross-repository hook matches, use `python3 docs/extensions/scripts/query.py hooks HOOK_NAME`; a same-name hook is only a candidate edge. For dynamic calls, inspect the referenced source and actual active callback list.
