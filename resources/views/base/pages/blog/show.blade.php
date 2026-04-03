@extends('base.layouts.app')

@php
    $locale = app()->getLocale();
    $slug = $blog_post->getTranslation('slug', $locale) ?: (string) reset($blog_post->getTranslations('slug'));
@endphp

@section('title', $articleTitle)
@section('description', $articleDescription)
@section('og_type', 'article')
@section('og_title', $blog_post->getTranslation('title', $locale))
@section('og_description', $articleDescription)
@section('og_image', $blog_post->coverUrlOg() ?: url('assets/img/og-default.jpg'))
@section('canonical', url()->current())

@push('head_links')
    <x-blog-json-ld :post="$blog_post" />
@endpush

@push('styles')
    @if($theme ==='dark')
        <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-blog-dark.css')}}">
    @else
        <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-blog-light.css')}}">
    @endif
    <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-blog.css')}}">
@endpush

@section('content')

<nav class="blog-breadcrumbs">
    <a href="{{route('index')}}">ГОЛОВНА</a>
    <span>/</span>
    <a href="{{route('blog.index')}}">БЛОГ</a>
    <span>/</span>
    <span>{{ mb_strtoupper(\Illuminate\Support\Str::limit($blog_post->getTranslation('title', $locale), 48)) }}</span>
</nav>

<div class="blog-article-hero{{ $blogCatalogNavRoot ? ' blog-article-hero--with-catalog-nav' : '' }}">
    @if($blog_post->hasCover())
        <picture>
            <source media="(max-width: 768px)" srcset="{{ $blog_post->coverUrlSmall() }}">
            <img src="{{ $blog_post->coverUrlHero() }}" alt="" class="blog-cover-img blog-cover-img--article-hero" width="1132" height="334" decoding="async">
        </picture>
    @else
        <div class="hero-image-placeholder">Hero Image</div>
    @endif
</div>

<div class="blog-article-content">
    <div class="container">
        <div class="article-main-wrapper{{ $blogCatalogNavRoot ? ' article-main-wrapper--with-product-catalog-nav' : '' }}{{ $categorySidebarPosts->isNotEmpty() ? ' article-main-wrapper--with-category-sidebar' : '' }}">
            @if($blogCatalogNavRoot)
                <aside class="article-categories-sidebar" aria-label="{{ __('blog.catalog_sidebar_label') }}">
                    <div class="categories-accordion">
                        <div class="accordion-item">
                            <button type="button" class="accordion-header active">
                                <span>{{ mb_strtoupper($blogCatalogNavRoot->getTranslation('name', $locale) ?: $blogCatalogNavRoot->getTranslation('name', 'uk')) }}</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="accordion-content active">
                                <ul class="accordion-list">
                                    @foreach($blogCatalogSidebarCategories as $cat)
                                        @php $path = $cat->catalogPath($locale); @endphp
                                        @if($path !== '')
                                            <li><a href="{{ route('products.category', ['path' => $path]) }}">{{ $cat->getTranslation('name', $locale) }}</a></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </aside>
            @endif

            <div class="article-main">
                <div class="article-content-card">
                    @php
                        $articleHeading = $blog_post->getTranslation('title', $locale);
                        $headingParts = preg_split('/\s+/u', $articleHeading, 2, PREG_SPLIT_NO_EMPTY);
                        $headingFirst = $headingParts[0] ?? $articleHeading;
                        $headingRest = isset($headingParts[1]) ? ' '.$headingParts[1] : '';
                    @endphp
                    <div class="article-header">
                        <h1 class="article-title"><span class="colord article-title-accent">{{ $headingFirst }}</span>@if($headingRest !== '')<span class="article-title-plain">{{ $headingRest }}</span>@endif</h1>
                        <div class="article-meta">
                            @if($blog_post->category)
                                <span class="article-tag">{{ mb_strtoupper($blog_post->category->getTranslation('name', $locale)) }}</span>
                            @endif
                            <div class="article-meta-right">
                                <span class="article-date">
                                    <span class="article-date-label">дата публікації:</span>
                                    <span class="article-date-value">{{ $blog_post->publicDisplayDate()->format('d.m.Y') }}</span>
                                </span>
                                @if($blog_post->author)
                                    <span class="article-date">
                                        <span class="article-date-label">автор:</span>
                                        <span class="article-date-value">
                                            <a href="{{ route('blog.author', $blog_post->author) }}">{{ $blog_post->author->getTranslation('name', $locale) }}</a>
                                        </span>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="article-body blog-post-body">
                        {!! $blog_post->bodyHtml() !!}
                    </div>

                    @if(session('status'))
                        <div class="alert alert-success blog-alert blog-alert--success">{{ session('status') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger blog-alert blog-alert--danger">
                            <ul class="blog-alert-list">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    @endif

                    @if($blog_post->comments_enabled)
                        @php
                            $commentFormOpen = $errors->any()
                                || filled(old('guest_name'))
                                || filled(old('guest_email'))
                                || filled(old('body'));
                        @endphp
                        <section class="blog-comments">
                            <h2 class="blog-comments-title">{{ __('blog.comments_title') }}@if(($blog_post->approved_comments_count ?? 0) > 0) ({{ $blog_post->approved_comments_count }})@endif</h2>
                            @foreach($comments as $comment)
                                <div class="blog-comment-item">
                                    <div class="blog-comment-item__head">
                                        <div class="blog-comment-item__author">
                                            <span class="blog-comment-item__avatar" aria-hidden="true"><i class="fas fa-user"></i></span>
                                            <strong class="blog-comment-item__name">{{ $comment->guest_name }}</strong>
                                        </div>
                                        <span class="blog-comment-item__date">{{ $comment->created_at?->format('d.m.Y') }}</span>
                                    </div>
                                    <p class="blog-comment-item__body">{{ $comment->body }}</p>
                                    @foreach($comment->replies as $reply)
                                        <div class="blog-comment-reply">
                                            <div class="blog-comment-item__author blog-comment-item__author--reply">
                                                <span class="blog-comment-item__avatar" aria-hidden="true"><i class="fas fa-user"></i></span>
                                                <strong class="blog-comment-item__name">{{ $reply->guest_name }}</strong>
                                            </div>
                                            <p class="blog-comment-item__body blog-comment-item__body--small">{{ $reply->body }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

                            <div class="blog-comment-form-accordion">
                                <button type="button"
                                        class="blog-comment-form-toggle"
                                        id="blog-comment-form-toggle"
                                        aria-expanded="{{ $commentFormOpen ? 'true' : 'false' }}"
                                        aria-controls="blog-comment-form-panel"
                                        @if($commentFormOpen) hidden @endif>
                                    {{ __('blog.leave_comment') }}
                                </button>
                                <div class="blog-comment-form-panel"
                                     id="blog-comment-form-panel"
                                     role="region"
                                     aria-labelledby="blog-comment-form-toggle"
                                     @unless($commentFormOpen) hidden @endunless>
                                    <div class="blog-comment-form-panel__header">
                                        <button type="button"
                                                class="blog-comment-form-close"
                                                id="blog-comment-form-close"
                                                aria-label="{{ __('blog.close_comment_form') }}">
                                            <i class="fas fa-times" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <form method="post" action="{{ route('blog.comments.store', ['blog_post' => $slug]) }}" class="blog-comment-form">
                                        @csrf
                                        <input type="text" name="comment_hp" value="" tabindex="-1" autocomplete="off" class="blog-comment-form__honeypot" aria-hidden="true">
                                        <div class="blog-comment-form__field">
                                            <label class="blog-comment-form__label" for="blog-comment-name">
                                                {{ __('blog.name') }}
                                                <abbr class="blog-comment-form__req" title="{{ __('blog.required_field') }}">*</abbr>
                                            </label>
                                            <input id="blog-comment-name" type="text" name="guest_name" class="blog-comment-form__input" required value="{{ old('guest_name') }}" maxlength="120" autocomplete="name">
                                        </div>
                                        <div class="blog-comment-form__field">
                                            <label class="blog-comment-form__label" for="blog-comment-email">
                                                {{ __('blog.email') }}
                                                <abbr class="blog-comment-form__req" title="{{ __('blog.required_field') }}">*</abbr>
                                            </label>
                                            <input id="blog-comment-email" type="email" name="guest_email" class="blog-comment-form__input" required value="{{ old('guest_email') }}" autocomplete="email">
                                        </div>
                                        <div class="blog-comment-form__field">
                                            <label class="blog-comment-form__label" for="blog-comment-body">
                                                {{ __('blog.message') }}
                                                <abbr class="blog-comment-form__req" title="{{ __('blog.required_field') }}">*</abbr>
                                            </label>
                                            <textarea id="blog-comment-body" name="body" class="blog-comment-form__textarea" rows="5" required maxlength="8000">{{ old('body') }}</textarea>
                                        </div>
                                        <button type="submit" class="blog-comment-form__submit">{{ __('blog.send') }}</button>
                                    </form>
                                </div>
                            </div>
                        </section>
                    @endif
                </div>
            </div>

            @if($categorySidebarPosts->isNotEmpty())
                <aside class="article-sidebar" aria-label="{{ __('blog.sidebar_category') }}">
                    <div class="sidebar-section">
                        <h2 class="sidebar-title">{{ __('blog.sidebar_category') }}</h2>
                        <div class="sidebar-articles">
                            @foreach($categorySidebarPosts as $cp)
                                @php
                                    $cs = $cp->getTranslation('slug', $locale) ?: (string) reset($cp->getTranslations('slug'));
                                    $ex = trim(strip_tags((string) ($cp->getTranslation('excerpt', $locale) ?? '')));
                                @endphp
                                <a href="{{ route('blog.show', ['blog_post' => $cs]) }}" class="sidebar-article">
                                    <div class="sidebar-article-meta">
                                        @if($cp->category)
                                            <span class="sidebar-article-tag">{{ mb_strtoupper($cp->category->getTranslation('name', $locale)) }}</span>
                                        @endif
                                        <span class="sidebar-article-date">{{ $cp->publicDisplayDate()->format('d.m.Y') }}</span>
                                    </div>
                                    <h3 class="sidebar-article-title">{{ \Illuminate\Support\Str::limit($cp->getTranslation('title', $locale), 110) }}</h3>
                                    @if($ex !== '')
                                        <p class="sidebar-article-excerpt">{{ \Illuminate\Support\Str::limit($ex, 160) }}</p>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </aside>
            @endif
        </div>
    </div>
</div>

@if($products->isNotEmpty())
    @include('base.pages.blog.partials.product-slider', [
        'products' => $products,
        'productsSectionTitle' => $blogProductsSectionTitle,
        'productsUseSlider' => $products->count() >= 3,
    ])
@endif

@if($similarPosts->isNotEmpty())
@php
    $similarUseSlider = $similarPosts->count() >= 3;
@endphp
<div class="blog-similar{{ $similarUseSlider ? '' : ' blog-similar--static' }}">
    <div class="container">
        <h2 class="section-title">{{ __('blog.similar') }}</h2>
        <div class="blog-similar-wrapper">
            @if($similarUseSlider)
                <div class="splide home-products-list" id="blogSimilarSlider">
                    <div class="splide__arrows home-products-slide-buttons">
                        <div class="line"></div>
                    </div>
                    <div class="splide__track">
                        <ul class="splide__list">
                            @foreach($similarPosts as $post)
                                <li class="splide__slide">
                                    @include('base.pages.blog.partials.post-card', ['post' => $post])
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <ul class="blog-similar-slider-dots mobile-only" aria-hidden="true"></ul>
                </div>
            @else
                <div class="blog-similar-static-grid">
                    @foreach($similarPosts as $post)
                        @include('base.pages.blog.partials.post-card', ['post' => $post])
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endif

@push('scripts')
<script src="{{mix('build/js/blog.js')}}"></script>
@endpush

@endsection
