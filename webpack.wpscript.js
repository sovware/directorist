const path = require('path');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const devHost = 'directorist.local';

module.exports = {
	...defaultConfig,
	entry: {
		'css/admin-order': './resources/sass/app.scss',
		'js/admin/order': './resources/js/admin/pages/index.tsx',
		'css/frontend': './resources/sass/frontend.scss',
		'js/frontend/payment-receipt':
			'./resources/js/frontend/payment-receipt.js',
		'js/frontend/listing-owner-dashboard':
			'./resources/js/frontend/listing-owner-dashboard/index.tsx',
	},
	output: {
		...defaultConfig.output,
		path: path.resolve(__dirname, './assets/build/'),
	},
	resolve: {
		...defaultConfig.resolve,
		alias: {
			...(defaultConfig.resolve && defaultConfig.resolve.alias
				? defaultConfig.resolve.alias
				: {}),
			'@babel/runtime': path.dirname(
				require.resolve('@babel/runtime/package.json')
			),
			'@': path.resolve(__dirname, 'resources/js/'),
		},
	},
	devServer: {
		devMiddleware: {
			writeToDisk: true,
		},
		allowedHosts: 'auto',
		port: 8887,
		host: devHost,
		proxy: {
			'/assets/build': {
				pathRewrite: {
					'^/assets/build': '',
				},
			},
		},
		headers: { 'Access-Control-Allow-Origin': '*' },
	},
};
