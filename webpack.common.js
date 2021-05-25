const path                 = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const VueLoaderPlugin      = require('vue-loader/lib/plugin');

const commonConfig = {
  resolve: {
    extensions: [ '.js', '.vue' ],
    alias: {
      'vue$': 'vue/dist/vue.esm.js'
    }
  },
  plugins: [
    new VueLoaderPlugin(),
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
                outputStyle: 'compressed',
              },
            },
          },
        ],
      },
    ],
  },
};

// Main Config
const MainConfig = {
  entry: {
    // Public
    // -------------------------------------------
    ['public-main']: ["./assets/src/js/public/main.js"],
    ['public-releated-listings-slider']: ["./assets/src/js/public/releated-listings-slider.js"],
    ['public-atmodal']: ["./assets/src/js/public/atmodal.js"],
    ['public-search-listing']: ["./assets/src/js/public/search-listing.js"],
    ['public-search-form-listing']: ["./assets/src/js/public/search-form-listing.js"],
    ['public-checkout']: ["./assets/src/js/public/checkout.js"],
    ['public-geolocation-widget']: ["./assets/src/js/public/geolocation-widget.js"],

    // Admin
    // -------------------------------------------
    ['admin-main']: "./assets/src/js/admin/admin.js",
    ['admin-multi-directory-archive']: "./assets/src/js/admin/multi-directory-archive.js",
    ['admin-multi-directory-builder']: "./assets/src/js/admin/multi-directory-builder.js",
    ['admin-settings-manager']: "./assets/src/js/admin/settings-manager.js",
    ['admin-plugins']: "./assets/src/js/admin/plugins.js",
    ['admin-custom-field']: "./assets/src/js/admin/custom-field.js",
    ['admin-extension-update']: "./assets/src/js/admin/extension-update.js",
    ['admin-import-export']: "./assets/src/js/admin/import-export.js",
    ['admin-setup-wizard']: "./assets/src/js/admin/setup-wizard.js",

    // Global
    // -------------------------------------------
    ['global-main']: ["./assets/src/js/global/global.js"],
    ['global-range-slider']: ["./assets/src/js/global/range-slider.js"],
    ['global-add-listing']: ["./assets/src/js/global/add-listing.js"],

    ['global-geolocation']: ["./assets/src/js/global/map-scripts/geolocation.js"],
    ['global-add-listing-openstreet-map-custom-script']: ["./assets/src/js/global/map-scripts/add-listing/openstreet-map.js"],
    ['global-add-listing-gmap-custom-script']: ["./assets/src/js/global/map-scripts/add-listing/google-map.js"],

    ['global-single-listing-openstreet-map-custom-script']: ["./assets/src/js/global/map-scripts/single-listing/openstreet-map.js"],
    ['global-single-listing-openstreet-map-widget-custom-script']: ["./assets/src/js/global/map-scripts/single-listing/openstreet-map-widget.js"],
    ['global-single-listing-gmap-custom-script']: ["./assets/src/js/global/map-scripts/single-listing/google-map.js"],
    ['global-single-listing-gmap-widget-custom-script']: ["./assets/src/js/global/map-scripts/single-listing/google-map-widget.js"],
    
    ['global-load-osm-map']: ["./assets/src/js/global/map-scripts/load-osm-map.js"],
    ['global-map-view']: ["./assets/src/js/global/map-scripts/map-view.js"],
    ['global-markerclusterer']: ["./assets/src/js/global/map-scripts/markerclusterer.js"],

    ['global-pure-select']: ["./assets/src/js/global/pureScriptSearchSelect.js"],
    ['global-directorist-plupload']: "./assets/src/js/global/directorist-plupload.js",
  },

  output: {
    path: path.resolve( process.cwd(), 'assets/js'),
  },

  ...commonConfig
};

module.exports = [ MainConfig ];
