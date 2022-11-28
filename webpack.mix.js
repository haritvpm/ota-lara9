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

mix.js('resources/assets/js/app.js', 'public/js')
   .js('resources/assets/js/form.1.5.js', 'public/js')
   .js('resources/assets/js/form_sitting.1.5.js', 'public/js')
   .js('resources/assets/js/home.1.js', 'public/js')
   .js('resources/assets/js/form_other.1.js', 'public/js')
   .js('resources/assets/js/form_sitting_other.1.js', 'public/js')
   .js('resources/assets/js/form_pa2mla.js', 'public/js')
   .js('resources/assets/js/form_show.1.0.js', 'public/js/')
   .js('resources/assets/js/form_other_show.js', 'public/js')
   .js('resources/assets/js/employee_index.js', 'public/js')
   .js('resources/assets/js/attendance_index.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');
