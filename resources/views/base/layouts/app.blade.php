<html dir="ltr" lang="{{ app()->getLocale() }}">

@include('base.layouts.head')

<body>
<!-- Page Loader -->
<div id="page-loader" class="page-loader">
    <div class="loader-content">
        <div class="loader-spinner"></div>
        <div class="loader-text">{{__('general-translate.loading')}}</div>
    </div>
</div>

<div class="ellipse-body">
  <div class="ellipse red"></div>
</div>

@include('base.layouts.header')

@yield('content')

@include('base.layouts.footer')

</body>
</html>
