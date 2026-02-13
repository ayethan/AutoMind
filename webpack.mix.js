const mix = require('laravel-mix');
const webpack = require('webpack');

mix.js('resources/js/app.js', 'public/js')
   .vue() // Critical for Vue 3 support in Mix v6
   .sass('resources/sass/app.scss', 'public/css')
   .webpackConfig({
       resolve: {
           fallback: {
               "crypto": require.resolve("crypto-browserify"),
               "stream": require.resolve("stream-browserify"),
               "buffer": require.resolve("buffer"),
           }
       },
       plugins: [
           new webpack.ProvidePlugin({
               Buffer: ['buffer', 'Buffer'],
               process: 'process/browser',
           }),
       ],
   });