@extends('base.layouts.app')

@php
    $locale = app()->getLocale();
@endphp

@section('title', $pageTitle ?? ($blog_author->getTranslation('name', $locale) . ' | Автор | Блог | Wrap.Shop'))
@section('description', $pageDescription ?? __('blog.author_meta', ['name' => $blog_author->getTranslation('name', $locale)]))

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
    <span>АВТОР</span>
</nav>

<div class="blog-article-content blog-author-page">
    <div class="container">
        <div class="article-main-wrapper">

            <aside class="article-categories-sidebar author-sidebar">
                <div class="author-card">
                    <div class="author-avatar">
                        <img
                            src="{{ $blog_author->avatarUrl() }}"
                            alt="{{ $blog_author->getTranslation('name', $locale) }}"
                            width="96"
                            height="96">
                    </div>

                    <div class="author-meta">
                        <div class="author-name">{{ $blog_author->getTranslation('name', $locale) }}</div>
                        @if($blog_author->getTranslation('role', $locale))
                            <div class="author-role">{{ $blog_author->getTranslation('role', $locale) }}</div>
                        @endif
                    </div>

                    @if($blog_author->getTranslation('bio', $locale))
                        <div class="author-bio">{{ $blog_author->getTranslation('bio', $locale) }}</div>
                    @endif

                    @php
                        $rawSocials = $blog_author->socials ?? [];
                        $socials = [];
                        foreach ($rawSocials as $key => $url) {
                            if ($url === null || $url === '') {
                                continue;
                            }
                            $socials[\Illuminate\Support\Str::lower((string) $key)] = $url;
                        }
                        $authorSocialDefs = [
                            'instagram' => ['class' => 'fab fa-instagram', 'label' => 'Instagram'],
                            'telegram' => ['class' => 'fab fa-telegram-plane', 'label' => 'Telegram'],
                            'facebook' => ['class' => 'fab fa-facebook-f', 'label' => 'Facebook'],
                            'youtube' => ['class' => 'fab fa-youtube', 'label' => 'YouTube'],
                            'twitter' => ['class' => 'fab fa-twitter', 'label' => 'Twitter'],
                            'x' => ['class' => 'fab fa-twitter', 'label' => 'X'],
                            'linkedin' => ['class' => 'fab fa-linkedin-in', 'label' => 'LinkedIn'],
                            'tiktok' => ['class' => 'fab fa-tiktok', 'label' => 'TikTok'],
                            'website' => ['class' => 'fas fa-globe', 'label' => 'Website'],
                        ];
                    @endphp

                    @if(!empty($socials))
                        <div class="author-socials">
                            @foreach($authorSocialDefs as $key => $meta)
                                @if(!empty($socials[$key]))
                                    <a class="author-social"
                                       href="{{ $socials[$key] }}"
                                       target="_blank"
                                       rel="noreferrer noopener"
                                       aria-label="{{ $meta['label'] }}">
                                        <i class="{{ $meta['class'] }}" aria-hidden="true"></i>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </aside>

            <div class="article-main">
                <div class="article-header">
                    <h1 class="article-title">
                        <span class="colord">Статті</span> автора
                    </h1>
                    <div class="article-meta">
                        <span class="article-tag">БЛОГ</span>
                        <span class="article-date">
                            <span class="article-date-label">всього:</span>
                            <span class="article-date-value">{{ $posts->total() }}</span>
                        </span>
                    </div>
                </div>

                <div class="author-articles">
                    @foreach($posts as $post)
                        @php
                            $slug = $post->getTranslation('slug', $locale) ?: (string) reset($post->getTranslations('slug'));
                        @endphp
                        <article class="author-article">
                            <div class="author-article-meta">
                                @if($post->category)
                                    <span class="article-tag">{{ $post->category->getTranslation('name', $locale) }}</span>
                                @endif
                                <span class="article-date">{{ $post->publicDisplayDate()->format('d.m.Y') }}</span>
                            </div>

                            <h2 class="author-article-title">
                                <a href="{{ route('blog.show', ['blog_post' => $slug]) }}">{{ $post->getTranslation('title', $locale) }}</a>
                            </h2>

                            @if($post->getTranslation('excerpt', $locale))
                                <p class="author-article-excerpt">{{ strip_tags($post->getTranslation('excerpt', $locale)) }}</p>
                            @endif

                            <div class="author-article-actions">
                                <a class="featured-read-btn" href="{{ route('blog.show', ['blog_post' => $slug]) }}">
                                    ЧИТАТИ
                                    <img src="{{ asset('assets/img/icons/arrow_outward.png') }}" alt="Перейти" class="featured-read-icon">
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $posts->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Минимальные стили только для страницы автора */
    .blog-author-page .author-card{
        padding:16px;
        border:1px solid rgba(255,255,255,.15);
        border-radius:12px;
        background:rgba(0,0,0,.08);
    }
    .blog-author-page .author-avatar img{
        border-radius:999px;
        object-fit:cover;
        display:block;
        margin: 0 auto;
        width: 96px !important;
        height: 96px !important;
    }
    .blog-author-page .author-name{ font-weight:700; font-size:18px; margin-top:12px; }
    .blog-author-page .author-role{ opacity:.8; margin-top:4px; }
    .blog-author-page .author-bio{ margin-top:12px; line-height:1.45; opacity:.95; }
    .blog-author-page .author-socials{ margin-top:12px; display:flex; gap:10px; flex-wrap:wrap; }
    .blog-author-page .author-social{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        width:36px;
        height:36px;
        border:1px solid rgba(255,255,255,.18);
        border-radius:10px;
        text-decoration:none;
    }
    .blog-author-page .author-social i{ font-size:18px; }
    .blog-author-page .author-articles{ display:flex; flex-direction:column; gap:18px; }
    .blog-author-page .author-article{ padding:16px; border:1px solid rgba(255,255,255,.12); border-radius:12px; }
    .blog-author-page .author-article-title{ margin:10px 0 6px; font-size:20px; }
    .blog-author-page .author-article-title a{ text-decoration:none; }
    .blog-author-page .author-article-excerpt{ opacity:.9; margin:0 0 12px; }
    .blog-author-page .author-article-meta{ display:flex; gap:12px; align-items:center; flex-wrap:wrap; }

    /* Светлая тема: бордеры/фон/текст */
    body.theme-light .blog-author-page .author-card{
        border-color: rgba(0,0,0,.12);
        background: rgba(255,255,255,.75);
    }
    body.theme-light .blog-author-page .author-name,
    body.theme-light .blog-author-page .author-role,
    body.theme-light .blog-author-page .author-bio{
        color: #212121;
    }
    body.theme-light .blog-author-page .author-role{ opacity:.75; }
    body.theme-light .blog-author-page .author-social{
        border-color: rgba(0,0,0,.14);
        background: rgba(255,255,255,.6);
    }
    body.theme-light .blog-author-page .author-article{
        border-color: rgba(0,0,0,.12);
        background: rgba(255,255,255,.65);
    }
    body.theme-light .blog-author-page .author-article-title a{
        color: #212121;
    }
    body.theme-light .blog-author-page .author-article-excerpt{
        color: rgba(33,33,33,.85);
        opacity: 1;
    }

    /* Тёмная тема: явные значения (на всякий случай) */
    body.theme-dark .blog-author-page .author-card{
        border-color: rgba(255,255,255,.15);
        background: rgba(0,0,0,.08);
    }
    body.theme-dark .blog-author-page .author-article{
        border-color: rgba(255,255,255,.12);
        background: transparent;
    }

    /* В тёмной теме явно подсвечиваем первое слово заголовка */
    body.theme-dark .blog-author-page .article-header .article-title .colord{
        color: #FFCE1C;
    }

    /* Растягиваем контент, чтобы не было пустого поля справа */
    .blog-author-page .article-main-wrapper{
        display:flex;
        align-items:flex-start;
        gap:24px;
        width:100%;
        padding: 0 20px;
        box-sizing: border-box;
    }
    .blog-author-page .author-sidebar{
        flex:0 0 300px;
    }
    .blog-author-page .article-main{
        flex:1 1 auto;
        min-width:0;
        max-width:none;
        width:100%;
    }
    @media (max-width: 991px){
        .blog-author-page .article-main-wrapper{ display:block; }
        .blog-author-page .author-sidebar{ margin-bottom:16px; }
        .blog-author-page .author-socials{ justify-content:center; }
    }

    /* Мобильные размеры карточек как на странице блога */
    @media screen and (max-width: 768px){
        .blog-author-page .author-article{
            padding:14px;
            border-radius:8px;
        }
        .blog-author-page .author-article-title{
            font-size:18px;
            line-height:1.4;
        }
        .blog-author-page .author-article-excerpt{
            font-size:14px;
            line-height:1.6;
        }
        /* Кнопка “ЧИТАТИ” на мобилке как в макете */
        .blog-author-page .author-article-actions{
            display:flex;
            justify-content:center;
        }
        .blog-author-page .author-article-actions .featured-read-btn{
            font-size:12px;
            min-height:35px;
            padding:10px 24px;
            width:115px;
            justify-content:center;
        }

        /* Header на мобилке: компактнее и без “ломающегося” выравнивания */
        .blog-author-page .article-header{
            display:flex;
            flex-direction:column;
            align-items:flex-start;
            gap:12px;
            margin-bottom:18px;
        }
        .blog-author-page .article-header .article-title{
            font-size:28px;
            line-height:1.1;
            margin:0;
        }
        /* На мобилке в header оставляем только заголовок */
        .blog-author-page .article-header .article-meta{
            display:none;
        }
    }
</style>

@endsection

