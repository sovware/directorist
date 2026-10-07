import props from './input-field-props.js';
import { WEBHOOK_CREDENTIAL_FIELDS, getSavedWebhookId } from './webhook-credential-fields.js';

export default {
	mixins: [props],
	inject: {
		saveWebhookCredentials: { default: null },
	},

	data() {
		return {
			local_value: false,
			async_processing: false,
			async_connected: Boolean(getSavedWebhookId(this.value)),
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

			if (this.async_connected && WEBHOOK_CREDENTIAL_FIELDS[this.fieldKey]) {
				return label.replace(/^(Register|Unregister)\b/, 'Disconnect');
			}

			return this.asyncCredentialsUnsaved && !this.asyncCredentialsMissing && WEBHOOK_CREDENTIAL_FIELDS[this.fieldKey]
				? `Save & ${label}`
				: label;
		},
		asyncActionUrl() {
			if (this.async_action_url) {
				return this.async_action_url;
			}

			const url = new URL(this.url.replace(/&amp;/g, '&'), window.location.href);

			const savedWebhookId = getSavedWebhookId(this.value);

			if (
				this.async_connected &&
				savedWebhookId &&
				WEBHOOK_CREDENTIAL_FIELDS[this.fieldKey] &&
				url.pathname.endsWith('/webhook-register')
			) {
				url.pathname = url.pathname.replace(/webhook-register$/, 'webhook-unregister');
				url.searchParams.delete('type');
				url.searchParams.set('id', savedWebhookId);
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
				!fields[key] || !String(fields[key].value ?? '').trim()
			);
		},
		asyncCredentialsUnsaved() {
			const credentialKeys = WEBHOOK_CREDENTIAL_FIELDS[this.fieldKey] || [];
			const { fields, cached_fields: cachedFields } = this.$store.state;

			return credentialKeys.some((key) => {
				if (!fields[key]) {
					return false;
				}
				if (!cachedFields[key]) {
					return true;
				}

				return String(fields[key].value ?? '') !== String(cachedFields[key].value ?? '');
			});
		},
		asyncActionBlocked() {
			return this.async_processing || this.asyncCredentialsMissing ||
				(this.async_connected && this.asyncCredentialsUnsaved);
		},
		asyncActionBlockReason() {
			if (this.asyncCredentialsMissing) {
				return this.async_connected
					? 'Add valid keys for the same gateway account and save before disconnecting.'
					: 'Add both API keys before registering.';
			}

			if (this.asyncCredentialsUnsaved) {
				return 'Save changes first to use the updated keys.';
			}

			return '';
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
				if (!this.async_connected && this.asyncCredentialsUnsaved) {
					if (typeof this.saveWebhookCredentials !== 'function') {
						throw new Error('Settings save is unavailable. Refresh this page and try again.');
					}

					await this.saveWebhookCredentials(WEBHOOK_CREDENTIAL_FIELDS[this.fieldKey]);

					if (this.asyncCredentialsUnsaved || this.asyncCredentialsMissing) {
						throw new Error('Keys changed while saving. Review them and try again.');
					}
				}

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
