---
name: directorist-settings-panel-agent
description: Use this skill whenever the user wants to redesign, extend, audit, or implement features in the Directorist WordPress plugin settings panel. Trigger for Directorist settings panel work, Figma-to-settings-panel tasks, wp-admin settings UI redesigns, adding settings fields, changing settings save behavior, or assessing whether a proposed settings feature is safe. This skill is intentionally strict: it must inspect the current settings architecture, produce a feasibility report first, and ask before risky structural changes.
---

# Directorist Settings Panel Agent

This skill guides safe redesign and feature work for the Directorist settings panel. Directorist is production software used by many sites, so preserve existing behavior unless the user explicitly approves a structural change after seeing the risk.

## Core Rule

Always produce a report before implementation. Do not start code changes until the report identifies easy work, risky work, affected systems, and open questions for the user.

## Required Context Pass

Before planning or editing, inspect the current implementation in the local repo:

- `includes/classes/class-settings-panel.php`
- `views/admin-templates/settings-manager/settings.php`
- `assets/src/js/admin/settings-manager.js`
- `assets/src/js/admin/vue/apps/settings-manager/Settings_Manager.vue`
- `assets/src/js/admin/vue/apps/settings-manager/TabContents.vue`
- `assets/src/js/admin/vue/store/CPT_Manager_Store.js`
- `assets/src/scss/layout/admin/settings-manager.scss`
- `assets/src/scss/layout/admin/builder/_builder_style__settings_panel.scss`
- `webpack-entry-list.js`

Also search for any setting key or UI class named by the user before assuming where it lives.

For bundled core modules that inject settings through filters, inspect the source that owns the injected section before planning:

- Review settings: `includes/review/class-settings-screen.php`
- Schema Markup settings: `includes/classes/class-schema.php`

For any setting-key, section, or behavior change, also read:

- `docs/agents/directorist-settings-panel/references/settings-option-catalog.md`

If the task mentions extensions, extension settings, or a design inside the Extensions menu, first identify whether the target UI is core extension discovery or an installed extension's own settings. Extension-specific settings depend on the installed/active extension set.

## Architecture Summary

- PHP class `ATBDP_Settings_Panel` prepares settings fields, layouts, and config.
- The admin template outputs base64-encoded JSON in `data-builder-data` on `#atbdp-settings-manager`.
- `assets/src/js/admin/settings-manager.js` decodes that JSON and mounts the Vue 2 app.
- `Settings_Manager.vue` renders the top bar, breadcrumbs, search, save buttons, sidebar, and tab contents.
- `TabContents.vue` renders the active menu/submenu sections through globally registered Vue modules.
- `CPT_Manager_Store.js` stores `fields`, `layouts`, `config`, cached field values, active navigation, and highlighted search results.
- Settings save through the AJAX action `save_settings_data`.
- Saved values are written into the shared WordPress option `atbdp_option`.
- `directorist_options_updated` fires after option updates and can affect rewrite rules, cache, pages, emails, maps, listings, payments, and extensions.

## Safety Rules

- Do not run `npm run dev`, `npm run dev-vue`, `npm run prod`, formatters, code generators, or build commands that write tracked files unless the user explicitly approves.
- If the user says they are manually running `npm run dev-vue`, use the generated output and browser QA, but do not start the command yourself.
- Do not replace the settings system with a new framework or persistence layer.
- Preserve the field/layout/config data contract unless the user approves a structural change.
- Do not change settings keys, option storage, nonce handling, capability checks, AJAX action names, or `atbdp_option` behavior without explicit QA with the user.
- Do not remove legacy `atbdp_` behavior just because new code prefers `directorist_`.
- Treat existing installations as compatibility-critical. Directorist settings features may affect 20k+ sites; prefer implementations that work automatically after plugin update without data migration or manual admin action.
- If a migration, upgrade routine, data backfill, or compatibility shim appears necessary, do not implement it silently. Create a Markdown note that explains the migration need, affected option keys/data, risks, rollback concerns, and QA plan before changing migration code.
- Keep generated build assets out of scope unless the user approves the required build command or specifically asks to edit committed build output.
- Treat Figma as design intent, not a reason to rewrite data flow.

## Work Classification

Classify every requested change before implementation.

Low risk:

- Visual-only SCSS changes scoped to the settings panel.
- Reordering or spacing within existing Vue structure without changing data flow.
- Copy, typography, alignment, responsive layout, and focus-state improvements.
- Search/breadcrumb/sidebar presentation changes that preserve existing store mutations.

Medium risk:

- Adding a new Vue-only interaction or UI state.
- Adding a new reusable form-field component that uses existing save conventions.
- Changing sidebar/search behavior while preserving hashes and layout paths.
- Adding a setting field that stores a new key in `atbdp_option`.

High risk:

- Changing save payload shape, sanitization, nonce, or permissions.
- Moving settings out of `atbdp_option`.
- Changing layout JSON structure, field type contracts, or `show-if` behavior.
- Changing page/permalink/map/payment/email settings because many frontend systems read them.
- Changes that require migrations, upgrade routines, or extension compatibility decisions.

Ask the user before medium-risk work if the report shows meaningful tradeoffs. Always ask before high-risk work.

## Figma Or Design Workflow

When the user provides a Figma design, screenshot, or visual reference:

1. Inspect the existing settings implementation first.
2. Extract layout, spacing, typography, color, navigation, and interaction intent.
3. Map each design area to existing shell, sidebar, sections, and field components.
4. Identify what can be achieved with SCSS/Vue template changes only.
5. Identify any design detail that would require new data shape, new field type, or changed save behavior.
6. Produce the required report and ask only the questions needed to resolve risky items.

Prefer matching the design through the existing Vue/settings architecture. Do not rebuild the panel from scratch unless the user explicitly approves a rewrite.

## Feature Workflow

When the user asks for a new setting or behavior:

1. Find existing related setting keys with `rg`.
2. Check `references/settings-option-catalog.md` to understand the existing settings area and nearby option keys.
3. Identify the connected frontend/admin systems that read those options.
4. Decide whether the feature is UI-only, new setting storage, or behavior-changing.
5. Confirm defaults, backward compatibility, and migration needs before editing.
6. Use existing field types and `show-if` conventions when possible.
7. If adding, removing, renaming, moving, or repurposing a key, update the settings option catalog after the implementation is confirmed.
8. If adding a new key, document default value, sanitize behavior, and all readers.

## Needs Design Workflow

When the user says to start work on the `Needs Design` menu, do a research pass before implementation. Treat every menu and option currently placed under `Needs Design` as pending classification, not final navigation.

1. Inventory all `Needs Design` menus, sections, and option keys from the current layout/source.
2. For each option, find the closest existing redesigned settings pattern and reuse that design language instead of inventing a new pattern.
3. Decide whether each option belongs inside an existing redesigned menu/submenu or should remain as a separate menu.
4. Explain the alignment decision step by step before editing code.
5. Only implement after the user confirms the proposed menu alignment and design pattern.

## Pattern Reuse Rule

Before creating a new settings interaction or card pattern, check whether the redesign already has the same pattern elsewhere. Reuse existing patterns first, including nested card advanced disclosures, page-level advanced disclosures, compact row controls, textarea message rows, save/footer behavior, and modal scaffolds. Only create a new pattern when no existing pattern can satisfy the reference design.

## Extension Settings Workflow

The `Extensions` menu is core, but many extension-specific settings are not present unless the related Directorist extension is installed and active.

When a design or feature targets extension UI/settings:

1. Identify the exact extension name/slug from the user's design, URL, screenshot, or installed plugins list.
2. Inspect active plugins and extension filter usage before treating any setting as available.
3. If the required extension is missing or inactive, ask the user before installing or activating it.
4. After the extension is active, inspect its filters, setting fields, layouts, templates, scripts, and styles.
5. Produce the feasibility report using both core settings-panel context and extension-specific source context.
6. Do not document extension-provided settings as core. Add them to the catalog only under an extension-specific heading after source/filter evidence confirms them.
7. If the design could affect multiple extensions, ask for the target extension before implementation.

## Required Report Template

Use this exact structure before implementation:

```markdown
# Settings Panel Feasibility Report

## Summary
- Request:
- Recommended approach:
- Risk level:

## Easy To Achieve
- ...

## Complex Or Risky
- ...

## Affected Areas
- PHP settings builder:
- Vue app/store:
- SCSS/build output:
- Saved options/data:
- Connected frontend/admin behavior:

## Questions Before Risky Work
- ...

## Verification Plan
- Static checks:
- Browser/manual QA:
- Build policy:
```

If there are no risky questions, say that clearly and proceed only if the active collaboration mode allows implementation.

## Verification Expectations

Use the smallest verification set that matches the change:

- Static inspection with `rg`, `git diff`, and focused file reads.
- PHP syntax checks for touched PHP files.
- Browser QA on the local settings URL when available:
  `http://directorist-core.local/wp-admin/edit.php?post_type=at_biz_dir&page=atbdp-settings`
- Confirm save still posts to `save_settings_data` and unchanged fields are not rewritten unnecessarily.
- Confirm responsive behavior at desktop and mobile widths for visual redesign work.
- Confirm no build commands were run unless the user approved them.

## Self-Learning Protocol

Self-learning is tracked-reference learning, not automatic memory. The agent learns only when it updates files in this package with confirmed repo facts.

Update the tracked references when:

- a settings-panel task confirms a new architecture fact, limitation, dependency, or regression pattern
- a setting key is added, removed, renamed, moved between sections, or given new behavior
- a core module injects settings through filters and the catalog does not yet identify that source
- an installed extension adds settings through filters and the user approves documenting that extension-specific behavior
- a Figma/design implementation reveals a reusable UI constraint or compatibility issue
- a verification step discovers a required QA path that future agents should repeat

Only record confirmed learning:

- facts observed in source code, diffs, browser QA, tests, or user-approved decisions
- exact file paths, option keys, hooks, filters, routes, and build constraints
- current behavior with the review date when the behavior may change later

Do not record:

- guesses, opinions, or unverified assumptions as architecture facts
- temporary implementation details that were reverted or not accepted
- user-private context that is not needed for future repo work
- broad lessons that are not specific to the Directorist settings panel

Keep learning useful:

- put architecture and constraints in `references/current-settings-panel-map.md`
- put setting areas, keys, and purposes in `references/settings-option-catalog.md`
- keep `SKILL.md` short and workflow-focused
- update references after the code/design decision is confirmed, not before
