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
      <a href="{{route('videoreviews')}}" class="button ocdw_blog-list-group-item active" title="{{__('video-reviews.title')}}">{{__('video-reviews.all')}}</a>
      <a href="https://wrap.shop/ru/videoobzorj/zasitnje-plenki/" class="button ocdw_blog-list-group-item" title="Защитные пленки">Защитные пленки</a>
      <a href="https://wrap.shop/ru/videoobzorj/cvetnje-plenki/" class="button ocdw_blog-list-group-item" title="Цветные пленки">Цветные пленки</a>
      <a href="https://wrap.shop/ru/videoobzorj/dizajnerskaja-plenka/" class="button ocdw_blog-list-group-item" title="Дизайнерская пленка">Дизайнерская пленка</a>
      <a href="https://wrap.shop/ru/videoobzorj/plenka-dlja-okon/" class="button ocdw_blog-list-group-item" title="Пленка для окон">Пленка для окон</a>
    </nav>


    <div class="page-video row">
      <div class="page-video-list flex-wrap">
        <div class="item" data-src="https://wrap.shop/image/catalog/video2/1.mp4" data-fancybox="videoreview">
          <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/video2/ferr-402x402.webp" title="Антигравийная пленка на Ferrari" alt="Антигравийная пленка на Ferrari" class="img-responsive">
          <div class="caption">
            <i class="fal fa-play button"></i>
            <div class="name">Антигравийная пленка на Ferrari</div>
          </div>
        </div>
        <div class="item" data-src="https://wrap.shop/image/catalog/video2/1.mp4" data-fancybox="videoreview">
          <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/video2/ferr-402x402.webp" title="Антигравийная пленка на Ferrari" alt="Антигравийная пленка на Ferrari" class="img-responsive">
          <div class="caption">
            <i class="fal fa-play button"></i>
            <div class="name">Антигравийная пленка на Ferrari</div>
          </div>
        </div>
        <div class="item" data-src="https://wrap.shop/image/catalog/video2/1.mp4" data-fancybox="videoreview">
          <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/video2/ferr-402x402.webp" title="Антигравийная пленка на Ferrari" alt="Антигравийная пленка на Ferrari" class="img-responsive">
          <div class="caption">
            <i class="fal fa-play button"></i>
            <div class="name">Антигравийная пленка на Ferrari</div>
          </div>
        </div>
        <div class="item" data-src="https://wrap.shop/image/catalog/video2/1.mp4" data-fancybox="videoreview">
          <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/video2/ferr-402x402.webp" title="Антигравийная пленка на Ferrari" alt="Антигравийная пленка на Ferrari" class="img-responsive">
          <div class="caption">
            <i class="fal fa-play button"></i>
            <div class="name">Антигравийная пленка на Ferrari</div>
          </div>
        </div>
        <div class="item" data-src="https://wrap.shop/image/catalog/video2/1.mp4" data-fancybox="videoreview">
          <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/video2/ferr-402x402.webp" title="Антигравийная пленка на Ferrari" alt="Антигравийная пленка на Ferrari" class="img-responsive">
          <div class="caption">
            <i class="fal fa-play button"></i>
            <div class="name">Антигравийная пленка на Ferrari</div>
          </div>
        </div>
        <div class="item" data-src="https://wrap.shop/image/catalog/video2/1.mp4" data-fancybox="videoreview">
          <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/video2/ferr-402x402.webp" title="Антигравийная пленка на Ferrari" alt="Антигравийная пленка на Ferrari" class="img-responsive">
          <div class="caption">
            <i class="fal fa-play button"></i>
            <div class="name">Антигравийная пленка на Ferrari</div>
          </div>
        </div>
        <div class="item" data-src="https://wrap.shop/image/catalog/video2/1.mp4" data-fancybox="videoreview">
          <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/video2/ferr-402x402.webp" title="Антигравийная пленка на Ferrari" alt="Антигравийная пленка на Ferrari" class="img-responsive">
          <div class="caption">
            <i class="fal fa-play button"></i>
            <div class="name">Антигравийная пленка на Ferrari</div>
          </div>
        </div>
        <div class="item" data-src="https://wrap.shop/image/catalog/video2/1.mp4" data-fancybox="videoreview">
          <img loading="lazy" src="https://wrap.shop/image/cachewebp/catalog/video2/ferr-402x402.webp" title="Антигравийная пленка на Ferrari" alt="Антигравийная пленка на Ferrari" class="img-responsive">
          <div class="caption">
            <i class="fal fa-play button"></i>
            <div class="name">Антигравийная пленка на Ferrari</div>
          </div>
        </div>
        <div class="item" onclick="ocdw_blog_open_video({a:this,b:'3'});">
          <img src="https://wrap.shop/image/cachewebp/catalog/video2/10-270x270.webp" data-src="https://wrap.shop/image/cachewebp/catalog/video2/10-402x402.webp" title="Дизайн для М8" alt="Дизайн для М8" class="img-responsive">
          <div class="caption">
            <i class="fal fa-play button"></i>
            <div class="name">Дизайн для М8</div>
          </div>
        </div>
        <div class="item" onclick="ocdw_blog_open_video({a:this,b:'7'});">
          <img src="https://wrap.shop/image/cachewebp/catalog/video2/8-270x270.webp" data-src="https://wrap.shop/image/cachewebp/catalog/video2/8-402x402.webp" title="Дизайнерская пленка для Audi" alt="Дизайнерская пленка для Audi" class="img-responsive">
          <div class="caption">
            <i class="fal fa-play button"></i>
            <div class="name">Дизайнерская пленка для Audi</div>
          </div>
        </div>
        <div class="item" onclick="ocdw_blog_open_video({a:this,b:'8'});">
          <img src="https://wrap.shop/image/cachewebp/catalog/video2/7-270x270.webp" data-src="https://wrap.shop/image/cachewebp/catalog/video2/7-402x402.webp" title="Защитная пленка Aston Martin DB11" alt="Защитная пленка Aston Martin DB11" class="img-responsive">
          <div class="caption">
            <i class="fal fa-play button"></i>
            <div class="name">Защитная пленка Aston Martin DB11</div>
          </div>
        </div>
        <div class="item" onclick="ocdw_blog_open_video({a:this,b:'1'});">
          <img src="https://wrap.shop/image/cachewebp/catalog/video2/11-270x270.webp" data-src="https://wrap.shop/image/cachewebp/catalog/video2/11-402x402.webp" title="Защитная пленка и тонировка для BMW XM" alt="Защитная пленка и тонировка для BMW XM" class="img-responsive">
          <div class="caption">
            <i class="fal fa-play button"></i>
            <div class="name">Защитная пленка и тонировка для BMW XM</div>
          </div>
        </div>
        <div class="item" onclick="ocdw_blog_open_video({a:this,b:'4'});">
          <img src="https://wrap.shop/image/cachewebp/catalog/video2/9-270x270.webp" data-src="https://wrap.shop/image/cachewebp/catalog/video2/9-402x402.webp" title="Золотая BMW M8 competition" alt="Золотая BMW M8 competition" class="img-responsive">
          <div class="caption">
            <i class="fal fa-play button"></i>
            <div class="name">Золотая BMW M8 competition</div>
          </div>
        </div>
        <div class="item" onclick="ocdw_blog_open_video({a:this,b:'5'});">
          <img src="https://wrap.shop/image/cachewebp/catalog/video2/6-270x270.webp" data-src="https://wrap.shop/image/cachewebp/catalog/video2/6-402x402.webp" title="Изменение цвета для BMW X7" alt="Изменение цвета для BMW X7" class="img-responsive">
          <div class="caption">
            <i class="fal fa-play button"></i>
            <div class="name">Изменение цвета для BMW X7</div>
          </div>
        </div>
        <div class="item" onclick="ocdw_blog_open_video({a:this,b:'2'});">
          <img src="https://wrap.shop/image/cachewebp/catalog/video2/12-270x270.webp" data-src="https://wrap.shop/image/cachewebp/catalog/video2/12-402x402.webp" title="Изменение цвета для Porsche Macan" alt="Изменение цвета для Porsche Macan" class="img-responsive">
          <div class="caption">
            <i class="fal fa-play button"></i>
            <div class="name">Изменение цвета для Porsche Macan</div>
          </div>
        </div>
      </div>
      <div class="category-bottom flex-center">
        <div class="category-pagination"><ul class="flex-center pagination"><li class="active"><span>1</span></li><li><a href="https://wrap.shop/ru/videoobzorj/?page=2">2</a></li><li class="item-next hide"><a href="https://wrap.shop/ru/videoobzorj/?page=2">&gt;</a></li><li class="item-last hide"><a href="https://wrap.shop/ru/videoobzorj/?page=2">&gt;|</a></li></ul></div>
      </div>
    </div>
  </div>

@endsection











