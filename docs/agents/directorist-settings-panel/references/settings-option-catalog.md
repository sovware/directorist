# Settings Option Catalog

Last reviewed: 2026-06-04

Source of truth reviewed:

- Direct core settings: `includes/classes/class-settings-panel.php`, especially `ATBDP_Settings_Panel::prepare_settings()` and the `$this->layouts` map.
- Core filter-injected review settings: `includes/review/class-settings-screen.php`.
- Core filter-injected schema settings: `includes/classes/class-schema.php`.

This catalog is for agents planning settings-panel redesigns or feature work. It groups settings by the admin settings navigation and explains what each area controls. It is not a replacement for source inspection; filters can add, remove, or move fields at runtime.

## How To Use This Catalog

- Before changing a setting key, find its current area here and then verify it in source with `rg`.
- Before adding a new key, choose the closest existing area and check related frontend/admin readers.
- After a confirmed key change, update this catalog in the same task.
- Treat page, permalink, map, payment, email, and save-contract options as higher risk because they affect runtime behavior outside the settings UI.
- Distinguish direct core settings, core filter-injected settings, and extension-provided settings before planning a redesign.
- For extension-specific settings, inspect the installed/active extension source before documenting or redesigning those settings.

## Listings

Purpose: global listing behavior, listing archive display, single listing behavior, taxonomy display, maps, and badges.

Source: most Listings settings are direct core settings from `class-settings-panel.php`. The Review submenu is a core filter-injected section from `includes/review/class-settings-screen.php`.

Redesigned UI note (2026-06-08): Core listing display settings are no longer routed through the temporary `Needs Design` fallback. `Listings page` and `Categories & locations` are permanent `Directory` submenus. Their fields remain the same saved keys and keep the existing save/show-if behavior.

### General

- `enable_multi_directory`: enables multi-directory mode and exposes directory type workflows.
- `guest_listings`: allows listing submission by non-logged-in visitors.
- `guest_email_label`, `guest_email_placeholder`: customize guest email field copy on listing submission.
- `new_user_registration`: controls whether registration is enabled from Directorist flows.
- `enable_email_verification`: requires users to verify email during registration.
- `count_loggedin_user`: controls whether logged-in visits count toward listing views.
- `dynamic_view_count_cache`: controls dynamic/cache behavior for view counts.
- `g_currency_note`, `g_currency`, `g_currency_position`: listing price currency display used outside checkout/payment order formatting.
- `email_to_expire_day`, `email_renewal_day`: timing for expiration/renewal notifications.
- `delete_expired_listing_permanently`, `delete_expired_listings_after`: expired listing cleanup behavior.
- `enable_archive_template`: enables Directorist taxonomy archive template behavior.
- `category_base`, `location_base`, `tag_base`: taxonomy URL slugs when archive templates are enabled.

### All Listings

- `all_listing_layout`: archive/search listing layout mode.
- `all_listing_columns`: grid column count for all listings. In the redesigned panel this renders as a normal select so quick settings search can edit it inline; the legacy PHP field remains `radio-images`.
- `all_listing_page_items`: listing count per page.
- `pagination_type`: pagination behavior.
- `listing_hide_top_search_bar`: hides the top search bar on all listings.
- `listings_sidebar_filter_text`: sidebar filter label.
- `listings_reset_text`, `listings_sidebar_reset_text`, `listings_apply_text`: filter action copy.
- `display_listings_header`: toggles archive header area.
- `listing_filters_button`, `listings_filter_button_text`: filter button visibility and copy.
- `display_listings_count`: shows total listing count.
- `all_listing_title`: archive title copy.
- `listings_view_as_items`, `default_listing_view`: available listing view modes and default mode.
- `display_sort_by`, `sort_by_text`, `listings_sort_by_items`: sorting UI visibility, label, and choices.
- `preview_image_quality`: image size/quality used for listing cards.
- `way_to_show_preview`: preview image fit/crop behavior.
- `crop_width`, `crop_height`: preview image dimensions.
- `prv_container_size_by`: unit mode for preview image container sizing.
- `prv_background_type`, `prv_background_color`: preview image empty/background display.

### Single Listing

- `single_listing_template`: selected single listing template.
- `disable_single_listing`: disables single listing page access/display.
- `restrict_single_listing_for_logged_in_user`: restricts single listing access to logged-in users.
- `atbdp_listing_slug`: base slug for single listing URLs.
- `single_listing_slug_with_directory_type`: adds directory type into single listing permalink when multi-directory is enabled.
- `submission_confirmation`: toggles post-submit confirmation messaging.
- `pending_confirmation_msg`, `publish_confirmation_msg`: confirmation messages for pending and published submissions.
- `dsiplay_slider_single_page`: toggles image slider on single listing pages.
- `single_slider_image_size`: image size for single listing slider.
- `single_slider_background_type`, `single_slider_background_color`: slider background behavior.
- `gallery_crop_width`, `gallery_crop_height`: gallery image crop dimensions.

### Category And Location

- `display_categories_as`, `categories_column_number`, `categories_depth_number`: category page layout, columns, and hierarchy depth.
- `order_category_by`, `sort_category_by`: category ordering field and direction.
- `display_listing_count`, `hide_empty_categories`: category listing counts and empty category visibility.
- `display_locations_as`, `locations_column_number`, `locations_depth_number`: location page layout, columns, and hierarchy depth.
- `order_location_by`, `sort_location_by`: location ordering field and direction.
- `display_location_listing_count`, `hide_empty_locations`: location listing counts and empty location visibility.

### Map

- `select_listing_map`: map provider selection.
- `map_api_key`: provider API key, especially for Google maps.
- `marker_clustering`: clusters markers for Google map views.
- `country_restriction`, `restricted_countries`: restricts autocomplete/geocoding to selected countries.
- `default_latitude`, `default_longitude`: default map center. In the redesigned settings UI these remain the saved keys behind the visible `Default address` row; the address row reverse-geocodes saved coordinates and writes selected/entered addresses back to these coordinate keys.
- `use_def_lat_long`: forces default coordinates in all-listings map behavior.
- `map_zoom_level`, `map_view_zoom_level`: default zoom levels for form/map views.
- `listings_map_height`: all-listings map height.
- `display_map_info`: toggles map info windows.
- `display_image_map`, `display_favorite_badge_map`, `display_user_avatar_map`: info window media/badge/avatar visibility.
- `display_title_map`, `display_review_map`, `display_price_map`, `display_address_map`, `display_direction_map`, `display_phone_map`: info window field visibility.

### Badges

- `badge_display_type`: controls how badges are displayed.
- `new_badge_text`, `new_listing_day`, `new_back_color`: new badge label, age threshold, and color.
- `popular_badge_text`, `listing_popular_by`, `views_for_popular`, `average_review_for_popular`, `popular_back_color`: popular badge label, popularity rule, thresholds, and color.
- `feature_badge_text`, `featured_back_color`: featured badge label and color.

### Review

Source: core filter-injected settings from `includes/review/class-settings-screen.php`, added through `atbdp_listing_type_settings_layout` and `atbdp_listing_type_settings_field_list`.

- `enable_review`: enables listing reviews.
- `enable_owner_review`: allows listing owners to review their own listings when reviews are enabled.
- `guest_review`: allows non-logged-in visitors to submit reviews when reviews are enabled.
- `review_enable_reply`: allows replies to reviews/comments when reviews are enabled.
- `approve_immediately`: auto-approves submitted reviews instead of requiring moderation.
- `review_num`: reviews displayed per page.

Related read points include `includes/review/directorist-review-functions.php`, `includes/review/init.php`, `includes/model/SingleListing.php`, and review REST/localized data paths.

Runtime-read but not active UI fields in the reviewed settings screen:

- `review_approval_text`: read by localized review data, but currently commented out in the Review settings screen.
- `enable_reviewer_content`: read by localized review data and AJAX review submission validation, but currently commented out in the Review settings screen.
- `required_reviewer_content`: read by AJAX review submission validation, but currently commented out in the Review settings screen.

Treat these as compatibility-sensitive legacy options if a redesign or feature asks to expose, rename, or remove review content controls.

## Page Setup

Purpose: maps Directorist workflows to WordPress pages and regenerates/upgrades required pages.

Redesigned UI note: Site & pages renders `Pages`, `SEO`, `Schema`, and `Maintenance` as presentation-only tabs. Page setup rows use custom copy and row layout while preserving the original page-select keys and values; shortcode chips are currently hidden by design.

- `regenerate_pages`: AJAX action field to upgrade/regenerate Directorist pages.
- `add_listing_page`: page used for listing submission.
- `all_listing_page`: page used for all listings archive.
- `user_dashboard`: user dashboard page.
- `signin_signup_page`: sign-in/sign-up page.
- `author_profile_page`: author profile page.
- `all_categories_page`, `single_category_page`: category index and single category pages.
- `all_locations_page`, `single_location_page`: location index and single location pages.
- `single_tag_page`: single tag page.
- `search_listing`, `search_result_page`: search form and search results pages.
- `checkout_page`, `payment_receipt_page`, `transaction_failure_page`: monetization checkout result pages.
- `privacy_policy`, `terms_conditions`: legal pages linked from submission/registration flows.

## Search

Purpose: search form copy/filter behavior and search result page layout.

Redesigned UI note (2026-06-08): Search settings now render as a permanent top-level `Search` menu with `Search form` and `Search results` tabs. This is a navigation/layout change only; all existing search option keys, values, and save behavior are preserved.

### Search Listing

- `search_title`, `search_subtitle`, `search_listing_text`: search form heading and input copy.
- `search_more_filter`, `search_more_filters`: more-filter toggle behavior/copy.
- `search_filters`: visible search form filters.
- `search_reset_text`, `search_apply_filter`: filter action copy.
- `show_popular_category`, `popular_cat_title`, `popular_cat_num`: popular category display in search.

### Search Result

- `search_result_layout`: search result layout mode.
- `search_listing_columns`: grid column count for search results.
- `search_posts_num`: listings per search result page.
- `search_result_hide_top_search_bar`: hides search bar above search results.
- `search_result_sidebar_filter_text`: sidebar filter label.
- `sresult_reset_text`, `sresult_sidebar_reset_text`, `sresult_apply_text`: search result filter action copy.
- `search_header`: toggles search result header.
- `search_result_filters_button_display`, `search_result_filter_button_text`: filter button visibility and copy.
- `display_search_result_listings_count`: shows listing count on search results.
- `search_result_listing_title`: search result title copy.
- `search_view_as_items`: available search result view modes.
- `search_sort_by`, `search_sortby_text`, `search_sort_by_items`: search sorting visibility, label, and choices.

## User

Purpose: registration/login form labels and visibility, dashboard tabs, author dashboard behavior, and all-authors display.

Redesigned UI note (2026-06-08): User settings now render as a permanent top-level `Users & accounts` menu with `Registration`, `Login`, `Dashboard`, and `Authors` tabs. This replaces the temporary `Needs Design` placement without adding, renaming, or migrating saved option keys.

### Registration Form

Redesigned UI note (2026-06-09): Registration keeps the redesigned card/row pattern, but uses the old readable section naming: `Username`, `Password`, `Email`, `Website`, `First Name`, `Last Name`, `About/Bio`, `User Type Registration`, `Privacy Policy`, `Terms Conditions`, `Sign Up Button`, `Login Message`, and `Registration Redirect`. This is layout/copy metadata only; option keys and saved values are unchanged.

- `reg_username`, `reg_email`: username and email label/copy.
- `display_password_reg`, `reg_password`, `require_password_reg`: password field visibility, label, and requirement.
- `display_website_reg`, `reg_website`, `require_website_reg`: website field visibility, label, and requirement.
- `display_fname_reg`, `reg_fname`, `require_fname_reg`: first name field visibility, label, and requirement.
- `display_lname_reg`, `reg_lname`, `require_lname_reg`: last name field visibility, label, and requirement.
- `display_bio_reg`, `reg_bio`, `require_bio_reg`: bio field visibility, label, and requirement.
- `display_user_type`: shows user type selection during registration.
- `registration_privacy`, `registration_privacy_label`, `registration_privacy_label_link`: privacy policy checkbox and label/link.
- `regi_terms_condition`, `regi_terms_label`, `regi_terms_label_link`: terms checkbox and label/link.
- `reg_signup`: sign-up button copy.
- `display_login`, `login_text`, `log_linkingmsg`: login prompt shown on registration form.
- `auto_login`, `redirection_after_reg`: post-registration login and redirect behavior.

### Login Form

Redesigned UI note (2026-06-09): Login keeps the redesigned card/row pattern, but uses the old readable section naming: `Username`, `Password`, `Remember Login Information`, `Login Button`, `Sign Up Message`, `Recover Password`, and `Login Redirect`. This is layout/copy metadata only; option keys and saved values are unchanged.

- `log_username`, `log_password`: login username/password labels.
- `display_rememberme`, `log_rememberme`: remember-me visibility and label.
- `log_button`: login button copy.
- `display_signup`, `reg_text`, `reg_linktxt`: sign-up prompt visibility and copy.
- `display_recpass`, `recpass_text`, `recpass_desc`, `recpass_username`, `recpass_placeholder`, `recpass_button`: password recovery visibility and copy.
- `redirection_after_login`: post-login redirect behavior.

### Dashboard

- `my_profile_tab`, `my_profile_tab_text`: profile tab visibility and label.
- `fav_listings_tab`, `fav_listings_tab_text`: favorite listings tab visibility and label.
- `my_listing_tab`, `my_listing_tab_text`: author listings tab visibility and label.
- `user_listings_pagination`, `user_listings_per_page`: author dashboard listing pagination.
- `submit_listing_button`: submit listing button copy.
- `become_author_button`, `become_author_button_text`: become-author CTA visibility and copy.

### All Authors

Redesigned UI note (2026-06-09): `all_authors_contact` renders as the same checkbox-array accordion pattern used by search/listing checkbox arrays. It keeps the existing selected contact values and option key.

- `all_authors_columns`: author grid column count.
- `all_authors_sorting`: author sorting behavior.
- `all_authors_image`, `all_authors_name`, `all_authors_contact`, `all_authors_description`, `all_authors_social_info`: author card field visibility.
- `all_authors_select_role`: roles included on all-authors page.
- `all_authors_description_limit`: author bio excerpt length.
- `all_authors_button`, `all_authors_button_text`: author card button visibility and copy.
- `all_authors_pagination`, `all_authors_per_page`: all-authors pagination behavior.

## Email

Purpose: sender identity, notification recipients/events, email template content, and placeholder guidance.

Redesigned UI note: Notifications renders as `Channels` plus merged `Events & Templates`. The merge is presentation-only; all fields still save through their existing option keys in `atbdp_option`. Core notification events render as toggle rows with an Edit modal. Extension email-template sections registered through `atbdp_email_templates_settings_sections` render in the same table as editable template rows without fake enable/disable toggles. Schedule timing lives as the bottom disclosure inside the `Notification events` card.

### Channels

- `email_from_name`, `email_from_email`: sender name and sender email.
- `disable_email_notification`: disables Directorist email notifications; the redesigned UI shows it as the inverse `Enable email notifications` toggle.
- `admin_email_lists`: admin recipient list.
- `allow_email_header`, `email_header_color`: email header visibility and color.

### Events & Templates

- `notify_admin`, `notify_user`: event lists for admin/user notifications.
- `email_note`: placeholder note shown in template settings.
- `email_sub_new_listing`, `email_tmpl_new_listing`: new listing email subject/body.
- `email_sub_pub_listing`, `email_tmpl_pub_listing`: approved/published listing subject/body.
- `email_sub_rejected_listing`, `email_tmpl_rejected_listing`: rejected listing subject/body.
- `email_sub_edit_listing`, `email_tmpl_edit_listing`: edited listing subject/body.
- `email_sub_to_expire_listing`, `email_tmpl_to_expire_listing`: about-to-expire listing subject/body.
- `email_sub_expired_listing`, `email_tmpl_expired_listing`: expired listing subject/body.
- `email_sub_to_renewal_listing`, `email_tmpl_to_renewal_listing`: renewal reminder subject/body.
- `email_sub_renewed_listing`, `email_tmpl_renewed_listing`: renewed listing subject/body.
- `email_sub_deleted_listing`, `email_tmpl_deleted_listing`: deleted/trashed listing subject/body.
- `email_sub_new_order`, `email_tmpl_new_order`: new order subject/body.
- `email_sub_offline_new_order`, `email_tmpl_offline_new_order`: offline bank-transfer order subject/body.
- `email_sub_completed_order`, `email_tmpl_completed_order`: completed order subject/body.
- `email_sub_listing_contact_email`, `email_tmpl_listing_contact_email`: listing contact email subject/body.
- `email_sub_registration_confirmation`, `email_tmpl_registration_confirmation`: registration confirmation subject/body.
- `email_sub_email_verification`, `email_tmpl_email_verification`: email verification subject/body.

## Monetization

Purpose: paid listing behavior, featured listings, payment gateways, payment currency display, and offline gateway copy.

Source: direct core settings from `includes/classes/class-settings-panel.php`.

### General And Currency

- `enable_monetization`: enables paid listing/payment features.
- `payment_currency_note`: explanatory note for payment currency settings.
- `payment_currency`: 3-letter payment currency code.
- `payment_thousand_separator`, `payment_decimal_separator`: payment number formatting.
- `payment_currency_position`: payment currency symbol position.

Redesigned UI note (2026-06-08): `Monetization > Currency` shows a presentation-only `Match display currency` toggle. There is no saved `match_display_currency` key. The toggle is ON when `payment_currency` / `payment_currency_position` match `g_currency` / `g_currency_position`; turning it ON writes the display values into the existing payment keys. Turning it OFF exposes `Checkout currency code` and `Checkout currency position` rows for editing the existing payment keys.

### Featured Listings

- `enable_featured_listing`: enables paid featured listing purchases.
- `featured_listing_desc`: checkout description for featured listing purchase.
- `featured_listing_price`: featured listing fee.
- `featured_listing_time`: featured listing duration in days.

### Payment Gateways And Bank Transfer

- `default_gateway`: selected default checkout gateway.
- `active_gateways`: enabled checkout gateways.
- `offline_payment_note`: note explaining manual bank-transfer order handling.
- `bank_transfer_title`, `bank_transfer_description`, `bank_transfer_instruction`: offline gateway title, customer description, and bank instructions.

Bank Transfer is the built-in offline gateway in core Directorist settings. It is not extension-only. Other gateways may be extension-provided and can extend `active_gateways`, `default_gateway`, and related gateway settings through filters or extension code.

Redesigned UI note (2026-06-10): payment gateway extension submenus registered through `atbdp_monetization_settings_submenu`, such as `Authorize.net Gateway` and `Paypal`, render under the existing `Monetization` sidebar menu as additional tabs after `Payment gateways`. They preserve the extension-provided labels, sections, fields, show-if behavior, and saved option keys. They must not be routed to `Needs Design` or generic `Extensions` simply because they are extension-owned.

## Personalization

Purpose: visual branding colors for Directorist frontend/admin output controlled by settings.

- `brand_color`: primary brand color.
- `button_type`: chooses which button style group is being edited.
- `button_primary_example`, `button_primary_color`, `button_primary_bg_color`: primary button preview and colors.
- `button_secondary_example`, `button_secondary_color`, `button_secondary_bg_color`: secondary button preview and colors.
- `marker_shape_color`, `marker_icon_color`: all-listings map marker colors.

## Extensions

Purpose: core extension discovery/promotion within the settings panel.

Source: direct core extension menu from `class-settings-panel.php`; extension-specific settings depend on installed/active extensions.

- `extension_promotion`: note linking admins to Directorist extensions.

Current redesign behavior (2026-06-08): the core `Extensions` menu renders `extension_promotion` as a reference-style empty-state card:

- Card header: `Extensions`.
- Empty state: `Installed extensions` with `No extensions installed yet. Each extension you install will add its own section here.`
- Browse row: `Browse extensions` with `30+ extensions available including PayPal, Stripe, Live Chat, Universal Search, Booking, and Pricing Plans.`
- Action: `View directory`, linking to the existing Directorist extensions admin page. This is presentation-only and does not add extension-specific settings.

Current redesign behavior (2026-06-09): runtime extension settings registered through `atbdp_extension_settings_submenu` now render under the redesigned `Extensions` menu using the extension-provided submenu label, for example `Booking`, `Pricing Plans`, or `Social Login` when those extensions are active. The redesign preserves each extension's existing section/field definitions and save keys. These extension-owned fields should not be routed to `Needs Design` simply because they are not core mockup fields. The canonical core layout key is `extension_settings`; `extensions_settings` remains only as a compatibility hash alias.

Hook-routing rule (2026-06-10): extension-owned settings should follow the hook/source layout that registered them. Settings registered through `atbdp_extension_settings_submenu` belong under `Extensions`; payment gateway extension settings registered through `atbdp_monetization_settings_submenu` belong under `Monetization`; settings registered by extensions through `atbdp_pages_settings_fields` belong under `Site & pages > Pages`; settings registered through `atbdp_email_templates_settings_sections` belong under `Notifications > Events & Templates`. Do not classify extension-owned fields as `Needs Design` when their source hook maps to an existing redesigned tab.

Extension-specific design rule: do not assume settings for Directorist extensions are present from core alone. If a design targets an extension, identify the exact extension, install/activate only after user approval, inspect its filters/settings/layouts, then document any confirmed extension-provided options under an extension-specific heading.

## Import And Export

Purpose: listing import/export actions and settings backup/restore actions.

Current redesign behavior (2026-06-08): the `Import / Export` menu renders two reference-style cards without subnavigation:

- `Listings`: CSV listing migration actions.
- `Settings`: JSON settings migration actions and defaults restore.

Existing keys and behavior are preserved:

- `listing_import_button`: link/action for running the CSV listing importer, shown as `Import listings` with an `Import CSV` button.
- `listing_export_button`: exports listings data, shown as `Export listings` with an `Export CSV` button.
- `import_settings`: imports a settings backup file, shown as `Import settings` with an `Import JSON` button.
- `export_settings`: exports a settings backup file, shown as `Export settings` with an `Export JSON` button.
- `restore_default_settings`: restores default settings from bundled sample data, shown as `Restore defaults` with a danger-outline `Restore defaults` button.

## Advanced

Purpose: SEO meta settings, cache controls, debug behavior, and uninstall cleanup.

Source: SEO and miscellaneous sections are direct core settings from `class-settings-panel.php`. Schema Markup is a core filter-injected Advanced submenu from `includes/classes/class-schema.php`.

### Title And Meta SEO

- `atbdp_enable_seo`: enables Directorist-managed SEO title/meta fields.
- `add_listing_page_meta_title`, `add_listing_page_meta_desc`: add listing page meta.
- `all_listing_meta_title`, `all_listing_meta_desc`: all listings page meta.
- `dashboard_meta_title`, `dashboard_meta_desc`: user dashboard page meta.
- `author_profile_meta_title`, `author_page_meta_desc`: author profile page meta.
- `category_meta_title`, `category_meta_desc`: category archive/index meta.
- `single_category_meta_title`, `single_category_meta_desc`: single category page meta.
- `all_locations_meta_title`, `all_locations_meta_desc`: all locations page meta.
- `single_locations_meta_title`, `single_locations_meta_desc`: single location page meta.
- `registration_meta_title`, `registration_meta_desc`: registration page meta.
- `login_meta_title`, `login_meta_desc`: login page meta.
- `homepage_meta_title`, `homepage_meta_desc`: home page meta.
- `meta_title_for_search_result`, `search_result_meta_title`, `search_result_meta_desc`: search result meta behavior/content.

Redesigned UI note: SEO renders as `Built-in SEO` plus `Page titles and descriptions`. `meta_title_for_search_result` remains in the layout as a hidden preserved key for now; no visible search-friendly title control is shown until the behavior is revisited. Page meta fields render as full-width title inputs and description textareas, with non-primary page fields under the `Meta for the other pages` disclosure. The UI is presentation-only and still writes the existing `*_meta_title`, `*_meta_desc`, `meta_title_for_search_result`, `search_result_meta_title`, and `search_result_meta_desc` option keys.

### Schema Markup

Source: core filter-injected settings from `includes/classes/class-schema.php`, added through `atbdp_advanced_submenu` and `atbdp_listing_type_settings_field_list`.

Redesigned UI note: Schema renders as a `Schema markup` card and, when `apply_schema_markup = per-directory`, a separate `Schema type per directory` card. `directory_schema_type_global` is rendered only once in the first card. Dynamic `directory_schema_type_{directory_id}` fields remain existing saved keys, are grouped in the per-directory card, and are not migrated or renamed.

- `enable_schema_markup`: enables JSON-LD schema output in the frontend footer.
- `apply_schema_markup`: chooses whether one schema type applies to all directories or different schema types apply per directory.
- `directory_schema_type_global`: global schema type when schema applies to all directories.
- `directory_schema_type_{directory_id}`: dynamic per-directory schema type keys generated when multi-directory data exists and per-directory schema is selected.

Schema output uses `Directorist\Schema::print_schema()` and `Directorist\Schema::get_schema()`. This affects frontend structured data, listing fields, rating data, address/geo/social data, and SEO behavior, so treat schema changes as high risk.

### Miscellaneous

- `atbdp_enable_cache`: enables Directorist cache behavior.
- `atbdp_reset_cache`: existing reset-cache toggle field. Hidden in the redesigned Maintenance tab for now because it is not a true instant reset action; revisit when adding a dedicated reset-cache AJAX action.
- `script_debugging`: loads unminified CSS/JS files for debugging.
- `enable_uninstall`: controls data cleanup behavior on uninstall.

## Filtered Or Extension-Provided Options

The settings panel applies many filters around fields and layout sections. Some are core module injections, and others may be extension-provided.

- `atbdp_listing_type_settings_field_list`
- `atbdp_listing_type_settings_layout`
- `atbdp_listing_settings_submenu`
- `atbdp_listing_settings_review_sections`
- `atbdp_pages_settings_fields`
- `directorist_search_setting_sections`
- `atbdp_user_settings_submenu`
- `atbdp_email_templates_settings_sections`
- `atbdp_monetization_settings_submenu`
- `atbdp_style_settings_controls`
- `atbdp_extension_fields`
- `atbdp_caching_controls`
- `atbdp_advanced_submenu`
- `directorist_schema_controls`

Before assuming this catalog is exhaustive for a live site, inspect active extensions and filter usage.

## Source Type Summary

- Direct core settings: settings defined and laid out directly in `includes/classes/class-settings-panel.php`.
- Core filter-injected settings: settings provided by bundled core modules through filters, such as Review and Schema Markup.
- Extension-provided settings: settings added only when a Directorist extension is installed/active. These must be inspected from the extension source before design or implementation work.
