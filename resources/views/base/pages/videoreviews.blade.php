@extends('base.layouts.app')

@php
    $locale = app()->getLocale();
    $videoTitle = __('video-reviews.title') . ' | Wrap.Shop';
    $videoDescription = 'Відеоогляди продуктів Wrap.Shop - плівки для авто, матеріали для детейлінгу, інструменти та аксесуари. Професійні огляди та інструкції.';
    
    if ($locale === 'ru') {
        $videoDescription = 'Видеообзоры продуктов Wrap.Shop - пленки для авто, материалы для детейлинга, инструменты и аксессуары. Профессиональные обзоры и инструкции.';
    } elseif ($locale === 'en') {
        $videoDescription = 'Video reviews of Wrap.Shop products - car wraps, detailing materials, tools and accessories. Professional reviews and tutorials.';
    }
@endphp

@section('title', $videoTitle)
@section('description', $videoDescription)
@section('keywords', 'відеоогляди, плівки для авто, детейлінг, інструменти, wrap shop, огляди продуктів')
@section('og_type', 'website')
@section('og_title', $videoTitle)
@section('og_description', $videoDescription)
@section('og_image', url('assets/img/og-default.jpg'))
@section('og_url', url()->current())
@section('twitter_card', 'summary_large_image')
@section('twitter_title', $videoTitle)
@section('twitter_description', $videoDescription)
@section('twitter_image', url('assets/img/og-default.jpg'))
@section('canonical', url()->current())

@push('styles')
    @if($theme ==='dark')
        <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-blog-dark.css')}}">
    @else
        <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-blog-light.css')}}">
    @endif
@endpush

@section('content')

  <nav class="breadcrumbs wrap row">
    <ul class="flex-center">
      <li>
        <a href="{{route('index')}}" title="{{__('header_footer.home')}}" class="button">{{__('header_footer.home')}}</a></li>
      <li>
        <span class="button">{{__('video-reviews.title')}}</span>
      </li>
    </ul>
  </nav>

  <div id="page-video" class="page-default wrap">
    <h1 class="default-title">{{__('video-reviews.title')}}</h1>
    <nav class="page-default-nav flex-center">
      <a href="{{route('videoreviews')}}" class="button ocdw_blog-list-group-item {{request()->route('category') ? '' : 'active'}}" title="{{__('video-reviews.title')}}">{{__('video-reviews.all')}}</a>
        @foreach($categories as $category)
      <a href="{{route('videos.show', ['category' => $category->id])}}" class="button ocdw_blog-list-group-item {{request()->route('category') && request()->route('category')->id === $category->id ? 'active' : ''}}" title="{{$category->name}}">{{$category->name}}</a>
        @endforeach
    </nav>
    <div class="page-video row">
      <div class="page-video-list flex-wrap">
          @foreach($videos as $video)
              <div class="item" data-src="{{$video->getUrl()}}" data-fancybox="videoreview">
          <img loading="lazy" src="{{$video->getImage()}}" title="{{$video->title}}" alt="{{$video->title}}" class="img-responsive">
          <div class="caption">
            <i class="fal fa-play button"></i>
            <div class="name">{{$video->title}}</div>
          </div>
        </div>
          @endforeach
      </div>
      <div class="category-bottom flex-center">
{{--          //TODO paginate--}}
      </div>
    </div>
  </div>
@endsection

@push('fixed-catalog')
  @include('base.components.categories-catalog')
@endpush









