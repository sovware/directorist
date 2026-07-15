# Appearance Badges Context

Last reviewed: 2026-07-14

## Design Reference

Use `/Users/rabbiislamrony/Local Sites/test/app/public/wp-content/plugins/directorist/assets/other/settings-redesign-mockup.html` as the visual reference. The relevant areas are the `app-badges` section, `.badge-toolbar`, `.badge-list`, `.badge-item`, `.b-chip`, `.b-type-picker`, `.b-edit-grid`, `.b-color-input`, and `.b-conditions` styles/scripts.

Match the visual language: toolbar tip, Reset defaults, Add badge, accordion badge rows, chip previews, compact color inputs, condition summary, fixed core badge cards, and custom badge rows appended below the core badges.

The mockup's match-condition builder includes `General`, `Field`, and `Pricing plan` sources, `All (AND)` / `Any (OR)` matching, add/remove condition rows, and dynamic operators by value type. Directorist core uses `directorist_badge_rules` for those rules. Core badges mirror compatible legacy options; custom badges live only in the rule store and become optional builder widgets.

## Required Source Inspection

Inspect these files before changing badge settings:

- `includes/classes/class-settings-panel.php`
- `assets/src/js/admin/vue/apps/settings-manager/settings-redesign-map.js`
- `assets/src/js/admin/vue/modules/Sections_Module.vue`
- `assets/src/js/admin/vue/apps/settings-manager/Settings_Manager.vue`
- `assets/src/js/admin/settings-manager.js`
- `assets/src/scss/layout/admin/builder/_settings_panel.scss`
- `includes/classes/class-stylesheet.php`
- `includes/class-helper.php`
- `includes/model/Listings.php`
- `templates/archive/fields/badge.php`
- `templates/single/fields/badges.php`

## Existing Option Keys

Global display:

- `badge_display_type`: `text_badge` or `icon_badge`; applies to archive badge rendering.
- `directorist_badge_rules`: hidden JSON-like badge rule store. Empty by default on existing sites; when core badge conditions are empty or invalid, runtime falls back to the legacy `New`, `Popular`, and `Featured` checks. Custom badges with empty conditions show wherever their builder widget is placed; invalid custom conditions fail closed. When present, the store may contain core badge overrides and custom badge definitions.
  - The setting field also carries `condition_sources.general`, `condition_sources.fields`, and `condition_sources.pricing` metadata for the custom Vue condition builder.

New badge:

- `new_badge_text`: visible text or icon hover text.
- `new_listing_day`: age threshold in days.
- `new_back_color`: badge background or icon-hover background label in settings.

Popular badge:

- `popular_badge_text`: visible text or icon hover text.
- `listing_popular_by`: `view_count` or `average_rating`.
- `views_for_popular`: view threshold.
- `average_review_for_popular`: rating threshold.
- `popular_back_color`: badge background or icon-hover background label in settings.

Featured badge:

- `feature_badge_text`: visible text or icon hover text.
- `featured_back_color`: badge background or icon-hover background label in settings.

## Runtime Readers

- `Directorist\Helper::new_badge_text()`, `popular_badge_text()`, and `featured_badge_text()` read badge labels.
- `Directorist\Helper::is_new()` reads `new_listing_day`.
- `Directorist\Helper::is_popular()` reads `listing_popular_by`, `views_for_popular`, and `average_review_for_popular`.
- `Directorist\Helper::display_badge()` and `matched_badges()` evaluate `directorist_badge_rules` when present. Core badges fall back to `is_new()`, `is_popular()`, and `is_featured()` for empty/invalid conditions. Custom badges match all listings when their conditions are empty, and fail closed when their saved conditions are invalid.
- `Directorist\Helper::badge_definitions()`, `custom_badge_definitions()`, `badge_template_data()`, and `badge_builder_widgets()` normalize rule data for rendering and builder registration.
- Field badge conditions read listing post meta by field key, first with the `_field_key` prefix and then unprefixed. The value can be adjusted with `directorist_badge_rule_field_value`.
- Pricing badge conditions read the core listing plan meta key `_fm_plans` and can be adjusted with `directorist_badge_rule_pricing_plan_meta_keys` and `directorist_badge_rule_pricing_plan_ids`.
- `Directorist\Directorist_Listings::render_badge_template()` maps the builder widget key back to a badge definition, checks `Helper::display_badge()`, then renders the existing archive badge template.
- `Directorist\Directorist_Single_Listing::matched_badges()` loops the same matched badge definitions while preserving the existing single listing badge wrapper.
- REST v1/v2 listing `new` and `popular` response fields use `Helper::display_badge()` for consistency with rendered badge visibility.
- `includes/classes/class-stylesheet.php` reads the three badge color keys and prints dynamic CSS.
- `includes/modules/multi-directory-setup/class-builder-data.php` keeps core builder widget keys and appends custom badge widgets only to badge-compatible accepted widget lists.
- `templates/archive/fields/badge.php` switches archive badge markup based on normalized per-badge type and supports inline background, text, and border colors.
- `templates/single/fields/badges.php` renders matched badge labels/icons with the same normalized colors while preserving the wrapper/span shape.

## Safe Implementation Shape

- Build a custom Vue settings component that receives the shared `fields` object and emits `update-field` events for existing keys and `directorist_badge_rules`.
- Keep values primitive strings, matching the existing field components.
- Keep backing fields in the layout but hide their generic rows for the Badges tab.
- Add `cptm-field-wraper-key-{field}` classes to custom controls so quick-search highlighting still finds target fields.
- Wire General conditions to PHP-provided condition metadata, existing option keys where they map cleanly, and `directorist_badge_rules`. Field and Pricing plan rows should update only `directorist_badge_rules`.
- Use the existing Directorist icon picker library for badge icons. The settings page must enqueue `directorist-icon-picker` before the settings manager bundle.
- Use stable custom keys such as `custom_badge_xxxxxx`.
- Internal name changes admin and builder labels; visible label changes frontend output.
- Preserve original core badge icon defaults: New uses `la la-bolt`, Popular uses `la la-fire`, and Featured uses `la la-star-o`. Saved badge icons override those defaults and update frontend rendering plus the builder widget icon class, but builder chips stay in the old neutral builder style and must not inherit badge colors or icon-only badge presentation.
- Existing core rules saved before explicit type/icon tracking may contain stale `type` values or the old generic star fallback. Treat unedited core `type` as the global `badge_display_type`, and treat unedited New/Popular `la la-star-o`/star aliases as the core defaults (`la la-bolt`, `la la-fire`) unless the rule has `typeEdited: true` or `iconEdited: true`.
- Treat `style.text` as the visible text color in text mode and as the icon mask color in icon mode.
- Text badges render the selected icon plus the visible label when `icon` is present; if the admin removes the icon, text badges render the visible label only. Icon badges always render icon-only with tooltip text and must restore the badge default icon if the saved icon is empty.
- Icon badge tooltip overrides live in `hover.text`, `hover.bg`, and `hover.textColor`. Empty tooltip values fall back to the visible label, badge background, and icon/text color so old rules do not need migration.
- Frontend archive, single, and legacy badge output must match the Appearance preview chip tokens: 999px radius, 24px text-badge height, 28px icon-only badge height, 4px vertical padding, 10px text-badge horizontal padding, 6px icon/label gap, 12px/500 text, 13px text-badge icons, 14px icon-only icons, border color from `style.border`, and no mobile shrink away from the saved preview.
- Runtime may tolerate old saved `showIcon` values, but new saves must not emit `showIcon` and must not render dot markers.
- Add/Delete applies to custom badges only. Reset defaults restores core labels, colors, icons, and the default visible core condition rows, and removes custom badges. Empty condition arrays remain valid when an admin manually removes all conditions after reset.
- Condition operators may pass through WordPress admin sanitization during save/load, so the UI must normalize encoded operator tokens such as `&lt;=`, `&lt;`, `&gt;=`, `&gt;`, and `&ne;` back to `<=`, `<`, `>=`, `>`, and `is_not` before rendering summaries, selects, or saved rules.
- Do not add migration code or default backfills.

## Hidden Rule Schema

`directorist_badge_rules` is saved only after a badge setting or condition changes in the custom Badges UI. The Vue component mirrors core badge settings and stores custom badges in this shape:

```json
{
  "version": 1,
  "badges": {
    "new": {
      "enabled": true,
      "internalName": "New",
      "label": "New",
      "type": "text",
      "typeEdited": false,
      "icon": "la la-bolt",
      "iconEdited": false,
      "color": "#2C99FF",
      "style": {
        "bg": "#2C99FF",
        "text": "#FFFFFF",
        "border": "#2C99FF"
      },
      "hover": {
        "text": "",
        "bg": "",
        "textColor": ""
      },
      "match": "all",
      "conditions": []
    },
    "custom_badge_xxxxxx": {
      "enabled": true,
      "internalName": "Custom badge",
      "label": "Custom badge",
      "type": "text",
      "typeEdited": false,
      "icon": "la la-certificate",
      "iconEdited": false,
      "color": "#2C99FF",
      "style": {
        "bg": "#2C99FF",
        "text": "#FFFFFF",
        "border": "#2C99FF"
      },
      "hover": {
        "text": "",
        "bg": "",
        "textColor": ""
      },
      "match": "all",
      "conditions": []
    }
  }
}
```

The other core default icons are `la la-fire` for Popular and `la la-star-o` for Featured. Custom badge defaults use `la la-certificate` unless the admin chooses another icon. The saved icon feeds the builder widget icon class and text badge frontend/admin preview output when present. Badge colors and icon-only badge type do not change the builder chip UI. Removing a text badge icon saves an intentionally empty `icon` with `iconEdited: true`, and the badge renders label-only. The UI must allow `conditions: []`; manual Add condition is the normal user action that creates a condition row, while Reset defaults recreates the default New, Popular, and Featured condition rows.

Supported core General condition keys in PHP and UI are `age_days`, `view_count`, `average_rating`, `review_count`, `is_featured`, and `listing_status`; extra General options must register both metadata through `directorist_badge_rule_general_condition_options` and runtime values through `directorist_badge_rule_general_condition_value`. Field condition keys are discovered from supported listing submission fields and read listing meta. Pricing plan condition keys are `has_plan` and `plan_id`. The UI supports `all` and `any` matching and add/remove condition rows for core and custom badge cards. The first legacy-compatible New and Popular General condition rows also mirror back to `new_listing_day`, `listing_popular_by`, `views_for_popular`, and `average_review_for_popular`.

Field source metadata supports text-like fields, numeric fields, select/radio/checkbox fields, and switch fields. Select-style options must tolerate both Directorist option shapes: `value`/`label` and `option_value`/`option_label`.

Pricing source metadata lists published core pricing plans from `atbdp_pricing_plans` and WooCommerce listing pricing products where available. Treat the runtime plan-ID resolver as extension-sensitive: core reads `_fm_plans` by default, and extensions should use the provided filters instead of hard-coding extension-specific storage outside the adapter.
