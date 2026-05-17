@php
    /** Глобальні змінні з View::share інколи недоступні у вкладених view (напр. Nova / нестандартний рендер) */
    $productCategories = $productCategories ?? collect();
@endphp
<header class="header ">
    <div class="general-popup head-top row" id="menu-popup">
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

            <form class="head-theme flex-center" action="{{route('change-theme')}}" method="post"
                  enctype="multipart/form-data" id="form-theme" aria-label="{{ __('general-translate.theme_switcher') }}">
                @csrf
                <button class="button theme-select {{$theme === 'dark' ?: 'active'}} fas fa-sun"
                        type="submit"
                        aria-label="{{ __('general-translate.switch_to_light_theme') }}"></button>
                <button class="button theme-select {{$theme === 'light' ?: 'active'}} far fa-moon"
                        type="submit"
                        aria-label="{{ __('general-translate.switch_to_dark_theme') }}"></button>
            </form>

            <nav class="head-menu">
                @include('base.layouts.menu')
            </nav>
            <div class="head-social flex-center">
                <a href="{{$settings->telegram ?? '#'}}" title="Telegram" target="_blank"
                   class="fab fa-telegram-plane"></a>
                <a href="{{$settings->instagram  ?? '#'}}" title="Instagram" target="_blank"
                   class="fab fa-instagram"></a>
            </div>
            <div class="head-phone flex-center">
                <div class="title">{{__('header_footer.phone')}}</div>
                <a href="{{ \App\Support\SitePhone::telHref($settings ?? null) }}">{{ \App\Support\SitePhone::display($settings ?? null) }}</a>
            </div>
            <div class="head-top-close button popup-close"><i class="fal fa-times"></i></div>
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
                <x-category-sub-menu-mobile :productCategories="$productCategories"/>
            </nav>

            <div class="head-logo">
                <a href="{{route('index')}}" title="Wrap.Shop">
                    <img loading="lazy" src="{{asset('assets/img/logo.png')}}"
                         title="Wrap.Shop" alt="Wrap.Shop" width="93"
                         height="98"></a>
            </div>
            <div class="head-phone-mobil">
                <div class="title">{{__('header_footer.phone')}}</div>
                <a href="{{ \App\Support\SitePhone::telHref($settings ?? null) }}">{{ \App\Support\SitePhone::display($settings ?? null) }}</a>
            </div>
            <div class="head-buttons">
                <div class="general-popup-btn button search far fa-search"
                data-popup="search-popup"></div>
                <button class="button general-popup-btn far fa-shopping-cart dropdown-toggle"
                        data-popup="cart-popup"><span
                        id="cart-total" class="cart-total flex-center">{{$cartItemsCount}}</span></button>
                @guest
                    <div id="wishlist-total" class="button heart general-popup-btn far fa-heart" data-popup="login-popup">
                        <span class="wishlist-total flex-center">{{count(session()->get('wishlist', []))}}</span>
                    </div>
                    <div class="button general-popup-btn far fa-user" data-popup="login-popup"></div>
                @endguest

                @auth
                    <a href="{{route('wishlist')}}" id="wishlist-total" title="{{__('checkout.wishlist')}}"
                       class="button heart far fa-heart"><span
                            class="wishlist-total flex-center">{{auth()->user()->favoriteCount()}}</span></a>
                    <a href="{{route('account')}}" title="{{__('checkout.personal_account')}}" class="button user far fa-user-check"></a>
                @endauth
                <div class="head-top-open button general-popup-btn" data-popup="menu-popup">
                    <i class="far fa-bars"></i>
                </div>

            </div>
        </div>
    </div>
</header>
