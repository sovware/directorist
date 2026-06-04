# AGENTS.md

This file provides guidance to Codex (Codex.ai/code) when working with code in this repository.

> Last updated: 2026-04-09

## Project

- **Name:** Directorist — Business Directory Solution
- **Type:** WordPress plugin (GPLv3)
- **Version:** 8.6.7+
- **Repo:** https://github.com/sovware/directorist
- **Entry point:** `directorist-base.php` → singleton `Directorist_Base`
- **Config:** `config.php` — constants: `ATBDP_VERSION`, `ATBDP_POST_TYPE` (`at_biz_dir`), `ATBDP_TEXTDOMAIN` (`directorist`)

## Tech Stack

| Layer | Technology |
|-------|-----------|
| PHP | 7.0+ (PHPCS targets 7.4), WordPress 4.6+ (tested 6.9) |
| Admin UI | Vue 2.6 + Vuex (builder/settings), jQuery (legacy) |
| Blocks | React via @wordpress/components |
| CSS | SCSS → Webpack → CSS + RTL variants |
| Build | Webpack 5, npm scripts, Husky + lint-staged |
| PHP QA | PHPCS + WordPress Coding Standards 2.3 |
| JS QA | Prettier 3.5.3, wp-scripts |

## Commands

| Task | Command |
|------|---------|
| Dev build (JS/CSS) | `npm run dev` |
| Dev build (Vue) | `npm run dev-vue` |
| Production build | `npm run prod` |
| Format JS/CSS/SCSS | `npm run format` |
| PHP lint | `composer phpcs` |
| PHP auto-fix | `composer format` |
| PHP 8.1 compat check | `composer sniffer:php8.1` |

## Workflow

- **Main branch:** `trunk`
- **Branch prefixes:** `fix/`, `add/`, `improve/`, `feature/`, `style/`, `build/`
- **Commit style:** Conventional — `fix:`, `improve:`, `revert:`, `style:`, `build:`
- **CI:** PHPCS on PRs (`phpcs.yml`), WordPress.org SVN deploy on tags (`wp-deploy.yml`), FTP staging on `development` push
- **Pre-commit:** Husky runs Prettier (JS/CSS/SCSS) + phpcbf (PHP) via lint-staged

## Architecture Overview

```
includes/
├── classes/          # Core classes (ajax, email, extension, listing, metabox, settings, user, tools)
├── model/            # Data models (9 classes — see Model Layer below)
├── fields/           # Custom field type system (Base_Field + 24 field types)
├── rest-api/         # REST controllers: Version1/ (16), Version2/ (1)
├── checkout/         # Checkout flow (ATBDP_Checkout)
├── gateways/         # Payment gateways (ATBDP_Gateway base, ATBDP_Offline_Gateway)
├── payments/         # Order management (ATBDP_Order)
├── elementor/        # 19 Elementor widgets
├── hooks/            # Hook registration
├── helpers/          # Traits: Icon, Markup, URI
├── modules/          # Background processing, multi-directory setup, Appsero
├── review/           # Review system
├── database/         # ATBDP_Database base class
├── data-store/       # Term caching (ATBDP_Terms_Store)
├── asset-loader/     # Dynamic asset enqueue system
└── widgets/          # Classic WordPress widgets
templates/            # Public templates (theme-overridable)
views/admin-templates/ # Admin UI templates
blocks/src/ → build/  # 21 Gutenberg blocks
assets/src/js/        # JS source (public/, admin/, global/, lib/)
assets/src/scss/      # SCSS source
```

## REST API

**Namespace:** `directorist/v1` (and `directorist/v2`)
**Base classes:** `Abstract_Controller` (extends `WP_REST_Controller`) → `Posts_Controller` | `Terms_Controller`

| Controller | Route | Base |
|-----------|-------|------|
| Listings_Controller | `/listings` | Posts_Controller |
| Listings_Actions_Controller | `/listings/{id}/actions` | Abstract_Controller |
| Listing_Reviews_Controller | `/listings/reviews` | Abstract_Controller |
| Categories_Controller | `/listings/categories` | Terms_Controller |
| Locations_Controller | `/listings/locations` | Terms_Controller |
| Tags_Controller | `/listings/tags` | Terms_Controller |
| Directories_Controller | `/directories` | Terms_Controller |
| Users_Controller | `/users` | Abstract_Controller |
| User_Favorites_Controller | `/users/{id}/favorites` | Abstract_Controller |
| Users_Account_Controller | `/users/account/...` | Abstract_Controller |
| Builder_Controller | `/builder/{tab}` | Abstract_Controller |
| Plans_Controller | `/plans` | Posts_Controller |
| Orders_Controller | `/orders` | Posts_Controller |
| Pages_Controller | `/pages` | Abstract_Controller |
| Admin_Controller | `/admin/install-plugin` | Abstract_Controller |
| Temporary_Media_Upload_Controller | `/temp-media-upload` | Abstract_Controller |
| v2 Listings_Controller | `/listings` (v2) | v1 Listings_Controller |

Location: `includes/rest-api/Version1/`, `includes/rest-api/Version2/`

## Field/Form Builder System

**Base class:** `Directorist\Fields\Base_Field` (`includes/fields/class-directorist-base-field.php`)
**Manager:** `Directorist\Fields\Fields` — `register()`, `get()`, `create()`, `exists()`
**Registration:** Each field extends `Base_Field`, sets `$type`, auto-registers at include time.
**Legacy mapping:** `Fields::translate_key_to_field()` + filter `directorist_listing_form_fields_class_map`

**24 field types:** text, textarea, number, url, date, time, color_picker, select, checkbox, radio, file, description, viewcount, email, video, switch, taxonomy, locations, categories, tags, pricing, map, social_info, image_upload

Location: `includes/fields/`

## Vue Admin Builder

**Entry points:**
- `assets/src/js/admin/multi-directory-builder.js` → mounts on `#atbdp-cpt-manager`
- Settings manager entry in `webpack-entry-list.js`

**Store:** `assets/src/js/admin/vue/store/CPT_Manager_Store.js` (Vuex)
- State: `fields`, `layouts`, `options`, `config`, `listing_type_id`, `metaKeys`, `sidebarNavigation`
- 18 mutations for managing builder data

**Apps:** `assets/src/js/admin/vue/apps/`
- `cpt-manager/` — listing type builder (CPT_Manager.vue)
- `settings-manager/` — settings UI (Settings_Manager.vue)

**Modules:** Auto-registered via `require.context()` in `global-component.js` (PascalCase naming)
- 17 card widget components, form builder drag-drop components, 66 form field components
- **Mixins:** 30 files in `vue/mixins/` for shared field logic
- **Themes:** Form fields support Default and Butterfly theme variants

**Data flow:** Server → base64-encoded JSON in DOM `data-builder-data` attribute → Vue instance → Vuex store

## Model Layer

| Model | File | Purpose |
|-------|------|---------|
| Listings | Listings.php (115 KB) | Core listing queries, taxonomy filtering, search, rendering |
| SingleListing | SingleListing.php (59 KB) | Single listing page: metadata, pricing, coordinates, reviews |
| SearchForm | SearchForm.php (47 KB) | Search form builder and filter management |
| ListingForm | ListingForm.php (43 KB) | Listing entry form, field handling, post management |
| ListingDashboard | ListingDashboard.php (25 KB) | User dashboard, listing management, renewals |
| ListingTaxonomy | ListingTaxonomy.php (19 KB) | Category/location/tag queries and display |
| ListingAuthor | ListingAuthor.php (13 KB) | Author profile with ratings and listing queries |
| Account | Account.php (12 KB) | Sign-in/sign-up shortcode and forms |
| All_Authors | All_Authors.php (5 KB) | Author listing with sorting |

Location: `includes/model/` — namespace `Directorist`

## Payment & Orders

**Gateway base:** `ATBDP_Gateway` (`includes/gateways/class-gateway.php`)
**Built-in:** `ATBDP_Offline_Gateway` (bank transfer)
**Checkout:** `ATBDP_Checkout` (`includes/checkout/class-checkout.php`) — `create_order()`, `process_payment()`, `complete_free_order()`
**Order post type:** `atbdp_orders`

**Order meta keys:** `_listing_id`, `_featured`, `_amount`, `_payment_gateway`, `_payment_status` (created/completed/pending), `_transaction_id`, `_refresh_renewal_token`

**Key hooks:** `atbdp_order_created`, `atbdp_order_completed`, `atbdp_process_{$gateway}_payment` (dynamic per gateway)

## Template System

**Lookup hierarchy** (via `atbdp_get_template()`):
1. Child theme: `wp-content/themes/{child}/directorist/{file}.php`
2. Parent theme: `wp-content/themes/{parent}/directorist/{file}.php`
3. Plugin fallback: `views/{file}.php`

**Functions:** `atbdp_get_template()`, `atbdp_get_template_path()`, `atbdp_get_admin_template()`, `atbdp_get_widget_template()`, `atbdp_get_extension_template()`

Location: `includes/template-functions.php`, public templates in `templates/`, admin in `views/admin-templates/`

## Block Editor (Gutenberg)

**21 blocks** registered in `blocks/init.php` via `register_block_type()` with `block.json` (apiVersion 3):
account-button, author-profile, authors, categories, checkout, dashboard, listing-form, listings, locations, payment-receipt, search-form, search-modal, search-result, signin-signup, single-category, single-listing, single-location, single-tag, transaction-failure, vendors

**Pattern:** Render callback (`directorist_block_render_callback`) converts block attributes to shortcode calls.
**Category:** `directorist-blocks-collection`
**Source:** `blocks/src/` → `blocks/build/`

## Elementor Integration

**19 widgets** in `includes/elementor/` — namespace `AazzTech\Directorist\Elementor`, base class in `base.php`
Widgets: all-listing, all-categories, all-locations, category, location, tag, search-listing, search-result, add-listing, user-login, custom-registration, user-dashboard, author-profile, transaction-failure, payment-receipt, checkout, deprecated-notice
**Theme override:** `STYLESHEETPATH/directorist-elementor/{widget}.php`

## AJAX Endpoints

**Handler:** `includes/classes/class-ajax-handler.php` (89 KB)
**Naming:** `wp_ajax_atbdp_*` (legacy) and `wp_ajax_directorist_*` (new)

**Key categories:**
- **Listings:** `remove_listing`, `directorist_upload_listing_image`, `directorist_import_listings`, `directorist_track_listing_views`
- **Search:** `directorist_instant_search`, `directorist_zipcode_search`, `bdas_public_dropdown_terms`, `directorist_category_custom_field_search`
- **Users:** `update_user_profile`, `update_user_preferences`, `ajaxlogin`, `atbdp_ajax_quick_login`, `atbdp_become_author`, `directorist_register_form`
- **Favorites:** `atbdp_public_add_remove_favorites`, `atbdp-favourites-all-listing`
- **Contact:** `atbdp_public_report_abuse`, `atbdp_public_send_contact_email`
- **Admin/Builder:** `directorist_type_slug_change`, `directorist_load_category_custom_fields`, `save_settings_data`, `atbdp_listing_default_type`
- **Extensions:** `atbdp_authenticate_the_customer`, `atbdp_download_file`, `atbdp_install_file_from_subscriptions`, `atbdp_plugins_bulk_action`
- **Taxonomy options:** `directorist_get_category_options`, `directorist_get_tag_options`, `directorist_get_location_options`

## Key Hooks & Filters

**Lifecycle:** `directorist_loaded`, `directorist_installed`, `directorist_updated`

**Listing CRUD:** `atbdp_listing_inserted`, `atbdp_listing_updated`, `atbdp_listing_published`, `atbdp_before_processing_listing_frontend`, `atbdp_after_created_listing`

**Data filters:** `atbdp_listing_meta_user_submission`, `directorist_update_listing_data`, `directorist_insert_listing_data`, `atbdp_add_listing_form_validation_logic`

**Email:** `directorist_email_on_notify_owner_*` (order_created, order_completed, listing_submitted, listing_published, listing_edited, listing_to_expire, listing_expired), `directorist_replace_in_content`

**Extension:** `directorist_extensions_aliases`, `directorist_required_extensions`
**Settings:** `atbdp_listing_type_settings_field_list`
**Templates:** `directorist_admin_template`
**Display:** `directorist_localized_data`, `directorist_scripts`, `directorist_load_inline_style`

**Prefix convention:** `atbdp_` (legacy, still widely used) → `directorist_` (new standard)

## Database Schema

**Post Types:** `at_biz_dir` (listings), `atbdp_orders`, `atbdp_fields` (custom fields)
**Taxonomies:** `at_biz_dir-category`, `at_biz_dir-location`, `at_biz_dir-tags`, `atbdp_listing_types` (directory types)

**Listing meta (`at_biz_dir`):** `_price`, `_price_range`, `_atbd_listing_pricing`, `_address`, `_email`, `_phone`, `_tagline`, `_social` (JSON), `_manual_lat`, `_manual_lng`, `_hide_map`, `_featured`, `_listing_status`, `_expiry_date`, `_never_expire`, `_directory_type`, `_disable_bz_hour_listing`

**Order meta (`atbdp_orders`):** `_listing_id`, `_featured`, `_amount`, `_payment_gateway`, `_payment_status`, `_transaction_id`

**User meta:** `_user_type`, `pro_pic` (profile picture), `_atbdp_purchased_products`, `_atbdp_has_subscriptions_sassion`, `_subscribed_users_plan_id`

**Term meta:** `_directory_type` (taxonomy→directory association), `_default`, `_created_date`

## Settings Architecture

**Class:** `ATBDP_Settings_Panel` (`includes/classes/class-settings-panel.php`, 263 KB)
**Registration:** Add fields via `atbdp_listing_type_settings_field_list` filter
**Save:** `wp_ajax_save_settings_data`
**Options prefix:** `atbdp_` (legacy) and `directorist_` (new)
**Features:** Import/export settings, restore defaults, per-directory-type settings, conditional field visibility (show-if)
**Settings panel agent:** Before redesigning, auditing, or adding settings-panel features, load `docs/agents/directorist-settings-panel/SKILL.md` and follow its report-first workflow.

## Build Pipeline

| Config | Purpose | Watch |
|--------|---------|-------|
| `webpack.common.js` | Shared loaders (Vue, Babel, SASS, images) | — |
| `webpack.dev.js` | JS/CSS dev build (commonEntries) | Yes |
| `webpack.dev.vue.js` | Vue admin dev build (vueEntries) | Yes |
| `webpack.prod.js` | Minified + RTL + zip packaging | No |

**Entry list:** `webpack-entry-list.js` defines `commonEntries` (30+ public/admin/global bundles) and `vueEntries` (admin-multi-directory-builder, admin-settings-manager)
**Output:** `assets/js/`, `assets/css/` — production adds `.min.js`, `.min.css`, `.rtl.min.css`
**Packaging:** Production builds to `__build/directorist/` → `directorist.zip`
**RTL:** Auto-generated via webpack-rtl-plugin

## Naming Conventions

- **PHP functions:** `snake_case` — `get_directorist_option()`
- **PHP classes:** `PascalCase` — `namespace Directorist;`
- **JS functions:** `camelCase`
- **CSS classes:** `directorist-` prefix, BEM-inspired — `.directorist-alert__close`
- **CSS variables:** `--directorist-color-primary`
- **Hooks:** `atbdp_` (legacy) / `directorist_` (new)
- **Text domain:** `directorist` (constant `ATBDP_TEXTDOMAIN`)
- **Custom PHPCS rules:** `directorist_clean` (sanitization), `directorist_kses` (escaping), `directorist_verify_nonce` (nonce)

## Extension System

**Manager:** `includes/classes/class-extension.php` (144 KB)
**Lifecycle:** Authentication → download → install → activate (via AJAX)
**Key AJAX actions:** `atbdp_authenticate_the_customer`, `atbdp_download_file`, `atbdp_install_file_from_subscriptions`
**Filters:** `directorist_extensions_aliases` (deprecated name mapping), `directorist_required_extensions`

## Notes

- Legacy prefix `atbdp_` (AT Business Directory Plus) coexists with `directorist_` — new code should use `directorist_`
- Build files (`assets/css/`, `assets/js/`) are committed to the repo
- Multi-directory support: each listing type (`atbdp_listing_types` taxonomy) has its own builder config stored as term meta
- Builder data is base64-encoded JSON containing fields, layouts, options, config
