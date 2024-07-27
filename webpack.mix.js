let mix = require('laravel-mix');

mix.js('resources/assets/js/app.js', 'public/build/js').version();
mix.css('resources/assets/css/reset.css', 'public/build/css').version();

mix.css('resources/assets/css/style-dark.css', 'public/build/css').version();
mix.css('resources/assets/css/style-light.css', 'public/build/css').version();
