const FileManagerPlugin = require('filemanager-webpack-plugin');
const devConfig = require('./webpack.legacy.dev.js');

module.exports = {
	...devConfig,
	mode: 'production',
	plugins: [
		...devConfig.plugins,
		new FileManagerPlugin({
			events: {
				onEnd: [
					{
						copy: [
							{
								source: './assets',
								destination:
									'./__build/directorist/directorist/assets',
							},
							{
								source: './blocks',
								destination:
									'./__build/directorist/directorist/blocks',
							},
							{
								source: './languages',
								destination:
									'./__build/directorist/directorist/languages',
							},
							{
								source: './includes',
								destination:
									'./__build/directorist/directorist/includes',
							},
							{
								source: './templates',
								destination:
									'./__build/directorist/directorist/templates',
							},
							{
								source: './views',
								destination:
									'./__build/directorist/directorist/views',
							},
							{
								source: './*.php',
								destination:
									'./__build/directorist/directorist',
							},
							{
								source: './*.txt',
								destination:
									'./__build/directorist/directorist',
							},
						],
					},
					{
						delete: [
							'./__build/directorist/directorist/assets/src',
						],
					},
					{
						archive: [
							{
								source: './__build/directorist',
								destination: './__build/directorist.zip',
							},
						],
					},
					{
						delete: ['./__build/directorist'],
					},
				],
			},
		}),
	],
	devtool: false,
};
