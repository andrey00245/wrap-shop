@extends('base.layouts.app')

@php
    $locale = app()->getLocale();
    $blogTitle = 'Блог | Wrap.Shop';
    $blogDescription = 'Блог про плівки для автомобілів, детейлінг, тюнінг та все, що пов\'язано з авто.';
@endphp

@section('title', $blogTitle)
@section('description', $blogDescription)

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
<div class="blog-hero">
    <!-- Breadcrumbs -->
    <nav class="blog-breadcrumbs">
        <a href="{{route('index')}}">ГОЛОВНА</a>
        <span> / </span>
        <span>БЛОГ</span>
    </nav>
    
    <!-- Category Navigation -->
    <div class="blog-categories-nav">
        <button class="category-filter active" data-category="all">ВСІ</button>
        <button class="category-filter" data-category="film">ПЛІВКА ДЛЯ АВТО</button>
        <button class="category-filter" data-category="insulation">ШУМОІЗОЛЯЦІЯ ТА ВІБРОІЗОЛЯЦІЯ</button>
        <button class="category-filter" data-category="detailing">ДЕТЕЙЛІНГ</button>
        <button class="category-filter" data-category="tuning">ТЮНІНГ</button>
        <button class="category-filter" data-category="tips">ЗАГАЛЬНІ ПОРАДИ ТА ОГЛЯДИ</button>
    </div>
</div>

<!-- Featured Article Section -->
<div class="blog-featured">
    <div class="container">
        <div class="featured-article">
            <div class="featured-content">
                <div class="featured-header-meta">
                    <div class="featured-tag">ПЛІВКА ДЛЯ АВТО</div>
                    <div class="featured-date">
                        <span class="date-label">дата публікації:</span>
                        <span class="date-value">01.07.2025</span>
                    </div>
                </div>
                <h1 class="featured-title">ТРЕНДИ В ДИЗАЙНІ ТА КОЛЬОРАХ ПЛІВОК.</h1>
                <p class="featured-description">
                    Світ автомобільного тюнінгу не стоїть на місці, і плівки для авто давно перестали бути просто засобом захисту. 
                    Сьогодні це потужний інструмент для самовираження та надання вашому автомобілю унікального стилю. 
                    Щорічно з'являються нові тренди в кольорах та дизайнах, які захоплюють автолюбителів по всьому світу. 
                    Давайте розберемося, що зараз на піку популярності у світі автомобільних плівок.
                </p>
                <div class="featured-footer">
                    <div class="featured-author">автор Full Name</div>
                    <a href="{{route('blog.show')}}" class="featured-read-btn">
                        ЧИТАТИ
                        <img src="{{ asset('assets/img/icons/arrow_outward.png') }}" alt="Container" class="featured-read-icon">
                    </a>
                </div>
            </div>
            <div class="featured-image">
                <div class="image-placeholder">Featured Image</div>
            </div>
        </div>
    </div>
</div>

<!-- Latest Publications Section -->
<div class="blog-latest">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">ОСТАННІ ПУБЛІКАЦІЇ</h2>
            <a href="#" class="section-link desktop-only">ВСІ ПУБЛІКАЦІЇ</a>
        </div>
        <div class="latest-slider-wrapper">
            <div class="articles-grid articles-grid-latest" id="latest-slider">
                <article class="article-card">
                    <div class="article-image">
                        <div class="image-placeholder">Image 1</div>
                    </div>
                    <div class="article-content">
                        <div class="article-meta">
                            <div class="article-tag">ДЕТЕЙЛІНГ</div>
                            <div class="article-date">20.02.2025</div>
                        </div>
                            <h3 class="article-title">ДОГЛЯД ЗА ІНТЕР'ЄРОМ: Хімчистка салону, догляд за шкірою, пластиком.</h3>
                            <p class="article-excerpt">Професійний догляд за салоном автомобіля включає регулярну хімчистку, догляд за шкіряними сидіннями та пластиковими елементами. Правильний підхід до догляду за інтер'єром продовжує термін служби матеріалів та підтримує естетичний вигляд автомобіля.</p>
                            <div class="article-author">
                                <span>автор: Full Name</span>
                                <a href="#" class="article-author-link">
                                    <img src="{{ asset('assets/img/icons/arrow_outward.png') }}" alt="Container">
                                </a>
                            </div>
                    </div>
                </article>
                
                <article class="article-card">
                    <div class="article-image">
                        <div class="image-placeholder">Image 2</div>
                    </div>
                    <div class="article-content">
                        <div class="article-meta">
                            <div class="article-tag">ТЮНІНГ</div>
                            <div class="article-date">09.02.2025</div>
                        </div>
                        <h3 class="article-title">ВИДИ ТЮНІНГУ: Екстер'єрний</h3>
                        <p class="article-excerpt">Різновиди тюнінгу автомобіля включають екстер'єрний, інтер'єрний та технічний напрямки. Кожен вид має свої особливості та призначення для покращення зовнішнього вигляду та функціональності транспортного засобу.</p>
                        <div class="article-author">
                            <span>автор: Full Name</span>
                            <a href="#" class="article-author-link">
                                <img src="{{ asset('assets/img/icons/arrow_outward.png') }}" alt="Container">
                            </a>
                        </div>
                    </div>
                </article>
                
                <article class="article-card">
                    <div class="article-image">
                        <div class="image-placeholder">Image 3</div>
                    </div>
                    <div class="article-content">
                        <div class="article-meta">
                            <div class="article-tag">ТЮНІНГ</div>
                            <div class="article-date">09.02.2025</div>
                        </div>
                        <h3 class="article-title">ВИДИ ТЮНІНГУ: Екстер'єрний</h3>
                        <p class="article-excerpt">Різновиди тюнінгу автомобіля включають екстер'єрний, інтер'єрний та технічний напрямки. Кожен вид має свої особливості та призначення для покращення зовнішнього вигляду та функціональності транспортного засобу.</p>
                        <div class="article-author">
                            <span>автор: Full Name</span>
                            <a href="#" class="article-author-link">
                                <img src="{{ asset('assets/img/icons/arrow_outward.png') }}" alt="Container">
                            </a>
                        </div>
                    </div>
                </article>
            </div>
            <ul class="latest-slider-dots mobile-only"></ul>
            <a href="#" class="latest-all-btn mobile-only">ВСІ ПУБЛІКАЦІЇ</a>
        </div>
    </div>
</div>

<!-- Category Sections -->
<div class="blog-categories">
    <div class="container">
        <!-- ПЛІВКА ДЛЯ АВТО -->
        <div class="category-section">
            <div class="section-header">
                <h2 class="section-title">ПЛІВКА ДЛЯ АВТО</h2>
                <a href="#" class="section-link desktop-only">БІЛЬШЕ ПУБЛІКАЦІЙ</a>
            </div>
            <div class="category-slider-wrapper">
                <div class="articles-grid articles-grid-2" data-category="film">
                <article class="article-card">
                    <div class="article-image">
                        <div class="image-placeholder">Film Image 1</div>
                    </div>
                    <div class="article-content">
                        <div class="article-meta">
                            <div class="article-tag">ПЛІВКА ДЛЯ АВТО</div>
                            <div class="article-date">10.02.2025</div>
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
                
                <article class="article-card">
                    <div class="article-image">
                        <div class="image-placeholder">Film Image 2</div>
                    </div>
                    <div class="article-content">
                        <div class="article-meta">
                            <div class="article-tag">ПЛІВКА ДЛЯ АВТО</div>
                            <div class="article-date">09.02.2025</div>
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
                </div>
                <ul class="category-slider-dots mobile-only" data-category="film"></ul>
                <a href="#" class="category-all-btn mobile-only">БІЛЬШЕ ПУБЛІКАЦІЙ</a>
            </div>
        </div>

        <!-- ШУМОІЗОЛЯЦІЯ ТА ВІБРОІЗОЛЯЦІЯ -->
        <div class="category-section">
            <div class="section-header">
                <h2 class="section-title">ШУМОІЗОЛЯЦІЯ ТА ВІБРОІЗОЛЯЦІЯ</h2>
                <a href="#" class="section-link desktop-only">БІЛЬШЕ ПУБЛІКАЦІЙ</a>
            </div>
            <div class="category-slider-wrapper">
                <div class="articles-grid articles-grid-2" data-category="insulation">
                <article class="article-card">
                    <div class="article-image">
                        <div class="image-placeholder">Insulation Image 1</div>
                    </div>
                    <div class="article-content">
                        <div class="article-meta">
                            <div class="article-tag">ШУМОІЗОЛЯЦІЯ</div>
                            <div class="article-date">20.02.2025</div>
                        </div>
                        <h3 class="article-title">ТИПИ МАТЕРІАЛІВ: Віброізоляція, шумоізоляція, комбіновані рішення.</h3>
                        <p class="article-excerpt">Огляд матеріалів для шумоізоляції включає віброізоляцію, шумоізоляцію та комбіновані рішення. Правильний вибір матеріалів забезпечує максимальний комфорт та зниження рівня шуму в салоні автомобіля.</p>
                        <div class="article-author">
                        <span>автор: Full Name</span>
                        <a href="#" class="article-author-link">
                            <img src="{{ asset('assets/img/icons/arrow_outward.png') }}" alt="Container">
                        </a>
                    </div>
                    </div>
                </article>
                
                <article class="article-card">
                    <div class="article-image">
                        <div class="image-placeholder">Insulation Image 2</div>
                    </div>
                    <div class="article-content">
                        <div class="article-meta">
                            <div class="article-tag">ШУМОІЗОЛЯЦІЯ</div>
                            <div class="article-date">20.02.2025</div>
                        </div>
                        <h3 class="article-title">ПЕРЕВАГИ ШУМОІЗОЛЯЦІЇ: Комфорт, захист від вібрацій, покращення акустики.</h3>
                        <p class="article-excerpt">Як шумоізоляція покращує комфорт включає захист від вібрацій, покращення акустики та створення більш приємної атмосфери в салоні. Правильно встановлена шумоізоляція може значно знизити рівень шуму та вібрацій під час руху.</p>
                        <div class="article-author">
                        <span>автор: Full Name</span>
                        <a href="#" class="article-author-link">
                            <img src="{{ asset('assets/img/icons/arrow_outward.png') }}" alt="Container">
                        </a>
                    </div>
                    </div>
                </article>
                </div>
                <ul class="category-slider-dots mobile-only" data-category="insulation"></ul>
                <a href="#" class="category-all-btn mobile-only">БІЛЬШЕ ПУБЛІКАЦІЙ</a>
            </div>
        </div>

        <!-- ДЕТЕЙЛІНГ -->
        <div class="category-section">
            <div class="section-header">
                <h2 class="section-title">ДЕТЕЙЛІНГ</h2>
                <a href="#" class="section-link desktop-only">БІЛЬШЕ ПУБЛІКАЦІЙ</a>
            </div>
            <div class="category-slider-wrapper">
                <div class="articles-grid articles-grid-2" data-category="detailing">
                <article class="article-card">
                    <div class="article-image">
                        <div class="image-placeholder">Detailing Image 1</div>
                    </div>
                    <div class="article-content">
                        <div class="article-meta">
                            <div class="article-tag">ДЕТЕЙЛІНГ</div>
                            <div class="article-date">20.02.2025</div>
                        </div>
                        <h3 class="article-title">ДОГЛЯД ЗА ІНТЕР'ЄРОМ: Хімчистка салону, догляд за шкірою, пластиком.</h3>
                        <p class="article-excerpt">Професійний догляд за салоном автомобіля включає регулярну хімчистку, догляд за шкіряними сидіннями та пластиковими елементами. Правильний підхід до догляду за інтер'єром продовжує термін служби матеріалів та підтримує естетичний вигляд автомобіля.</p>
                        <div class="article-author">
                        <span>автор: Full Name</span>
                        <a href="#" class="article-author-link">
                            <img src="{{ asset('assets/img/icons/arrow_outward.png') }}" alt="Container">
                        </a>
                    </div>
                    </div>
                </article>
                
                <article class="article-card">
                    <div class="article-image">
                        <div class="image-placeholder">Detailing Image 2</div>
                    </div>
                    <div class="article-content">
                        <div class="article-meta">
                            <div class="article-tag">ДЕТЕЙЛІНГ</div>
                            <div class="article-date">20.02.2025</div>
                        </div>
                        <h3 class="article-title">ДОГЛЯД ЗА ЕКСТЕР'ЄРОМ: Полірування, захист фарби, керамічне покриття.</h3>
                        <p class="article-excerpt">Як правильно доглядати за зовнішнім виглядом авто включає полірування, захист фарби та керамічне покриття. Регулярний догляд за екстер'єром допомагає зберегти оригінальний вигляд автомобіля та захистити його від зовнішніх факторів.</p>
                        <div class="article-author">
                        <span>автор: Full Name</span>
                        <a href="#" class="article-author-link">
                            <img src="{{ asset('assets/img/icons/arrow_outward.png') }}" alt="Container">
                        </a>
                    </div>
                    </div>
                </article>
                </div>
                <ul class="category-slider-dots mobile-only" data-category="detailing"></ul>
                <a href="#" class="category-all-btn mobile-only">БІЛЬШЕ ПУБЛІКАЦІЙ</a>
            </div>
        </div>

        <!-- ТЮНІНГ -->
        <div class="category-section">
            <div class="section-header">
                <h2 class="section-title">ТЮНІНГ</h2>
                <a href="#" class="section-link desktop-only">БІЛЬШЕ ПУБЛІКАЦІЙ</a>
            </div>
            <div class="category-slider-wrapper">
                <div class="articles-grid articles-grid-2" data-category="tuning">
                <article class="article-card">
                    <div class="article-image">
                        <div class="image-placeholder">Tuning Image 1</div>
                    </div>
                    <div class="article-content">
                        <div class="article-meta">
                            <div class="article-tag">ТЮНІНГ</div>
                            <div class="article-date">20.02.2025</div>
                        </div>
                        <h3 class="article-title">ВИДИ ТЮНІНГУ: Екстер'єрний, інтер'єрний, технічний.</h3>
                        <p class="article-excerpt">Огляд основних видів тюнінгу включає екстер'єрний, інтер'єрний та технічний напрямки. Кожен вид має свої особливості та призначення для покращення зовнішнього вигляду та функціональності транспортного засобу.</p>
                        <div class="article-author">
                        <span>автор: Full Name</span>
                        <a href="#" class="article-author-link">
                            <img src="{{ asset('assets/img/icons/arrow_outward.png') }}" alt="Container">
                        </a>
                    </div>
                    </div>
                </article>
                
                <article class="article-card">
                    <div class="article-image">
                        <div class="image-placeholder">Tuning Image 2</div>
                    </div>
                    <div class="article-content">
                        <div class="article-meta">
                            <div class="article-tag">ТЮНІНГ</div>
                            <div class="article-date">20.02.2025</div>
                        </div>
                        <h3 class="article-title">ТЮНІНГ ДВИГУНА: Підвищення потужності, чип-тюнінг, доопрацювання.</h3>
                        <p class="article-excerpt">Як покращити характеристики двигуна включає підвищення потужності, чип-тюнінг та доопрацювання. Правильний підхід до тюнінгу двигуна може значно покращити динаміку та ефективність автомобіля без компромісів у надійності.</p>
                        <div class="article-author">
                        <span>автор: Full Name</span>
                        <a href="#" class="article-author-link">
                            <img src="{{ asset('assets/img/icons/arrow_outward.png') }}" alt="Container">
                        </a>
                    </div>
                    </div>
                </article>
                </div>
                <ul class="category-slider-dots mobile-only" data-category="tuning"></ul>
                <a href="#" class="category-all-btn mobile-only">БІЛЬШЕ ПУБЛІКАЦІЙ</a>
            </div>
        </div>

        <!-- ЗАГАЛЬНІ ПОРАДИ ТА ОГЛЯДИ -->
        <div class="category-section">
            <div class="section-header">
                <h2 class="section-title">ЗАГАЛЬНІ ПОРАДИ ТА ОГЛЯДИ</h2>
                <a href="#" class="section-link desktop-only">БІЛЬШЕ ПУБЛІКАЦІЙ</a>
            </div>
            <div class="category-slider-wrapper">
                <div class="articles-grid articles-grid-2" data-category="tips">
                <article class="article-card">
                    <div class="article-image">
                        <div class="image-placeholder">Tips Image 1</div>
                    </div>
                    <div class="article-content">
                        <div class="article-meta">
                            <div class="article-tag">ЗАГАЛЬНІ ПОРАДИ</div>
                            <div class="article-date">20.02.2025</div>
                        </div>
                        <h3 class="article-title">ЯК ВИБРАТИ ПЛІВКУ: Поради для початківців.</h3>
                        <p class="article-excerpt">На що звернути увагу при виборі плівки включає якість матеріалу, товщину, колір та призначення. Правильний вибір плівки забезпечує довготривалий захист та естетичний вигляд автомобіля на багато років.</p>
                        <div class="article-author">
                        <span>автор: Full Name</span>
                        <a href="#" class="article-author-link">
                            <img src="{{ asset('assets/img/icons/arrow_outward.png') }}" alt="Container">
                        </a>
                    </div>
                    </div>
                </article>
                
                <article class="article-card">
                    <div class="article-image">
                        <div class="image-placeholder">Tips Image 2</div>
                    </div>
                    <div class="article-content">
                        <div class="article-meta">
                            <div class="article-tag">ЗАГАЛЬНІ ПОРАДИ</div>
                            <div class="article-date">20.02.2025</div>
                        </div>
                        <h3 class="article-title">ОГЛЯД НОВИНОК: Нові продукти та технології 2025.</h3>
                        <p class="article-excerpt">Що нового в світі автотюнінгу включає нові продукти та технології 2025 року. Огляд останніх інновацій допомагає зрозуміти тенденції розвитку індустрії та вибрати найкращі рішення для свого автомобіля.</p>
                        <div class="article-author">
                        <span>автор: Full Name</span>
                        <a href="#" class="article-author-link">
                            <img src="{{ asset('assets/img/icons/arrow_outward.png') }}" alt="Container">
                        </a>
                    </div>
                    </div>
                </article>
                </div>
                <ul class="category-slider-dots mobile-only" data-category="tips"></ul>
                <a href="#" class="category-all-btn mobile-only">БІЛЬШЕ ПУБЛІКАЦІЙ</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{mix('build/js/blog.js')}}"></script>
@endpush

@endsection
