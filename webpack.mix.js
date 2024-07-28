let mix = require('laravel-mix');

mix.js('resources/assets/js/app.js', 'public/build/js').version();
mix.js('resources/assets/js/sliders.js', 'public/build/js').version();
mix.js('resources/assets/js/mask/mask.js', 'public/build/js').version();

mix.css('resources/assets/css/reset.css', 'public/build/css').version();
mix.sass('resources/assets/css/style-dark.scss', 'public/build/css').version();
mix.sass('resources/assets/css/social-login.scss', 'public/build/css').version();
