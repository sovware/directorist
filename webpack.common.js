const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const VueLoaderPlugin = require('vue-loader/lib/plugin');
const { commonEntries } = require('./webpack-entry-list.js');

const commonConfig = {
	resolve: {
		extensions: ['.js', '.vue'],
		alias: {
			vue$: 'vue/dist/vue.esm.js',
		},
	},
	plugins: [new VueLoaderPlugin()],
	module: {
		rules: [
			// Loading Images
			{
				test: /\.(png|jpe?g|gif|svg)$/i,
				use: [
					{
						loader: 'file-loader',
						options: {
							outputPath: '../images',
						},
					},
				],
			},
			// Loading Fonts
			{
				test: /\.(woff|woff2|eot|ttf|otf)$/,
				use: {
					loader: 'file-loader',
					options: {
						outputPath: '../fonts',
					},
				},
			},
			// Loading JS
			{
				test: /\.vue$/,
				loader: 'vue-loader',
			},
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
			// Loading SASS
			{
				test: /\.s[ac]ss$/i,
				use: [
					{
						loader: MiniCssExtractPlugin.loader,
						options: {},
					},
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
								path: 'postcss.config.js',
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
									'mixed-decls',
									'import',
									'color-functions',
									'global-builtin',
									'legacy-js-api',
								],
							},
						},
					},
				],
			},
		],
	},
};

// Main Config
const MainConfig = {
	entry: commonEntries,

	output: {
		path: path.resolve(process.cwd(), 'assets/js'),
	},

	...commonConfig,
};

module.exports = [MainConfig];
