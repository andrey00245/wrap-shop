let mix = require('laravel-mix');

mix.js('resources/assets/js/app.js', 'public/build/js').version();
mix.js('resources/assets/js/sliders.js', 'public/build/js').version();
mix.js('resources/assets/js/mask/mask.js', 'public/build/js').version();
mix.js('resources/assets/js/delivery_tabs.js', 'public/build/js').version();
mix.js('resources/assets/js/auth.js', 'public/build/js').version();
mix.js('resources/assets/js/productShow.js', 'public/build/js').version();

mix.css('resources/assets/scss/reset.css', 'public/build/css').version();
mix.sass('resources/assets/scss/form.scss', 'public/build/css').version();
mix.sass('resources/assets/scss/style-dark.scss', 'public/build/css').version();
mix.sass('resources/assets/scss/social-login.scss', 'public/build/css').version();
mix.sass('resources/assets/scss/style-information-dark.scss', 'public/build/css').version();
mix.sass('resources/assets/scss/style-blog-dark.scss', 'public/build/css').version();
mix.sass('resources/assets/scss/account-dark.scss', 'public/build/css').version();
mix.sass('resources/assets/scss/style-category-dark.scss', 'public/build/css').version();
mix.sass('resources/assets/scss/all-dark.scss', 'public/build/css').version();
mix.sass('resources/assets/scss/style-product-dark.scss', 'public/build/css').version();
