let mix = require('laravel-mix');

// Объединяем все JS файлы в один
mix.js([
    'resources/assets/js/app.js',
    'resources/assets/js/sliders.js',
    'resources/assets/js/delivery_tabs.js',
    'resources/assets/js/auth.js',
    'resources/assets/js/productShow.js',
    'resources/assets/js/productIndex.js',
    'resources/assets/js/baseSearch.js',
    'resources/assets/js/checkoutPage.js'
], 'public/build/js/app.js').version();

// Объединяем CSS файлы по темам
mix.sass('resources/assets/scss/style-light.scss', 'public/build/css/light.css')
   .sass('resources/assets/scss/style-dark.scss', 'public/build/css/dark.css')
   .sass('resources/assets/scss/form.scss', 'public/build/css/form.css')
   .css('resources/assets/scss/reset.css', 'public/build/css/reset.css')
   .version();

// Оптимизация для production
if (mix.inProduction()) {
    mix.options({
        terser: {
            terserOptions: {
                compress: {
                    drop_console: true,
                },
            },
        },
        cssMinifier: 'cssnano',
    });
}
