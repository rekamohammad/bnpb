const { mix } = require('laravel-mix');

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

mix.disableNotifications();

mix.setPublicPath('public/vendor/core/');

mix
    .sass('./resources/assets/core/sass/core.scss', 'css')
    .sass('./resources/assets/core/sass/elements/admin-bar.scss', 'css')
    .sass('./resources/assets/core/sass/layouts/themes/blue.scss', 'css/themes')
    .sass('./resources/assets/core/sass/layouts/themes/darkblue.scss', 'css/themes')
    .sass('./resources/assets/core/sass/layouts/themes/default.scss', 'css/themes')
    .sass('./resources/assets/core/sass/layouts/themes/grey.scss', 'css/themes')
    .sass('./resources/assets/core/sass/layouts/themes/light.scss', 'css/themes')
    .sass('./plugins/ecommerce/resources/assets/source/sass/ecommerce.scss', 'css');

mix.combine(
    [
        './resources/assets/core/js/app.js',
        './resources/assets/core/js/csrf.js',
        './resources/assets/core/js/demo.js',
        './resources/assets/core/js/layout.js',
        './resources/assets/core/js/routes.js',
        './resources/assets/core/js/script.js',
        './resources/assets/core/js/utility.js'
    ],
    './public/vendor/core/js/core.js'
);

mix
    .js('./resources/assets/core/js/app_modules/admin-menu-left-hand.js', 'js/app_modules')
    .js('./resources/assets/core/js/app_modules/ckeditor.js', 'js/app_modules')
    .js('./resources/assets/core/js/app_modules/dashboard.js', 'js/app_modules')
    .js('./resources/assets/core/js/app_modules/datatables.js', 'js/app_modules')
    .js('./resources/assets/core/js/app_modules/profile.js', 'js/app_modules')
    .js('./resources/assets/core/js/app_modules/tags.js', 'js/app_modules')
    .js('./resources/assets/core/js/app_modules/media.js', 'js/app_modules')
    .js('./resources/assets/core/js/app_modules/slug.js', 'js/app_modules')
    .js('./resources/assets/core/js/app_modules/feature.js', 'js/app_modules')
    .js('./resources/assets/core/js/app_modules/role.js', 'js/app_modules')
    .js('./resources/assets/core/js/app_modules/custom-fields.js', 'js/app_modules')
    .js('./resources/assets/core/js/app_modules/menu.js', 'js/app_modules')
    .js('./resources/assets/core/js/app_modules/widget.js', 'js/app_modules')
    .js('./resources/assets/core/js/app_modules/translation.js', 'js/app_modules')
    .js('./resources/assets/core/js/app_modules/backup.js', 'js/app_modules')
    .js('./resources/assets/core/js/app_modules/language.js', 'js/app_modules')
    .js('./resources/assets/core/js/app_modules/plugin.js', 'js/app_modules')
    .js('./resources/assets/core/js/app_modules/login.js', 'js/app_modules')
    .js('./plugins/ecommerce/resources/assets/source/js/edit-product.js', 'js/app_modules');
