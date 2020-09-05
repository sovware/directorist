const common    = require("./webpack.common");
const { merge } = require('webpack-merge');

const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const WebpackRTLPlugin     = require("webpack-rtl-plugin");

module.exports = merge(common, {
  mode: "production", // production | development
  plugins: [
    new MiniCssExtractPlugin({
      filename: "../css/[name].css",
      minify: true,
    }),
    new WebpackRTLPlugin({
      minify: true,
    }),
  ],
});
