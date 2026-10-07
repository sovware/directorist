import props from './input-field-props.js';

const WEBHOOK_CREDENTIAL_FIELDS = {
	paypal_live_webhook: ['paypal_live_client_id', 'paypal_live_secret'],
	paypal_test_webhook: ['paypal_test_client_id', 'paypal_test_secret'],
	stripe_live_webhook: ['stripe_live_pk', 'stripe_live_sk'],
	stripe_test_webhook: ['stripe_test_pk', 'stripe_test_sk'],
};

export default {
	mixins: [props],

	data() {
		return {
			local_value: false,
			async_processing: false,
			async_connected: Boolean(this.value),
			async_feedback: null,
			async_action_url: '',
			async_action_label: '',
		};
	},

	computed: {
		asyncButtonLabel() {
			if (this.async_processing) {
				return this.buttonLabelOnProcessing || 'Working…';
			}

			const label = this.async_action_label || this.buttonLabel;

			return this.async_connected && WEBHOOK_CREDENTIAL_FIELDS[this.fieldKey]
				? label.replace(/^(Register|Unregister)\b/, 'Disconnect')
				: label;
		},
		asyncActionUrl() {
			if (this.async_action_url) {
				return this.async_action_url;
			}

			const url = new URL(this.url.replace(/&amp;/g, '&'), window.location.href);

			if (
				this.async_connected &&
				this.value &&
				WEBHOOK_CREDENTIAL_FIELDS[this.fieldKey] &&
				url.pathname.endsWith('/webhook-register')
			) {
				url.pathname = url.pathname.replace(/webhook-register$/, 'webhook-unregister');
				url.searchParams.delete('type');
				url.searchParams.set('id', this.value);
			}

			return url.href;
		},
		asyncStatusLabel() {
			if (this.async_connected && this.asyncCredentialsMissing) {
				return 'Keys missing';
			}

			if (this.async_connected && WEBHOOK_CREDENTIAL_FIELDS[this.fieldKey]) {
				return 'Webhook ID saved';
			}

			return this.async_connected
				? this.statusLabelConnected
				: this.statusLabelDisconnected;
		},
		asyncCredentialsMissing() {
			const credentialKeys = WEBHOOK_CREDENTIAL_FIELDS[this.fieldKey] || [];
			const { fields } = this.$store.state;

			return credentialKeys.some((key) =>
				fields[key] && !String(fields[key].value ?? '').trim()
			);
		},
		asyncCredentialsUnsaved() {
			const credentialKeys = WEBHOOK_CREDENTIAL_FIELDS[this.fieldKey] || [];
			const { fields, cached_fields: cachedFields } = this.$store.state;

			return credentialKeys.some((key) => {
				if (!fields[key] || !cachedFields[key]) {
					return false;
				}

				return String(fields[key].value ?? '') !== String(cachedFields[key].value ?? '');
			});
		},
		asyncActionBlocked() {
			return this.async_processing || this.asyncCredentialsUnsaved || this.asyncCredentialsMissing;
		},
		asyncActionBlockReason() {
			if (this.asyncCredentialsUnsaved) {
				return 'Save changes first to use the updated keys.';
			}

			return this.async_connected
				? 'Restore the original keys and save changes before disconnecting.'
				: 'Add both API keys and save changes before registering.';
		},
	},

	methods: {
		async submitAsyncAction() {
			if (!this.asyncAction || this.asyncActionBlocked) {
				return;
			}

			this.async_processing = true;
			this.async_feedback = null;

			try {
				const response = await fetch(this.asyncActionUrl, {
					credentials: 'same-origin',
					cache: 'no-store',
					headers: {
						Accept: 'application/json',
						'X-Directorist-Async': '1',
					},
				});
				const result = await response.json();

				if (!response.ok || !result.success) {
					throw new Error(result.message || 'The action could not be completed.');
				}

				const nextUrl = new URL(result.action_url, window.location.href);

				if (nextUrl.origin !== window.location.origin || !result.action_label) {
					throw new Error('The action completed, but the new button could not be shown. Refresh this page.');
				}

				this.async_connected = Boolean(result.connected);
				this.async_action_url = nextUrl.href;
				this.async_action_label = result.action_label;
				this.$store.commit('setPersistedAsyncField', {
					field_key: this.fieldKey,
					value: result.webhook_id || '',
					url: nextUrl.href,
					'button-label': result.action_label,
				});
				this.async_feedback = {
					type: 'success',
					message: result.message || 'The action completed.',
				};
			} catch (error) {
				this.async_feedback = {
					type: 'error',
					message: error.message || 'The request failed. Check your connection and try again.',
				};
			} finally {
				this.async_processing = false;
			}
		},
	},
};
