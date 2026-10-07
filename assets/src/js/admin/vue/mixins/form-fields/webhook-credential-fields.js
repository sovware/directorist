export const WEBHOOK_CREDENTIAL_FIELDS = {
	paypal_live_webhook: ['paypal_live_client_id', 'paypal_live_secret'],
	paypal_test_webhook: ['paypal_test_client_id', 'paypal_test_secret'],
	stripe_live_webhook: ['stripe_live_pk', 'stripe_live_sk'],
	stripe_test_webhook: ['stripe_test_pk', 'stripe_test_sk'],
};

export const getSavedWebhookId = (value) => {
	if (typeof value === 'string' || typeof value === 'number') {
		return String(value);
	}

	return value && !Array.isArray(value) && typeof value === 'object'
		? String(value.id || '')
		: '';
};

export const isWebhookCredentialLocked = (fields, credentialKey) => {
	const webhookKey = Object.keys(WEBHOOK_CREDENTIAL_FIELDS).find((key) =>
		WEBHOOK_CREDENTIAL_FIELDS[key].includes(credentialKey)
	);

	if (!webhookKey || !fields[webhookKey] || !getSavedWebhookId(fields[webhookKey].value)) {
		return false;
	}

	// Keep a recovery path if credentials were removed outside the locked UI.
	return WEBHOOK_CREDENTIAL_FIELDS[webhookKey].every((key) =>
		fields[key] && String(fields[key].value ?? '').trim()
	);
};
