const path = require('path');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const devHost = 'directorist.local';

module.exports = {
	...defaultConfig,
	entry: {
		'css/admin-order': './resources/sass/app.scss',
		'js/admin/order': './resources/js/admin/order/index.tsx',
		'js/frontend/payment-receipt':
			'./resources/js/frontend/payment-receipt.js',
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
