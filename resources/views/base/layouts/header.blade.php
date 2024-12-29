<header class="header ">
  <div class="head-top row">
    <div class="head-top-wrap flex-justify wrap">
      <div class="head-lang flex-center" id="form-language">
        <a href="{{ LaravelLocalization::getLocalizedURL('uk') }}"
           class="button language-select {{LaravelLocalization::getCurrentLocale() === 'uk' ? 'active' : ''}}">Ua</a>
        <a href="{{ LaravelLocalization::getLocalizedURL('ru') }}"
           class="button language-select {{LaravelLocalization::getCurrentLocale() === 'ru' ? 'active' : ''}}">Ru</a>
        <a href="{{ LaravelLocalization::getLocalizedURL('en') }}"
           class="button language-select {{LaravelLocalization::getCurrentLocale() === 'en' ? 'active' : ''}}">En</a>
        <input type="hidden" name="code" value="">
        <meta name="csrf-token" content="{{ csrf_token() }}">

      </div>

      <div class="head-theme" style="padding: 0; background-color: transparent"></div>

      <nav class="head-menu">
        @include('base.layouts.menu')
      </nav>
      <div class="head-social flex-center">
        <a href="{{$settings->getTelegramUrl()}}" title="Telegram" target="_blank" class="fab fa-telegram-plane"></a>
        <a href="{{$settings->getInstagramUrl()}}" title="Instagram" target="_blank" class="fab fa-instagram"></a>
      </div>
      <div class="head-phone flex-center">
        <div class="title">{{__('header_footer.phone')}}</div>
        <a href="tel:{{$settings->getPhone()}}">{!! $settings->getPhoneView() !!}</a>
      </div>
      <div class="head-top-close button"><i class="fal fa-times"></i></div>
    </div>
  </div>

  <div class="fixed-header">
    <div class="head-boot flex-justify wrap">
      <div class="head-catalog-open button">
        <div class="butt-menu"><span></span></div>
        {{__('header_footer.catalog_products')}}
      </div>
      <div class="head-catalog-bg"></div>
      <nav class="head-catalog">
        <div class="head-catalog-container">
          <div class="head-catalog-close button"><i class="far fa-times"></i></div>
          <ul class="menu">
            @foreach($productCategories as $category)
              <x-category-menu :category="$category"/>
            @endforeach
          </ul>
        </div>
        <div class="head-catalog-container for-mob mainMenu show">
          <div class="head-catalog-close button"><i class="far fa-times"></i></div>
          <ul class="menu">
            @foreach($productCategories as $category)
              <x-category-menu-mobile :category="$category"/>
            @endforeach
          </ul>
        </div>

        <x-category-sub-menu-mobile :category="$category"/>



      </nav>

      <div class="head-logo">
        <a href="{{route('index')}}" title="Wrap.Shop">
          <img loading="lazy" src="{{asset('assets/img/logo.png')}}"
               title="Wrap.Shop" alt="Wrap.Shop" width="93"
               height="98"></a>
      </div>
      <div class="head-phone-mobil">
        <div class="title">{{__('header_footer.phone')}}</div>
        <a href="tel:+380660003202"><span>+38</span> 066 000 32 02</a>
      </div>
      <div class="head-buttons">
        <div class="search-popup-open button search far fa-search"></div>
        <button class="button cart-open far fa-shopping-cart dropdown-toggle"><span
            id="cart-total" class="cart-total flex-center">0</span></button>
        @guest
          <div id="wishlist-total" class="button heart login-show far fa-heart login-popup-open"><span
              class="wishlist-total flex-center">{{count(session()->get('wishlist', []))}}</span></div>
          <div class="button userform-open login-show far fa-user login-popup-open"></div>
        @endguest

        @auth
          <a href="{{route('wishlist')}}" id="wishlist-total" title="Закладки (0)"
             class="button heart far fa-heart"><span
              class="wishlist-total flex-center">{{auth()->user()->favoriteCount()}}</span></a>
          <a href="{{route('account')}}" title="Особистий кабінет" class="button user far fa-user-check"></a>
        @endauth
        <div class="head-top-open button"><i class="far fa-bars"></i></div>

      </div>
    </div>
  </div>
</header>
