const path = require( 'path' );
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const devHost = 'directorist.local';

module.exports = {
	...defaultConfig,
	entry: {
		'js/admin-order': './resources/js/admin/index.tsx',
		'css/admin-order': './resources/sass/app.scss',
	},
	output: {
		...defaultConfig.output,
		path: path.resolve( __dirname, './assets/build/' ),
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
	}
};