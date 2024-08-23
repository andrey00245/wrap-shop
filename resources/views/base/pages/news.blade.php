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
        <span class="button">{{__('news.title')}}</span>
      </li>
    </ul>
  </nav>

  <div id="page-video" class="page-default wrap">
    <h1 class="default-title">{{__('news.title')}}</h1>
    <nav class="page-default-nav flex-center">
      <a href="{{route('news')}}" class="button ocdw_blog-list-group-item active" title="{{__('news.title')}}">{{__('news.all')}}</a>
      <a href="https://wrap.shop/novini-ta-akcii/plivki/" class="button ocdw_blog-list-group-item" title="Плівки">Плівки</a>
      <a href="https://wrap.shop/novini-ta-akcii/instrumenti-rozhidniki/" class="button ocdw_blog-list-group-item" title="Інструменти/Розхідники">Інструменти/Розхідники</a>
      <a href="https://wrap.shop/novini-ta-akcii/materiali-dlja-sumoizoljacii/" class="button ocdw_blog-list-group-item" title="Матеріали для шумоізоляції">Матеріали для шумоізоляції</a>
      <a href="https://wrap.shop/novini-ta-akcii/materiali-dlja-detejlingu/" class="button ocdw_blog-list-group-item" title="Матеріали для ДеТейлінгу">Матеріали для ДеТейлінгу</a>
      <a href="https://wrap.shop/novini-ta-akcii/akcii/" class="button ocdw_blog-list-group-item sale" title="Акції %">Акції %</a>
    </nav>


    <div class="page-video row">
      <div class="page-video-list flex-wrap">
        <a class="item" href="https://wrap.shop/novini-ta-akcii/instrumenti-rozhidniki/naviso-potribni-spojler-ta-antikrilo-jaka-miz-nimi-riznicja-1" title="Навіщо потрібні спойлер та антикрило? Яка між ними різниця?">
          <img src="https://wrap.shop/image/cachewebp/catalog/sale/2-402x402.webp" data-src="https://wrap.shop/image/cachewebp/catalog/sale/2-402x402.webp" title="Навіщо потрібні спойлер та антикрило? Яка між ними різниця?" alt="Навіщо потрібні спойлер та антикрило? Яка між ними різниця?" class="img-responsive">
          <div class="caption">
            <div class="description"><i class="fal fa-book-open button"></i>10 хв</div>
            <div class="name">Навіщо потрібні спойлер та антикрило? Яка між ними різниця?</div>
          </div>
        </a>
      </div>
      <div class="category-bottom flex-center">
        <div class="category-pagination"></div>
      </div>
    </div>
  </div>
@endsection











