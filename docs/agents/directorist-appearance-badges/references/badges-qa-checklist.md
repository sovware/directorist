# Appearance Badges QA Checklist

## Static Checks

- Confirm `class-settings-panel.php` still registers all existing badge keys.
- Confirm `directorist_badge_rules` is registered as a hidden setting, defaults to empty, and does not require a migration/backfill.
- Confirm no new AJAX action, nonce behavior, capability behavior, or save payload shape was introduced.
- Confirm `settings-redesign-map.js` routes `style_settings__badges` to the custom badge manager and does not duplicate generic rows.
- Confirm custom UI emits updates only for existing badge option keys plus `directorist_badge_rules`.
- Confirm core internal name changes affect admin/builder labels only; core visible labels still mirror to `new_badge_text`, `popular_badge_text`, and `feature_badge_text`.
- Confirm core background colors still mirror to `new_back_color`, `popular_back_color`, and `featured_back_color`; text and border colors remain in `directorist_badge_rules`.
- Confirm match-condition rows save General, Field, and Pricing plan conditions in `directorist_badge_rules`.
- Confirm badges can save `conditions: []`; Add condition manually creates condition rows, and Reset defaults recreates the core default condition rows.
- Confirm Reset defaults opens a warning confirmation before changing draft data, Cancel leaves data unchanged, and confirming does not auto-save.
- Confirm Reset defaults creates Popular with `Any (OR)`, `View count >= 5`, and `Average rating >= 4`.
- Confirm legacy-compatible New and Popular condition rows still mirror to `new_listing_day`, `listing_popular_by`, `views_for_popular`, and `average_review_for_popular`; Popular reset keeps `listing_popular_by` as `view_count`.
- Confirm Field and Pricing plan condition rows do not mirror into legacy General badge keys.
- Confirm Add condition, Remove condition, and All/Any match mode update only `directorist_badge_rules`.
- Confirm encoded condition operators such as `&lt;=`, `&lt;`, `&gt;=`, `&gt;`, and `&ne;` normalize back to valid raw operators in summaries, selects, future saved rules, and PHP frontend matching.
- Confirm `directorist_badge_rules` resets to the cached value when visible badge edits are reverted.
- Confirm Field source options come from raw supported listing submission fields, are not hidden by Pricing Plans add-listing gating, and tolerate both `value`/`label` and `option_value`/`option_label` option shapes.
- Confirm General source options come from `condition_sources.general` and unsupported mockup-only conditions are absent unless an extension registers metadata and a runtime value resolver.
- Confirm Pricing plan source options include published legacy core pricing plans, v4 Pricing Plans rows from `directorist_plans`, and WooCommerce listing pricing products when those sources exist.
- Confirm Pricing plan specific-plan rows still render a valid exact-match operator when no plan options are available and the UI falls back to a text value.
- Confirm Add badge creates a stable `custom_badge_*` definition in `directorist_badge_rules`.
- Confirm Delete is available only for custom badges and does not remove core badge definitions.
- Confirm Reset defaults restores core labels/colors/icons, restores default visible core condition rows, and removes custom badges.
- Confirm Reset defaults mirrors `average_review_for_popular` to `4`.
- Confirm Reset defaults preserves original core badge icons: `la la-bolt`, `la la-fire`, and `la la-star-o`.
- Confirm old saved core rules without `typeEdited` follow global `badge_display_type`, and old saved New/Popular rules with an unedited generic star icon normalize back to `la la-bolt` and `la la-fire`, while rules with `typeEdited: true` or `iconEdited: true` keep the selected type/icon.
- Confirm custom badge widgets are added only to badge-compatible builder accepted widget lists and do not change existing `maxWidget` values.
- Confirm the grouped single listing `Badges` widget settings shows custom badge toggles disabled by default while New, Popular, and Featured remain enabled by default.
- Confirm saved badge icons feed builder widget metadata, archive badges, and single listing badges. Badge colors and icon-only badge type must affect settings/frontend badges only; builder chips must keep the old neutral builder UI.
- Confirm icon-only badge tooltip overrides save inside `directorist_badge_rules.badges.*.hover` and empty hover values fall back to visible label, badge background, and icon/text color without a migration.
- Confirm new badge saves do not emit `showIcon`; old saved `showIcon` data loads without false dirty state.
- Confirm text badges with an icon render icon plus label, text badges with an intentionally removed icon render label-only, and icon-only badges render icon plus tooltip across admin preview, archive, and single listing.
- Confirm archive, single, and legacy badge output match the Appearance preview chip sizing, typography, border, radius, icon size, icon/label gap, and color values.
- Confirm removing a text badge icon saves `icon: ""` with `iconEdited: true`, and switching that badge to icon-only restores the default icon.
- Confirm custom badges are not auto-inserted into existing builder layouts.
- Confirm invalid or unsupported saved core rules fall back to legacy badge behavior, while invalid custom rules fail closed.
- Confirm conditionless custom badges show on archive/listing-card only where their direct custom badge widget is placed, and on single listing only when their toggle inside the grouped `Badges` widget is enabled.
- Confirm blank text or blank specific-plan condition values fail closed instead of matching every listing.
- Confirm text `does not contain` summaries render human-readable labels, not raw operator keys.
- Inspect `git diff` and keep generated build assets out of scope unless the user approved a build.

## Browser QA

Use Agent Browser when an authenticated admin session is available:

1. Open `https://directorist-core.local/wp-admin/edit.php?post_type=at_biz_dir&page=atbdp-settings#style_settings__badges`.
2. Fresh load should open Appearance > Badges, show no unsaved warning, and keep Save disabled.
3. Change `badge_display_type`; verify the UI switches between text and icon wording without duplicate old rows.
4. Edit core internal names and verify Save enables; after save, builder widget labels should change while frontend visible labels remain unchanged.
5. Change a badge icon through the badge icon picker; verify the chip preview uses the selected icon class, not a text fallback.
6. Switch a badge to icon mode and change Icon color; verify the admin chip, archive badge, and single badge use that color for the icon, while the builder badge widget keeps the old neutral builder chip style.
7. Change icon badge Hover tooltip, Tooltip background, and tooltip text color; save, refresh, and verify archive/single tooltip text and colors match.
8. Remove a text badge icon; verify the admin chip, archive badge, and single badge render label-only with no dot marker, then switch to icon mode and verify the default icon is restored.
9. Edit New visible label, duration, background, text, and border color; Save should enable.
10. Edit Popular visible label, popularity rule, threshold, background, text, and border color; Save should enable.
11. Edit Featured visible label, background, text, and border color; Save should enable.
12. Edit General match-condition row keys/operators/values, Add condition, Remove condition, and All/Any mode; verify Save enables and no duplicate old generic rows appear.
13. Remove the final condition row, save, refresh, and verify `conditions: []` persists.
14. Switch a row to Field, choose a real listing field, set operator/value, save, refresh, and verify the saved source/key/operator/value persist without changing legacy threshold keys.
15. Switch a row to Pricing plan, test both `Has pricing plan` and a specific `Pricing plan`, save, refresh, and verify persistence.
16. Add a custom badge, set internal name, visible label, type/icon, colors, and conditions; save, refresh, and verify persistence.
17. Delete the custom badge, save, refresh, and verify it is gone.
18. Add a custom badge again, click Reset defaults, verify the warning popup copy, cancel once, then confirm reset; save, refresh, and verify core labels/colors/icons are restored, custom badges are removed, New shows `Listing age (days) <= 3`, Popular shows `Any (OR)` with `View count >= 5` and `Average rating >= 4`, and Featured shows `Is featured is true`.
19. Save and verify the request posts changed badge keys and, when badge settings changed, `directorist_badge_rules` to `save_settings_data`.
20. Refresh and verify saved values persist and Save is disabled after load.
21. Use settings search for a badge field and verify it opens the Badges tab and highlights/scrolls to the custom control.

### LocalWP Auth Fallback

If Agent Browser redirects to `wp-login.php?reauth=1` and normal test credentials are unavailable:

- Verify the current LocalWP MySQL socket first; do not reuse a stale socket path.
- Run read-only WP-CLI checks through PHP's mysqli socket override, for example `php -d mysqli.default_socket='<local-mysql-socket>' $(command -v wp) --path='<site-public-path>' option get siteurl --allow-root`.
- For browser QA, create only a short-lived WordPress auth session with WP-CLI, set the generated `wordpress_sec_*` and `wordpress_logged_in_*` cookies in an isolated Agent Browser session, and destroy the generated session token after QA.
- Restore any temporary badge option edits and reload a fresh browser session before recording the final clean-state result.

## Responsive QA

- Test desktop, tablet, and mobile widths.
- Confirm badge card titles, summaries, buttons, selects, number inputs, and color inputs do not overlap.
- Confirm the settings subnav and save footer do not cover the badge editor.
- Confirm color inputs and dropdowns are not clipped inside the scroll container.

## Frontend QA

- Check archive listing cards for New, Popular, Featured, and builder-placed custom badge labels/colors/borders/icons.
- Toggle text/icon badge display in settings and verify archive badge behavior follows saved badge definitions.
- Check single listing badge output for label/color regressions and confirm the wrapper/span structure remains compatible. Custom badges should not show from the grouped `Badges` widget until the matching custom badge toggle is enabled in Directory Builder.
- Verify REST v1/v2 listing `new` and `popular` fields match rendered badge visibility when rules are present.
- Create or identify listings that satisfy Field and Pricing plan rules, then verify archive, single, and REST badge visibility use the same result. For Pricing Plans v4, test both listing `_plan_id` / package assignment and legacy `_fm_plans` compatibility.

## Negative QA

- Navigating inside the settings panel should not trigger the leave guard.
- Leaving the settings page with real unsaved changes should trigger the existing unsaved-changes guard.
- Loading Appearance should not create false unsaved changes. If it does, inspect `Settings_Manager.vue` around `settingsValuesAreSame` before changing UI code.
