const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const VueLoaderPlugin = require('vue-loader/lib/plugin');
const { commonEntries } = require('./webpack-entry-list.js');

// Plugin to suppress specific warnings
class SuppressWarningsPlugin {
	constructor(warningsToSuppress) {
		this.warningsToSuppress = warningsToSuppress;
	}

	apply(compiler) {
		compiler.hooks.afterCompile.tap('SuppressWarningsPlugin', (compilation) => {
			compilation.warnings = compilation.warnings.filter(warning => {
				return !this.warningsToSuppress.some(suppressPattern => {
					return warning.message && warning.message.includes(suppressPattern);
				});
			});
		});
	}
}

const commonConfig = {
	resolve: {
		extensions: ['.js', '.vue'],
		alias: {
			vue$: 'vue/dist/vue.esm.js',
		},
	},
	plugins: [
		new VueLoaderPlugin(),
		new SuppressWarningsPlugin(['mixed-decls deprecation is obsolete']),
	],
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
								quietDeps: true,
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
