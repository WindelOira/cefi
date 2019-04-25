const path = require('path')
const mix = require('laravel-mix')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
   resolve  : {
      extensions  : ['.js', '.json', '.vue'],
      alias       : {
         '@css'      : path.resolve(__dirname, './resources/css'),
         '@js'       : path.resolve(__dirname, './resources/js'),
      }
   },
   module   : {
      rules       : [
         {
            test     : /\.js$/,
            loader   : 'babel-loader',
            exclude  : file => {
               return /node_modules/.test(file) &&
                  (!/\.vue\.js/.test(file) &&
                     !/vue-data-tables\/src\/mixins\/ShareMixin\.js/.test(file))
            }
         }
      ]
   }
})

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .styles([
      'resources/css/shards-dashboards.1.2.0.css',
      'resources/css/app.css'
   ], 'public/css/app.css')
