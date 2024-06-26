const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const path = require('path');

module.exports = {
  entry: {
    back: './_dev/back',
    front: './_dev/front',
  },
  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, './views/dist'),
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: ['babel-loader'],
      },
      {
        test: /\.s?css$/,
        use: [
          'style-loader',
          {
            loader: MiniCssExtractPlugin.loader,
            options: {
              esModule: false,
            },
          },
          'css-loader',
          'sass-loader',
        ],
      },
      {
        test: /\.(eot|png|ttf|woff|woff2)(\?[a-z0-9=.]+)?$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: '../../views/dist/[hash].[ext]',
            },
          },
        ],
      },
    ],
  },
  plugins: [
    new CleanWebpackPlugin(),
    new MiniCssExtractPlugin({
      filename: '[name].css',
    }),
  ],
  devtool: 'source-map',
  resolve: {
    extensions: ['.js', '.vue'], // enable webpack to resolve .js and .vue extensions
    alias: {
      'vue$': 'vue/dist/vue.esm-bundler.js', // alias for importing Vue in its full build
    },
  },
};
