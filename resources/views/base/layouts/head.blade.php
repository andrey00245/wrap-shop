<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>@yield('title', 'Default')</title>
  <meta name="description" content="@yield('description', 'Default')">

  <link href="{{mix('build/css/style-dark.css')}}" rel="stylesheet" type="text/css">
  <link rel="stylesheet" type="text/css" href="{{mix('build/css/social-login.css')}}">
  <link rel="stylesheet" href="{{asset('assets/css/fonts.css')}}" type="text/css" media="screen">
  <link rel="stylesheet" href="{{asset('assets/css/font-awesome.css')}}" type="text/css" media="screen">
  <link rel="stylesheet" href="{{asset('assets/css/swiper-bundle.min.css')}}" media="screen">

  <link rel="icon" href="{{asset('assets/favicon/favicon.ico')}}" type="image/x-icon">
  <link rel="icon" sizes="16x16" href="{{asset('assets/favicon/favicon-16x16.png')}}">
  <link rel="icon" sizes="32x32" href="{{asset('assets/favicon/favicon-32x32.png')}}">
  <meta name="theme-color" content="#ffffff">

  <link rel="apple-touch-icon" href="{{asset('assets/favicon/apple-touch-icon.png')}}">
  <link rel="icon" type="image/png" sizes="192x192" href="{{asset('assets/favicon/android-chrome-96x96.png')}}">


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
