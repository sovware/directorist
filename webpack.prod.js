const common    = require("./webpack.common");
const { merge } = require('webpack-merge');

const MiniCssExtractPlugin   = require("mini-css-extract-plugin");
const WebpackRTLPlugin       = require("webpack-rtl-plugin");
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const FileManagerPlugin      = require('filemanager-webpack-plugin');

const prodConfig = {
  mode: "production", // production | development
  watch: false,
  
  plugins: [
    new MiniCssExtractPlugin({
      filename: "../css/[name].min.css",
      minify: true,
    }),
    new WebpackRTLPlugin({
      minify: true,
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
              { source: './admin', destination: './directorist/admin' },
              { source: './assets', destination: './directorist/assets' },
              { source: './languages', destination: './directorist/languages' },
              { source: './includes', destination: './directorist/includes' },
              { source: './templates', destination: './directorist/templates' },
              { source: './views', destination: './directorist/views' },
              { source: './*.php', destination: './directorist' },
              { source: './readme.md', destination: './directorist/readme.md' },
              { source: './readme.txt', destination: './directorist/readme.txt' },
            ],
          },
          {
            archive: [
              { source: './directorist', destination: './directorist.zip' },
            ],
          },
          {
            delete: ['./directorist'],
          },
        ],
      },
    }),
    
  ],

  output: {
    filename: "../js/[name].min.js",
  },
};

let configs = [];
common.forEach(element => {
  const _config = merge( element, prodConfig );
  configs.push( _config );
});

module.exports = configs;