# Settings Redesign Design Gaps

Last reviewed: 2026-06-04

This report lists current Directorist settings that exist in the old settings panel but are not represented in the static redesign mockup at:

`D:\New folder (4)\Directorist setting panel new design static design\settings-redesign-mockup.html`

Implementation status:

- These options are preserved and still save through the existing settings contract.
- Recognizable old-source settings are routed into their matching redesigned tabs before the `Needs Design` fallback runs.
- Only truly unknown runtime fields are routed to the bottom `Needs Design` menu in the redesigned settings panel.
- They need final static-design treatment before they should be merged into the main redesigned navigation.

## Needs Design Menu

### Listings / All Listings

The static mockup does not include a complete All Listings display/layout configuration screen.

- `all_listing_layout`
- `all_listing_columns`
- `all_listing_page_items`
- `pagination_type`
- `listing_hide_top_search_bar`
- `listings_sidebar_filter_text`
- `listings_reset_text`
- `listings_sidebar_reset_text`
- `listings_apply_text`
- `display_listings_header`
- `listing_filters_button`
- `listings_filter_button_text`
- `display_listings_count`
- `all_listing_title`
- `listings_view_as_items`
- `default_listing_view`
- `display_sort_by`
- `sort_by_text`
- `listings_sort_by_items`
- `preview_image_quality`
- `way_to_show_preview`
- `crop_width`
- `crop_height`
- `prv_container_size_by`
- `prv_background_type`
- `prv_background_color`

Design needed:

- Listing archive layout/card/grid controls.
- Filter/search header controls for all listings.
- Sorting/view-mode controls.
- Preview image sizing/crop/background controls.

### Listings / Category & Location

The static mockup includes taxonomy archive slugs, but not category/location listing-page display controls.

- `display_categories_as`
- `categories_column_number`
- `categories_depth_number`
- `order_category_by`
- `sort_category_by`
- `display_listing_count`
- `hide_empty_categories`
- `display_locations_as`
- `locations_column_number`
- `locations_depth_number`
- `order_location_by`
- `sort_location_by`
- `display_location_listing_count`
- `hide_empty_locations`

Design needed:

- Category directory display layout.
- Location directory display layout.
- Ordering, depth, count, and empty-term visibility controls.

### Search / Search Listing

The static mockup has page mapping for Search Listing, but does not include the search form builder/copy controls.

- `search_title`
- `search_subtitle`
- `search_listing_text`
- `search_more_filter`
- `search_more_filters`
- `search_filters`
- `search_reset_text`
- `search_apply_filter`
- `show_popular_category`
- `popular_cat_title`
- `popular_cat_num`

Design needed:

- Search form heading/copy controls.
- More-filter behavior and visible filter selection.
- Popular category controls.

### Search / Search Result

The static mockup has page mapping and SEO for search results, but not search-result layout controls.

- `search_result_layout`
- `search_listing_columns`
- `search_posts_num`
- `search_result_hide_top_search_bar`
- `search_result_sidebar_filter_text`
- `sresult_reset_text`
- `sresult_sidebar_reset_text`
- `sresult_apply_text`
- `search_header`
- `search_result_filters_button_display`
- `search_result_filter_button_text`
- `display_search_result_listings_count`
- `search_result_listing_title`
- `search_view_as_items`
- `search_sort_by`
- `search_sortby_text`
- `search_sort_by_items`

Design needed:

- Search-result layout and pagination controls.
- Search-result filter/sidebar copy controls.
- Sorting and view-mode controls.

### User / Registration Form

The static mockup includes account-level registration toggles, but not the detailed registration form field controls.

- `reg_username`
- `reg_email`
- `display_password_reg`
- `reg_password`
- `require_password_reg`
- `display_website_reg`
- `reg_website`
- `require_website_reg`
- `display_fname_reg`
- `reg_fname`
- `require_fname_reg`
- `display_lname_reg`
- `reg_lname`
- `require_lname_reg`
- `display_bio_reg`
- `reg_bio`
- `require_bio_reg`
- `display_user_type`
- `registration_privacy`
- `registration_privacy_label`
- `registration_privacy_label_link`
- `regi_terms_condition`
- `regi_terms_label`
- `regi_terms_label_link`
- `reg_signup`
- `display_login`
- `login_text`
- `log_linkingmsg`
- `auto_login`
- `redirection_after_reg`

Design needed:

- Registration form field matrix.
- Required/optional field controls.
- Privacy/terms copy/link controls.
- Post-registration behavior controls.

### User / Login Form

The static mockup does not include detailed login and password-recovery form controls.

- `log_username`
- `log_password`
- `display_rememberme`
- `log_rememberme`
- `log_button`
- `display_signup`
- `reg_text`
- `reg_linktxt`
- `display_recpass`
- `recpass_text`
- `recpass_desc`
- `recpass_username`
- `recpass_placeholder`
- `recpass_button`
- `redirection_after_login`

Design needed:

- Login form labels and CTA copy.
- Signup prompt controls.
- Password recovery controls.
- Post-login redirect behavior.

### User / Dashboard

The static mockup maps the User Dashboard page, but does not include dashboard tab and author workflow controls.

- `my_profile_tab`
- `my_profile_tab_text`
- `fav_listings_tab`
- `fav_listings_tab_text`
- `my_listing_tab`
- `my_listing_tab_text`
- `user_listings_pagination`
- `user_listings_per_page`
- `submit_listing_button`
- `become_author_button`
- `become_author_button_text`

Design needed:

- Dashboard tab visibility/label controls.
- Dashboard listing pagination controls.
- Submit listing and become-author CTA controls.

### User / All Authors

The static mockup maps the Author Profile page, but not All Authors page display controls.

- `all_authors_columns`
- `all_authors_sorting`
- `all_authors_image`
- `all_authors_name`
- `all_authors_contact`
- `all_authors_description`
- `all_authors_social_info`
- `all_authors_select_role`
- `all_authors_description_limit`
- `all_authors_button`
- `all_authors_button_text`
- `all_authors_pagination`
- `all_authors_per_page`

Design needed:

- All Authors card layout controls.
- Author card field visibility controls.
- Role filtering and pagination controls.

## Runtime/Extension Gaps

The settings panel can receive additional fields through filters and active extensions. Runtime extension fields registered under `atbdp_extension_settings_submenu` are preserved under the redesigned `Extensions` menu by extension submenu label. Runtime fields from known old-source areas are routed to their matching redesigned tabs before the fallback: page setup fields go to `Site & pages > Pages`, email template fields go to `Notifications > Events & Templates`, and extension general fields go to `Extensions > Extensions General`. Other runtime fields not explicitly mapped to the static mockup are preserved and routed to `Needs Design` automatically.

Known examples:

- Filter-injected fields from `atbdp_listing_type_settings_field_list`.
- Filter-injected sections from `directorist_search_setting_sections`, `atbdp_user_settings_submenu`, `atbdp_pages_settings_fields`, `atbdp_style_settings_controls`, `atbdp_caching_controls`, and related settings filters.

Design needed:

- A standard pattern for unmapped filtered fields so they can be reviewed before being promoted into the main redesign.
