<!DOCTYPE html>
<html dir="ltr" lang="{{ app()->getLocale() }}">

@include('base.layouts.head')

<body class="theme-{{ $theme ?? 'light' }} page-checkout-layout">

@include('base.layouts.checkout.header')

@yield('content')

@include('base.layouts.footer')

@stack('scripts')

</body>
</html>
