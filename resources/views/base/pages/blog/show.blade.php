@extends('base.layouts.app')

@php
    $locale = app()->getLocale();
    $articleTitle = 'ТРЕНДИ В ДИЗАЙНІ ТА КОЛЬОРАХ ПЛІВОК | Блог | Wrap.Shop';
    $articleDescription = 'Огляд нових трендів у дизайні та кольорах плівок для автомобілів.';
@endphp

@section('title', $articleTitle)
@section('description', $articleDescription)

@push('styles')
    @if($theme ==='dark')
        <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-blog-dark.css')}}">
    @else
        <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-blog-light.css')}}">
    @endif
    <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-blog.css')}}">
@endpush

@section('content')

<!-- Hero Section -->
<!-- Breadcrumbs отдельно над Hero -->
<nav class="blog-breadcrumbs">
    <a href="{{route('index')}}">ГОЛОВНА</a>
    <span>/</span>
    <a href="{{route('blog.index')}}">БЛОГ</a>
    <span>/</span>
    <span>СТАТТЯ</span>
</nav>
<div class="blog-article-hero">
    <div class="hero-image-placeholder">Hero Image</div>
</div>

<!-- Article Content -->
<div class="blog-article-content">
    <div class="container">
        <div class="article-main-wrapper">
            <!-- Left Sidebar - Categories Accordion -->
            <aside class="article-categories-sidebar">
                <div class="categories-accordion">
                    <div class="accordion-item">
                        <button class="accordion-header active">
                            <span>ПЛІВКИ</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="accordion-content active">
                            <ul class="accordion-list">
                                <li><a href="#">Кольорові плівки</a></li>
                                <li><a href="#">Захисні плівки</a></li>
                                <li><a href="#">Кольорові захисні плівки</a></li>
                                <li><a href="#">Плівка для автомобільних вікон</a></li>
                                <li><a href="#">Готові дизайни та плівка для друку</a></li>
                                <li><a href="#">Лекала для салона</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="article-main">
                <!-- Article Header -->
                <div class="article-header">
                    <h1 class="article-title">ТРЕНДИ В ДИЗАЙНІ ТА КОЛЬОРАХ ПЛІВОК.</h1>
                    <div class="article-meta">
                        <span class="article-tag">ПЛІВКА ДЛЯ АВТО</span>
                        <span class="article-date">
                            <span class="article-date-label">дата публікації:</span>
                            <span class="article-date-value">26.02.2025</span>
                        </span>
                    </div>
                </div>

            <!-- Table of Contents -->
            <div class="article-toc">
                <h3 class="toc-title">ЗМІСТ</h3>
                <ol class="toc-list">
                    <li><a href="#section-1">МАТОВІ ТА САТИНОВІ ВІДТІНКИ: ЕЛЕГАНТНІСТЬ І СТРИМАНІСТЬ</a></li>
                    <li><a href="#section-2">ПАСТЕЛЬНІ ТА НЮДОВІ КОЛЬОРИ: НІЖНІСТЬ І ВИШУКАНІСТЬ</a></li>
                    <li><a href="#section-3">ЕФЕКТИ "ХАМЕЛЕОН" ТА "ГОЛОГРАФІК": ГРА СВІТЛА ТА КОЛЬОРУ</a></li>
                    <li><a href="#section-4">ЯСКРАВІ ТА НАСИЧЕНІ КОЛЬОРИ: СМІЛИВІСТЬ ТА ІНДИВІДУАЛЬНІСТЬ</a></li>
                </ol>
            </div>

            <!-- Article Body -->
            <div class="article-body">
                <section id="section-1" class="article-section">
                    <h2>МАТОВІ ТА САТИНОВІ ВІДТІНКИ: ЕЛЕГАНТНІСТЬ І СТРИМАНІСТЬ</h2>
                    <p>
                        Матові та сатинові плівки продовжують залишатися на піку популярності серед автолюбителів, 
                        які цінують елегантність та стриманість. Ці відтінки створюють вишуканий вигляд, 
                        не привертаючи зайвої уваги, але водночас роблячи автомобіль справді унікальним.
                    </p>
                    <p>
                        Особливо популярними стали матові відтінки сірого, чорного та білого кольорів. 
                        Вони ідеально підходять для преміум-автомобілів та створюють ефект "stealth" вигляду.
                    </p>
                    <div class="article-image-inline">
                        <div class="image-placeholder">Inline Image 1</div>
                    </div>
                </section>

                <section id="section-2" class="article-section">
                    <h2>ПАСТЕЛЬНІ ТА НЮДОВІ КОЛЬОРИ: НІЖНІСТЬ І ВИШУКАНІСТЬ</h2>
                    <p>
                        Пастельні та нюдові кольори набувають все більшої популярності, особливо серед жінок-автолюбительок. 
                        М'які відтінки рожевого, бежевого, лавандового та м'ятного створюють ніжний та вишуканий образ.
                    </p>
                    <p>
                        Ці кольори чудово поєднуються з сучасним дизайном автомобілів та додають їм особливої привабливості. 
                        Вони не виглядають нав'язливо, але водночас роблять автомобіль помітним та стильним.
                    </p>
                </section>

                <section id="section-3" class="article-section">
                    <h2>ЕФЕКТИ "ХАМЕЛЕОН" ТА "ГОЛОГРАФІК": ГРА СВІТЛА ТА КОЛЬОРУ</h2>
                    <p>
                        Ефекти "хамелеон" та "голографік" стали справжньою сенсацією в світі автотюнінгу. 
                        Ці плівки змінюють колір залежно від кута огляду та освітлення, створюючи дивовижні візуальні ефекти.
                    </p>
                    <div class="article-image-inline">
                        <div class="image-placeholder">Chameleon Effect Image</div>
                    </div>
                    <p>
                        Голографічні плівки створюють ефект райдуги та переливання кольорів, що робить автомобіль 
                        справжнім витвором мистецтва. Такі рішення ідеально підходять для тих, хто хоче виділитися 
                        та створити справді унікальний образ свого автомобіля.
                    </p>
                </section>

                <section id="section-4" class="article-section">
                    <h2>ЯСКРАВІ ТА НАСИЧЕНІ КОЛЬОРИ: СМІЛИВІСТЬ ТА ІНДИВІДУАЛЬНІСТЬ</h2>
                    <p>
                        Для тих, хто не боїться бути помітним, яскраві та насичені кольори залишаються актуальними. 
                        Яскраво-червоні, сині, зелені та жовті відтінки продовжують привертати увагу автолюбителів.
                    </p>
                    <p>
                        Особливою популярністю користуються металеві та перламутрові відтінки, які додають глибини 
                        та об'ємності кольору. Такі рішення ідеально підходять для спортивних автомобілів та 
                        створюють агресивний та динамічний образ.
                    </p>
                </section>
            </div>

            <!-- Article Footer -->
            <div class="article-footer">
                <div class="article-progress">
                    <span class="progress-text">25/100</span>
                </div>
                <div class="article-social">
                    <a href="#" class="social-icon">
                        <img src="{{ asset('assets/img/icons/x.png') }}" alt="X">
                    </a>
                    <a href="#" class="social-icon">
                        <img src="{{ asset('assets/img/icons/share.png') }}" alt="Share">
                    </a>
                    <a href="#" class="social-icon">
                        <img src="{{ asset('assets/img/icons/instagram.png') }}" alt="Instagram">
                    </a>
                    <a href="#" class="social-icon">
                        <img src="{{ asset('assets/img/icons/facebook.png') }}" alt="Facebook">
                    </a>
                </div>
            </div>
            </div>

            <!-- Right Sidebar -->
            <aside class="article-sidebar">
            <div class="sidebar-section">
                <h3 class="sidebar-title">ІНШІ СТАТТІ ЗА КАТЕГОРІЄЮ</h3>
                <div class="sidebar-articles">
                    <article class="sidebar-article">
                        <div class="sidebar-article-meta">
                            <div class="sidebar-article-tag">ТЮНІНГ</div>
                            <div class="sidebar-article-date">26.02.2025</div>
                        </div>
                        <h4 class="sidebar-article-title">ВИДИ ТЮНІНГУ:</h4>
                        <p class="sidebar-article-excerpt">Екстер'єрний, інтер'єрний, технічний</p>
                    </article>
                    
                    <article class="sidebar-article">
                        <div class="sidebar-article-meta">
                            <div class="sidebar-article-tag">ТЮНІНГ</div>
                            <div class="sidebar-article-date">26.02.2025</div>
                        </div>
                        <h4 class="sidebar-article-title">ВИДИ ТЮНІНГУ:</h4>
                        <p class="sidebar-article-excerpt">Екстер'єрний, інтер'єрний, технічний</p>
                    </article>
                    
                    <article class="sidebar-article">
                        <div class="sidebar-article-meta">
                            <div class="sidebar-article-tag">ТЮНІНГ</div>
                            <div class="sidebar-article-date">26.02.2025</div>
                        </div>
                        <h4 class="sidebar-article-title">ВИДИ ТЮНІНГУ:</h4>
                        <p class="sidebar-article-excerpt">Екстер'єрний, інтер'єрний, технічний</p>
                    </article>
                    
                    <article class="sidebar-article">
                        <div class="sidebar-article-meta">
                            <div class="sidebar-article-tag">ТЮНІНГ</div>
                            <div class="sidebar-article-date">26.02.2025</div>
                        </div>
                        <h4 class="sidebar-article-title">ВИДИ ТЮНІНГУ:</h4>
                        <p class="sidebar-article-excerpt">Екстер'єрний, інтер'єрний, технічний</p>
                    </article>
                </div>
            </div>
        </aside>
    </div>
</div>

<!-- Products Section -->
<div class="blog-products">
    <div class="container">
        <h2 class="products-title">КОЛЬОРОВІ ПЛІВКИ</h2>
        <div class="blog-products-wrapper">
            <div class="splide home-products-list" id="blogProductsSlider">
                <div class="splide__arrows home-products-slide-buttons">
                    <div class="line"></div>
                </div>
                <div class="splide__track">
                <ul class="splide__list">
                    @forelse($products as $key => $product)
                    <li class="splide__slide home-products-item product-default" id="blogProduct{{ $key }}" data-ids="{{ $product->category->id ?? '' }}" role="group">
                        <div class="product-default-texts-wrapper">
                            <div class="top flex-justify">
                                <div class="sale statuses">
                                    <div class="category-status category-status-1 status-inline text rectangle "
                                         style=" color:#ffffff; background-color:#d04b4b;">
                                        {{__('general-translate.sales_hit')}}
                                    </div>
                                </div>
                                <div class="sku">{{ __('general-translate.product_card.code') }} {{ $product->code }}</div>
                                <div class="wishlist">
                                    <button type="button"
                                            class="button {{ $product->isFavorite() ? 'fas in-wishlist' : 'far' }} fa-heart"
                                            data-product-id="{{ $product->id }}"
                                            title="{{ __('general-translate.product_card.add_wishlist') }}"></button>
                                </div>
                            </div>
                        </div>

                        <div class="image">
                            <i class="far fa-search-plus colord"
                               data-src="{{ $product->getPreviewImage() }}"
                               data-fancybox="blogProduct{{ $product->id }}"
                               data-caption="{{ $product->getName() }}"></i>
                            <a href="{{ route('products.show', ['product' => $product->slugEn]) }}" title="{{ $product->getName() }}">
                                <div class="splide products-images">
                                    <div class="splide__track">
                                        <ul class="splide__list">
                                            @foreach($product->getMedia('images') as $imgKey => $image)
                                                @if($imgKey > 0)
                                                    <div class="hide"
                                                         data-src="{{ $image->getUrl() }}"
                                                         data-fancybox="blogProduct{{ $product->id }}"
                                                         data-caption="{{ $product->getName() }}"></div>
                                                @endif
                                                <li class="splide__slide" role="group">
                                                    <img loading="lazy"
                                                         src="{{ $image->getUrl('preview_webp') }}"
                                                         alt="{{ $product->getName() }}"
                                                         title="{{ $product->getName() }}"
                                                         width="310" height="310">
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="product-default-texts-wrapper">
                            <div class="category">{{ $product->category->name ?? '' }}</div>
                            <a href="{{ route('products.show', ['product' => $product->slugEn]) }}" title="{{ $product->getName() }}" class="name">{{ $product->getName() }}</a>
                            <div class="bottom flex-center">
                                <div class="price">{{ number_format($product->getPrice()) }} ₴<span class="price-unit-xvr"></span></div>
                                @if($product->getStock() > 0)
                                    <button class="button colord button-cart-product general-popup-btn"
                                            data-popup="cart-popup"
                                            data-product-quantity="{{ $product->getDefaultQuantity() }}"
                                            data-product-id="{{ $product->id }}">
                                        <i class="fas fa-chevron-right"></i>{{ __('general-translate.product_card.add_to_cart') }}
                                    </button>
                                @else
                                    <button class="button colord notify-available-btn general-popup-btn"
                                            data-popup="report-availability-popup"
                                            data-product-id="{{ $product->id }}">
                                        <i class="fas fa-bell"></i><span class="hidden-xs hidden-sm hidden-md"> {{ __('product-index.notify') }}</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                        @if($product->getPurpose() || $product->getStructure() || $product->getType())
                            <div class="hover-additional-info">
                                @if($product->getPurpose())
                                    <div class="additional-info">
                                        <p class="text-left additional-title">{{ $product->getPurpose()->name }}</p>
                                        <p class="text-left additional-text">{{ $product->getPurpose()?->pivot?->value }}</p>
                                    </div>
                                @endif
                                @if($product->getStructure())
                                    <div class="additional-info">
                                        <p class="text-left additional-title">{{ $product->getStructure()->name }}</p>
                                        <p class="text-left additional-text">{{ $product->getStructure()?->pivot?->value }}</p>
                                    </div>
                                @elseif($product->getType())
                                    <div class="additional-info">
                                        <p class="text-left additional-title">{{ $product->getType()->name }}</p>
                                        <p class="text-left additional-text">{{ $product->getType()?->pivot?->value }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </li>
                    @empty
                    <li class="splide__slide">
                        <p>Продуктів не знайдено</p>
                    </li>
                    @endforelse
                </ul>
            </div>
            {{-- Одна единственная полоса-переключатель для мобильного слайдера --}}
            <ul class="blog-products-slider-dots mobile-only"></ul>
        </div>
    </div>
</div>

<!-- Similar Articles Section -->
<div class="blog-similar">
    <div class="container">
        <h2 class="section-title">СХОЖІ СТАТТІ ЗА КАТЕГОРІЄЮ</h2>
        <div class="blog-similar-wrapper">
            <div class="splide home-products-list" id="blogSimilarSlider">
                <div class="splide__arrows home-products-slide-buttons">
                    <div class="line"></div>
                </div>
                <div class="splide__track">
                    <ul class="splide__list">
                        <li class="splide__slide">
                            <article class="article-card">
                                <div class="article-image">
                                    <div class="image-placeholder">Similar Image 1</div>
                                </div>
                                <div class="article-content">
                                    <div class="article-meta">
                                        <div class="article-tag">ПЛІВКА ДЛЯ АВТО</div>
                                        <div class="article-date">20.02.2025</div>
                                    </div>
                                    <h3 class="article-title">ТИПИ ПЛІВОК: Вінілові, поліуретанові, матові, глянцеві, хамелеон, антигравійні тощо.</h3>
                                    <p class="article-excerpt">Огляд основних типів плівок для автомобілів включає вінілові, поліуретанові, матові, глянцеві, хамелеон та антигравійні варіанти. Кожен тип має свої переваги та призначення для захисту та зміни зовнішнього вигляду автомобіля.</p>
                                    <div class="article-author">
                                        <span>автор: Full Name</span>
                                        <a href="#" class="article-author-link">
                                            <img src="{{ asset('assets/img/icons/arrow_outward.png') }}" alt="Container">
                                        </a>
                                    </div>
                                </div>
                            </article>
                        </li>
                        
                        <li class="splide__slide">
                            <article class="article-card">
                                <div class="article-image">
                                    <div class="image-placeholder">Similar Image 2</div>
                                </div>
                                <div class="article-content">
                                    <div class="article-meta">
                                        <div class="article-tag">ПЛІВКА ДЛЯ АВТО</div>
                                        <div class="article-date">15.02.2025</div>
                                    </div>
                                    <h3 class="article-title">ОСОБЛИВОСТІ ТА ПЕРЕВАГИ: Захист, зміна кольору, самовідновлення, легкість догляду.</h3>
                                    <p class="article-excerpt">Переваги використання плівок для автомобілів включають захист від подряпин, зміну кольору, самовідновлення та легкість догляду. Правильно встановлена плівка може значно покращити зовнішній вигляд та захистити оригінальне покриття автомобіля.</p>
                                    <div class="article-author">
                                        <span>автор: Full Name</span>
                                        <a href="#" class="article-author-link">
                                            <img src="{{ asset('assets/img/icons/arrow_outward.png') }}" alt="Container">
                                        </a>
                                    </div>
                                </div>
                            </article>
                        </li>
                        
                        <li class="splide__slide">
                            <article class="article-card">
                                <div class="article-image">
                                    <div class="image-placeholder">Similar Image 3</div>
                                </div>
                                <div class="article-content">
                                    <div class="article-meta">
                                        <div class="article-tag">ПЛІВКА ДЛЯ АВТО</div>
                                        <div class="article-date">10.02.2025</div>
                                    </div>
                                    <h3 class="article-title">ЯК ВИБРАТИ ПЛІВКУ: Поради для початківців та професіоналів.</h3>
                                    <p class="article-excerpt">На що звернути увагу при виборі плівки включає якість матеріалу, товщину, колір та призначення. Правильний вибір плівки забезпечує довготривалий захист та естетичний вигляд автомобіля на багато років.</p>
                                    <div class="article-author">
                                        <span>автор: Full Name</span>
                                        <a href="#" class="article-author-link">
                                            <img src="{{ asset('assets/img/icons/arrow_outward.png') }}" alt="Container">
                                        </a>
                                    </div>
                                </div>
                            </article>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Navigation dots for mobile -->
            <ul class="blog-similar-slider-dots"></ul>
        </div>
    </div>
</div>

<!-- Tags Section -->
<div class="blog-tags">
    <div class="container">
        <div class="tags-list">
            <a href="#" class="tag-item">Захисна плівка</a>
            <a href="#" class="tag-item">Кольорові захисні плівки</a>
            <a href="#" class="tag-item">Захисні покриття</a>
            <a href="#" class="tag-item">Захисна плівка</a>
            <a href="#" class="tag-item">Кольорові захисні плівки</a>
            <a href="#" class="tag-item">Захисні покриття</a>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{mix('build/js/blog.js')}}"></script>
@endpush

@endsection
