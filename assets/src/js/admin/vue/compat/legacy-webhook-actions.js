// Compatibility with the async contract shipped by Core #3069.
// New integrations must provide async-action-config and async-action-state.
const credentials = {
	paypal_live_webhook: ['paypal_live_client_id', 'paypal_live_secret'],
	paypal_test_webhook: ['paypal_test_client_id', 'paypal_test_secret'],
	stripe_live_webhook: ['stripe_live_pk', 'stripe_live_sk'],
	stripe_test_webhook: ['stripe_test_pk', 'stripe_test_sk'],
};

const savedId = (value) =>
	typeof value === 'string' || typeof value === 'number'
		? String(value)
		: value && !Array.isArray(value)
			? String(value.id || '')
			: '';

export function adaptLegacyWebhookFields(fields) {
	for (const [key, field] of Object.entries(fields)) {
		if (
			['stripe_gateway_test_mode', 'paypal_gateway_test_mode'].includes(key) &&
			field['section-toggle'] === undefined
		) {
			field['section-toggle'] = false;
		}
		const condition = field.showIf || field.show_if || field['show-if'];
		if (
			condition &&
			['stripe_gateway_test_mode', 'paypal_gateway_test_mode'].includes(
				condition.where
			) &&
			field['search-respect-show-if'] === undefined
		) {
			field['search-respect-show-if'] = true;
		}
		if (field['async-action'] !== true || field['async-action-config'])
			continue;
		const id = savedId(field.value);
		let url;
		try {
			url = new URL(
				String(field.url || '').replace(/&amp;/g, '&'),
				window.location.href
			);
		} catch (error) {
			// Leave malformed links for the action's error handling; do not break the settings page.
			url = null;
		}
		if (
			url &&
			credentials[key] &&
			id &&
			url.pathname.endsWith('/webhook-register')
		) {
			url.pathname = url.pathname.replace(
				/webhook-register$/,
				'webhook-unregister'
			);
			url.searchParams.delete('type');
			url.searchParams.set('id', id);
		}
		const label =
			id && credentials[key]
				? String(field['button-label'] || '').replace(
						/^(Register|Unregister)\b/,
						'Disconnect'
					)
				: field['button-label'];
		field['async-action-state'] = {
			active: !!id,
			value: id,
			url: field.url && url ? url.href : '',
			label,
		};
		field['async-action-config'] = {
			legacy: true,
			legacyWebhook: !!credentials[key],
			requiredFields: credentials[key] || [],
			lockFields: credentials[key] || [],
			saveBeforeAction: true,
			labels: {
				save: `Save & ${field['button-label']}`,
				active: credentials[key]
					? 'Webhook ID saved'
					: field['status-label-connected'] || 'Connected',
				inactive: field['status-label-disconnected'] || 'Not registered',
				missing: 'Keys missing',
				required: 'Add both API keys before registering.',
				recovery:
					'Add valid keys for the same gateway account and save before disconnecting.',
				unsaved: 'Save changes first to use the updated keys.',
				locked: 'Disconnect the webhook to edit these keys.',
				details:
					'Webhook ID saved on this site; provider status has not been rechecked. Disconnect to remove the gateway webhook.',
				recoveryDetails:
					'Webhook ID is still saved. Add valid keys for the same gateway account, save changes, then disconnect. Removing keys does not remove the gateway webhook.',
			},
		};
	}
	return fields;
}

export function legacyActionState(result, config = {}) {
	return {
		active: Boolean(result.connected),
		value: result.webhook_id || '',
		url: result.action_url,
		label:
			result.connected && config.legacyWebhook
				? result.action_label?.replace(/^(Register|Unregister)\b/, 'Disconnect')
				: result.action_label,
	};
}
