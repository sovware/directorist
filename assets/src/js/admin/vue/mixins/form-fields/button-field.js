import props from './input-field-props.js';

export default {
	mixins: [props],

	data() {
		return {
			local_value: false,
			async_processing: false,
			async_connected: Boolean(this.value),
			async_feedback: null,
		};
	},

	computed: {
		asyncButtonLabel() {
			return this.async_processing
				? this.buttonLabelOnProcessing || 'Working…'
				: this.buttonLabel;
		},
		asyncStatusLabel() {
			return this.async_connected
				? this.statusLabelConnected
				: this.statusLabelDisconnected;
		},
	},

	methods: {
		async submitAsyncAction() {
			if (!this.asyncAction || this.async_processing) {
				return;
			}

			this.async_processing = true;
			this.async_feedback = null;

			try {
				const response = await fetch(this.url.replace(/&amp;/g, '&'), {
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
