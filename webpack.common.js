const path                 = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const VueLoaderPlugin      = require('vue-loader/lib/plugin');
const DependencyExtractionWebpackPlugin = require( '@wordpress/dependency-extraction-webpack-plugin' );

const commonConfig = {
  resolve: {
    extensions: [ '.js', '.vue' ],
    alias: {
      'vue$': 'vue/dist/vue.esm.js'
    }
  },
  plugins: [
    new VueLoaderPlugin(),
    new DependencyExtractionWebpackPlugin({
      injectPolyfill: true,
    })
  ],
  module: {
    rules: [
      // Loading Images
      {
        test: /\.(png|jpe?g|gif|svg)$/i,
        use: [
          {
            loader: "file-loader",
            options: {
              outputPath: "../images",
            },
          },
        ],
      },
      // Loading Fonts
      {
        test: /\.(woff|woff2|eot|ttf|otf)$/,
        use: {
          loader: "file-loader",
          options: {
            outputPath: "../fonts",
          },
        },
      },
      // Loading JS
      {
        test: /\.vue$/,
        loader: 'vue-loader',
      },
      {
        test: /\.m?js$/,
        exclude: /(node_modules|bower_components)/,
        use: [
          {
            loader: "babel-loader",
            options: {
              presets: ["@wordpress/default"],
            }
          },
        ]
      },
      // Loading SASS
      {
        test: /\.s[ac]ss$/i,
        use: [
          {
            loader: MiniCssExtractPlugin.loader,
            options: {
              hmr: process.env.NODE_ENV === "development",
              reloadAll: true,
            },
          },
          {
            loader: 'css-loader',
            options: {
              sourceMap: true,
            }
          },
          'resolve-url-loader',
          {
            loader: 'postcss-loader',
            options: {
              sourceMap: true,
              config: {
                path: 'postcss.config.js'
              }
            }
          },
          {
            loader: "sass-loader",
            options: {
              sourceMap: true,
              sassOptions: {
                // outputStyle: 'compressed',
              },
            },
          },
        ],
      },
    ],
  },

  devtool: 'source-map'
};

// Public Config
const publicConfig = {
  entry: {
    // JS
    ['main']: ["./assets/src/js/main.js"],
    ['global']: ["./assets/src/js/global.js"],
    ['checkout']: ["./assets/src/js/checkout.js"],
    
    // CSS
    ['search-style']: ["./assets/src/scss/layout/public/search-style.scss"],
    ['openstreet-map']: ["./assets/src/scss/component/openstreet-map/index.scss"],
  },

  output: {
    path: path.resolve(__dirname, "assets/dest/public/js/"),
  },

  ...commonConfig
};

// Admin Config
const adminConfig  = {
  entry: {
    // JS
    ['admin']: "./assets/src/js/admin/admin.js",
    ['custom-field']: "./assets/src/js/admin/custom-field.js",
    ['directorist-plupload']: "./assets/src/js/admin/directorist-plupload.js",
    ['extension-update']: "./assets/src/js/admin/extension-update.js",
    ['import-export']: "./assets/src/js/admin/import-export.js",
    ['plugins']: "./assets/src/js/admin/plugins.js",
    ['setup-wizard']: "./assets/src/js/admin/setup-wizard.js",
    ['multi-directory-builder']: "./assets/src/js/admin/multi-directory-builder.js",
    ['multi-directory-archive']: "./assets/src/js/admin/multi-directory-archive.js",
    ['settings-manager']: "./assets/src/js/admin/settings-manager.js",

    // CSS
    ['drag-drop']: "./assets/src/scss/layout/admin/drag_drop.scss",
  },

  output: {
    path: path.resolve(__dirname, "assets/dest/admin/js/"),
  },

  ...commonConfig
};

module.exports = [ publicConfig, adminConfig ];
