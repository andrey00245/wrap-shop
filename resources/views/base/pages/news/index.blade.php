@extends('base.layouts.app')

@push('styles')
  <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-blog-dark.css')}}">
@endpush


@section('content')

  <nav class="breadcrumbs wrap row">
    <ul class="flex-center">
      <li>
        <a href="{{route('index')}}" title="{{__('header_footer.home')}}"
           class="button">{{__('header_footer.home')}}</a></li>
      <li>
        <span class="button">{{__('news.title')}}</span>
      </li>
    </ul>
  </nav>

  <div id="page-video" class="page-default wrap">
    <h1 class="default-title">{{__('news.title')}}</h1>
    <nav class="page-default-nav flex-center">
      <a href="{{route('news.index')}}"
         class="button ocdw_blog-list-group-item {{Route::currentRouteName() === 'news.index' ? 'active' : ''}}"
         title="{{__('news.title')}}">{{__('news.all')}}</a>
      @foreach($categories as $category)
        @if($category->news()->exists())
          @if(!empty($news_category))
            <a href="{{route('news.category', ['news_category' => $category->slugEn])}}"
               class="button ocdw_blog-list-group-item {{$news_category->slugEn === $category->slugEn ? 'active' : ''}}"
               title="{{$category->name}}">{{$category->name}}</a>
          @else
            <a href="{{route('news.category', ['news_category' => $category->slugEn])}}"
               class="button ocdw_blog-list-group-item" title="{{$category->name}}">{{$category->name}}</a>
          @endif
        @endif
      @endforeach

{{--      <a href="https://wrap.shop/novini-ta-akcii/akcii/" class="button ocdw_blog-list-group-item sale" title="Акції %">Акції--}}
{{--        %</a>--}}
    </nav>


    <div class="page-video row">
      <div class="page-video-list flex-wrap">
        @if(Route::currentRouteName() === 'news.index')
          @foreach($categories as $category)
            @foreach($category->news as $new)
              <a class="item"
                 href="{{route('news.show', ['news_category' => $new->category->slugEn, 'news' => $new->slugEn])}}"
                 title="{{$new->title}}">
                <img src="{{$new->getMedia('main')[0]->getUrl('preview')}}" title="{{$new->title}}"
                     alt="{{$new->title}}"
                     class="img-responsive">
                <div class="caption">
                  <div class="description"><i class="fal fa-book-open button"></i>{{$new->read_time}}</div>
                  <div class="name">{{$new->title}}</div>
                </div>
              </a>
            @endforeach
          @endforeach
        @else
          @foreach($news_category->news as $new)
            <a class="item"
               href="{{route('news.show', ['news_category' => $new->category->slugEn, 'news' => $new->slugEn])}}"
               title="{{$new->title}}">
              <img src="{{$new->getMedia('main')[0]->getUrl('preview')}}" title="{{$new->title}}"
                   alt="{{$new->title}}"
                   class="img-responsive">
              <div class="caption">
                <div class="description"><i class="fal fa-book-open button"></i>{{$new->read_time}}</div>
                <div class="name">{{$new->title}}</div>
              </div>
            </a>
          @endforeach
        @endif
      </div>
      <div class="category-bottom flex-center">
        <div class="category-pagination"></div>
      </div>
    </div>
  </div>
@endsection

@push('fixed-catalog')
  @include('base.components.categories-catalog')
@endpush









