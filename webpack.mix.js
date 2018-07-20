let mix = require('laravel-mix');

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

let options = {
  processCssUrls: false,
}

let config = {
  output: {
    chunkFilename: 'assets/js/chunks/[name].js',
    publicPath: '/'
  }
}

if (mix.inProduction()) {
  config.output.chunkFilename = 'assets/js/chunks/[name].[chunkhash].js'
}

mix
  .sass('resources/assets/sass/app.scss',                    'public/assets/css')
  .sass('resources/assets/sass/bulma.scss',                   'public/assets/css')

  .js('resources/assets/js/app.js',                           'public/assets/js')
  
  .copy('resources/assets/img',                               'public/assets/img')
  .copy('resources/assets/icon',                              'public/assets/icon')

  .extract([
    'vue', 'vue-router', 'vuex', 'vue-progressbar',
    'axios', 'at-ui', 'buefy'
  ])

  .disableNotifications()
  .webpackConfig(config)
  .options(options)

if (mix.inProduction()) {
  mix.version()
}
