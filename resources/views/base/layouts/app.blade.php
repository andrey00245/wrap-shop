<!DOCTYPE html>
<html dir="ltr" lang="{{ app()->getLocale() }}">

@include('base.layouts.head')

<body class="theme-{{$theme ?? 'light'}}">

<div class="ellipse-body">
  <div class="ellipse red"></div>
</div>

@include('base.layouts.header')

<main id="main-content" role="main">
@yield('content')
</main>

@include('base.layouts.footer')

@stack('drawers')

</body>
</html>
