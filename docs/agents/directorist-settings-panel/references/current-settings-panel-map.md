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
- For this settings-panel redesign work, do not run `npm run dev`, `npm run dev-vue`, `npm run prod`, or equivalent build commands. Tell the user a build is needed and wait for them to run or explicitly approve it.
- `.agents/` is ignored in this repo; repo-local agent docs should live under `docs/agents/`.

## Recommended Change Strategy

- Prefer SCSS-only changes for pure visual polish.
- Prefer template-level Vue changes when layout needs to move but state/data does not.
- Reuse existing field types and layout definitions for new settings.
- Ask before adding a field type, changing payload structure, or changing `show-if` logic.
- Ask before changing defaults for existing option keys.
- For setting-key work, check and update `references/settings-option-catalog.md`.

## Redesign Implementation Notes

Last updated: 2026-06-04

- The static/reference redesign keeps WordPress admin chrome visible. Only the Directorist settings surface should be redesigned inside the available `#wpcontent` area.
- Use the native Vue shell rather than an iframe, but mirror the reference shell behavior: breadcrumb above a rounded contained settings panel, with standard admin-content gutters.
- Do not render the legacy PHP `atbdp-settings-manager__top` fallback header (`Settings`, `Documentation`, `Support`) in `views/admin-templates/settings-manager/settings.php`; it flashes during reload before Vue mounts and conflicts with the redesigned Vue shell.
- Do not let the browser window become the main settings scroller. Keep `.atbdp-settings-manager` viewport-height bounded, `.setting-body` fixed inside the shell with `overflow: hidden`, and `.settings-contents` as a flex column.
- Keep the settings subnav/header outside the scrolling area. `.atbdp-cptm-tab-contents` and `.atbdp-tab-sub-contents` should be flex column wrappers with `overflow: hidden`; the actual scroll owners are `.atbdp-tab-sub-contents > .atbdp-tab-content-item` for submenus and `.atbdp-tab-content-body` for menus without submenus. Do not use `scrollbar-gutter: stable` for this redesign because it leaves an empty right-side strip when content is short and no scrollbar is needed.
- Keep `.settings-footer` in normal flex layout at the bottom of `.settings-contents`; avoid a fixed footer because it overlaps scrolled settings rows and causes page-level scroll conflicts.
- Browser QA path for this issue: refresh the settings page, set page scroll to top, wheel over the main settings content, and confirm `window.scrollY` stays `0` while the active body scroll owner (`.atbdp-tab-sub-contents > .atbdp-tab-content-item` or `.atbdp-tab-content-body`) changes `scrollTop`; the footer and subnav/header coordinates should remain stable.
- The comparison page `https://sovqa.site/rony/wp-admin/edit.php?post_type=at_biz_dir&page=atbdp-settings` wraps the redesign in `.directorist-redesign-shell` plus `#directorist-settings-redesign-iframe`. Treat that as behavior reference only; do not reproduce the iframe in core.
- The static redesign uses inline SVG icons with `stroke="currentColor"` instead of Font Awesome for the settings shell. `assets/src/js/admin/vue/apps/settings-manager/settings-redesign-map.js` owns the menu/submenu SVG catalog, and `Sidebar_Navigation.vue` owns the search/footer SVGs. Keep icon size, color, hover, and active states in `_settings_panel.scss` aligned with the static values.
- Reference measurements confirmed from `settings-redesign-mockup.html`: sidebar divider before Extensions uses `margin: 12px 20px` and `border-top: 1px solid #e5e7eb`; subnav is `54px` tall with `padding: 6px 32px 0 38px`, corrected tab `gap: 25px`, and tab item `padding: 14px 0`; content body wrappers are transparent while cards own the white backgrounds; right save bar is `55px` tall and the unsaved label is hidden until a real field value changes.
- Default text/number fields now expose a sanitized `cptm-form-group--{fieldKey}` class on the `.cptm-form-group` wrapper. Prefer this class for field-specific redesign alignment instead of section order or `nth-child` selectors.
- The redesigned `Listing lifecycle` card keeps the original `delete_expired_listing_permanently` and `delete_expired_listings_after` option keys. `delete_expired_listings_after` should render as one row: left label, 200px / 34px-high number input, then the visual suffix `days in trash`; the suffix is display-only CSS, not a saved field or PHP setting label change.
- Static `Directory > General` places `View tracking` and `Archive pages` below a centered `Show advanced settings` pill. Keep those existing fields mounted for validation/save compatibility, hidden with `v-show` until the disclosure opens, and auto-open the disclosure when a highlighted search/deep-link target lives in an advanced section. `sectionFromFieldGroup()` must preserve `group.advanced` on the returned section object; otherwise the compiled UI has the disclosure component but no section qualifies for it.
- The advanced disclosure wrapper is a sibling after the basic `.cptm-tab-content`, not a child inside that padded content column. Compensate for the existing basic wrapper bottom padding plus the last card margin: use horizontal `32px` margins to align with cards, a negative top margin so the visible gap from the last card to the pill is about `18px`, and a `24px` bottom margin so the bottom space above the save footer matches the top content padding.
- Because the advanced disclosure uses a negative top margin, give `.settings-panel-advanced` its own local stacking context and keep the pill above its expanded body. Do not raise it above `.settings-panel-subnav` (`z-index: 30`) or `.settings-footer` (`z-index: 45`), otherwise content can overlap fixed navigation or save controls.
- Some settings number fields, including `delete_expired_listings_after`, still render legacy `.atbdp-row > .atbdp-col-*` markup inside `.cptm-form-group`. Field-specific visual fixes for these rows should target the nested `.atbdp-row` structure, not only direct `.cptm-form-group > .cptm-form-control` children.
- Settings save should compare field values with the same object-aware comparator used for the unsaved indicator. Do not update `cached_fields` before the AJAX response confirms `status.success`; otherwise a failed save can hide the unsaved indicator. When Axios fails or WordPress returns an unexpected response, surface the response message instead of always showing the generic `Something went wrong`.
- The redesign footer should not submit a no-op save just to show WordPress's backend `No changes made` / `Nothing to save` success message. If the changed field list is empty, leave the footer quiet; only show the orange-dot unsaved hint for real local changes and keep backend error messages visible.
- The footer `Save changes` button should use the same `hasUnsavedChanges` comparator as the unsaved hint: disabled when there are no real changes, enabled only after a setting value differs from `cached_fields`, and disabled again while the save request is processing.
- The footer save button's loading label is injected via `v-html` with a Font Awesome `<i>` tag. Keep `.settings-save-btn` as inline-flex with a small gap, and normalize child `i` to a 14px inline-flex box so the spinner is centered vertically/horizontally with `Saving...`.
- The settings leave guard is page-exit only. `Settings_Manager.vue` listens for document clicks outside the Vue settings shell and opens a modal only when `hasUnsavedChanges` is true and the target is a normal same-window navigation link. Internal settings menu/submenu/search clicks stay unguarded because they are inside `this.$el`. Browser refresh/close/back uses the native `beforeunload` prompt because browsers do not allow a custom modal there. The leave guard modal should use the same `cptm-modal-container`, `cptm-modal-header`, `cptm-modal-action-link`, `cptm-modal-confirmation-title`, and `cptm-btn-rounded` scaffold as `Confirmation_Modal.vue`, which is the modal used by the `enable_multi_directory` toggle confirmation. Keep only minimal unsaved-specific overrides such as width, copy spacing, and button sizing; do not add a separate body background or custom close-icon treatment.
- Leave-guard copy should stay short. Use `Save changes before leaving?` with a brief helper such as `Unsaved changes will be lost.` rather than long explanatory text.
- Shared confirmation modals should not render empty feedback placeholders. If no feedback/error message exists, omit the `.cptm-form-group-feedback` node rather than leaving a blank div above the title.
- The leave guard's `Save & leave` action must reuse the same settings AJAX payload and update `cached_fields` only after `status.success`, then set a local bypass flag before redirecting so `beforeunload` does not prompt again. Save failures and validation errors must keep the user on the settings page and show the error in the modal.
- Fields that use existing conditional visibility must be hidden at the row wrapper level in `Sections_Module.vue`, not only inside the field component. Support all current key shapes (`showIf`, `show_if`, and PHP's `show-if`). Otherwise the component disappears but the redesign card keeps empty rows, as seen with `category_base`, `location_base`, and `tag_base` under `enable_archive_template`.
- The redesigned archive slug rows (`category_base`, `location_base`, `tag_base`) keep their labels and saved keys but intentionally blank their helper descriptions in `settings-redesign-map.js` to match the compact design.
- Static row controls in `settings-redesign-mockup.html` use a compact 200px footprint with `13px` text, `7px 10px` padding, `6px` radius, and `#e5e7eb` border. Keep default text/email/number/password/select/dropdown controls in the redesigned settings panel aligned to that size; keep textareas full-width with a 70px minimum height.
- The sidebar search input is not a normal row control. Keep its explicit full-width `36px`-high override after the generic input rule so the compact 200px row-control max-width does not shrink it.
- Rows without helper descriptions should vertically center their label beside the input/toggle/select. Rows with helper descriptions should keep the label/description stack. Directorist custom dropdown selected text also needs explicit flex centering so the visible option label sits in the middle of the 34px control.
- `Directory > Single listing` in the static mockup maps only to current core settings approved for the redesigned screen: `single_listing_template`, `disable_single_listing`, `restrict_single_listing_for_logged_in_user`, `atbdp_listing_slug`, `single_listing_slug_with_directory_type`, `dsiplay_slider_single_page`, `single_slider_image_size`, `single_slider_background_type`, and `single_slider_background_color`. `gallery_crop_width` and `gallery_crop_height` are intentionally suppressed from this redesigned display per the 2026-06-04 design QA request; do not re-add them to the Single listing card or fallback `Needs Design` menu unless the user reverses that decision.
- The Single listing redesign copy is owned by `settings-redesign-map.js` field overrides. Keep title/description wording aligned with the static design, but preserve original field keys, `show-if` behavior, and option values.
- The Single listing `Slider image` card uses a card-level advanced disclosure, not the page-level centered `Show advanced settings` pill. `settings-redesign-map.js` marks those rows with `advancedFields`, and `Sections_Module.vue` keeps the fields mounted but hidden until the inline `Advanced` button is expanded. The approved order after the main toggle is `Image fit`, `Background type`, then `Background color`; keep the background color row styled like the static compact color input. Other settings areas keep the broader page-level `Show advanced settings` label unless their static card design explicitly requires nested advanced content.
