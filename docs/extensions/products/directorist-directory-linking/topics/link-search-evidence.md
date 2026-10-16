# Multi Directory Linking: link-search

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Linked-directory search and single display must use the configured target directory and valid IDs.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Search by linked listing and compare native single output with Divi LinkDirectory; test two directory types and legacy serialized selection.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Entry points and feature coverage

Read the canonical snapshot rows first. Local/default/development rows are available for variant comparison only. Every matching source file is listed with declarations; supporting styles/configuration may have no symbols.

| Snapshot / source | Functions or classes |
| --- | --- |
| directory-linking--alpha / [app/Builder/Builder.php:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/Builder/Builder.php#L1) | `Builder` (L4), `register` (L10), `dirlink_searching_type` (L23), `get_preview_img` (L64), `parse_selected_listing_ids` (L94), `get_review_data` (L106), `preset_widgets` (L145), `single_listing_content_widgets` (L219), `directorist_field_template` (L285), `directorist_single_item_template` (L334) |
| directory-linking--alpha / [app/Setup/Settings.php:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/Setup/Settings.php#L1) | `Settings` (L4), `register` (L6), `__construct` (L14), `atbdp_extension_fields` (L19), `atbdp_listing_type_settings_field_list` (L24) |
| directory-linking--alpha / [app/base.php:1](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/base.php#L1) | `Directorist_Directory_Linking` (L9), `instance` (L24), `search_query_argument` (L37), `load_textdomain` (L51), `__construct` (L60), `includes` (L70), `get_services` (L91), `Enqueue` (L93), `Builder` (L94), `register_services` (L95), `register_services` (L104), `Directorist_Directory_Linking` (L121), `directorist_is_plugin_active` (L126), `directorist_is_plugin_active_for_network` (L132) |

## Data readers, writers and lifecycle

Keys/callback expressions below are extracted without stored values. `get` reads; `update/add/delete` writes; scheduled/remote calls are side effects. ORM repositories and schema definitions are linked as source even where literal-key extraction cannot resolve them.

| Snapshot / source | Operation and key |
| --- | --- |
| directory-linking--alpha / [app/Builder/Builder.php:12](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/Builder/Builder.php#L12) | `add_filter('atbdp_form_preset_widgets', array( $this, 'preset_widgets' ))` |
| directory-linking--alpha / [app/Builder/Builder.php:13](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/Builder/Builder.php#L13) | `add_filter('atbdp_single_listing_content_widgets', array( $this, 'single_listing_content_widgets' ))` |
| directory-linking--alpha / [app/Builder/Builder.php:14](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/Builder/Builder.php#L14) | `add_filter('directorist_field_template', array( $this, 'directorist_field_template' ))` |
| directory-linking--alpha / [app/Builder/Builder.php:15](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/Builder/Builder.php#L15) | `add_filter('directorist_single_item_template', array( $this, 'directorist_single_item_template' ))` |
| directory-linking--alpha / [app/Builder/Builder.php:18](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/Builder/Builder.php#L18) | `add_action('wp_ajax_dirlink_searching_type', array( $this, 'dirlink_searching_type' ))` |
| directory-linking--alpha / [app/Builder/Builder.php:19](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/Builder/Builder.php#L19) | `add_action('wp_ajax_nopriv_dirlink_searching_type', array( $this, 'dirlink_searching_type' ))` |
| directory-linking--alpha / [app/Builder/Builder.php:67](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/Builder/Builder.php#L67) | `get_directorist_option('preview_image_quality')` |
| directory-linking--alpha / [app/Builder/Builder.php:68](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/Builder/Builder.php#L68) | `get_post_meta($id, '_listing_prv_img')` |
| directory-linking--alpha / [app/Builder/Builder.php:69](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/Builder/Builder.php#L69) | `get_post_meta($id, '_listing_img')` |
| directory-linking--alpha / [app/Builder/Builder.php:149](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/Builder/Builder.php#L149) | `get_directorist_option('enable_multi_directory')` |
| directory-linking--alpha / [app/Builder/Builder.php:222](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/Builder/Builder.php#L222) | `get_directorist_option('enable_linking_type')` |
| directory-linking--alpha / [app/Builder/Builder.php:288](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/Builder/Builder.php#L288) | `get_directorist_option('enable_linking_type')` |
| directory-linking--alpha / [app/Builder/Builder.php:337](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/Builder/Builder.php#L337) | `get_directorist_option('enable_linking_type')` |
| directory-linking--alpha / [app/Setup/Settings.php:8](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/Setup/Settings.php#L8) | `add_filter('atbdp_extension_fields', array( $this, 'atbdp_extension_fields' ))` |
| directory-linking--alpha / [app/Setup/Settings.php:9](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/Setup/Settings.php#L9) | `add_filter('atbdp_listing_type_settings_field_list', array( $this, 'atbdp_listing_type_settings_field_list' ))` |
| directory-linking--alpha / [app/base.php:29](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/base.php#L29) | `add_action('plugins_loaded', array( self::$instance, 'load_textdomain' ))` |
| directory-linking--alpha / [app/base.php:30](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/base.php#L30) | `add_filter('atbdp_listing_search_query_argument', array( self::$instance, 'search_query_argument'))` |
| directory-linking--alpha / [app/base.php:62](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/base.php#L62) | `register_services()` |
| directory-linking--alpha / [app/base.php:127](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/base.php#L127) | `get_option('active_plugins')` |
| directory-linking--alpha / [app/base.php:137](https://github.com/sovware/directorist-directory-linking/blob/ade10cf1e0303510a7c9ad18a925f0799932ce46/app/base.php#L137) | `get_site_option('active_sitewide_plugins')` |

## Core and integration context

Load [Core listing/form/query contracts](../../../core/listings.md) for a form/data/query issue, [rendering and builders](../../../core/rendering.md) for display, or [payment lifecycle](../../../core/payments.md) for orders. Do not load all three automatically.

Conditional integration candidates (load only when installed configuration or the cited hook/guard connects them):
- [directorist-divi-integration](../../directorist-divi-integration/README.md)

For exact cross-repository hook matches, use `python3 docs/extensions/scripts/query.py hooks HOOK_NAME`; a same-name hook is only a candidate edge. For dynamic calls, inspect the referenced source and actual active callback list.
