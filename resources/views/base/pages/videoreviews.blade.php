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











