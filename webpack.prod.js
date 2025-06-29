const common    = require("./webpack.common");
const { merge } = require('webpack-merge');

const MiniCssExtractPlugin   = require("mini-css-extract-plugin");
const WebpackRTLPlugin       = require("webpack-rtl-plugin");
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const FileManagerPlugin      = require('filemanager-webpack-plugin');
const { vueEntries }         = require('./webpack-entry-list.js');

const prodConfig = {
  mode: "production", // production | development
  watch: false,
  entry: {
    ['admin-multi-directory-builder']: "./assets/src/js/admin/multi-directory-builder.js",
    ['admin-settings-manager']: "./assets/src/js/admin/settings-manager.js",
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: "../css/[name].min.css"
    }),
    new WebpackRTLPlugin({
      filename: "../css/[name].rtl.min.css"
    }),
    new CleanWebpackPlugin({
      dry: false,
      cleanOnceBeforeBuildPatterns: [ '../css/*.map', '../js/*.map' ],
      dangerouslyAllowCleanPatternsOutsideProject: true,
    }),
    new FileManagerPlugin({
      events: {
        onEnd: [
          {
            copy: [
              { source: './assets', destination: './__build/directorist/directorist/assets' },
              { source: './blocks', destination: './__build/directorist/directorist/blocks' },
              { source: './languages', destination: './__build/directorist/directorist/languages' },
              { source: './includes', destination: './__build/directorist/directorist/includes' },
              { source: './templates', destination: './__build/directorist/directorist/templates' },
              { source: './views', destination: './__build/directorist/directorist/views' },
              { source: './*.php', destination: './__build/directorist/directorist' },
              { source: './*.txt', destination: './__build/directorist/directorist' },
            ],
          },
          {
            delete: ['./__build/directorist/directorist/assets/src'],
          },
          {
            archive: [
              { source: './__build/directorist', destination: './__build/directorist.zip' },
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
    filename: "../js/[name].min.js",
  },
};

const devConfig = {
  mode: "development", // production | development
  watch: true,
  entry: vueEntries,
  plugins: [
    new MiniCssExtractPlugin({
      filename: "../css/[name].css"
    }),
    new WebpackRTLPlugin({
      filename: "../css/[name].rtl.css"
    }),
  ],
  output: {
    filename: "../js/[name].js",
  },

  devtool: 'source-map'
};

let configs = [];
common.forEach(element => {
  const _devConfig = merge( element, devConfig );
  _devConfig.watch = false;
  configs.push( _devConfig );

  const _prodConfig = merge( element, prodConfig );
  configs.push( _prodConfig );
});

module.exports = configs;