const path = require('path');
const { VueLoaderPlugin } = require('vue-loader');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const WebpackRTLPlugin = require('@automattic/webpack-rtl-plugin');

module.exports = {
	mode: 'development',
	entry: {
		// Public JS
		'js/public/checkout': './assets/src/js/public/checkout.js',
		'js/public/search-form': './assets/src/js/public/search-form.js',
		'js/public/range-slider': './assets/src/js/public/range-slider.js',
		'js/public/listing-slider': './assets/src/js/public/listing-slider.js',

		// Public Modules
		'js/public/all-listings': './assets/src/js/public/modules/all-listings.js',
		'js/public/all-authors': './assets/src/js/public/modules/all-authors.js',
		'js/public/all-location-category': './assets/src/js/public/modules/all-location-category.js',
		'js/public/directorist-dashboard': './assets/src/js/public/modules/dashboard.js',
		'js/public/author-profile': './assets/src/js/public/modules/author-profile.js',
		'js/public/account': './assets/src/js/public/modules/account.js',
		'js/public/single-listing': './assets/src/js/public/modules/single-listing.js',
		'js/public/widgets': './assets/src/js/public/modules/widgets.js',

		// Admin JS
		'js/admin/main': './assets/src/js/admin/admin.js',
		'js/admin/setup-wizard': './assets/src/js/admin/setup-wizard.js',
		'js/admin/import-export': './assets/src/js/admin/import-export.js',
		'js/admin/plugins': './assets/src/js/admin/plugins.js',
		'js/admin/builder-archive': './assets/src/js/admin/multi-directory-archive.js',
		'js/admin/multi-directory-builder': './assets/src/js/admin/multi-directory-builder.js',
		'js/admin/settings-manager': './assets/src/js/admin/settings-manager.js',

		// Global JS
		'js/global/main': './assets/src/js/global/global.js',
		'js/global/add-listing': './assets/src/js/global/add-listing.js',
		'js/global/geolocation': './assets/src/js/global/map-scripts/geolocation.js',
		'js/global/openstreet-map': './assets/src/js/global/map-scripts/openstreet-map.js',
		'js/global/google-map': './assets/src/js/global/map-scripts/map-view.js',
		'js/global/directorist-plupload': './assets/src/js/global/directorist-plupload.js',

		// CSS
		'css/admin/main': './assets/src/scss/layout/admin/admin-style.scss',
		'css/public/main': './assets/src/scss/layout/public/main-style.scss',
	},
	output: {
		path: path.resolve(__dirname, './assets/build/'),
	},
	resolve: {
		extensions: ['.js', '.vue', '.ts'],
		alias: {
			vue$: 'vue/dist/vue.esm.js',
		},
	},
	plugins: [
		new VueLoaderPlugin(),
		new MiniCssExtractPlugin(),
		new WebpackRTLPlugin(),
	],
	module: {
		rules: [
			// Images
			{
				test: /\.(png|jpe?g|gif|svg)$/i,
				use: [
					{
						loader: 'file-loader',
						options: {
							outputPath: 'images',
						},
					},
				],
			},
			// Fonts
			{
				test: /\.(woff|woff2|eot|ttf|otf)$/,
				use: {
					loader: 'file-loader',
					options: {
						outputPath: 'fonts',
					},
				},
			},
			// Vue
			{
				test: /\.vue$/,
				loader: 'vue-loader',
			},
			// JavaScript
			{
				test: /\.m?js$/,
				exclude: /(node_modules|bower_components)/,
				use: [
					{
						loader: 'babel-loader',
						options: {
							presets: ['@wordpress/default'],
						},
					},
				],
			},
			// SCSS/SASS
			{
				test: /\.s[ac]ss$/i,
				use: [
					{ loader: MiniCssExtractPlugin.loader },
					{
						loader: 'css-loader',
						options: {
							sourceMap: true,
						},
					},
					'resolve-url-loader',
					{
						loader: 'postcss-loader',
						options: {
							sourceMap: true,
							config: {
								path: __dirname,
							},
						},
					},
					{
						loader: 'sass-loader',
						options: {
							sourceMap: true,
							implementation: require('sass'),
							sassOptions: {
								silenceDeprecations: [
									'import',
									'color-functions',
									'global-builtin',
									'legacy-js-api',
									'if-function',
								],
							},
						},
					},
				],
			},
		],
	},
	devtool: 'source-map',
};
