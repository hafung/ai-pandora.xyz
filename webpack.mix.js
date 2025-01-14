const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');
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

mix.webpackConfig({
    stats: {
        children: true
    }
});
mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        // require('postcss-import'),
        tailwindcss('./tailwind.config.js'),
        require('autoprefixer'),
    ])
    .css('resources/css/watermark-styles.css', 'public/css')
    .js('resources/js/i18n.js', 'public/js')
    .js('resources/js/watermark.js', 'public/js')
    .version();

if (mix.inProduction()) {
    mix.options({
        terser: {
            extractComments: false,
        },
        cssNano: {
            discardComments: {
                removeAll: true,
            },
        },
    });
}
