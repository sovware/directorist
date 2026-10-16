# AddonsKit for Bricks: dynamic-tags

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Dynamic field/tag values depend on listing/directory/taxonomy context; editor sample is not final queried object.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Use two directories with same custom field type, category images and empty values. Compare loop/detail/editor output and escaped field values.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Entry points and feature coverage

Read the canonical snapshot rows first. Local/default/development rows are available for variant comparison only. Every matching source file is listed with declarations; supporting styles/configuration may have no symbols.

| Snapshot / source | Functions or classes |
| --- | --- |
| addonskit-for-bricks--main / [addonskit-for-bricks.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/addonskit-for-bricks.php#L1) | `akfb_is_supported_php` (L44), `akfb_auto_deactivate` (L48), `akfb_is_plugin_installed` (L75), `akfb_directorist_missing_notice` (L85), `akfb_directorist_required_version_notice` (L107), `akfb_bricks_missing_notice` (L118), `akfb_is_required_dependencies_installed` (L128) |
| addonskit-for-bricks--main / [src/DynamicTags/DataProvider.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/DynamicTags/DataProvider.php#L1) | `DataProvider` (L10), `init` (L12) |
| addonskit-for-bricks--main / [src/DynamicTags/Provider.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/DynamicTags/Provider.php#L1) | `Provider_Listing` (L10), `load_me` (L28), `register_tags` (L32), `get_fields` (L77), `add_fields_to_list` (L112), `get_tag_value` (L137), `get_category_image_fields` (L279), `get_category_image_value` (L310), `get_current_category_term` (L338), `normalize_term` (L370), `get_fields_by_context` (L391) |
| addonskit-for-bricks--main / [src/Support/Utils.php:1](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Support/Utils.php#L1) | `Utils` (L12), `do_shortcode` (L14), `maybe_editor` (L24), `get_directories` (L28), `get_builder_data` (L38), `get_quick_actions_fields` (L44), `get_meta_info_fields` (L68), `load_listing_widget` (L94), `load_listing_section` (L126), `load_listing_field` (L137), `prepare_checkbox_data` (L181), `load_template` (L195), `to_term_taxonomy_map` (L199), `to_term_ids` (L219), `to_term_slugs` (L230) |
| addonskit-for-bricks--development / [addonskit-for-bricks.php:1](https://github.com/sovware/addonskit-for-bricks/blob/c3056318b531babf4673c18bf2114525814244fa/addonskit-for-bricks.php#L1) | `akfb_is_supported_php` (L44), `akfb_auto_deactivate` (L48), `akfb_is_plugin_installed` (L75), `akfb_directorist_missing_notice` (L85), `akfb_directorist_required_version_notice` (L107), `akfb_bricks_missing_notice` (L118), `akfb_is_required_dependencies_installed` (L128) |
| addonskit-for-bricks--development / [src/DynamicTags/Provider.php:1](https://github.com/sovware/addonskit-for-bricks/blob/c3056318b531babf4673c18bf2114525814244fa/src/DynamicTags/Provider.php#L1) | `Provider_Listing` (L10), `load_me` (L28), `register_tags` (L32), `get_fields` (L65), `add_fields_to_list` (L100), `get_tag_value` (L125), `get_fields_by_context` (L268) |

## Data readers, writers and lifecycle

Keys/callback expressions below are extracted without stored values. `get` reads; `update/add/delete` writes; scheduled/remote calls are side effects. ORM repositories and schema definitions are linked as source even where literal-key extraction cannot resolve them.

| Snapshot / source | Operation and key |
| --- | --- |
| addonskit-for-bricks--main / [addonskit-for-bricks.php:69](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/addonskit-for-bricks.php#L69) | `register_activation_hook(AKFB_PLUGIN_FILE)` |
| addonskit-for-bricks--main / [addonskit-for-bricks.php:130](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/addonskit-for-bricks.php#L130) | `add_action('admin_notices', 'akfb_bricks_missing_notice')` |
| addonskit-for-bricks--main / [addonskit-for-bricks.php:135](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/addonskit-for-bricks.php#L135) | `add_action('admin_notices', 'akfb_directorist_missing_notice')` |
| addonskit-for-bricks--main / [addonskit-for-bricks.php:140](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/addonskit-for-bricks.php#L140) | `add_action('admin_notices', 'akfb_directorist_required_version_notice')` |
| addonskit-for-bricks--main / [addonskit-for-bricks.php:147](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/addonskit-for-bricks.php#L147) | `add_action('plugins_loaded', static function() { if ( ! akfb_is_required_dependencies_installed() ) { return; } require_once AKFB_PLUGIN_DIR . '/src/Plugin.php'; \WpWax\AKFB\Plugin::instance(); })` |
| addonskit-for-bricks--main / [src/DynamicTags/DataProvider.php:13](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/DynamicTags/DataProvider.php#L13) | `add_filter('bricks/dynamic_data/register_providers', static function( $providers ) { require_once __DIR__ . '/Provider.php'; $providers[] = 'listing'; return $providers; })` |
| addonskit-for-bricks--main / [src/DynamicTags/Provider.php:159](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/DynamicTags/Provider.php#L159) | `get_post_meta($post_id, '_' . $field['meta_key'])` |
| addonskit-for-bricks--main / [src/DynamicTags/Provider.php:317](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/DynamicTags/Provider.php#L317) | `get_term_meta($term->term_id, 'image')` |
| addonskit-for-bricks--main / [src/Support/Utils.php:142](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Support/Utils.php#L142) | `get_post_meta(get_the_ID(), '_directory_type')` |
| addonskit-for-bricks--main / [src/Support/Utils.php:192](https://github.com/sovware/addonskit-for-bricks/blob/abedcb63f283ae13e899a33c0b2a2a1c7a922c51/src/Support/Utils.php#L192) | `join(', ')` |
| addonskit-for-bricks--development / [addonskit-for-bricks.php:69](https://github.com/sovware/addonskit-for-bricks/blob/c3056318b531babf4673c18bf2114525814244fa/addonskit-for-bricks.php#L69) | `register_activation_hook(AKFB_PLUGIN_FILE)` |
| addonskit-for-bricks--development / [addonskit-for-bricks.php:130](https://github.com/sovware/addonskit-for-bricks/blob/c3056318b531babf4673c18bf2114525814244fa/addonskit-for-bricks.php#L130) | `add_action('admin_notices', 'akfb_bricks_missing_notice')` |
| addonskit-for-bricks--development / [addonskit-for-bricks.php:135](https://github.com/sovware/addonskit-for-bricks/blob/c3056318b531babf4673c18bf2114525814244fa/addonskit-for-bricks.php#L135) | `add_action('admin_notices', 'akfb_directorist_missing_notice')` |
| addonskit-for-bricks--development / [addonskit-for-bricks.php:140](https://github.com/sovware/addonskit-for-bricks/blob/c3056318b531babf4673c18bf2114525814244fa/addonskit-for-bricks.php#L140) | `add_action('admin_notices', 'akfb_directorist_required_version_notice')` |
| addonskit-for-bricks--development / [addonskit-for-bricks.php:147](https://github.com/sovware/addonskit-for-bricks/blob/c3056318b531babf4673c18bf2114525814244fa/addonskit-for-bricks.php#L147) | `add_action('plugins_loaded', static function() { if ( ! akfb_is_required_dependencies_installed() ) { return; } require_once AKFB_PLUGIN_DIR . '/src/Plugin.php'; \WpWax\AKFB\Plugin::instance(); })` |
| addonskit-for-bricks--development / [src/DynamicTags/Provider.php:143](https://github.com/sovware/addonskit-for-bricks/blob/c3056318b531babf4673c18bf2114525814244fa/src/DynamicTags/Provider.php#L143) | `get_post_meta($post_id, '_' . $field['meta_key'])` |

## Core and integration context

Load [Core listing/form/query contracts](../../../core/listings.md) for a form/data/query issue, [rendering and builders](../../../core/rendering.md) for display, or [payment lifecycle](../../../core/payments.md) for orders. Do not load all three automatically.

For exact cross-repository hook matches, use `python3 docs/extensions/scripts/query.py hooks HOOK_NAME`; a same-name hook is only a candidate edge. For dynamic calls, inspect the referenced source and actual active callback list.
