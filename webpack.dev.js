const common    = require("./webpack.common");
const { merge } = require('webpack-merge');

const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const WebpackRTLPlugin     = require("webpack-rtl-plugin");

module.exports = merge(common, {
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
});
