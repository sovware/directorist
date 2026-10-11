# Async settings actions

Core renders reusable async actions in the Butterfly settings button field. Extensions own dependency keys, provider operations, saved provider data, initial/next URLs, and translated labels. Ordinary buttons remain links. This is an additive follow-up to Core #3069.

## Field contract

```php
$fields['example_action'] = [
    'type' => 'button',
    'async-action' => true,
    'url' => $legacy_url,
    'button-label' => $legacy_label,
    'button-label-on-processing' => __( 'Working…', 'example-extension' ),
    'async-action-config' => [
        'requiredFields' => [ 'example_user', 'example_key' ],
        'lockFields' => [ 'example_user', 'example_key' ],
        'saveBeforeAction' => true,
        'labels' => [
            'save' => __( 'Save and connect', 'example-extension' ),
            'active' => __( 'Configuration saved', 'example-extension' ),
            'inactive' => __( 'Not configured', 'example-extension' ),
            'missing' => __( 'Required fields missing', 'example-extension' ),
            'required' => __( 'Complete both fields first.', 'example-extension' ),
            'recovery' => __( 'Restore the matching credentials and save first.', 'example-extension' ),
            'unsaved' => __( 'Save changes first.', 'example-extension' ),
            'locked' => __( 'Disconnect before editing these fields.', 'example-extension' ),
            'pending' => __( 'Wait for this action to finish.', 'example-extension' ),
            'details' => __( 'Configuration is saved locally.', 'example-extension' ),
            'recoveryDetails' => __( 'Saved state exists but required fields are missing.', 'example-extension' ),
        ],
    ],
    'async-action-state' => [
        'active' => $is_active, // Boolean, not a string.
        'value' => $public_ui_value, // No secret needed by the UI.
        'url' => $current_action_url,
        'label' => $current_action_label,
    ],
];
```

The extension must derive initial state from saved data. Core does not interpret provider option structures or infer action routes for this contract. The legacy `url` and `button-label` remain necessary for older Core and normal navigation.

An action endpoint receives the existing `X-Directorist-Async: 1` header. A successful JSON response has `success: true`, an optional `message`, and `action_state` with the same four state properties. Both initial and next URLs must resolve to the current origin; malformed URLs fail without sending a request. Endpoints continue enforcing their own nonce, capability, and input validation.

On failure, return `success: false` and an actionable `message`. Core retains the previously acknowledged state. A saved/active state is not proof of external provider health, delivery, or payment completion.

## Save, lock, and visibility semantics

- While inactive, `saveBeforeAction: true` saves only changed `requiredFields` through `save_settings_data`, then executes the action. Failure or edits detected during that save prevent the action.
- While active, unsaved required fields block the action until saved. Missing fields keep the recovery path editable.
- `lockFields` are disabled when active and all required fields exist, in both the text field and quick search. During a request, required/locked fields are disabled even before connection succeeds. These are UI guards, not server enforcement.
- Action-owned values are excluded from the normal settings save payload. This prevents a public UI identifier from overwriting provider-owned structured data, such as a saved signing secret.
- An acknowledged action updates live and cached state together, so it does not create an unrelated unsaved settings change.
- Set `search-respect-show-if: true` on fields whose quick-search visibility should use their existing `show-if` condition. This reuses the settings condition evaluator and does not change unrelated search behavior.
- Set `section-toggle: false` on a first toggle field that selects a mode instead of enabling/disabling its entire section.
- Supply whole translated labels. Core renders them as text and decodes the HTML entities introduced by settings sanitization; it does not replace English verbs.

## Compatibility boundary

`assets/src/js/admin/vue/compat/legacy-webhook-actions.js` adapts the previous async contract before the settings app mounts. Its Stripe/PayPal mappings, old URL correction, old response keys and old copy are an intentional compatibility exception, isolated from generic components. New extensions supply explicit metadata and bypass that mapping. Existing opt-in async consumers without the new metadata still use the old response contract; non-async buttons keep link behavior and editable credentials.

Stripe #26 and PayPal #7 return `action_state` alongside their existing `connected`, `webhook_id`, `action_url`, and `action_label` fields. Requests without the async header retain redirects. No option keys, settings storage, AJAX endpoint, nonce/capability contract, or migration changed.

Do not remove the adapter based only on the status of an extension PR. It also protects development/third-party installations using the previously merged contract. Removal needs a separately agreed compatibility policy.

## Verification

Run `node tests/async-button-field-smoke.mjs` for arbitrary-service action state, translated labels, selective payload construction, pending/active locks, recovery, response validation, URL guards, legacy JSON, and non-async behavior.

For a targeted production build, load `webpack.legacy.dev.js`, select only `js/admin/settings-manager` and `css/admin/main` from its entry map, set `mode: 'production'` and `devtool: false`, then run Webpack. This emits settings JS and admin CSS/RTL without running the full packaging pipeline.

The local browser matrix and fixture-restoration evidence are recorded in `docs/qa/async-webhook/generic-actions-qa-20261011.md`.
