@extends('base.layouts.app')

@php
    $locale = app()->getLocale();
    $implementationsTitle = __('implementations.title') . ' | Wrap.Shop';
    $implementationsDescription = 'Наші реалізації - приклади робіт з плівками для автомобілів, детейлінгу та тюнінгу. Професійний підхід, якісні матеріали, унікальні рішення.';

    if ($locale === 'ru') {
        $implementationsDescription = 'Наши реализации - примеры работ с пленками для автомобилей, детейлинга и тюнинга. Профессиональный подход, качественные материалы, уникальные решения.';
    } elseif ($locale === 'en') {
        $implementationsDescription = 'Our implementations - examples of work with car wraps, detailing and tuning. Professional approach, quality materials, unique solutions.';
    }
@endphp

@section('title', $implementationsTitle)
@section('description', $implementationsDescription)
@section('keywords', 'реалізації, приклади робіт, плівки для авто, детейлінг, тюнінг, wrap shop')
@section('og_type', 'website')
@section('og_title', $implementationsTitle)
@section('og_description', $implementationsDescription)
@section('og_image', url('assets/img/og-default.jpg'))
@section('og_url', url()->current())
@section('twitter_card', 'summary_large_image')
@section('twitter_title', $implementationsTitle)
@section('twitter_description', $implementationsDescription)
@section('twitter_image', url('assets/img/og-default.jpg'))
@section('canonical', url()->current())

@push('styles')
    @if($theme ==='dark')
        <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-blog-dark.css')}}">
    @else
        <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-blog-light.css')}}">
    @endif
    <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-implementations.css')}}">
@endpush

@section('content')

<!-- Hero Section -->
<div class="implementations-hero">
    <div class="hero-background"></div>
    
    <!-- Breadcrumbs -->
    <nav class="hero-breadcrumbs">
        <ul>
            <li><a href="{{route('index')}}">{{__('header_footer.home')}}</a></li>
            <li><a href="{{route('about-us')}}">{{__('header_footer.about_us')}}</a></li>
            <li><span>{{__('implementations.title')}}</span></li>
        </ul>
    </nav>
    
    <!-- Hero Title -->
    <div class="hero-content">
        <h1 class="hero-title">{{__('implementations.title')}}</h1>
    </div>
    
    <!-- Category Filter Strip -->
    <div class="implementations-categories">
        <div class="category-buttons">
            <button class="category-btn active" data-category="all">{{__('implementations.category_all')}}</button>
            <button class="category-btn" data-category="protection">{{__('implementations.category_protection')}}</button>
            <button class="category-btn" data-category="decorative">{{__('implementations.category_decorative')}}</button>
            <button class="category-btn" data-category="wheels">{{__('implementations.category_wheels')}}</button>
            <button class="category-btn" data-category="tinting">{{__('implementations.category_tinting')}}</button>
            <button class="category-btn" data-category="armoring">{{__('implementations.category_armoring')}}</button>
        </div>
    </div>
</div>

<!-- Description Section -->
<div class="implementations-description">
    <div class="container">
        <div class="description-content">
            {!! __('implementations.description') !!}
        </div>
    </div>
</div>

<!-- Brands Strip -->
<div class="implementations-brands">
    <div class="container">
        <div class="brands-logos">
            <img src="{{asset('assets/img/brands/car-brands-strip.svg')}}" alt="Car Brands" class="brands-strip-image">
        </div>
    </div>
</div>

<!-- Gallery Section -->
<div class="implementations-gallery">
    <div class="container">
        @if($implementations->count() > 0)
        <div class="gallery-grid">
            @foreach($implementations as $implementation)
                <div class="gallery-item" data-category="{{$implementation->product->category->slug ?? 'all'}}">
                    <a href="{{$implementation->getImage()}}" data-fancybox="implementations-gallery" title="{{$implementation->title}}">
                        <img src="{{$implementation->getPreviewImage()}}" alt="{{$implementation->title}}">
                        <div class="gallery-overlay">
                            <div class="gallery-info">
                                <h3 class="gallery-title">{{$implementation->title}}</h3>
                                <span class="gallery-date">{{$implementation->getData()}}</span>
                                @if($implementation->product)
                                <div class="gallery-product">
                                    <span class="product-category">{{$implementation->product->category->name}}</span>
                                    <span class="product-name">{{$implementation->product->name}}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        
        <!-- More Projects Button -->
        <div class="more-projects">
            <a href="#" class="more-projects-btn">БІЛЬШЕ ПРОЄКТІВ</a>
        </div>
        @else
        <div class="gallery-empty">
            <p>{{__('implementations.no_implementations')}}</p>
        </div>
        @endif
    </div>
</div>

</div>

@push('scripts')
<script>
    // Category Filter
    $(document).ready(function() {
        $('.category-btn').on('click', function() {
            $('.category-btn').removeClass('active');
            $(this).addClass('active');
            
            var category = $(this).data('category');
            
            if (category === 'all') {
                $('.gallery-item').show();
            } else {
                $('.gallery-item').hide();
                $('.gallery-item[data-category="' + category + '"]').show();
            }
        });
    });
</script>
@endpush

@push('fixed-catalog')
    @include('base.components.categories-catalog')
@endpush

@endsection
