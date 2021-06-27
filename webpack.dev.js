const commonConfig = require("./webpack.common");
const { merge } = require('webpack-merge');

const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const WebpackRTLPlugin     = require("webpack-rtl-plugin");

const devConfig = {
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

module.exports = merge( commonConfig, devConfig );