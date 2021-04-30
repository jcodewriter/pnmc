const mix = require('laravel-mix');

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

mix
    .js('resources/js/app.js', 'public/js')
    .js('resources/js/chartjs.js', 'public/js')
    .js('resources/js/chartjs.headerGraph.js', 'public/js')
    .js('resources/js/chartjs.smallGraph.js', 'public/js')
    .js('resources/js/chartjs.lineGraph.js', 'public/js')
    .js('resources/js/home.js', 'public/js')
    .js('resources/js/dataTables.js', 'public/js')
    .js('resources/js/translationManager.js', 'public/js')
    .sass('resources/scss/app.scss', 'public/css')
;

if (mix.inProduction())
{
    mix.version();
}
