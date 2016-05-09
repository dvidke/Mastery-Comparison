var elixir = require('laravel-elixir');
elixir.extend('sourcemaps', false);

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

 elixir(function(mix) {
    mix.less([
        'default.less'
        ], 'resources/assets/css/less.css');

    mix.styles([
        'bootstrap.min.css',
        'tether.min.css',
        'less.css'
        ],'public/assets/css/all.css');

    mix.scripts([
        'jquery.min.js',
        'tether.min.js',
        'jquery.ui.js',
        'bootstrap.min.js',
        'chart.min.js',
        'default.js'
        ], 'public/assets/js/all.js');
});
