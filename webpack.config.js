var path = require('path');
var webpack = require('webpack');

module.exports = {
  entry: './public/anfildro.js',

  output: {
    path: path.resolve(__dirname, 'public'),
    filename: 'bundle.js'
  },

  plugins: [
    new webpack.optimize.UglifyJsPlugin()
  ],

  resolve: {
    alias: {
      'vue$': 'vue/dist/vue.js'
    }
  }
};
