const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const WebpackRTLPlugin     = require("webpack-rtl-plugin");

const { merge } = require('webpack-merge');
const commonConfig = require("./webpack.common");
const { commonEntries } = require('./webpack-entry-list.js');

const devConfig = {
  entry: commonEntries,
  mode: "development", // production | development
  watch: true,
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

const config = merge( commonConfig, devConfig );

module.exports = config;
