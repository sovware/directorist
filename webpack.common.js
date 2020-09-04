const path                 = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const WebpackRTLPlugin     = require("webpack-rtl-plugin");
const VueLoaderPlugin      = require('vue-loader/lib/plugin')

module.exports = {
  entry: {
    app: "./public/assets/src/js/index.js",
  },
  output: {
    filename: "[name].js",
    path: path.resolve(__dirname, "public/assets/js"),
  },
  resolve: {
    extensions: [ '.js', '.vue' ],
    alias: {
      'vue$': 'vue/dist/vue.esm.js'
    }
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: "../css/app.css",
    }),
    new WebpackRTLPlugin({
      minify: true,
    }),
    new VueLoaderPlugin()
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
        options: {
          esModule: true,
        }
      },
      {
        test: /\.m?js$/,
        exclude: /(node_modules|bower_components)/,
        use: [
          {
            loader: "babel-loader",
            options: {
              presets: ["@babel/preset-env"],
            }
          },
        ]
      },
      // Loading TS
      // {
      //   test: /\.ts$/,
      //   include: [ path.resolve(__dirname, "assets/src/js") ],
      //   exclude: /(node_modules|bower_components)/,
      //   options: {
      //     appendTsSuffixTo: [/\.vue$/]
      //   },
      //   loader: 'ts-loader',
      // },
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

  devtool: 'eval-source-map'
};
