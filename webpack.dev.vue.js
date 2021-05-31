const common = require("./webpack.common");
const { merge } = require('webpack-merge');

const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const WebpackRTLPlugin     = require("webpack-rtl-plugin");
const { vueEntries }       = require('./webpack-entry-list.js');

const devConfig = {
    mode: "development", // production | development
    watch: true,
    entry: vueEntries,
    plugins: [
      new MiniCssExtractPlugin({
        filename: "../css/[name].css",
        minify: false,
      }),
      new WebpackRTLPlugin({
        minify: false,
      }),
    ],

    output: {
      filename: "../js/[name].js",
    },

    devtool: 'source-map'
};

let configs = [];

common.forEach(element => {
  const _config = merge( element, devConfig );
  configs.push( _config );
});

module.exports = configs;
