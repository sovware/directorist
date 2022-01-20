const MiniCssExtractPlugin   = require('mini-css-extract-plugin');
const WebpackRTLPlugin       = require('webpack-rtl-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const FileManagerPlugin      = require('filemanager-webpack-plugin');
const { merge }              = require('webpack-merge');

const commonConfig = require('./webpack.common');
const devConfig    = require('./webpack.dev');
const entriesList  = require('./webpack-entry-list.js');

// Get All Entries
let allEntries = {};
for ( const entryGroupKey of Object.keys( entriesList ) ) {
  allEntries = { ...allEntries, ...entriesList[ entryGroupKey ] };
}

// Prod Config
// ------------------------------
const prodConfig = {
  mode: 'production', // production | development
  watch: false,
  entry: allEntries,
  plugins: [
    new MiniCssExtractPlugin({
      filename: '../css/[name].min.css',
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
              { source: './admin', destination: './__build/directorist/directorist/admin' },
              { source: './assets', destination: './__build/directorist/directorist/assets' },
              { source: './includes', destination: './__build/directorist/directorist/includes' },
              { source: './languages', destination: './__build/directorist/directorist/languages' },
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
    filename: '../js/[name].min.js',
  },
};

// Dev Config
// ------------------------------
delete devConfig.entry;
devConfig.entry = allEntries;
devConfig.watch = false;

// Final Config
// ------------------------------
let configs = [];

// Add Development Config
configs.push( devConfig );

// Add Production Config
delete commonConfig.entry;
const _prodConfig = merge( commonConfig, prodConfig );
configs.push( _prodConfig );

module.exports = configs;