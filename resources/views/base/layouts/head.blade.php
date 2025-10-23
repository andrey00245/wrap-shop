<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="locale" content="{{ app()->getLocale() }}"/>

    <!-- SEO Meta Tags -->
    <title>@yield('title', __('seo.default_title'))</title>
    <meta name="description" content="@yield('description', __('seo.default_description'))">
    <meta name="keywords" content="@yield('keywords', __('seo.default_keywords'))">
    <meta name="robots" content="@yield('robots', __('seo.default_robots'))">
    <meta name="author" content="@yield('author', __('seo.default_author'))">

    <!-- Open Graph Meta Tags -->
    <meta property="og:type" content="@yield('og_type', __('seo.default_og_type'))">
    <meta property="og:title" content="@yield('og_title', __('seo.default_title'))">
    <meta property="og:description" content="@yield('og_description', __('seo.default_description'))">
    <meta property="og:image" content="@yield('og_image', url('assets/img/og-default.jpg'))">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:site_name" content="@yield('og_site_name', __('seo.site_name'))">
    <meta property="og:locale" content="{{ app()->getLocale() === 'uk' ? 'uk_UA' : (app()->getLocale() === 'ru' ? 'ru_UA' : 'en_GB') }}">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="@yield('twitter_card', __('seo.default_twitter_card'))">
    <meta name="twitter:title" content="@yield('twitter_title', __('seo.default_title'))">
    <meta name="twitter:description" content="@yield('twitter_description', __('seo.default_description'))">
    <meta name="twitter:image" content="@yield('twitter_image', url('assets/img/og-default.jpg'))">

    <!-- Canonical URL -->
    <link rel="canonical" href="@yield('canonical', url()->current())">

    <!-- Additional SEO Meta Tags -->
    <meta name="theme-color" content="@yield('theme_color', __('seo.default_theme_color'))">
    <meta name="msapplication-TileColor" content="@yield('msapplication_tile_color', __('seo.default_theme_color'))">
    <meta name="apple-mobile-web-app-title" content="@yield('apple_mobile_web_app_title', __('seo.default_apple_mobile_web_app_title'))">
    <meta name="application-name" content="@yield('application_name', __('seo.default_application_name'))">

    <!-- Itemprop Microdata -->
    <meta itemprop="name" content="@yield('itemprop_name', __('seo.site_name'))">
    <meta itemprop="email" content="@yield('itemprop_email', 'info@wrap.shop')">
    <meta itemprop="priceRange" content="@yield('itemprop_price_range', 'UAH')">
    <meta itemprop="target" content="@yield('itemprop_target', url('/search'))">

    <!-- JSON-LD Structured Data -->
    @include('components.json-ld')

    <!-- Google search console -->
    <meta name="google-site-verification" content="LmHagwQx1TXU6CUHxrW-lm7EPyzPe-0noBJuXR2m1FM" />

    @if($theme === 'dark')
        <link href="{{mix('build/css/style-dark.css')}}" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="{{mix('build/css/social-login-dark.css')}}">
    @else
        <link href="{{mix('build/css/style-light.css')}}" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="{{mix('build/css/social-login-light.css')}}">
    @endif
    <!-- Font Preloads -->
    <link rel="preload" href="{{asset('assets/fonts/DINPro-Light.woff2')}}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{asset('assets/fonts/DINPro.woff2')}}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{asset('assets/fonts/DINPro-Medium.woff2')}}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{asset('assets/fonts/DINPro-Bold.woff2')}}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{asset('assets/fonts/Gilroy-Regular.woff2')}}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{asset('assets/fonts/Gilroy-Medium.woff2')}}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{asset('assets/fonts/Gilroy-Semibold.woff2')}}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{asset('assets/fonts/Gilroy-Bold.woff2')}}" as="font" type="font/woff2" crossorigin>

    <link rel="stylesheet" href="{{asset('assets/css/fonts.css')}}" type="text/css" media="screen">
    <link rel="stylesheet" href="{{asset('assets/css/font-awesome.css')}}" type="text/css" media="screen">

    <link rel="icon" href="{{asset('assets/favicon/favicon.ico')}}" type="image/x-icon">
    <link rel="icon" sizes="16x16" href="{{asset('assets/favicon/favicon-16x16.png')}}">
    <link rel="icon" sizes="32x32" href="{{asset('assets/favicon/favicon-32x32.png')}}">
    <link rel="manifest" href="{{asset('assets/favicon/site.webmanifest')}}">
    <link rel="mask-icon" href="{{asset('assets/favicon/safari-pinned-tab.svg')}}" color="#5bbad5">
    <link rel="shortcut icon" href="{{asset('assets/favicon/favicon.ico')}}">
    <meta name="msapplication-config" content="{{asset('assets/favicon/browserconfig.xml')}}">
    <meta name="theme-color" content="#ffffff">

    <link rel="apple-touch-icon" href="{{asset('assets/favicon/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{asset('assets/favicon/android-chrome-192x192.png')}}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{asset('assets/favicon/android-chrome-512x512.png')}}">

    <link rel="stylesheet" href="{{asset('assets/css/form.css')}}" media="screen">
    <link rel="stylesheet" href="{{asset('third-party/splide/css/splide.min.css')}}" media="screen">
    <link rel="stylesheet" href="{{asset('third-party/fancybox/jquery.fancybox.min.css')}}" media="screen">
    <link rel="stylesheet" href="{{asset('third-party/intlTelInput/css/intlTelInput.css')}}" media="screen">

    <!-- Custom Head Code from Settings -->
    @if(isset($settings) && $settings->head_code)
        {!! $settings->head_code !!}
    @endif
    @stack('styles')

    <link rel="alternate" hreflang="uk-ua" href="{{LaravelLocalization::getLocalizedURL('uk')}}">
    <link rel="alternate" hreflang="x-default" href="{{LaravelLocalization::getLocalizedURL('uk')}}">
    <link rel="alternate" hreflang="ru-ua" href="{{LaravelLocalization::getLocalizedURL('ru')}}">
    <link rel="alternate" hreflang="en-gb" href="{{LaravelLocalization::getLocalizedURL('en')}}">

    <style>

      header {
        min-height: 84px;
      }

    </style>
</head>
