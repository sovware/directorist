# Gamipress Integration: points

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Listing/review/favorite/order events feed GamiPress rules; event occurrence and rule eligibility are separate.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Configure one rule each for publish/review/favorite, trigger and repeat, inspect points/log and unrelated user balance.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Entry points and feature coverage

Read the canonical snapshot rows first. Local/default/development rows are available for variant comparison only. Every matching source file is listed with declarations; supporting styles/configuration may have no symbols.

| Snapshot / source | Functions or classes |
| --- | --- |
| gamipress-integration--master / [assets/js/admin-script.js:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/assets/js/admin-script.js#L1) | template / configuration / styling; inspect file |
| gamipress-integration--master / [includes/class-assets.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-assets.php#L1) | `Assets` (L11), `init` (L13), `enqueue_frontend_scripts` (L18), `enqueue_admin_scripts` (L40) |
| gamipress-integration--master / [includes/class-author.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-author.php#L1) | `Author` (L11), `init` (L13), `render` (L17) |
| gamipress-integration--master / [includes/class-dashboard.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-dashboard.php#L1) | `Dashboard` (L11), `init` (L13), `register_tab` (L17), `get_title` (L27), `get_content` (L31) |
| gamipress-integration--master / [includes/class-listeners.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-listeners.php#L1) | `Listeners` (L16), `init` (L18), `order_created_listener` (L39), `listing_views_count_updated_listener` (L57), `review_listener` (L73), `trigger_listing_become_popular` (L125), `transition_post_status_listener` (L154), `delete_listing_listener` (L203), `listing_report_listener` (L221), `user_favorites_listener` (L239) |
| gamipress-integration--master / [includes/class-requirements.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-requirements.php#L1) | `Requirements` (L11), `init` (L13), `requirement_object` (L27), `register_ui_fields` (L44), `ajax_update` (L65) |
| gamipress-integration--master / [includes/class-rules-engine.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-rules-engine.php#L1) | `Rules_Engine` (L13), `init` (L15), `user_meets_points_requirement` (L19) |
| gamipress-integration--master / [includes/class-triggers.php:1](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-triggers.php#L1) | `Triggers` (L14), `init` (L16), `register_activity_triggers` (L29), `log_event_trigger_meta_data` (L47), `log_extra_data_fields` (L88) |

## Data readers, writers and lifecycle

Keys/callback expressions below are extracted without stored values. `get` reads; `update/add/delete` writes; scheduled/remote calls are side effects. ORM repositories and schema definitions are linked as source even where literal-key extraction cannot resolve them.

| Snapshot / source | Operation and key |
| --- | --- |
| gamipress-integration--master / [includes/class-assets.php:14](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-assets.php#L14) | `add_action('wp_enqueue_scripts', array( __CLASS__, 'enqueue_frontend_scripts' ))` |
| gamipress-integration--master / [includes/class-assets.php:15](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-assets.php#L15) | `add_action('admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_scripts' ))` |
| gamipress-integration--master / [includes/class-assets.php:19](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-assets.php#L19) | `get_directorist_option('author_profile_page')` |
| gamipress-integration--master / [includes/class-assets.php:20](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-assets.php#L20) | `get_directorist_option('user_dashboard')` |
| gamipress-integration--master / [includes/class-author.php:14](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-author.php#L14) | `add_action('directorist_author_listing_after_about_section', array( __CLASS__, 'render' ))` |
| gamipress-integration--master / [includes/class-dashboard.php:14](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-dashboard.php#L14) | `add_filter('directorist_dashboard_tabs', array( __CLASS__, 'register_tab' ))` |
| gamipress-integration--master / [includes/class-dashboard.php:51](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-dashboard.php#L51) | `get_directorist_option('gamipress_discount_points_required')` |
| gamipress-integration--master / [includes/class-dashboard.php:52](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-dashboard.php#L52) | `get_user_meta(get_current_user_id(), 'directorist_gamipress_used_points')` |
| gamipress-integration--master / [includes/class-dashboard.php:54](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-dashboard.php#L54) | `get_user_meta(get_current_user_id(), 'directorist_gamipress_coupon_id')` |
| gamipress-integration--master / [includes/class-dashboard.php:55](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-dashboard.php#L55) | `get_post_meta($claimed_coupon_id, 'swbdpc_coupon_code')` |
| gamipress-integration--master / [includes/class-listeners.php:19](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-listeners.php#L19) | `add_action('trashed_post', array( __CLASS__, 'delete_listing_listener' ))` |
| gamipress-integration--master / [includes/class-listeners.php:20](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-listeners.php#L20) | `add_action('before_delete_post', array( __CLASS__, 'delete_listing_listener' ))` |
| gamipress-integration--master / [includes/class-listeners.php:21](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-listeners.php#L21) | `add_action('transition_post_status', array( __CLASS__, 'transition_post_status_listener' ))` |
| gamipress-integration--master / [includes/class-listeners.php:23](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-listeners.php#L23) | `add_action('directorist_listing_reported', array( __CLASS__, 'listing_report_listener' ))` |
| gamipress-integration--master / [includes/class-listeners.php:24](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-listeners.php#L24) | `add_action('directorist_user_favorites_added', array( __CLASS__, 'user_favorites_listener' ))` |
| gamipress-integration--master / [includes/class-listeners.php:26](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-listeners.php#L26) | `add_action('directorist_listing_views_count_updated', array( __CLASS__, 'listing_views_count_updated_listener' ))` |
| gamipress-integration--master / [includes/class-listeners.php:27](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-listeners.php#L27) | `add_action('comment_approved_', array( __CLASS__, 'review_listener' ))` |
| gamipress-integration--master / [includes/class-listeners.php:28](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-listeners.php#L28) | `add_action('comment_approved_review', array( __CLASS__, 'review_listener' ))` |
| gamipress-integration--master / [includes/class-listeners.php:29](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-listeners.php#L29) | `add_action('directorist_review_updated', array( __CLASS__, 'review_listener' ))` |
| gamipress-integration--master / [includes/class-listeners.php:31](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-listeners.php#L31) | `add_action('atbdp_order_created', array( __CLASS__, 'order_created_listener' ))` |
| gamipress-integration--master / [includes/class-listeners.php:41](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-listeners.php#L41) | `get_post_meta($order_id, '_fm_plan_ordered')` |
| gamipress-integration--master / [includes/class-listeners.php:185](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-listeners.php#L185) | `get_post_meta($post->ID, '_listing_status')` |
| gamipress-integration--master / [includes/class-requirements.php:14](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-requirements.php#L14) | `add_action('gamipress_ajax_update_requirement', array( __CLASS__, 'ajax_update' ))` |
| gamipress-integration--master / [includes/class-requirements.php:15](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-requirements.php#L15) | `add_filter('gamipress_requirement_object', array( __CLASS__, 'requirement_object' ))` |
| gamipress-integration--master / [includes/class-requirements.php:16](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-requirements.php#L16) | `add_action('gamipress_requirement_ui_html_after_achievement_post', array( __CLASS__, 'register_ui_fields' ))` |
| gamipress-integration--master / [includes/class-rules-engine.php:16](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-rules-engine.php#L16) | `add_filter('user_deserves_achievement', array( __CLASS__, 'user_meets_points_requirement' ))` |
| gamipress-integration--master / [includes/class-triggers.php:17](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-triggers.php#L17) | `add_filter('gamipress_activity_triggers', array( __CLASS__, 'register_activity_triggers' ))` |
| gamipress-integration--master / [includes/class-triggers.php:18](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-triggers.php#L18) | `add_filter('gamipress_log_extra_data_fields', array( __CLASS__, 'log_extra_data_fields' ))` |
| gamipress-integration--master / [includes/class-triggers.php:19](https://github.com/sovware/directorist-gamipress-integration/blob/d28b69c0ecce4b699fa8a9bbc7456c7fa7d36dff/includes/class-triggers.php#L19) | `add_filter('gamipress_log_event_trigger_meta_data', array( __CLASS__, 'log_event_trigger_meta_data' ))` |

## Core and integration context

Load [Core listing/form/query contracts](../../../core/listings.md) for a form/data/query issue, [rendering and builders](../../../core/rendering.md) for display, or [payment lifecycle](../../../core/payments.md) for orders. Do not load all three automatically.

Conditional integration candidates (load only when installed configuration or the cited hook/guard connects them):
- [directorist-coupon](../../directorist-coupon/README.md)

For exact cross-repository hook matches, use `python3 docs/extensions/scripts/query.py hooks HOOK_NAME`; a same-name hook is only a candidate edge. For dynamic calls, inspect the referenced source and actual active callback list.
