const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js([
    'resources/js/leaflet.browser.print.js',
    'resources/js/leaflet.browser.print.utils.js',
    'resources/js/leaflet.browser.print.sizes.js',
    'resources/js/leaflet-sidebar.js',
    'resources/js/leaflet.markercluster.js',
    'resources/js/leaflet.groupedlayercontrol.js',
    'resources/js/ui-bootstrap-tpls-2.5.0.min.js',
    'resources/js/angular-moment-picker.min.js',

], 'public/js/scripts.js')

.styles([
    'resources/css/leaflet-sidebar.css',
    'resources/css/nouislider.css',
    'resources/css/angular-moment-picker.min.css',
],'public/css/top.css');