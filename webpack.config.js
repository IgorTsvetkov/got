const path = require('path');
const VueLoaderPlugin = require('vue-loader/lib/plugin')
const MiniCssExtractPlugin=require('mini-css-extract-plugin');
module.exports = {
    entry: "./src/app.js",
    output: {
        filename: 'main.js',
        path: path.resolve(__dirname, 'web/dist')
    },
    module: {
        rules: [
          {
            test: /\.vue$/,
            loader: 'vue-loader',
          },
          // this will apply to both plain `.js` files
          // AND `<script>` blocks in `.vue` files
          {
            test: /\.js$/,
            loader: 'babel-loader'
          },
          // this will apply to both plain `.css` files
          // AND `<style>` blocks in `.vue` files
          {
            test: /\.css$/,
            use: [
              'vue-style-loader',
              MiniCssExtractPlugin.loader,
              'css-loader',
              // 'stylus-loader'
            ]
          },
          {
            test: /\.(png|svg|jpg|gif)$/,
            use: [
              'file-loader',
            ]
          },
          {
            test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
            use: [
              'file-loader',
            ]
          }
        ]
      },
      plugins: [
        new VueLoaderPlugin(),
        new MiniCssExtractPlugin({
          filename: "[name].css"
      })
      ],
      resolve: {
        alias: {
          vue: 'vue/dist/vue.js'
        }
      }
       
}