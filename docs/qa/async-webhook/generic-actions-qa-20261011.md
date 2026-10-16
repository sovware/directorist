# Generic settings actions — local QA, 2026-10-11

## Scope and ownership

Core #3076 follows #3069. Stripe #27 and PayPal #8 contain the extension updates after Stripe #26 and PayPal #7 merged at their original heads during implementation. The three reviewed follow-up PRs target `development`. Generic Core components consume extension-supplied field dependencies, state, URLs, labels, and visibility metadata. Legacy provider mappings and response interpretation are restricted to `vue/compat/legacy-webhook-actions.js`.

No option migration, endpoint rename, nonce/capability change, or live deployment. Credential locking remains a UI safeguard, not a server-side policy preventing all external option changes.

## Executed verification

- `node tests/async-button-field-smoke.mjs`: passed. Exercises a provider-independent service with arbitrary field keys and Bengali labels, encoded label rendering, selective payload construction, pending/active locks, missing-field recovery, failed saves, changed fields during save, failed responses, malformed state, initial/next URL validation, legacy responses, legacy non-async editability, and old Stripe/PayPal saved-value forms.
- PHP syntax checks: all six changed extension PHP files passed.
- Stripe mocked SDK smoke and PayPal mocked HTTP smoke: passed, including new action metadata/initial-state assertions and agreement between legacy and generic response state. Scalar legacy IDs and structured IDs both render connected, and an existing scalar ID blocks duplicate registration. Tests retain actual settings through filters and make no real provider requests.
- PHPCS using each extension's own `dev-tools/phpcs.xml`: Stripe's three changed files clean. PayPal's three changed files have zero errors and two pre-existing `WordPress.Security.NonceVerification.Recommended` warnings in read-only settings-page detection. PayPal's PHPCS exit is nonzero for those warnings; this is not a clean WPCS claim.
- Production Webpack build restricted to `js/admin/settings-manager` and `css/admin/main`: passed. Committed outputs are settings JS and admin CSS/RTL. Existing bundle-size and CSS `end` compatibility warnings remain. No full packaging build was run.
- `git diff --check`: passed in all three repositories.

## Browser checks

Local HTTPS WordPress site, active Stripe and PayPal, fake sandbox credentials, real local `save_settings_data` requests, and intercepted provider action responses:

| Scenario | Result |
| --- | --- |
| Empty saved ID and missing keys | Register blocked; disconnected status |
| Unsaved Stripe keys + unrelated unsaved gateway title | Only two credential fields saved; title stayed unsaved and database title stayed unchanged |
| PayPal Save & Register | Only its credential fields saved before mocked registration |
| Register success | Disconnect URL/label and saved-state feedback updated inline; normal form and quick-search credentials locked |
| Failed disconnect | Error shown; saved state and locks retained |
| Successful disconnect | Fields unlocked and Register restored |
| Reload with a saved ID | Both gateways loaded correct Disconnect URLs/IDs and locks, without marking settings unsaved |
| Reload with saved ID and missing secret | Inputs editable, including quick search; action blocked with recovery copy; saved ID retained |
| Sandbox/live mode toggle | Correct environment fields visible; section remained undimmed; hidden-mode keys excluded from quick search |
| Desktop and 390px width | No horizontal overflow; actions remained reachable; screenshots inspected |
| Browser errors during asserted flows | None |

Provider responses in the browser were mocked. Registration/response handling was separately exercised by each extension's mocked controller tests. These results do not establish public webhook delivery, live provider connectivity, or payment completion.

## Compatibility matrix exercised

- New Core bundle + updated extension definitions: generic contract, no legacy mapping used.
- New Core bundle + the original extension PR-head settings definitions (before this follow-up): compatibility adapter used; saved IDs and credential locking worked.
- New Core bundle + those definitions with async opt-in removed: ordinary navigation links and editable credentials. This is a simulated non-async metadata contract, not a full release installation test.
- Merged #3069 bundle + updated extensions: existing async UI still works with additive metadata; saved state/URL/locking checks passed.
- Pre-#3069 bundle (merge first parent) + updated extensions: ordinary Unregister navigation links and editable credentials. This replaces the settings JS/CSS assets for the check, not the entire historical Core installation.

All temporary source/asset swaps were restored. No claim is made about every historical Core/extension release combination.

## Cleanup and evidence

All eight modified fixture option keys were restored exactly, including original absence where applicable. The temporary administrator session was destroyed and private cookie/session/backup files removed. No fixture keys or provider IDs remain from this browser run. Local Stripe runtime source matches its updated PR source.

Screenshots contain obvious fake QA credentials and mocked provider status:

- [Desktop](generic-paypal-desktop-20261011.png)
- [390px mobile](generic-paypal-mobile-20261011.png)

The contract is documented in [async settings actions](../../agents/directorist-settings-panel/references/async-settings-actions.md).

## GitHub checks on the follow-up PRs

- [Stripe #27 PHPCS run](https://github.com/sovware/directorist-stripe-new/actions/runs/38133163105): failed. Its existing `pull_request_target` workflow explicitly checks out `github.event.pull_request.base.ref`, so it linted the merged base rather than this PR's tested head. Logs include the old smoke-test naming/ABSPATH errors corrected in this PR, plus existing `UpdateServiceProvider.php` and `EDDPluginUpdaterStripe.php` failures. Local targeted PHPCS on the actual changed head files is clean. Workflow scope/base-branch lint debt was not changed by this action-contract refactor.
- [PayPal #8 PHPCS run](https://github.com/sovware/directorist-paypal-new/actions/runs/38133163366): failed during Composer setup before PHPCS. Existing constraints (`squizlabs/php_codesniffer` 3.7.2 and WPCS ^2.3) are rejected by Composer security advisories. Local targeted lint uses the available Core-hosted PHPCS runtime with the extension's rules and has the two advisory warnings described above. No Composer security checks were disabled.
- Core #3076 has no check results on the inspected head; its PHP workflow is path-filtered and this follow-up changes no PHP source.

These PRs have local functional/build evidence, but the extension GitHub checks are not green. The workflow/dependency failures require separate CI maintenance before claiming clean repository-wide CI.
