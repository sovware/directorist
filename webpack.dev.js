const common = require('./webpack.common');
const { merge } = require('webpack-merge');

const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const WebpackRTLPlugin = require('webpack-rtl-plugin');
//const { CleanWebpackPlugin } = require('clean-webpack-plugin');

const devConfig = {
	mode: 'development', // production | development
	watch: true,
	plugins: [
		new MiniCssExtractPlugin({
			filename: '../css/[name].css',
		}),
		new WebpackRTLPlugin({
			minify: false,
		}) /* ,
      new CleanWebpackPlugin({
        dry: false,
        cleanOnceBeforeBuildPatterns: [ '../css', '../js', '!./assets/js/main.js' ],
        dangerouslyAllowCleanPatternsOutsideProject: true,
      }) */,
	],

	output: {
		filename: '../js/[name].js',
	},

	devtool: 'source-map',
};

let configs = [];

common.forEach((element) => {
	const _config = merge(element, devConfig);
	configs.push(_config);
});

module.exports = configs;
