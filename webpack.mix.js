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
mix.js('resources/assets/js/users/app.js', 'public/js')
  .js('resources/assets/js/admin/app.js', 'public/js/admin-js/')
  .sass('resources/assets/sass/app.scss', 'public/css')
  .extract([
    'vue',
    'vuex',
    'vue-carousel',
    'vue-color',
    'vue-coverflow',
    'vue-echo',
    'vue-i18n',
    'vue-progressbar',
    'vue-router',
    'axios',
    'bootstrap',
    'plyr',
    'vee-validate',
    'jquery',
  ]).version();

mix.autoload({
  jquery: ['$', 'window.jQuery', 'jQuery', 'window.$', 'jquery', 'window.jquery']
});

mix.browserSync({
  proxy: 'http://localhost:8000'
});
mix.webpackConfig({
  node: {
    fs: 'empty'
  }
});

