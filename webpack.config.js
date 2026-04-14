const path          = require('path');
const WebpackBar    = require('webpackbar');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');

const devHost = 'directorist.local';

module.exports = {
	...defaultConfig,
	entry: {
		'js/react/admin/order': './assets/src/js/react/admin/pages/index.tsx',
		'js/react/frontend/payment-receipt': './assets/src/js/react/frontend/payment-receipt.js',
		'js/react/frontend/listing-owner-dashboard': './assets/src/js/react/frontend/listing-owner-dashboard/index.tsx',
		
		'css/admin/app': './assets/src/scss/admin-app.scss',
		'css/public/app': './assets/src/scss/public-app.scss',
	},
	output: {
		...defaultConfig.output,
		path: path.resolve(__dirname, './assets/build/'),
	},
	plugins: [
		...defaultConfig.plugins,
		new WebpackBar({
			name: 'Default Build',
			color: '#4CAF50',
			profile: true,
			basic: false,
		}),
	],
	resolve: {
		...defaultConfig.resolve,
		alias: {
			...(defaultConfig.resolve && defaultConfig.resolve.alias
				? defaultConfig.resolve.alias
				: {}),
			'@': path.resolve(__dirname, 'assets/src/js/react/'),
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
