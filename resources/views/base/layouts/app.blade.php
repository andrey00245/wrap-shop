<html dir="ltr" lang="{{ app()->getLocale() }}">

@include('base.layouts.head')

<body class="theme-{{$theme ?? 'light'}}">

<div class="ellipse-body">
  <div class="ellipse red"></div>
</div>

@include('base.layouts.header')

@yield('content')

@include('base.layouts.footer')

@stack('drawers')

@stack('scripts')

</body>
</html>
