@extends('base.layouts.app')

@push('styles')
  <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-blog-dark.css')}}">
@endpush


@section('content')

  <nav class="breadcrumbs post wrap row">
    <ul class="flex-center">
      <li>
        <a href="{{route('index')}}" title="{{__('header_footer.home')}}" class="button">{{__('header_footer.home')}}</a></li>
      <li>
        <a href="{{route('news.index')}}" title="{{__('news.title')}}" class="button">{{__('news.title')}}</a></li>

      <li>
        <a href="{{route('news.category', ['news_category'=>$news_category->slugEn])}}" title="{{$news_category->name}}"
           class="button">{{$news_category->name}}</a></li>
      <li>
        <span class="button">{{$news->title}}</span>
      </li>
    </ul>
  </nav>

  <section id="page-post">
    <div class="page-post-top">
      <img src="{{$news->getMedia('main')[0]->getUrl()}}" title="{{$news->title}}" alt="{{$news->title}}">
      <div class="info">
        <h1>{{$news->title}}</h1>
        <div class="items flex-center">
          <div class="item">
            <div class="icon flex-center">
              <i class="fal fa-book-open"></i>
            </div>
            <div class="text">{{$news->read_time}}</div>
          </div>
          <div class="item">
            <div class="icon flex-center">
              <i class="fal fa-calendar-alt"></i>
            </div>
            <div class="text">{{\Carbon\Carbon::createFromDate($news->created_at)->isoFormat('D MMMM')}}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="page-post-content">
      {!! $news->description !!}
    </div>
    <div class="page-post-bottom flex-justify">
      <div class="post-top button"><i class="fal fa-chevron-up"></i>{{__('news.to_the_top')}}</div>
      <div class="post-social flex-center">
        <span>{{__('news.share')}}</span>
        <a href="#" title="Telegram" target="_blank" class="fab fa-telegram-plane"></a>
        <a href="#" title="Instagram" target="_blank" class="fab fa-instagram"></a>
      </div>
    </div>
  </section>

@endsection

@push('scripts')
  <script>
    $(document).ready(function(){
      $(".page-post-bottom .post-top").click(function () {
        $('html, body').animate({scrollTop:0}, 1500);
      });
    });
  </script>
@endpush

@push('fixed-catalog')
  @include('base.components.categories-catalog')
@endpush









