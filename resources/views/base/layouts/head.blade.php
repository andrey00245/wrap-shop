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
