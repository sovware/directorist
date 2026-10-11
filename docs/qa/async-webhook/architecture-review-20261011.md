# Settings Panel Feasibility Report

## Summary
- Request: Review legacy compatibility and extension-specific code introduced by Core #3069, Stripe #26, and PayPal #7; define a cleaner implementation.
- Recommended approach: Keep a reusable, opt-in async settings action in Core. Supply provider-specific dependencies, normalized action state, URLs, and translated messages from each extension through additive field metadata. Preserve existing settings storage and ordinary button behavior.
- Risk level: Medium for reusable UI changes; high for changing the cross-repository field/response contract and payment credential save behavior. Implementation requires approval after this report.
- Verified 2026-10-11: Core #3069 merged into `development` at `8167787be4acdb4e00bc2179d9ee10cfa538d858`; Stripe #26 is open at `25ac68af0413763d25e2648554f8a9cbf5e05bd5`; PayPal #7 is open at `cbbf3237932409d10ce333df5300f8ae10c64ebd`.
- Local Core is clean before this report, detached at `ac5517e2afe25508e42530ed628ccd1f5801a048`, with the merge in its ancestry. The reviewed button mixin, credential helper, settings manager, and section module match the merged version.

## Easy To Achieve
- Keep ordinary link buttons as the default; async remains an explicit capability.
- Retain loading, feedback, accessible status, selective save, and live/cached state synchronization in Core.
- Move complete translated labels and explanatory copy to extension configuration; remove English `Register`/`Unregister` replacement and `Save &` concatenation from provider UI behavior.
- Keep provider API requests, key validation, webhook persistence, and PayPal token cache isolation in the extension that already owns them.

## Complex Or Risky
- Core currently hardcodes four Stripe/PayPal webhook-to-credential mappings in `assets/src/js/admin/vue/mixins/form-fields/webhook-credential-fields.js:1`.
- Quick search names the two gateway mode keys directly in `assets/src/js/admin/vue/apps/settings-manager/Settings_Manager.vue:553`; section styling repeats those keys in `assets/src/js/admin/vue/modules/Sections_Module.vue:1018`.
- The button mixin rewrites `/webhook-register` into `/webhook-unregister` and supplies the `id` query parameter at `assets/src/js/admin/vue/mixins/form-fields/button-field.js:37`. The extension should provide a correct next URL both on initial load and after an action.
- The nominally generic async response consumes `webhook_id`, while the shared button template displays webhook-specific explanatory text. A generic action needs explicit normalized state/value and configured messages.
- Credential locks currently protect the form and quick-search UI. They are not a server-enforced credential immutability guarantee. Server enforcement, if desired, needs a separately specified recovery policy.
- The current extensions expose a header-gated JSON response and retain redirects without the header. Keep that negotiation while introducing any additive response fields; do not assume old Core understands a new contract.
- Replacing generic quick-search visibility for every field could change unrelated settings behavior. Reuse the existing condition evaluator and scope the first rollout to explicitly opted-in action dependencies/visibility; expand only after regression QA.
- Saved values and rendered field defaults are assembled by Core. Establish when saved webhook state is available before removing URL correction; do not simply delete the workaround and assume the PHP field URL is current.

## Affected Areas
- PHP settings builder: Existing `atbdp_listing_type_settings_field_list` extension filter. Both extension `SettingsServiceProvider.php` files should declare action dependencies and UI metadata; their controllers should return additive normalized state/action data. No option-key rename or database migration is proposed.
- Vue app/store: `Settings_Manager.vue`, `button-field.js`, `input-field-props.js`, credential dependency helper, `Sections_Module.vue`, `Sidebar_Navigation.vue`, Butterfly button/text templates, and `setPersistedAsyncField` in `CPT_Manager_Store.js`.
- SCSS/build output: Reuse existing async-button styles; generalize webhook-only lock selectors if required. Rebuild only the necessary legacy settings/admin assets after approval. The skill references `webpack-entry-list.js`, which is absent in this checkout; inspect the actual legacy webpack configs before selecting the build command.
- Saved options/data: Keep `atbdp_option`, `save_settings_data`, the existing field-list payload, nonce/capability checks, and `directorist_options_updated`. Store only the selected changed credential fields during Save & Register. Keep webhook data shapes, including Stripe signing secret, owned by extensions.
- Connected frontend/admin behavior: Registration, disconnect, mode visibility, quick-search edits, reload state, and recovery from a saved ID with missing keys. Checkout/payment engine behavior is outside this UI refactor.

Proposed responsibility contract (field names below are a design proposal, not an existing API):

| Concern | Core | Extension |
| --- | --- | --- |
| Action prerequisites | Evaluate configured `required-fields` and `save-before-action` | Supply exact field keys for this environment |
| State | Render configured `action-state` and sync acknowledged values | Normalize saved provider data into active/inactive UI state |
| Credential locking | Evaluate configured `lock-fields` for an active action; retain missing-field recovery | Specify which fields belong to the action and recovery message |
| URLs | Execute a validated same-origin URL | Build initial and subsequent action URLs with nonce and provider route parameters |
| Copy | Render messages supplied by configuration/response | Supply translated register, disconnect, save/register, status, and recovery copy |
| Mode visibility | Reuse condition evaluation and explicit section behavior metadata | Declare show-if conditions and that mode selection is not section enable/disable |
| Result | Accept a documented state/action/value response | Return normalized data alongside existing response keys during transition |

Compatibility and delivery:

1. Prepare an additive Core follow-up against current `development`, because #3069 is already merged. Do not rewrite the merged PR.
2. Update the two open extension branches to emit both existing fields and the new metadata/response contract. Existing merged Core must continue to operate with those versions.
3. Core should prioritize explicit generic metadata. Audit released extension versions for the old async contract before removing its compatibility adapter. Open PR status alone is not proof that no installations use it.
4. If support for already distributed async extensions is needed, keep the old mapping isolated in a clearly bounded compatibility adapter, separate from generic components. Remove it only under an agreed support policy. Otherwise coordinate the clean removal with the extension releases.
5. Older extensions without async metadata retain ordinary links and editable keys. Older Core with updated extensions retains redirect behavior. Confirm both with actual supported versions; source inspection is not a completed runtime compatibility matrix.

## Questions Before Risky Work
- Approve the proposed additive cross-repository refactor and targeted generated-asset build, keeping existing option keys, save endpoint, and redirect fallback?
- Compatibility default: preserve any already distributed async contract through an isolated adapter until release history is checked. No decision to drop that support is assumed.

## Verification Plan
- Static checks: Verify exact PR heads and changed source, lint changed PHP, run focused JS tests, and confirm no provider field names/routes remain in generic components (any approved compatibility adapter is an explicit exception).
- Browser/manual QA: Ordinary buttons; old extension/new Core; updated extension/merged Core; new/new; supported old Core/new extension; saved and absent IDs; `[]`/object/string saved representations; sandbox/live; quick search; missing-key recovery; translated labels; reload persistence; unrelated unsaved settings; save failure; request failure; keys edited during save/action; stale link; provider deletion failure preserving state.
- Provider verification: Use mocked clients first. Public sandbox registration/delivery/payment checks require appropriate environments and are separate evidence. No provider requests, payment, or webhook-delivery verification occurred during this review.
- Build policy: No build or formatter was run. Targeted committed assets must be rebuilt and checked when implementation/build scope is approved.
- Executed now: `node tests/async-button-field-smoke.mjs` passed on local Core. It covers legacy credential editability, async opt-in locking, save-before-register, and selected error states. It does not prove browser rendering, all version combinations, translation behavior, or real provider requests.

Sources: https://github.com/sovware/directorist/pull/3069 ; https://github.com/sovware/directorist-stripe-new/pull/26 ; https://github.com/sovware/directorist-paypal-new/pull/7 ; current local Core source at the commit above.

## Approved implementation follow-up

The user approved the proposed cross-repository refactor, targeted build, QA, Core follow-up PR, and updates to both extension PRs on 2026-10-11. The report above records the pre-implementation review. The implemented contract is documented in `docs/agents/directorist-settings-panel/references/async-settings-actions.md`; executed QA is recorded in `generic-actions-qa-20261011.md`.
