@php
    /** Глобальні змінні з View::share інколи недоступні у вкладених view (напр. Nova / нестандартний рендер) */
    $productCategories = $productCategories ?? collect();
    $locale = app()->getLocale();
    $localeLabel = match ($locale) {
        'ru' => 'RU',
        'en' => 'EN',
        default => 'UA',
    };
    $popularChips = $productCategories
        ->flatMap(fn ($category) => $category->children ?? collect())
        ->take(4)
        ->values();
    if ($popularChips->isEmpty()) {
        $popularChips = $productCategories->take(4)->values();
    }
    $wishlistCount = auth()->check()
        ? auth()->user()->favoriteCount()
        : count(session()->get('wishlist', []));
@endphp
<header class="header">
    {{-- Mobile burger menu (panel) --}}
    <div class="general-popup head-top" id="menu-popup">
        <div class="head-top-mobile">
            <img
                class="mob-menu-watermark"
                src="{{ asset('assets/img/menu/mob-menu-bg.png') }}"
                alt=""
                aria-hidden="true"
            >
            <div class="mob-menu-top">
                <button type="button" class="mob-menu-close button popup-close" aria-label="Close">
                    <img
                        src="{{ asset('assets/img/menu/mob-menu-close.png') }}"
                        alt=""
                        width="13"
                        height="21"
                        aria-hidden="true"
                    >
                </button>
            </div>

            <button
                type="button"
                class="mob-menu-catalog"
                aria-expanded="false"
                aria-controls="mob-menu-catalog-panel"
            >
                @include('base.components.partials.mobil-catalog-icon')
                <span class="mob-menu-catalog__title">{{ __('header_footer.catalog_products') }}</span>
                <span class="mob-menu-catalog__chevron" aria-hidden="true"></span>
            </button>

            <nav class="mob-menu-nav" aria-label="{{ __('header_footer.catalog_products') }}">
                <ul class="mob-menu-nav__list">
                    <li><a href="{{ route('videoreviews') }}">{{ __('header_footer.video_reviews') }}</a></li>
                    <li><a href="{{ route('delivery') }}">{{ __('header_footer.delivery') }}</a></li>
                    <li><a href="{{ route('news.index') }}">{{ __('header_footer.news_and_promos') }}</a></li>
                    <li><a href="{{ route('about-us') }}">{{ __('header_footer.about_us') }}</a></li>
                    <li><a href="{{ route('blog.index') }}">{{ __('header_footer.blog') }}</a></li>
                    <li><a href="{{ route('contacts') }}">{{ __('header_footer.contact_us') }}</a></li>
                </ul>
            </nav>

            <div class="mob-menu-catalog-panel" id="mob-menu-catalog-panel" hidden>
                <ul class="menu head-catalog-mob">
                    @foreach($productCategories as $category)
                        <x-category-menu-mobile :category="$category"/>
                    @endforeach
                </ul>
            </div>

            <div class="mob-menu-account">
                @guest
                    <button type="button" class="mob-menu-account__link general-popup-btn" data-popup="login-popup">
                        <i class="far fa-user" aria-hidden="true"></i>
                        <span>{{ __('header_footer.login_cabinet') }}</span>
                    </button>
                @endguest
                @auth
                    <a href="{{ route('account') }}" class="mob-menu-account__link">
                        <i class="far fa-user-check" aria-hidden="true"></i>
                        <span>{{ __('checkout.personal_account') }}</span>
                    </a>
                @endauth

                <button type="button" class="mob-menu-account__link general-popup-btn" data-popup="cart-popup">
                    <span class="mob-menu-account__icon">
                        <i class="far fa-shopping-cart" aria-hidden="true"></i>
                        <span class="mob-menu-account__badge cart-total" id="cart-total-mobile">{{ $cartItemsCount }}</span>
                    </span>
                    <span>{{ __('header_footer.cart') }}</span>
                </button>

                @guest
                    <button type="button" class="mob-menu-account__link general-popup-btn" data-popup="login-popup">
                        <span class="mob-menu-account__icon">
                            <i class="far fa-heart" aria-hidden="true"></i>
                            <span class="mob-menu-account__badge wishlist-total">{{ $wishlistCount }}</span>
                        </span>
                        <span>{{ __('header_footer.favorites') }}</span>
                    </button>
                @endguest
                @auth
                    <a href="{{ route('wishlist') }}" class="mob-menu-account__link">
                        <span class="mob-menu-account__icon">
                            <i class="far fa-heart" aria-hidden="true"></i>
                            <span class="mob-menu-account__badge wishlist-total">{{ $wishlistCount }}</span>
                        </span>
                        <span>{{ __('header_footer.favorites') }}</span>
                    </a>
                @endauth
            </div>

            <div class="mob-menu-footer">
                <div class="mob-menu-footer__row">
                    <div class="mob-menu-social">
                        <a href="{{ $settings->telegram ?? '#' }}" title="Telegram" target="_blank" class="fab fa-telegram-plane"></a>
                        <a href="{{ $settings->instagram ?? '#' }}" title="Instagram" target="_blank" class="fab fa-instagram"></a>
                    </div>
                    <div class="mob-menu-phone">
                        <span class="mob-menu-phone__label">{{ __('header_footer.phone') }}</span>
                        <a href="{{ \App\Support\SitePhone::telHref($settings ?? null) }}">{{ \App\Support\SitePhone::display($settings ?? null) }}</a>
                    </div>
                </div>

                <div class="mob-menu-footer__row mob-menu-footer__row--tools">
                    <div class="mob-menu-lang" id="form-language-mobile">
                        <button type="button" class="mob-menu-lang__current" aria-expanded="false">
                            {{ $localeLabel }}
                            <i class="fas fa-chevron-down" aria-hidden="true"></i>
                        </button>
                        <div class="mob-menu-lang__list">
                            <a href="{{ LaravelLocalization::getLocalizedURL('uk') }}" class="{{ $locale === 'uk' ? 'is-active' : '' }}">UA</a>
                            <a href="{{ LaravelLocalization::getLocalizedURL('ru') }}" class="{{ $locale === 'ru' ? 'is-active' : '' }}">RU</a>
                            <a href="{{ LaravelLocalization::getLocalizedURL('en') }}" class="{{ $locale === 'en' ? 'is-active' : '' }}">EN</a>
                        </div>
                    </div>

                    <form class="head-theme mob-menu-theme flex-center" action="{{ route('change-theme') }}" method="post"
                          enctype="multipart/form-data" aria-label="{{ __('general-translate.theme_switcher') }}">
                        @csrf
                        <button class="button theme-select {{ $theme === 'dark' ?: 'active' }} fas fa-sun"
                                type="submit"
                                aria-label="{{ __('general-translate.switch_to_light_theme') }}"></button>
                        <button class="button theme-select {{ $theme === 'light' ?: 'active' }} far fa-moon"
                                type="submit"
                                aria-label="{{ __('general-translate.switch_to_dark_theme') }}"></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed-header">
        <div class="head-main wrap">
            <div class="head-logo">
                <a href="{{ route('index') }}" title="Wrap.Shop">
                    <img loading="lazy" src="{{ asset('assets/img/logo.png') }}"
                         title="Wrap.Shop" alt="Wrap.Shop" width="93"
                         height="98">
                </a>
            </div>

            <div class="head-main__body">
                <div class="head-main__top">
                    <nav class="head-menu">
                        @include('base.layouts.menu')
                    </nav>
                    <div class="head-top-aside flex-center">
                        <div class="head-social flex-center">
                            <a href="{{ $settings->telegram ?? '#' }}" title="Telegram" target="_blank"
                               class="fab fa-telegram-plane"></a>
                            <a href="{{ $settings->instagram ?? '#' }}" title="Instagram" target="_blank"
                               class="fab fa-instagram"></a>
                        </div>
                        <div class="head-phone flex-center">
                            <div class="title">{{ __('header_footer.phone') }}</div>
                            <a href="{{ \App\Support\SitePhone::telHref($settings ?? null) }}">{{ \App\Support\SitePhone::display($settings ?? null) }}</a>
                        </div>
                    </div>
                </div>

                <div class="head-main__bottom head-boot">
                    <div class="head-catalog-open button" role="button" aria-expanded="false" aria-controls="headCatalogMega">
                        <div class="butt-menu"><span></span></div>
                        {{ __('header_footer.catalog_products') }}
                    </div>
                    <div class="head-catalog-bg"></div>
                    @include('base.components.head-catalog-mega', ['productCategories' => $productCategories])
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
                            <ul class="menu head-catalog-mob">
                                @foreach($productCategories as $category)
                                    <x-category-menu-mobile :category="$category"/>
                                @endforeach
                            </ul>
                        </div>
                    </nav>

                    <div class="head-search" id="head-search">
                        <div class="head-search__wrap">
                            <input
                                type="text"
                                name="head-search"
                                class="head-search__input"
                                value=""
                                placeholder="{{ __('header_footer.menu_search_placeholder') }}"
                                autocomplete="off"
                                aria-label="{{ __('header_footer.menu_search_placeholder') }}"
                            >
                            <button type="button" class="head-search__submit" aria-label="{{ __('popup.search_popup.search') }}">
                                <i class="far fa-search" aria-hidden="true"></i>
                            </button>
                            <ul class="dropdown-menu head-search__dropdown" role="listbox" aria-label="{{ __('popup.search_popup.products') }}"></ul>
                        </div>
                    </div>

                    <div class="head-actions-near">
                        <button class="button general-popup-btn far fa-shopping-cart dropdown-toggle"
                                data-popup="cart-popup"><span
                                id="cart-total" class="cart-total flex-center">{{ $cartItemsCount }}</span></button>
                        @guest
                            <div id="wishlist-total" class="button heart general-popup-btn far fa-heart" data-popup="login-popup">
                                <span class="wishlist-total flex-center">{{ count(session()->get('wishlist', [])) }}</span>
                            </div>
                            <div class="button general-popup-btn far fa-user" data-popup="login-popup"></div>
                        @endguest

                        @auth
                            <a href="{{ route('wishlist') }}" id="wishlist-total" title="{{ __('checkout.wishlist') }}"
                               class="button heart far fa-heart"><span
                                    class="wishlist-total flex-center">{{ auth()->user()->favoriteCount() }}</span></a>
                            <a href="{{ route('account') }}" title="{{ __('checkout.personal_account') }}" class="button user far fa-user-check"></a>
                        @endauth
                    </div>

                    <div class="head-phone-mobil">
                        <div class="title">{{ __('header_footer.phone') }}</div>
                        <a href="{{ \App\Support\SitePhone::telHref($settings ?? null) }}">{{ \App\Support\SitePhone::display($settings ?? null) }}</a>
                    </div>

                    <div class="head-buttons">
                        <div class="general-popup-btn button search far fa-search head-search-mobile-btn"
                             data-popup="search-popup"></div>

                        <div class="head-lang-dropdown" id="form-language">
                            <button type="button" class="head-lang-dropdown__current" aria-expanded="false">
                                <span>{{ $localeLabel }}</span>
                                <i class="fas fa-chevron-down" aria-hidden="true"></i>
                            </button>
                            <div class="head-lang-dropdown__list">
                                <a href="{{ LaravelLocalization::getLocalizedURL('uk') }}" class="{{ $locale === 'uk' ? 'is-active' : '' }}">UA</a>
                                <a href="{{ LaravelLocalization::getLocalizedURL('ru') }}" class="{{ $locale === 'ru' ? 'is-active' : '' }}">RU</a>
                                <a href="{{ LaravelLocalization::getLocalizedURL('en') }}" class="{{ $locale === 'en' ? 'is-active' : '' }}">EN</a>
                            </div>
                            <meta name="csrf-token" content="{{ csrf_token() }}">
                        </div>

                        <form class="head-theme flex-center" action="{{ route('change-theme') }}" method="post"
                              enctype="multipart/form-data" id="form-theme" aria-label="{{ __('general-translate.theme_switcher') }}">
                            @csrf
                            <button class="button theme-select {{ $theme === 'dark' ?: 'active' }} fas fa-sun"
                                    type="submit"
                                    aria-label="{{ __('general-translate.switch_to_light_theme') }}"></button>
                            <button class="button theme-select {{ $theme === 'light' ?: 'active' }} far fa-moon"
                                    type="submit"
                                    aria-label="{{ __('general-translate.switch_to_dark_theme') }}"></button>
                        </form>

                        <div class="head-top-open button general-popup-btn" data-popup="menu-popup">
                            <i class="far fa-bars"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
