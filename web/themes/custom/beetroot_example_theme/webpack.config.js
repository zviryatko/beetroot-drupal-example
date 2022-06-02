const CopyWebpackPlugin = require("copy-webpack-plugin")
const { CleanWebpackPlugin } = require("clean-webpack-plugin")

module.exports = {
  entry: {
    global: [
      './js/main'
    ]
  },
  output: {
    filename: 'assets/[name].js'
  },
  module: {
    rules: [
      {
        test: /.js$/,
        exclude: /node_modules/,
        use: [{
          loader: 'babel-loader',
          options: {
            presets: [
              ['@babel/preset-env', {
                "targets": "defaults"
              }],
              '@babel/preset-react'
            ]
          }
        }]
      },
      {
        test: /\.css$/,
        use: "css-loader"
      }
    ]
  },
  externals: {
    "react": "React",
    "react-dom": "ReactDOM"
  },
  plugins: [
    new CopyWebpackPlugin({
      patterns: [
        {
          from: "node_modules/react/umd/react.production.min.js",
          to: "assets/react.js"
        },
        {
          from: "node_modules/react-dom/umd/react-dom.production.min.js",
          to: "assets/react-dom.js"
        }
      ]
    }),
    new CleanWebpackPlugin()
  ]
}
