# Gamipress Integration: redeem-coupon

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Coupon redemption bridges GamiPress points and Directorist Coupon state; balance and coupon uses must stay consistent.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Redeem with sufficient/insufficient points, repeat request and use/expire coupon. Check both point deduction and coupon ownership/usage.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Entry points and feature coverage

Read the canonical snapshot rows first. Local/default/development rows are available for variant comparison only. Every matching source file is listed with declarations; supporting styles/configuration may have no symbols.

| Snapshot / source | Functions or classes |
| --- | --- |
| gamipress-integration--master / [assets/js/script.js:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/assets/js/script.js#L1) | template / configuration / styling; inspect file |
| gamipress-integration--master / [includes/class-coupon-manager.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-coupon-manager.php#L1) | `Coupon_Manager` (L13), `init` (L20), `clean_user_coupon` (L28), `update_coupon_stats` (L41), `process_claim_discount` (L61), `get_discount_type` (L127) |
| gamipress-integration--master / [includes/class-settings.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-settings.php#L1) | `Settings` (L11), `init` (L13), `get_coupon_fields` (L18), `register_fields` (L95), `register_panel` (L102) |
| gamipress-integration--master / [includes/class-utils.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-utils.php#L1) | `Utils` (L11), `is_listing` (L13), `get_total_points` (L18), `is_coupon_extension_enabled` (L53), `is_discount_coupon_enabled` (L57) |

## Data readers, writers and lifecycle

Keys/callback expressions below are extracted without stored values. `get` reads; `update/add/delete` writes; scheduled/remote calls are side effects. ORM repositories and schema definitions are linked as source even where literal-key extraction cannot resolve them.

| Snapshot / source | Operation and key |
| --- | --- |
| gamipress-integration--master / [includes/class-coupon-manager.php:21](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-coupon-manager.php#L21) | `add_action('wp_ajax_' . self::ACTION, array( __CLASS__, 'process_claim_discount' ))` |
| gamipress-integration--master / [includes/class-coupon-manager.php:22](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-coupon-manager.php#L22) | `add_action('atbdp_order_created', array( __CLASS__, 'update_coupon_stats' ))` |
| gamipress-integration--master / [includes/class-coupon-manager.php:24](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-coupon-manager.php#L24) | `add_action('trashed_post', array( __CLASS__, 'clean_user_coupon' ))` |
| gamipress-integration--master / [includes/class-coupon-manager.php:25](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-coupon-manager.php#L25) | `add_action('before_delete_post', array( __CLASS__, 'clean_user_coupon' ))` |
| gamipress-integration--master / [includes/class-coupon-manager.php:53](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-coupon-manager.php#L53) | `get_directorist_option('gamipress_discount_points_required')` |
| gamipress-integration--master / [includes/class-coupon-manager.php:54](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-coupon-manager.php#L54) | `get_user_meta(get_current_user_id(), 'directorist_gamipress_used_points')` |
| gamipress-integration--master / [includes/class-coupon-manager.php:57](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-coupon-manager.php#L57) | `update_user_meta(get_current_user_id(), 'directorist_gamipress_used_points')` |
| gamipress-integration--master / [includes/class-coupon-manager.php:58](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-coupon-manager.php#L58) | `delete_user_meta(get_current_user_id(), 'directorist_gamipress_coupon_id')` |
| gamipress-integration--master / [includes/class-coupon-manager.php:77](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-coupon-manager.php#L77) | `get_user_meta(get_current_user_id(), 'directorist_gamipress_coupon_id')` |
| gamipress-integration--master / [includes/class-coupon-manager.php:80](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-coupon-manager.php#L80) | `get_post_meta($coupon_id, 'swbdpc_coupon_code')` |
| gamipress-integration--master / [includes/class-coupon-manager.php:86](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-coupon-manager.php#L86) | `get_directorist_option('gamipress_discount_points_required')` |
| gamipress-integration--master / [includes/class-coupon-manager.php:87](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-coupon-manager.php#L87) | `get_user_meta(get_current_user_id(), 'directorist_gamipress_used_points')` |
| gamipress-integration--master / [includes/class-coupon-manager.php:110](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-coupon-manager.php#L110) | `get_directorist_option('gamipress_discount_amount')` |
| gamipress-integration--master / [includes/class-coupon-manager.php:119](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-coupon-manager.php#L119) | `update_user_meta(get_current_user_id(), 'directorist_gamipress_coupon_id')` |
| gamipress-integration--master / [includes/class-coupon-manager.php:133](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-coupon-manager.php#L133) | `get_directorist_option('gamipress_discount_type')` |
| gamipress-integration--master / [includes/class-settings.php:14](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-settings.php#L14) | `add_action('atbdp_listing_type_settings_layout', array( __CLASS__, 'register_panel' ))` |
| gamipress-integration--master / [includes/class-settings.php:15](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-settings.php#L15) | `add_filter('atbdp_listing_type_settings_field_list', array( __CLASS__, 'register_fields' ))` |
| gamipress-integration--master / [includes/class-utils.php:58](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-utils.php#L58) | `get_directorist_option('gamipress_enable_discount_coupon')` |

## Core and integration context

Load [Core listing/form/query contracts](../../../core/listings.md) for a form/data/query issue, [rendering and builders](../../../core/rendering.md) for display, or [payment lifecycle](../../../core/payments.md) for orders. Do not load all three automatically.

Conditional integration candidates (load only when installed configuration or the cited hook/guard connects them):
- [directorist-coupon](../../directorist-coupon/README.md)

For exact cross-repository hook matches, use `python3 docs/extensions/scripts/query.py hooks HOOK_NAME`; a same-name hook is only a candidate edge. For dynamic calls, inspect the referenced source and actual active callback list.
