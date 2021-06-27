const commonConfig      = require("./webpack.common");
let   devConfig         = require("./webpack.dev");
const { merge }         = require('webpack-merge');
const { vueEntries }    = require('./webpack-entry-list.js');
const { commonEntries } = require('./webpack-entry-list.js');

devConfig.entry = { commonEntries, vueEntries };

module.exports = merge( commonConfig, devConfig );