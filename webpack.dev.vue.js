const devConfig      = require("./webpack.dev");
const { vueEntries } = require('./webpack-entry-list.js');

delete devConfig.entry;
devConfig.entry = vueEntries;

module.exports = devConfig;