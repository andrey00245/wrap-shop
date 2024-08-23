@extends('base.layouts.app')

@push('styles')
  <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-blog-dark.css')}}">
@endpush


@section('content')

  <nav class="breadcrumbs wrap row">
    <ul class="flex-center">
      <li>
        <a href="{{route('index')}}" title="{{__('header_footer.home')}}" class="button">{{__('header_footer.home')}}</a></li>
      <li>
        <span class="button">{{__('about-us.title')}}</span>
      </li>
    </ul>
  </nav>

  <div id="page-pronas" class="page-pronas page-default wrap">

    <h1 class="default-title">{{__('about-us.title')}}</h1>

    <div class="page-pronas-description">{!! __('about-us.description') !!}</div>

    <div class="page-pronas-list" style="height: 1393px;">
      <a class="item image-item" href="{{asset('assets/img/about-us/1.jpg')}}" data-fancybox="pronas-gallery" title="{{__('about-us.title')}}">
        <img src="{{asset('assets/img/about-us/1.jpg')}}" data-src="{{asset('assets/img/about-us/1.jpg')}}" title="{{__('about-us.title')}}" alt="{{__('about-us.title')}}">
      </a>
      <a class="item image-item" href="{{asset('assets/img/about-us/2.jpg')}}" data-fancybox="pronas-gallery" title="{{__('about-us.title')}}">
        <img src="{{asset('assets/img/about-us/2.jpg')}}" data-src="{{asset('assets/img/about-us/2.jpg')}}" title="{{__('about-us.title')}}" alt="{{__('about-us.title')}}">
      </a>
      <a class="item image-item" href="{{asset('assets/img/about-us/3.jpg')}}" data-fancybox="pronas-gallery" title="{{__('about-us.title')}}">
        <img src="{{asset('assets/img/about-us/3.jpg')}}" data-src="{{asset('assets/img/about-us/3.jpg')}}" title="{{__('about-us.title')}}" alt="{{__('about-us.title')}}">
      </a>
      <a class="item video-item" href="image/catalog/video.mp4" data-fancybox="pronas-gallery" title="{{__('about-us.title')}}" >
        <img src="{{asset('assets/img/about-us/5.jpg')}}" data-src="{{asset('assets/img/about-us/5.jpg')}}" title="{{__('about-us.title')}}" alt="{{__('about-us.title')}}">
        <i class="fal fa-play button"></i>
      </a>
      <a class="item video-item" href="image/catalog/video.mp4" data-fancybox="pronas-gallery" title="{{__('about-us.title')}}">
        <img src="{{asset('assets/img/about-us/6.jpg')}}" data-src="{{asset('assets/img/about-us/6.jpg')}}" title="{{__('about-us.title')}}" alt="{{__('about-us.title')}}">
        <i class="fal fa-play button"></i>
      </a>
      <a class="item image-item" href="{{asset('assets/img/about-us/4.jpg')}}" data-fancybox="pronas-gallery" title="{{__('about-us.title')}}">
        <img src="{{asset('assets/img/about-us/4.jpg')}}" data-src="{{asset('assets/img/about-us/4.jpg')}}" title="{{__('about-us.title')}}" alt="{{__('about-us.title')}}">
      </a>
      <a class="item image-item" href="{{asset('assets/img/about-us/9.jpg')}}" data-fancybox="pronas-gallery" title="{{__('about-us.title')}}">
        <img src="{{asset('assets/img/about-us/9.jpg')}}" data-src="{{asset('assets/img/about-us/9.jpg')}}" title="{{__('about-us.title')}}" alt="{{__('about-us.title')}}">
      </a>
      <a class="item image-item" href="{{asset('assets/img/about-us/7.jpg')}}" data-fancybox="pronas-gallery" title="{{__('about-us.title')}}">
        <img src="{{asset('assets/img/about-us/7.jpg')}}" data-src="{{asset('assets/img/about-us/7.jpg')}}" title="{{__('about-us.title')}}" alt="{{__('about-us.title')}}">
      </a>
      <a class="item video-item" href="image/catalog/video.mp4" data-fancybox="pronas-gallery" title="{{__('about-us.title')}}">
        <img src="{{asset('assets/img/about-us/8.jpg')}}" data-src="{{asset('assets/img/about-us/8.jpg')}}" title="{{__('about-us.title')}}" alt="{{__('about-us.title')}}">
        <i class="fal fa-play button"></i>
      </a>
      <a class="item image-item" href="{{asset('assets/img/about-us/11.jpg')}}" data-fancybox="pronas-gallery" title="{{__('about-us.title')}}">
        <img src="{{asset('assets/img/about-us/11.jpg')}}" data-src="{{asset('assets/img/about-us/11.jpg')}}" title="{{__('about-us.title')}}" alt="{{__('about-us.title')}}">
      </a>
      <a class="item image-item" href="{{asset('assets/img/about-us/10.jpg')}}" data-fancybox="pronas-gallery" title="{{__('about-us.title')}}">
        <img src="{{asset('assets/img/about-us/10.jpg')}}" data-src="{{asset('assets/img/about-us/10.jpg')}}" title="{{__('about-us.title')}}" alt="{{__('about-us.title')}}">
      </a>
    </div>

    <div class="page-pronas-child">
      <h2 class="default-title">{{__('about-us.title_h2')}}</h2>
      <div class="page-pronas-description">{!! __('about-us.description_after_h2') !!}</div>
    </div>

    <div class="page-pronas-team row">
    </div>

  </div>

@push('scripts')
  <script src="https://wrap.shop/catalog/view/theme/wrapshop/js/blocksit.min.js"></script>

  <script>
    $(document).ready(function() {
      // if ($(window).width() > 320) {
      // 	$('.page-pronas-list').BlocksIt({
      // 		numOfCol: 3,
      // 		offsetX: 5,
      // 		offsetY: 5,
      // 		blockElement: '.page-pronas-list .item'
      // 	});
      // }
      // if ($(window).width() > 1020) {
      // 	$('.page-pronas-list').BlocksIt({
      // 		numOfCol: 3,
      // 		offsetX: 10,
      // 		offsetY: 10,
      // 		blockElement: '.page-pronas-list .item'
      // 	});
      // }
    });
    function numRowChange() {
      if ($(window).width() > 320) {
        $('.page-pronas-list').BlocksIt({
          numOfCol: 3,
          offsetX: 5,
          offsetY: 5,
          blockElement: '.page-pronas-list .item'
        });
      }
      if ($(window).width() > 1020) {
        $('.page-pronas-list').BlocksIt({
          numOfCol: 3,
          offsetX: 10,
          offsetY: 10,
          blockElement: '.page-pronas-list .item'
        });
      }
    }

    $(window).on('load resize', numRowChange);
  </script>
@endpush

@endsection











