# Mailchimp Integration: contact-subscription

[Identity and topic index](../README.md) · [Focused issue workflow](../../../WORKFLOW.md)

## Behavior and limits

Contact-owner submission has its own subscription hook and consent path.

This is a source-derived investigation contract. Check the branches and file references below before treating it as behavior of an installed site.

## Reproduction and browser regression recipe

Submit contact with permitted consent settings; inspect locally captured mail and API request independently. Ensure failed contact validation does not falsely imply subscription.

Record exact inputs, expected/actual result, user role, listing/directory IDs, installed source fingerprint and environment. Use browser interactions for rendered/interactive claims; state inspection alone does not prove rendering. Restore disposable fixtures and capture errors without secrets.

## Entry points and feature coverage

Read the canonical snapshot rows first. Local/default/development rows are available for variant comparison only. Every matching source file is listed with declarations; supporting styles/configuration may have no symbols.

| Snapshot / source | Functions or classes |
| --- | --- |
| mailchimp--master / [directorist-mailchimp-integration.php:1](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/directorist-mailchimp-integration.php#L1) | `Directorist_Mailchimp_Integration` (L19), `instance` (L25), `__construct` (L37), `init` (L39), `update_controller` (L48), `load_textdomain` (L62), `includes` (L66), `setup_constants` (L80), `get_version_from_file_content` (L97), `get_version_from_content` (L110), `Directorist_Mailchimp_Integration` (L125) |
| mailchimp--master / [includes/class-mailchimp.php:1](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/includes/class-mailchimp.php#L1) | `Mailchimp` (L10), `instance` (L14), `set_config` (L23) |
| mailchimp--master / [includes/class-settings-manager.php:1](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/includes/class-settings-manager.php#L1) | `Settings_Manager` (L10), `__construct` (L12), `atbdp_listing_type_settings_field_list` (L17), `atbdp_extension_settings_submenu` (L132) |
| mailchimp--master / [includes/class-subscribe-after-listing-contact.php:1](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/includes/class-subscribe-after-listing-contact.php#L1) | `Subscribe_After_Listing_Contact` (L13), `__construct` (L15), `hooks` (L19), `atbdp_listing_contact_owner_submitted` (L30), `add_member_to_list` (L57) |

## Data readers, writers and lifecycle

Keys/callback expressions below are extracted without stored values. `get` reads; `update/add/delete` writes; scheduled/remote calls are side effects. ORM repositories and schema definitions are linked as source even where literal-key extraction cannot resolve them.

| Snapshot / source | Operation and key |
| --- | --- |
| mailchimp--master / [directorist-mailchimp-integration.php:31](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/directorist-mailchimp-integration.php#L31) | `add_action('admin_init', [ self::$instance, 'update_controller' ])` |
| mailchimp--master / [directorist-mailchimp-integration.php:40](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/directorist-mailchimp-integration.php#L40) | `add_action('plugins_loaded', array( $this, 'load_textdomain' ))` |
| mailchimp--master / [directorist-mailchimp-integration.php:50](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/directorist-mailchimp-integration.php#L50) | `get_user_meta(get_current_user_id(), '_plugins_available_in_subscriptions')` |
| mailchimp--master / [directorist-mailchimp-integration.php:130](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/directorist-mailchimp-integration.php#L130) | `get_option('active_plugins')` |
| mailchimp--master / [includes/class-settings-manager.php:13](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/includes/class-settings-manager.php#L13) | `add_filter('atbdp_listing_type_settings_field_list', array( $this, 'atbdp_listing_type_settings_field_list' ))` |
| mailchimp--master / [includes/class-settings-manager.php:14](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/includes/class-settings-manager.php#L14) | `add_filter('atbdp_extension_settings_submenu', array( $this, 'atbdp_extension_settings_submenu' ))` |
| mailchimp--master / [includes/class-subscribe-after-listing-contact.php:21](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/includes/class-subscribe-after-listing-contact.php#L21) | `get_directorist_option('enalbe_mailchimp_for_directorist')` |
| mailchimp--master / [includes/class-subscribe-after-listing-contact.php:22](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/includes/class-subscribe-after-listing-contact.php#L22) | `get_directorist_option('mailchmp_for_directorist_enable_subscribe_listing_contact')` |
| mailchimp--master / [includes/class-subscribe-after-listing-contact.php:27](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/includes/class-subscribe-after-listing-contact.php#L27) | `add_action('atbdp_listing_contact_owner_submitted', array( $this, 'atbdp_listing_contact_owner_submitted' ))` |
| mailchimp--master / [includes/class-subscribe-after-listing-contact.php:42](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/includes/class-subscribe-after-listing-contact.php#L42) | `get_directorist_option('mailchmp_for_directorist_enable_subscribe_listing_contact_double_opt_in')` |
| mailchimp--master / [includes/class-subscribe-after-listing-contact.php:59](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/includes/class-subscribe-after-listing-contact.php#L59) | `get_directorist_option('enalbe_mailchimp_for_directorist')` |
| mailchimp--master / [includes/class-subscribe-after-listing-contact.php:60](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/includes/class-subscribe-after-listing-contact.php#L60) | `get_directorist_option('mailchmp_for_directorist_api_key')` |
| mailchimp--master / [includes/class-subscribe-after-listing-contact.php:61](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/includes/class-subscribe-after-listing-contact.php#L61) | `get_directorist_option('mailchmp_for_directorist_server')` |
| mailchimp--master / [includes/class-subscribe-after-listing-contact.php:62](https://github.com/sovware/directorist-mailchimp/blob/00943169669bf9caa01ae6ebb1c2a34fb62098ca/includes/class-subscribe-after-listing-contact.php#L62) | `get_directorist_option('mailchmp_for_directorist_list_id')` |

## Core and integration context

Load [Core listing/form/query contracts](../../../core/listings.md) for a form/data/query issue, [rendering and builders](../../../core/rendering.md) for display, or [payment lifecycle](../../../core/payments.md) for orders. Do not load all three automatically.

For exact cross-repository hook matches, use `python3 docs/extensions/scripts/query.py hooks HOOK_NAME`; a same-name hook is only a candidate edge. For dynamic calls, inspect the referenced source and actual active callback list.
