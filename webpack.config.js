const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CopyPlugin = require('copy-webpack-plugin');

const config = {
  entry: ["./index.js"],
  plugins: [
    new MiniCssExtractPlugin({
      filename: '[name].css'
    })
  ],
  module: {
    rules: [
      {
        test: /\.(scss|css)$/,
        use: [MiniCssExtractPlugin.loader, 'css-loader', 'sass-loader'],
      }
    ]
  },
  devServer: {
    static: path.join(__dirname, "/")
  }
}

module.exports = (env, argv) => {
  if (argv.mode === 'production') {
    config.output = {
      path: path.resolve(__dirname, "./dist/build/"),
      filename: '[name].js'
    };

    config.plugins.push(
      new CopyPlugin({
        patterns: [
          { from: "*.php", to: path.resolve(__dirname, "./dist") },
          { from: "assets", to: path.resolve(__dirname, "./dist/assets") },
          { from: "includes", to: path.resolve(__dirname, "./dist/includes") },
          { from: "classes", to: path.resolve(__dirname, "./dist/classes") },
          { from: "updater", to: path.resolve(__dirname, "./dist/updater") }
        ],
      })
    );
  }

  if (argv.mode === 'development') {
    config.output = {
      path: path.resolve(__dirname, "./build/"),
      filename: '[name].js'
    };
  }

  return config;
}