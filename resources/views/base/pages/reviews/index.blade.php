@extends('base.layouts.app')

@php
    $locale = app()->getLocale();
    $pageTitle = __('reviews.title') . ' | Wrap.Shop';
    $pageDescription = __('reviews.description');
@endphp

@section('title', $pageTitle)
@section('description', $pageDescription)
@section('keywords', __('reviews.keywords'))
@section('og_type', 'website')
@section('og_title', $pageTitle)
@section('og_description', $pageDescription)
@section('og_image', url('assets/img/og-default.jpg'))
@section('og_url', url()->current())
@section('twitter_card', 'summary_large_image')
@section('twitter_title', $pageTitle)
@section('twitter_description', $pageDescription)
@section('twitter_image', url('assets/img/og-default.jpg'))
@section('canonical', url()->current())

@push('styles')
    @if($theme ==='dark')
        <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-reviews-dark.css')}}">
    @else
        <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-reviews-light.css')}}">
    @endif
@endpush

@section('content')

    <nav class="breadcrumbs wrap row">
        <ul class="flex-center">
            <li>
                <a href="{{route('index')}}" title="{{__('header_footer.home')}}" class="button">{{__('header_footer.home')}}</a>
            </li>
            <li>
                <span class="button">{{__('reviews.title')}}</span>
            </li>
        </ul>
    </nav>

    <section class="wrap reviews-page">
        <h1 class="default-title reviews-page__title">
            <span class="highlight">{{ __('reviews.heading_accent') }}</span> {{ __('reviews.heading_rest') }}
        </h1>

        @if(($totalReviews ?? 0) > 0)
            <div class="reviews-summary">
                <div class="reviews-summary__left">
                    <div class="reviews-summary__score">
                        {{ number_format($averageRating, 1) }}<span>/5</span>
                    </div>
                    <div>
                        <div class="reviews-summary__stars">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= round($averageRating) ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                        </div>
                        <div class="reviews-summary__text">
                            {{ __('reviews.summary_based_on', ['count' => $totalReviews]) }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($reviews->count())
            <div class="reviews-grid">
                @foreach($reviews as $review)
                        @php
                            $product = $review->product;
                            $productName = $product?->getName();
                            $productImage = $product?->getPreviewImage();
                            $initial = mb_substr($review->name, 0, 1, 'UTF-8');
                        @endphp
                        <article class="reviews-card">
                            @if($productImage)
                                <div class="reviews-card__image">
                                    <img src="{{ $productImage }}" alt="{{ $productName }}" loading="lazy">
                                </div>
                            @endif
                            <div class="reviews-card__content">
                                <div class="reviews-card__header">
                                    <div class="reviews-card__avatar">
                                        <span>{{ $initial }}</span>
                                    </div>
                                    <div class="reviews-card__meta">
                                        <div class="reviews-card__name">{{ $review->name }}</div>
                                        @if($product && $productName)
                                            <div class="reviews-card__product">
                                                {{ __('reviews.product_label') }}:
                                                <a href="{{ route('products.show', ['product' => $product->slugEn]) }}"
                                                   class="reviews-card__product-link">
                                                    {{ $productName }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="reviews-card__rating">
                                    @php $reviewStars = \App\Models\Product::reviewRatingStarsCount((float) $review->rating); @endphp
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="{{ $i <= $reviewStars ? 'fas' : 'far' }} fa-star"></i>
                                    @endfor
                                </div>

                                <div class="reviews-card__text">
                                    {{ $review->text }}
                                </div>

                                <div class="reviews-card__footer">
                                    <span class="reviews-card__date">{{ $review->created_at->format('d.m.Y') }}</span>
                                </div>
                            </div>
                        </article>
                @endforeach
            </div>

            <div class="reviews-page__pagination">
                {{ $reviews->links() }}
            </div>
        @else
            <p class="text-center">{{ __('reviews.empty') }}</p>
        @endif
    </section>
@endsection

@push('fixed-catalog')
    @include('base.components.categories-catalog')
@endpush

