import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import vm from 'node:vm';
const read = (file) =>
	readFileSync(
		new URL('../assets/src/js/admin/' + file, import.meta.url),
		'utf8'
	);
const sandbox = {
	module: { exports: {} },
	URL,
	window: {
		location: {
			href: 'https://example.test/wp-admin/',
			origin: 'https://example.test',
		},
	},
	__: (text) => text,
};
const helper = read('vue/mixins/form-fields/async-action-fields.js').replaceAll(
	'export ',
	''
);
const legacy = read('vue/compat/legacy-webhook-actions.js').replaceAll(
	'export ',
	''
);
const source = read('vue/mixins/form-fields/button-field.js')
	.replace(/import[\s\S]*?from\s+['"][^'"]+['"];?/g, '')
	.replace('export default', 'module.exports =');
vm.runInNewContext(
	'const props = {};\n' +
		helper +
		legacy +
		source +
		'\nmodule.exports.helpers = { getActionFieldLock, adaptLegacyWebhookFields };',
	sandbox
);
const mixin = sandbox.module.exports;
const { getActionFieldLock, adaptLegacyWebhookFields } = mixin.helpers;
const fields = {
	service_user: { value: 'demo' },
	service_token: { value: 'saved' },
	unrelated: { value: 'unsaved-other-setting' },
	service_action: {
		'async-action': true,
		'async-action-config': {
			requiredFields: ['service_user', 'service_token'],
			lockFields: ['service_user', 'service_token'],
			saveBeforeAction: true,
			labels: {
				save: 'সংরক্ষণ ও সংযোগ',
				active: 'সংযুক্ত',
				inactive: 'বিচ্ছিন্ন',
				locked: 'আগে বিচ্ছিন্ন করুন',
				pending: 'অপেক্ষা করুন',
			},
		},
		'async-action-state': {
			active: false,
			value: '',
			url: '/connect',
			label: 'সংযোগ',
		},
	},
};
const cached = JSON.parse(JSON.stringify(fields));
const events = [];
const context = {
	...mixin.data.call({}),
	fieldKey: 'service_action',
	asyncAction: true,
	buttonLabel: 'fallback',
	get asyncActionConfig() {
		return fields.service_action['async-action-config'];
	},
	get asyncActionState() {
		return fields.service_action['async-action-state'];
	},
	$store: {
		state: { fields, cached_fields: cached },
		commit(name, payload) {
			events.push(name);
			if (name === 'setAsyncActionPending')
				fields.service_action.asyncActionPending = payload.pending;
			if (name === 'setPersistedAsyncField') {
				for (const key of [
					'value',
					'url',
					'button-label',
					'async-action-state',
				]) {
					fields.service_action[key] = payload[key];
					cached.service_action[key] = payload[key];
				}
			}
		},
	},
	async saveActionFields(keys) {
		assert.deepEqual(Array.from(keys), ['service_user', 'service_token']);
		events.push('save');
		cached.service_token.value = fields.service_token.value;
	},
};
for (const [name, fn] of Object.entries(mixin.computed))
	Object.defineProperty(context, name, { get: () => fn.call(context) });
for (const [name, fn] of Object.entries(mixin.methods))
	context[name] = fn.bind(context);
let requests = 0;
sandbox.fetch = async (url, options) => {
	requests++;
	assert.equal(url, 'https://example.test/connect');
	assert.equal(options.headers['X-Directorist-Async'], '1');
	assert.equal(getActionFieldLock(fields, 'service_token').locked, true);
	assert.equal(getActionFieldLock(fields, 'unrelated').locked, false);
	events.push('request');
	return {
		ok: true,
		json: async () => ({
			success: true,
			action_state: {
				active: true,
				value: 'service-123',
				url: '/disconnect',
				label: 'বিচ্ছিন্ন করুন',
			},
		}),
	};
};
fields.service_token.value = 'new-token';
assert.equal(context.asyncButtonLabel, 'সংরক্ষণ ও সংযোগ');
await context.submitAsyncAction();
assert.deepEqual(events, [
	'setAsyncActionPending',
	'save',
	'request',
	'setPersistedAsyncField',
	'setAsyncActionPending',
]);
assert.equal(context.async_feedback.type, 'success');
assert.equal(context.asyncButtonLabel, 'বিচ্ছিন্ন করুন');
assert.equal(context.asyncStatusLabel, 'সংযুক্ত');
assert.equal(fields.unrelated.value, 'unsaved-other-setting');
assert.equal(getActionFieldLock(fields, 'service_token').locked, true);
assert.equal(
	getActionFieldLock(fields, 'service_token').message,
	'আগে বিচ্ছিন্ন করুন'
);
fields.service_token.value = '';
assert.equal(context.asyncActionBlocked, true);
assert.equal(
	getActionFieldLock(fields, 'service_user').locked,
	false,
	'Missing dependencies keep a recovery path'
);
await context.submitAsyncAction();
assert.equal(requests, 1);
fields.service_token.value = cached.service_token.value;
const savedState = fields.service_action['async-action-state'];
sandbox.fetch = async () => ({
	ok: true,
	json: async () => ({
		success: false,
		message: 'Provider did not confirm removal',
	}),
});
await context.submitAsyncAction();
assert.equal(context.async_feedback.type, 'error');
assert.equal(fields.service_action['async-action-state'], savedState);
assert.equal(fields.service_action.asyncActionPending, false);
sandbox.fetch = async () => ({
	ok: true,
	json: async () => ({
		success: true,
		action_state: {
			active: false,
			value: '',
			url: 'https://outside.test/action',
			label: 'Connect',
		},
	}),
});
await context.submitAsyncAction();
assert.equal(context.async_feedback.type, 'error');
assert.equal(fields.service_action['async-action-state'], savedState);
fields.service_action['async-action-state'] = {
	active: false,
	value: '',
	url: 'https://outside.test/connect',
	label: 'Connect',
};
fields.service_token.value = 'unsaved';
const before = events.filter((event) => event === 'save').length;
await context.submitAsyncAction();
assert.equal(
	events.filter((event) => event === 'save').length,
	before,
	'Unsafe initial URL must fail before save'
);
fields.service_action['async-action-state'].url = '/connect';
context.saveActionFields = async () => {
	throw new Error('Save failed');
};
sandbox.fetch = async () => {
	throw new Error('Must not request after save failure');
};
await context.submitAsyncAction();
assert.equal(context.async_feedback.message, 'Save failed');
context.saveActionFields = async () => {};
await context.submitAsyncAction();
assert.match(context.async_feedback.message, /Fields changed while saving/);
fields.service_token.value = cached.service_token.value;
sandbox.fetch = async () => ({
	ok: true,
	json: async () => ({
		success: true,
		action_state: {
			active: 'true',
			value: 'bad',
			url: '/disconnect',
			label: 'Bad',
		},
	}),
});
await context.submitAsyncAction();
assert.equal(
	context.async_feedback.type,
	'error',
	'Reject malformed state without persisting'
);
sandbox.fetch = async () => ({
	ok: true,
	json: async () => ({
		success: true,
		action_state: {
			active: false,
			value: '',
			url: '/connect',
			label: 'Connect',
		},
	}),
});
await context.submitAsyncAction();
assert.equal(context.async_connected, false);
assert.equal(getActionFieldLock(fields, 'service_token').locked, false);

for (const gateway of ['stripe', 'paypal'])
	for (const environment of ['test', 'live']) {
		const key = `${gateway}_${environment}_webhook`;
		const keys =
			gateway === 'stripe'
				? [`stripe_${environment}_pk`, `stripe_${environment}_sk`]
				: [`paypal_${environment}_client_id`, `paypal_${environment}_secret`];
		const old = {
			[key]: {
				value: { id: 'saved-id' },
				url: `https://example.test/wp-json/${gateway}/webhook-register?type=sandbox&key=${key}`,
				'button-label': 'Register Webhook',
			},
		};
		keys.forEach((name) => {
			old[name] = { value: 'saved' };
		});
		adaptLegacyWebhookFields(old);
		assert.equal(
			getActionFieldLock(old, keys[0]).locked,
			false,
			'Legacy non-async fields remain editable'
		);
		old[key]['async-action'] = true;
		adaptLegacyWebhookFields(old);
		assert.equal(old[key]['async-action-state'].active, true);
		assert.match(old[key]['async-action-state'].url, /webhook-unregister/);
		assert.equal(
			new URL(old[key]['async-action-state'].url).searchParams.get('id'),
			'saved-id'
		);
		assert.equal(getActionFieldLock(old, keys[0]).locked, true);
		old[key]['async-action-state'].active = false;
		assert.equal(getActionFieldLock(old, keys[0]).locked, false);
		// Updated extensions bypass legacy inference even if raw option value is an array.
		const explicit = {
			...old[key],
			value: [],
			'async-action-config': { requiredFields: keys },
			'async-action-state': {
				active: true,
				value: 'custom',
				url: '/explicit',
				label: 'বাংলা',
			},
		};
		adaptLegacyWebhookFields({ [key]: explicit });
		assert.equal(explicit['async-action-state'].label, 'বাংলা');
		assert.equal(explicit['async-action-config'].legacy, undefined);
	}
for (const value of [[], {}, '', null]) {
	const old = {
		stripe_test_webhook: {
			value,
			'async-action': true,
			url: '/webhook-register',
			'button-label': 'Register',
		},
	};
	adaptLegacyWebhookFields(old);
	assert.equal(old.stripe_test_webhook['async-action-state'].active, false);
}
console.log(
	'PASS: generic actions, translated labels, scoped save, pending locks, recovery, URL/state validation, errors, and legacy Stripe/PayPal combinations.'
);
fields.service_action['async-action-config'].labels.save = 'Save &amp; Connect';
fields.service_token.value = 'new-unsaved-token';
assert.equal(
	context.asyncButtonLabel,
	'Save & Connect',
	'Encoded PHP field metadata must display as text'
);
const settingsSource = read('vue/apps/settings-manager/Settings_Manager.vue')
	.split('<script>')[1]
	.split('</script>')[0]
	.replace(/import[\s\S]*?from\s+['"][^'"]+['"];?/g, '')
	.replace('export default', 'module.exports =');
const settingsSandbox = {
	module: { exports: {} },
	require: () => ({ default: {} }),
	mapState: () => ({}),
	mapGetters: () => ({}),
	tabContents: {},
	helpers: {},
	FormData,
};
vm.runInNewContext(settingsSource, settingsSandbox);
const settings = settingsSandbox.module.exports;
const saveContext = {
	fields,
	cached_fields: cached,
	hasUnsavedChanges: true,
	getFieldsValue: () =>
		Object.fromEntries(
			Object.entries(fields).map(([key, field]) => [key, field.value])
		),
	settingsValuesAreSame: (a, b) => JSON.stringify(a) === JSON.stringify(b),
	maybeJSON: (value) => JSON.stringify(value),
};
fields.service_action.forceUpdate = true;
const payload = settings.methods.getSettingsSavePayload.call(saveContext, [
	'service_token',
]);
assert.deepEqual(Array.from(payload.field_list), ['service_token']);
assert.equal(payload.form_data.has('unrelated'), false);
const allPayload = settings.methods.getSettingsSavePayload.call(saveContext);
assert.equal(
	allPayload.field_list.includes('service_action'),
	false,
	'Do not overwrite endpoint-owned values through normal Save'
);
console.log(
	'PASS: real selective-save payload builder excludes unrelated and endpoint-owned values.'
);
fields.service_action['async-action-config'] = {
	legacy: true,
	legacyWebhook: true,
	requiredFields: [],
};
fields.service_action['async-action-state'] = {
	active: false,
	value: '',
	url: '/legacy',
	label: 'Register',
};
sandbox.fetch = async () => ({
	ok: true,
	json: async () => ({
		success: true,
		connected: true,
		webhook_id: 'legacy-id',
		action_url: '/legacy-remove',
		action_label: 'Unregister Webhook',
	}),
});
await context.submitAsyncAction();
assert.equal(context.async_connected, true);
assert.equal(context.asyncButtonLabel, 'Disconnect Webhook');
assert.equal(fields.service_action.value, 'legacy-id');
assert.doesNotThrow(() =>
	adaptLegacyWebhookFields({
		broken: { 'async-action': true, url: 'http://[' },
	})
);
console.log(
	'PASS: legacy JSON response negotiation and malformed-link isolation.'
);
