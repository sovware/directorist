import { __ } from '@wordpress/i18n';
import props from './input-field-props.js';
import {
	actionFieldKeys,
	hasActionFieldValue,
	actionText,
} from './async-action-fields.js';
import { legacyActionState } from '../../compat/legacy-webhook-actions.js';

export default {
	mixins: [props],
	inject: { saveActionFields: { default: null } },
	data() {
		return {
			local_value: false,
			async_processing: false,
			async_feedback: null,
		};
	},
	computed: {
		actionConfig() {
			return this.asyncActionConfig || {};
		},
		actionState() {
			return this.asyncActionState || {};
		},
		actionLabels() {
			return Object.fromEntries(
				Object.entries(this.actionConfig.labels || {}).map(([key, value]) => [
					key,
					actionText(value),
				])
			);
		},
		async_connected() {
			return this.actionState.active === true;
		},
		requiredFieldKeys() {
			return actionFieldKeys(this.actionConfig.requiredFields);
		},
		asyncButtonLabel() {
			if (this.async_processing)
				return (
					actionText(this.buttonLabelOnProcessing) ||
					__('Working…', 'directorist')
				);
			if (
				!this.async_connected &&
				this.asyncFieldsUnsaved &&
				!this.asyncFieldsMissing &&
				this.actionConfig.saveBeforeAction
			) {
				return (
					this.actionLabels.save ||
					actionText(this.actionState.label || this.buttonLabel)
				);
			}
			return actionText(this.actionState.label || this.buttonLabel);
		},
		asyncActionUrl() {
			return this.actionState.url || this.url;
		},
		asyncStatusLabel() {
			if (this.async_connected && this.asyncFieldsMissing)
				return (
					this.actionLabels.missing ||
					__('Required fields missing', 'directorist')
				);
			return this.async_connected
				? this.actionLabels.active || this.statusLabelConnected
				: this.actionLabels.inactive || this.statusLabelDisconnected;
		},
		asyncDetails() {
			return this.async_connected
				? this.asyncFieldsMissing
					? this.actionLabels.recoveryDetails
					: this.actionLabels.details
				: '';
		},
		asyncFieldsMissing() {
			const { fields } = this.$store.state;
			return this.requiredFieldKeys.some(
				(key) => !fields[key] || !hasActionFieldValue(fields[key].value)
			);
		},
		asyncFieldsUnsaved() {
			const { fields, cached_fields: cached } = this.$store.state;
			return this.requiredFieldKeys.some(
				(key) =>
					fields[key] &&
					(!cached[key] ||
						JSON.stringify(fields[key].value) !==
							JSON.stringify(cached[key].value))
			);
		},
		asyncActionBlocked() {
			return (
				this.async_processing ||
				this.asyncFieldsMissing ||
				(this.asyncFieldsUnsaved &&
					(this.async_connected || !this.actionConfig.saveBeforeAction))
			);
		},
		asyncActionBlockReason() {
			if (this.asyncFieldsMissing)
				return (
					(this.async_connected
						? this.actionLabels.recovery
						: this.actionLabels.required) ||
					__('Complete the required fields first.', 'directorist')
				);
			return this.asyncFieldsUnsaved
				? this.actionLabels.unsaved || __('Save changes first.', 'directorist')
				: '';
		},
	},
	methods: {
		validateActionUrl(value) {
			if (typeof value !== 'string' || !value.trim())
				throw new Error(
					__('The action URL is missing. Refresh this page.', 'directorist')
				);
			const url = new URL(value.replace(/&amp;/g, '&'), window.location.href);
			if (
				url.origin !== window.location.origin ||
				!['https:', 'http:'].includes(url.protocol) ||
				url.username ||
				url.password
			) {
				throw new Error(
					__('The action URL is invalid. Refresh this page.', 'directorist')
				);
			}
			return url.href;
		},
		async submitAsyncAction() {
			if (!this.asyncAction || this.asyncActionBlocked) return;
			this.async_processing = true;
			this.async_feedback = null;
			this.$store.commit('setAsyncActionPending', {
				field_key: this.fieldKey,
				pending: true,
			});
			try {
				// Validate before saving any dependencies or making a request.
				const url = this.validateActionUrl(this.asyncActionUrl);
				if (
					!this.async_connected &&
					this.asyncFieldsUnsaved &&
					this.actionConfig.saveBeforeAction
				) {
					if (typeof this.saveActionFields !== 'function')
						throw new Error(
							__(
								'Settings save is unavailable. Refresh this page and try again.',
								'directorist'
							)
						);
					await this.saveActionFields(this.requiredFieldKeys);
					if (this.asyncFieldsUnsaved || this.asyncFieldsMissing)
						throw new Error(
							__(
								'Fields changed while saving. Review them and try again.',
								'directorist'
							)
						);
				}
				const response = await fetch(url, {
					credentials: 'same-origin',
					cache: 'no-store',
					headers: { Accept: 'application/json', 'X-Directorist-Async': '1' },
				});
				const result = await response.json();
				if (!response.ok || !result.success)
					throw new Error(
						result.message ||
							__('The action could not be completed.', 'directorist')
					);
				const state = this.actionConfig.legacy
					? legacyActionState(result, this.actionConfig)
					: result.action_state;
				if (
					!state ||
					typeof state.active !== 'boolean' ||
					typeof state.label !== 'string' ||
					!state.label ||
					!Object.prototype.hasOwnProperty.call(state, 'value')
				) {
					throw new Error(
						__(
							'The action completed, but its state could not be shown. Refresh this page.',
							'directorist'
						)
					);
				}
				const nextState = {
					active: state.active,
					value: state.value,
					url: this.validateActionUrl(state.url),
					label: state.label,
				};
				this.$store.commit('setPersistedAsyncField', {
					field_key: this.fieldKey,
					value: nextState.value,
					url: nextState.url,
					'button-label': nextState.label,
					'async-action-state': nextState,
				});
				this.async_feedback = {
					type: 'success',
					message: result.message || __('The action completed.', 'directorist'),
				};
			} catch (error) {
				this.async_feedback = {
					type: 'error',
					message:
						error.message ||
						__(
							'The request failed. Check your connection and try again.',
							'directorist'
						),
				};
			} finally {
				this.async_processing = false;
				this.$store.commit('setAsyncActionPending', {
					field_key: this.fieldKey,
					pending: false,
				});
			}
		},
	},
};
