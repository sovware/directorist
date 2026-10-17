const assert = require('node:assert/strict');
const { readFileSync } = require('node:fs');
const path = require('node:path');
const { test } = require('node:test');
const vm = require('node:vm');
const { transformSync } = require('@babel/core');

const filename = path.resolve(
	__dirname,
	'../assets/src/js/formgent-integration/utils/enquiryUtils.js'
);
const { code } = transformSync(readFileSync(filename, 'utf8'), {
	filename,
	babelrc: false,
	configFile: false,
	plugins: ['@babel/plugin-transform-modules-commonjs'],
});

function loadUtils(
	apiFetch = async () => {
		throw new Error('Unexpected API call');
	}
) {
	const opens = [];
	const notices = [];
	const context = {
		exports: {},
		require(name) {
			if (name === '@wordpress/api-fetch')
				return { __esModule: true, default: apiFetch };
			if (name === '@wordpress/hooks')
				return { doAction: (...args) => notices.push(args) };
			throw new Error(`Unexpected import: ${name}`);
		},
		window: { open: (...args) => opens.push(args) },
		console: { error() {} },
	};
	vm.runInNewContext(code, context, { filename });
	return { ...context.exports, opens, notices };
}

const emailAnswer = (value) => ({ field_type: 'email', value });

test('guest reply uses the submitted email answer', async () => {
	const utils = loadUtils();
	await utils.sendEmailToUser({
		id: '2',
		answers: [emailAnswer('guest@example.com')],
		listing_title: 'Holiday & House',
		user: { user_email: '' },
	});
	const url = new URL(utils.opens[0][0]);
	assert.equal(decodeURIComponent(url.pathname), 'guest@example.com');
	assert.equal(
		url.searchParams.get('subject'),
		'Your Enquiry on Holiday & House'
	);
	assert.equal(utils.opens[0][1], '_self');
});

test('submitted email takes priority over a different account email, including nested answers', () => {
	const utils = loadUtils();
	assert.equal(
		utils.getUserEmail({
			answers: [
				{
					field_type: 'repeater',
					children: [emailAnswer(' contact@example.com ')],
				},
			],
			user: { user_email: 'account@example.com' },
		}),
		'contact@example.com'
	);
});

test('account email remains the fallback when no valid email answer exists', () => {
	const utils = loadUtils();
	assert.equal(
		utils.getUserEmail({
			answers: [
				emailAnswer('invalid'),
				{ field_type: 'text', value: 'ignore@example.com' },
			],
			user_email: 'account@example.com',
		}),
		'account@example.com'
	);
});

test('a table action fetches unloaded answers and preserves the listing subject', async () => {
	const requests = [];
	const utils = loadUtils(async (request) => {
		requests.push(request);
		return {
			success: true,
			response: {
				id: '2',
				user_email: 'account@example.com',
				answers: [emailAnswer('guest@example.com')],
			},
		};
	});
	await utils.sendEmailToUser({
		id: '2',
		listing_title: 'Holiday House',
		user: { user_email: 'account@example.com' },
	});
	assert.equal(requests[0].path, '/directorist/formgent/responses/single?id=2');
	assert.equal(
		decodeURIComponent(new URL(utils.opens[0][0]).pathname),
		'guest@example.com'
	);
	assert.equal(
		new URL(utils.opens[0][0]).searchParams.get('subject'),
		'Your Enquiry on Holiday House'
	);
});

test('missing email and failed detail lookup show an error without opening a mail client', async () => {
	const missing = loadUtils();
	await missing.sendEmailToUser({
		id: '2',
		answers: [],
		user: { user_email: '' },
	});
	assert.equal(missing.opens.length, 0);
	assert.equal(missing.notices[0][1].type, 'error');
	const failed = loadUtils(async () => ({ success: false }));
	await failed.sendEmailToUser({
		id: '2',
		user: { user_email: 'account@example.com' },
	});
	assert.equal(failed.opens.length, 0);
	assert.equal(failed.notices[0][1].type, 'error');
});
