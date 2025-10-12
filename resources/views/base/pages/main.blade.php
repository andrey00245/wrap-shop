@extends('base.layouts.app')

@php
    $seoService = app(\App\Services\SeoService::class);
    $homeTitle = $seoService->generateHomeTitle();
    $homeDescription = $seoService->generateHomeDescription();
@endphp

@section('title', $homeTitle)
@section('description', $homeDescription)
@section('keywords', 'плівки для авто, детейлінг, тюнінг, шумоізоляція, інструменти, аксесуари, wrap shop, 3m, kybertane, yellotools')
@section('og_type', 'website')
@section('og_title', $homeTitle)
@section('og_description', $homeDescription)
@section('og_image', url('assets/img/og-default.jpg'))
@section('og_url', url('/'))
@section('twitter_card', 'summary_large_image')
@section('twitter_title', $homeTitle)
@section('twitter_description', $homeDescription)
@section('twitter_image', url('assets/img/og-default.jpg'))
@section('canonical', url('/'))

@section('content')

  <div class="home-top">
    <div class="ellipse orange"></div>
    @include('base.components.big-banners')
    <div class="mobil-catalog wrap">
      <div class="mobil-catalog-open button"><i class="far fa-th-large"></i>{{__('header_footer.catalog_products')}}</div>
    </div>
    @include('base.components.categories-slider')
    @include('base.components.bestseller')
{{--    @include('base.components.3m-color-wrap')--}}
{{--    @include('base.components.yellotools')--}}
    @include('base.components.custom-blocks')
    @include('base.components.latest')
    {{-- @include('base.components.examples-of-work') --}}

    @push('scripts')
      <script src="{{mix('build/js/sliders.js')}}"></script>
    @endpush
  </div>
@endsection
