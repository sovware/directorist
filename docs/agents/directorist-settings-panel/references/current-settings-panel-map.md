# Current Settings Panel Map

Last reviewed: 2026-06-04

## Main Entry Points

- PHP builder: `includes/classes/class-settings-panel.php`
- Admin template: `views/admin-templates/settings-manager/settings.php`
- Vue mount: `assets/src/js/admin/settings-manager.js`
- Main Vue shell: `assets/src/js/admin/vue/apps/settings-manager/Settings_Manager.vue`
- Tab renderer: `assets/src/js/admin/vue/apps/settings-manager/TabContents.vue`
- Vuex store: `assets/src/js/admin/vue/store/CPT_Manager_Store.js`
- SCSS entry: `assets/src/scss/layout/admin/settings-manager.scss`
- Settings-specific SCSS partial: `assets/src/scss/layout/admin/builder/_builder_style__settings_panel.scss`
- Vue build entry: `webpack-entry-list.js` key `admin-settings-manager`

## Data Flow

1. `ATBDP_Settings_Panel::prepare_settings()` builds `$this->fields`, `$this->layouts`, and `$this->config`.
2. `ATBDP_Settings_Panel::menu_page_callback__settings_manager()` sanitizes field data, base64-encodes JSON, and passes it as `settings_builder_data`.
3. `views/admin-templates/settings-manager/settings.php` renders `#atbdp-settings-manager` with `data-builder-data`.
4. `assets/src/js/admin/settings-manager.js` decodes the base64 JSON, parses it, and mounts Vue 2.
5. `Settings_Manager.vue` commits `fields`, `layouts`, and `config` into `CPT_Manager_Store.js`.
6. The store prepares active navigation and search layout paths.
7. Field modules update values in the Vuex `fields` object.
8. Save collects changed fields and posts a `FormData` payload to the configured AJAX endpoint.
9. PHP verifies nonce and capability, sanitizes submitted values, writes changed keys into `atbdp_option`, and fires `directorist_options_updated`.

## Save Contract

- AJAX action: `save_settings_data`
- Capability check: `current_user_can( 'manage_options' )`
- Nonce check: `directorist_verify_nonce()`
- Payload includes `field_list`, then one entry per changed field.
- Complex values are JSON/base64 encoded by the Vue helper before submit.
- PHP decodes with `Directorist\Helper::maybe_json()`.
- Only keys registered in `$this->fields` are saved.
- Values persist inside the shared WordPress option `atbdp_option`.

## UI Contract

- The settings shell owns the top bar, breadcrumbs, search, save buttons, sidebar, tab contents, and footer save area.
- Sidebar navigation depends on `layouts` and active menu/submenu state.
- Deep links use URL hashes in the pattern `menu__submenu__section__field`.
- Search suggestions are built from cached field labels and jump to layout paths prepared by the store.
- Field rendering is handled by globally registered Vue modules under `assets/src/js/admin/vue/modules`.
- Existing field types and theme variants should be reused before adding new components.

## Connected Systems

Settings values are read throughout Directorist. Treat these areas as connected when changing settings:

- listing submission and listing display
- page setup and permalink routing
- maps and geolocation
- search and archive behavior
- review behavior
- email templates and notifications
- payments, checkout, gateways, and orders
- multi-directory behavior
- cache/rewrite updates via `directorist_options_updated`
- extensions that filter or read settings data

## Known Limitations And Cautions

- The settings panel is a Vue 2 app, not a React or WordPress components UI.
- The same Vuex store is also used by builder-related flows, so changes must be scoped carefully.
- Settings are stored in one shared option, so key changes can break existing installations.
- Some settings control frontend routing and page IDs; changing them can require rewrite/cache verification.
- Some fields use legacy naming and behavior. Preserve backward compatibility unless a migration is approved.
- Build output is committed in this repo, but build commands should not be run by the agent without explicit approval.
- `.agents/` is ignored in this repo; repo-local agent docs should live under `docs/agents/`.

## Recommended Change Strategy

- Prefer SCSS-only changes for pure visual polish.
- Prefer template-level Vue changes when layout needs to move but state/data does not.
- Reuse existing field types and layout definitions for new settings.
- Ask before adding a field type, changing payload structure, or changing `show-if` logic.
- Ask before changing defaults for existing option keys.
- For setting-key work, check and update `references/settings-option-catalog.md`.
