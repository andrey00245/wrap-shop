<html dir="ltr" lang="{{ app()->getLocale() }}">

@include('base.layouts.head')

<body>

<div class="ellipse-body">
  <div class="ellipse red"></div>
</div>

@include('base.layouts.header')

@yield('content')

@include('base.layouts.footer')

</body>
</html>
