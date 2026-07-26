---
name: directorist-appearance-badges
description: Use this skill when working on Directorist Settings Appearance Badges, including redesigning New, Popular, Featured, and custom badge settings UI, matching the badge-editor mockup, checking badge option keys and frontend readers, dynamic builder badge widgets, Add badge, Reset defaults, or QAing badge save and dirty-state behavior in the Directorist WordPress plugin settings panel.
---

# Directorist Appearance Badges

## Required First Step

Read `../directorist-settings-panel/SKILL.md` before planning or editing. This badge skill is narrower than the general settings-panel agent and inherits its report-first workflow, save-contract safety rules, and build policy.

## Scope

Implement the Badges screen as a visual editor for the three core badges and optional saved custom badges:

- New listing
- Popular
- Featured
- Custom badges stored in `directorist_badge_rules`

Core badge option keys and builder widget keys must stay compatible. Custom badges are additive only: store them in `directorist_badge_rules`, expose them as builder widgets in the same badge-compatible slots, and do not auto-insert them into existing layouts. Do not add migrations, change the settings save contract, or change frontend wrapper/markup structure.

## Workflow

1. Read `references/badges-context.md`.
2. Inspect the current source files listed there before editing.
3. Match the badge-editor design language from the static mockup while preserving existing option keys.
4. Wire custom UI through existing Vuex field values and `updateFieldValue`; do not bypass the normal `save_settings_data` AJAX flow.
5. Use `references/badges-qa-checklist.md` for verification.

## Implementation Rules

- Render the Badges tab as one custom settings component for `style_settings__badges`.
- Hide the old generic field rows only at the redesigned presentation layer; keep all backing fields registered and present in the layout so search/deep links still resolve.
- Update only existing saved options plus the hidden rule store: `badge_display_type`, `directorist_badge_rules`, `new_badge_text`, `new_listing_day`, `new_back_color`, `popular_badge_text`, `listing_popular_by`, `views_for_popular`, `average_review_for_popular`, `popular_back_color`, `feature_badge_text`, and `featured_back_color`.
- Preserve `atbdp_option`, nonce checks, capability checks, AJAX action names, payload shape, and `directorist_options_updated`.
- Core visible labels and background colors must continue to mirror legacy options; core internal names affect admin and builder labels only.
- Add/Delete is allowed only for custom badges. Core badges can be reset, but not deleted.
- Store per-badge `type`, `icon`, text color, border color, icon-badge tooltip overrides, match mode, conditions, and enabled state in `directorist_badge_rules`.
- Border color must fall back to the badge background color.
- Keep frontend behavior honest: archive and single listing outputs should read the same matched badge definitions while preserving existing wrappers/classes.

## Build Policy

Do not run build/watch commands unless the user explicitly approves them. If build output is needed, ask the user to run or approve `npm run build-legacy`, because the settings manager source entry is `webpack.legacy.dev.js` key `js/admin/settings-manager`.
