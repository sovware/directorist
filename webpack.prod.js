const common = require('./webpack.common');
const { merge } = require('webpack-merge');

const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const WebpackRTLPlugin = require('webpack-rtl-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const FileManagerPlugin = require('filemanager-webpack-plugin');
const WebpackBar = require('webpackbar');

const prodConfig = {
	mode: 'production', // production | development
	watch: false,
	entry: {
		['admin-multi-directory-builder']:
			'./assets/src/js/admin/multi-directory-builder.js',
		['admin-settings-manager']: './assets/src/js/admin/settings-manager.js',
	},
	optimization: {
		splitChunks: {
			cacheGroups: {
				vueVendor: {
					test: /[\\/]node_modules[\\/](vue|vuex|vue-slide-up-down|vuedraggable|vue-dndrop|vue-multiselect|vue-native-color-picker)[\\/]/,
					name: 'admin-vue-vendor',
					chunks: 'all',
					minSize: 0,
				},
			},
		},
	},
	plugins: [
		new WebpackBar({
			name: 'Production Build',
			color: '#4CAF50',
			profile: true,
			basic: false,
		}),
		new MiniCssExtractPlugin({
			filename: '../css/[name].min.css',
		}),
		new WebpackRTLPlugin({
			filename: '../css/[name].rtl.min.css',
		}),
		new CleanWebpackPlugin({
			dry: false,
			cleanOnceBeforeBuildPatterns: ['../css/*.map', '../js/*.map'],
			dangerouslyAllowCleanPatternsOutsideProject: true,
		}),
		new FileManagerPlugin({
			events: {
				onEnd: [
					{
						copy: [
							// Only copy minified JS files
							{
								source: './assets/js/*.min.js',
								destination:
									'./__build/directorist/directorist/assets/js',
							},
							// Only copy minified CSS files
							{
								source: './assets/css/*.min.css',
								destination:
									'./__build/directorist/directorist/assets/css',
							},
							// Copy icon font files (woff2 + css only, skip SVGs)
							{
								source: './assets/icons/font-awesome/css',
								destination:
									'./__build/directorist/directorist/assets/icons/font-awesome/css',
							},
							{
								source: './assets/icons/font-awesome/fonts',
								destination:
									'./__build/directorist/directorist/assets/icons/font-awesome/fonts',
							},
							{
								source: './assets/icons/line-awesome/css',
								destination:
									'./__build/directorist/directorist/assets/icons/line-awesome/css',
							},
							{
								source: './assets/icons/line-awesome/fonts',
								destination:
									'./__build/directorist/directorist/assets/icons/line-awesome/fonts',
							},
							{
								source: './assets/icons/unicons/css',
								destination:
									'./__build/directorist/directorist/assets/icons/unicons/css',
							},
							{
								source: './assets/icons/unicons/fonts',
								destination:
									'./__build/directorist/directorist/assets/icons/unicons/fonts',
							},
							// Copy remaining asset directories
							{
								source: './assets/images',
								destination:
									'./__build/directorist/directorist/assets/images',
							},
							{
								source: './assets/vendor-js',
								destination:
									'./__build/directorist/directorist/assets/vendor-js',
							},
							{
								source: './assets/vendor-css',
								destination:
									'./__build/directorist/directorist/assets/vendor-css',
							},
							{
								source: './assets/sample-data',
								destination:
									'./__build/directorist/directorist/assets/sample-data',
							},
							{
								source: './assets/other',
								destination:
									'./__build/directorist/directorist/assets/other',
							},
							// Copy blocks (build output only)
							{
								source: './blocks/build',
								destination:
									'./__build/directorist/directorist/blocks/build',
							},
							{
								source: './blocks/assets',
								destination:
									'./__build/directorist/directorist/blocks/assets',
							},
							{
								source: './blocks/includes',
								destination:
									'./__build/directorist/directorist/blocks/includes',
							},
							{
								source: './blocks/templates',
								destination:
									'./__build/directorist/directorist/blocks/templates',
							},
							{
								source: './blocks/preview',
								destination:
									'./__build/directorist/directorist/blocks/preview',
							},
							{
								source: './blocks/init.php',
								destination:
									'./__build/directorist/directorist/blocks/init.php',
							},
							// Copy other top-level directories
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

	output: {
		filename: '../js/[name].min.js',
	},
};

let configs = [];
common.forEach((element) => {
	const _prodConfig = merge(element, prodConfig);
	configs.push(_prodConfig);
});

module.exports = configs;
