@extends('base.layouts.app')

@php
    $locale = app()->getLocale();
@endphp

@section('title', $blogTitle ?? __('blog.index_title'))
@section('description', $blogDescription ?? __('blog.index_description'))

@push('styles')
    @if($theme ==='dark')
        <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-blog-dark.css')}}">
    @else
        <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-blog-light.css')}}">
    @endif
    <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-blog.css')}}">
@endpush

@section('content')

<div class="blog-hero">
    <nav class="blog-breadcrumbs">
        <a href="{{route('index')}}">ГОЛОВНА</a>
        <span> / </span>
        <span>БЛОГ</span>
    </nav>

    <div class="blog-categories-nav">
        <button type="button" class="category-filter active" data-category="all">{{ __('blog.all_posts') }}</button>
        @foreach($categories as $cat)
            <button type="button" class="category-filter" data-category="{{ $cat->id }}">
                {{ mb_strtoupper($cat->getTranslation('name', $locale)) }}
            </button>
        @endforeach
    </div>
</div>

@if($featuredPost)
    @php
        $fs = $featuredPost->getTranslation('slug', $locale) ?: (string) reset($featuredPost->getTranslations('slug'));
    @endphp
    <div class="blog-featured" data-category="{{ $featuredPost->blog_category_id ?? '' }}">
        <div class="container">
            <div class="featured-article">
                <div class="featured-content">
                    <div class="featured-header-meta">
                        <div class="featured-tag">{{ $featuredPost->category?->getTranslation('name', $locale) ?? '—' }}</div>
                        <div class="featured-date">
                            <span class="date-label">дата публікації:</span>
                            <span class="date-value">{{ $featuredPost->publicDisplayDate()->format('d.m.Y') }}</span>
                        </div>
                    </div>
                    <h1 class="featured-title">{{ $featuredPost->getTranslation('title', $locale) }}</h1>
                    <p class="featured-description">{{ strip_tags($featuredPost->getTranslation('excerpt', $locale) ?? '') }}</p>
                    <div class="featured-footer">
                        <div class="featured-author">
                            автор:
                            @if($featuredPost->author)
                                <a href="{{ route('blog.author', $featuredPost->author) }}">{{ $featuredPost->author->getTranslation('name', $locale) }}</a>
                            @endif
                        </div>
                        <a href="{{ route('blog.show', ['blog_post' => $fs]) }}" class="featured-read-btn">
                            {{ __('blog.read') }}
                            <img src="{{ asset('assets/img/icons/arrow_outward.png') }}" alt="" class="featured-read-icon">
                        </a>
                    </div>
                </div>
                <div class="featured-image">
                    @if($featuredPost->hasCover())
                        <picture>
                            <source media="(max-width: 768px)" srcset="{{ $featuredPost->coverUrlSmall() }}">
                            <img src="{{ $featuredPost->coverUrlArticle() }}" alt="" class="blog-cover-img blog-cover-img--featured" loading="lazy" width="630" height="355" decoding="async">
                        </picture>
                    @else
                        <div class="image-placeholder">Featured Image</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

<div class="blog-latest" id="blog-latest">
    <div class="container blog-latest__inner">
        <h2 class="section-title blog-latest__title">ОСТАННІ ПУБЛІКАЦІЇ</h2>
        <div class="latest-slider-wrapper blog-latest__slider">
            <div class="articles-grid articles-grid-latest" id="latest-slider">
                @forelse($latestPosts as $post)
                    @include('base.pages.blog.partials.post-card', ['post' => $post, 'variant' => 'latest', 'latestIsLead' => $loop->first])
                @empty
                    <p class="text-center">{{ __('blog.no_posts') }}</p>
                @endforelse
            </div>
            <ul class="latest-slider-dots" role="presentation"></ul>
        </div>
        <a href="#" class="section-link js-blog-show-all-publications blog-latest__all-link">{{ __('blog.all_publications') }}</a>
    </div>
</div>

<div class="blog-categories">
    @foreach($categories as $cat)
        @php
            $catPosts = $postsByCategory[$cat->id] ?? collect();
        @endphp
        @if($catPosts->isNotEmpty())
        <section id="blog-category-{{ $cat->id }}" class="category-section" data-category="{{ $cat->id }}">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">{{ mb_strtoupper($cat->getTranslation('name', $locale)) }}</h2>
                    @if(($categoryPublishedCounts[$cat->id] ?? 0) > 3)
                        <a href="#" class="section-link js-blog-filter-to-category" data-category="{{ $cat->id }}">{{ __('blog.more_publications') }}</a>
                    @endif
                </div>
                <div class="category-slider-wrapper">
                    <div class="articles-grid articles-grid-2">
                        @foreach($catPosts as $post)
                            @include('base.pages.blog.partials.post-card', ['post' => $post])
                        @endforeach
                    </div>
                    <ul class="category-slider-dots" data-category="{{ $cat->id }}" aria-hidden="true"></ul>
                </div>
            </div>
        </section>
        @endif
    @endforeach
</div>

@push('scripts')
<script src="{{mix('build/js/blog.js')}}"></script>
@endpush

@endsection
