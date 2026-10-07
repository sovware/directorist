import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import vm from 'node:vm';

const source = readFileSync(
	new URL('../assets/src/js/admin/vue/mixins/form-fields/button-field.js', import.meta.url),
	'utf8'
)
	.replace("import props from './input-field-props.js';", 'const props = {};')
	.replace('export default {', 'module.exports = {');

const sandbox = {
	module: { exports: {} },
	URL,
	Error,
	Boolean,
	window: { location: { href: 'https://example.test/wp-admin/', origin: 'https://example.test' } },
};

vm.runInNewContext(source, sandbox);

const mixin = sandbox.module.exports;
const commits = [];
const context = {
	asyncAction: true,
	async_processing: false,
	async_feedback: null,
	async_connected: false,
	url: 'https://example.test/wp-json/gateway/webhook-register?_wpnonce=test',
	fieldKey: 'gateway_test_webhook',
	$store: { commit: (name, payload) => commits.push({ name, payload }) },
};

sandbox.fetch = async () => ({
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

await mixin.methods.submitAsyncAction.call(context);

assert.equal(context.async_processing, false);
assert.equal(context.async_connected, true);
assert.equal(context.async_feedback.type, 'success');
assert.equal(commits.length, 1);
assert.equal(commits[0].name, 'setPersistedAsyncField');
assert.equal(commits[0].payload.value, 'wh_local_test');

sandbox.fetch = async () => ({
	ok: true,
	json: async () => ({ success: false, message: 'The saved webhook belongs to another app.' }),
});

await mixin.methods.submitAsyncAction.call(context);

assert.equal(context.async_connected, true);
assert.equal(context.async_feedback.type, 'error');
assert.equal(commits.length, 1);

console.log('PASS: Async button updates the local action state on success and preserves it on error.');
