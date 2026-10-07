import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import vm from 'node:vm';

const helperSource = readFileSync(
	new URL('../assets/src/js/admin/vue/mixins/form-fields/webhook-credential-fields.js', import.meta.url),
	'utf8'
).replaceAll('export const', 'const');
const source = helperSource + '\n' + readFileSync(
	new URL('../assets/src/js/admin/vue/mixins/form-fields/button-field.js', import.meta.url),
	'utf8'
)
	.replace("import props from './input-field-props.js';", 'const props = {};')
	.replace("import { WEBHOOK_CREDENTIAL_FIELDS, getSavedWebhookId } from './webhook-credential-fields.js';", '')
	.replace('export default {', 'module.exports = {');

const sandbox = {
	module: { exports: {} },
	URL,
	Error,
	Boolean,
	window: { location: { href: 'https://example.test/wp-admin/', origin: 'https://example.test' } },
};

vm.runInNewContext(source + '\nmodule.exports.isWebhookCredentialLocked = isWebhookCredentialLocked;', sandbox);

const mixin = sandbox.module.exports;
assert.equal(mixin.data.call({ value: [] }).async_connected, false);
assert.equal(mixin.data.call({ value: {} }).async_connected, false);
assert.equal(mixin.data.call({ value: { id: 'we_saved' } }).async_connected, true);
const credentialFields = {
	stripe_test_pk: { value: 'publishable' },
	stripe_test_sk: { value: 'secret' },
	stripe_test_webhook: { value: 'we_saved' },
};
assert.equal(mixin.isWebhookCredentialLocked(credentialFields, 'stripe_test_pk'), true);
assert.equal(mixin.isWebhookCredentialLocked(credentialFields, 'stripe_test_sk'), true);
assert.equal(mixin.isWebhookCredentialLocked(credentialFields, 'paypal_test_secret'), false);
credentialFields.stripe_test_webhook.value = [];
assert.equal(mixin.isWebhookCredentialLocked(credentialFields, 'stripe_test_sk'), false);
credentialFields.stripe_test_webhook.value = 'we_saved';
credentialFields.stripe_test_sk.value = '';
assert.equal(mixin.isWebhookCredentialLocked(credentialFields, 'stripe_test_pk'), false);
credentialFields.paypal_test_client_id = { value: 'client' };
credentialFields.paypal_test_secret = { value: 'secret' };
credentialFields.paypal_test_webhook = { value: { id: 'paypal_saved' } };
assert.equal(mixin.isWebhookCredentialLocked(credentialFields, 'paypal_test_secret'), true);
assert.equal(mixin.isWebhookCredentialLocked(credentialFields, 'paypal_live_secret'), false);
const commits = [];
let fetchCount = 0;
const actionOrder = [];
const context = {
	asyncAction: true,
	async_processing: false,
	async_feedback: null,
	async_connected: false,
	async_action_url: '',
	async_action_label: '',
	buttonLabel: 'Register Sandbox Webhook',
	fieldKey: 'stripe_test_webhook',
	url: 'https://example.test/wp-json/gateway/webhook-register?type=sandbox&key=stripe_test_webhook&_wpnonce=test',
	$store: {
		state: {
			fields: { stripe_test_pk: { value: 'saved-publishable' }, stripe_test_sk: { value: 'saved-secret' } },
			cached_fields: { stripe_test_pk: { value: 'saved-publishable' }, stripe_test_sk: { value: 'saved-secret' } },
		},
		commit: (name, payload) => commits.push({ name, payload }),
	},
	saveWebhookCredentials: async (keys) => {
		actionOrder.push('save');
		assert.deepEqual(Array.from(keys), ['stripe_test_pk', 'stripe_test_sk']);
		context.$store.state.cached_fields.stripe_test_sk.value = context.$store.state.fields.stripe_test_sk.value;
	},
};

for (const [name, compute] of Object.entries(mixin.computed)) {
	Object.defineProperty(context, name, { get: () => compute.call(context) });
}

sandbox.fetch = async () => {
	fetchCount++;
	actionOrder.push('register');
	return ({
	ok: true,
	json: async () => ({
		success: true,
		connected: true,
		webhook_id: 'wh_local_test',
		action_url: 'https://example.test/wp-json/gateway/webhook-unregister?_wpnonce=test',
		action_label: 'Unregister Webhook',
		message: 'Webhook registered.',
	}),
	});
};

context.$store.state.fields.stripe_test_sk.value = 'unsaved-test-key';
assert.equal(context.asyncCredentialsUnsaved, true);
assert.equal(context.asyncActionBlocked, false);
assert.equal(context.asyncButtonLabel, 'Save & Register Sandbox Webhook');
await mixin.methods.submitAsyncAction.call(context);
assert.equal(fetchCount, 1);
assert.deepEqual(actionOrder, ['save', 'register']);

assert.equal(context.async_processing, false);
assert.equal(context.async_connected, true);
assert.equal(context.async_feedback.type, 'success');
assert.equal(commits.length, 1);
assert.equal(commits[0].name, 'setPersistedAsyncField');
assert.equal(commits[0].payload.value, 'wh_local_test');
assert.equal(context.asyncButtonLabel, 'Disconnect Webhook');
assert.equal(context.asyncStatusLabel, 'Webhook ID saved');
assert.match(context.asyncActionUrl, /webhook-unregister/);

context.async_action_url = '';
context.async_action_label = '';
context.buttonLabel = 'Register Sandbox Webhook';
context.value = 'wh_local_test';
assert.equal(context.asyncButtonLabel, 'Disconnect Sandbox Webhook');
assert.match(context.asyncActionUrl, /webhook-unregister\?key=stripe_test_webhook&_wpnonce=test&id=wh_local_test/);
context.value = { id: 'wh_local_test' };
assert.match(context.asyncActionUrl, /webhook-unregister\?key=stripe_test_webhook&_wpnonce=test&id=wh_local_test/);
context.value = 'wh_local_test';

context.async_action_url = 'https://example.test/wp-json/gateway/webhook-unregister?id=wh_local_test';
context.async_action_label = 'Unregister Sandbox Webhook';

sandbox.fetch = async () => ({
	ok: true,
	json: async () => ({ success: false, message: 'The saved webhook belongs to another app.' }),
});

await mixin.methods.submitAsyncAction.call(context);

assert.equal(context.async_connected, true);
assert.equal(context.async_feedback.type, 'error');
assert.equal(commits.length, 1);

context.$store.state.fields.stripe_test_sk.value = '';
context.$store.state.cached_fields.stripe_test_sk.value = '';
assert.equal(context.asyncCredentialsMissing, true);
assert.equal(context.asyncActionBlocked, true);
assert.equal(context.asyncStatusLabel, 'Keys missing');
assert.match(context.asyncActionBlockReason, /valid keys for the same gateway account/);
await mixin.methods.submitAsyncAction.call(context);
assert.equal(commits.length, 1);

context.async_connected = false;
context.async_action_url = '';
context.async_action_label = '';
context.$store.state.fields.stripe_test_pk.value = '';
assert.equal(context.asyncButtonLabel, 'Register Sandbox Webhook');
assert.match(context.asyncActionBlockReason, /Add both API keys/);
context.$store.state.fields.stripe_test_pk.value = 'saved-publishable';
context.$store.state.fields.stripe_test_sk.value = 'new-secret';
context.$store.state.cached_fields.stripe_test_sk.value = 'old-secret';
context.saveWebhookCredentials = async () => {
	throw new Error('Settings save failed.');
};
const beforeFailedSave = fetchCount;
await mixin.methods.submitAsyncAction.call(context);
assert.equal(fetchCount, beforeFailedSave);
assert.equal(context.async_feedback.type, 'error');
assert.match(context.async_feedback.message, /Settings save failed/);

console.log('PASS: One-click scoped save precedes registration; failed save blocks registration.');
