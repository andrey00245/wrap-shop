@php
    $items = ($homeReviews ?? collect())->values();
    $itemsCount = $items->count();
    $displayCount = $itemsCount >= 6 ? intdiv($itemsCount, 6) * 6 : $itemsCount;
    $sliderItems = $items->take($displayCount);
    $mobileItems = $sliderItems
        ->filter(fn ($review) => filled($review->photoUrl()) || filled($review->product?->getPreviewImage()))
        ->values();
    $chunks = $sliderItems->chunk(6);
    $useSlider = $chunks->count() > 1;
    $useMobileSlider = $mobileItems->count() > 1;
@endphp

@if($sliderItems->isNotEmpty())
    <section class="home-reviews row" id="homeReviews">
        <div class="wrap">
            <div class="home-reviews__head flex-justify">
                <h2 class="home-title"><span class="colord">{{ __('reviews.heading_accent') }}</span> {{ __('reviews.heading_rest') }}</h2>
                <button type="button" class="home-reviews__open button general-popup-btn" data-popup="home-review-popup">
                    {{ __('popup.reviews_popup.leave_review') }}
                </button>
            </div>

            @if($useSlider)
                <div class="home-reviews__desktop">
                    <div class="splide home-reviews__list home-reviews__list--desktop">
                        <div class="splide__arrows home-products-slide-buttons">
                            <div class="line"></div>
                        </div>
                        <div class="splide__track">
                            <ul class="splide__list">
                                @foreach($chunks as $chunk)
                                    <li class="splide__slide home-reviews__slide">
                                        @include('base.components.home-reviews-grid', ['chunkItems' => $chunk])
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="home-reviews__mobile">
                    @if($mobileItems->isNotEmpty())
                        @if($useMobileSlider)
                    <div class="splide home-reviews__list home-reviews__list--mobile">
                        <div class="splide__arrows home-products-slide-buttons">
                            <div class="line"></div>
                        </div>
                        <div class="splide__track">
                            <ul class="splide__list">
                                @foreach($mobileItems as $review)
                                    <li class="splide__slide home-reviews__slide home-reviews__slide--mobile">
                                        @include('base.components.home-reviews-card-mobile', ['review' => $review])
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                        @else
                    <div class="home-reviews__list home-reviews__list--mobile home-reviews__list--mobile-static">
                        @foreach($mobileItems as $review)
                            @include('base.components.home-reviews-card-mobile', ['review' => $review])
                        @endforeach
                    </div>
                        @endif
                    @endif
                </div>
            @else
                <div class="home-reviews__desktop">
                    <div class="home-reviews__list home-reviews__list--static home-reviews__list--desktop">
                        <div class="home-reviews__slide">
                            @include('base.components.home-reviews-grid', ['chunkItems' => $chunks->first()])
                        </div>
                    </div>
                </div>

                <div class="home-reviews__mobile">
                    @if($mobileItems->isNotEmpty())
                    <div class="home-reviews__list home-reviews__list--mobile home-reviews__list--mobile-static">
                        @foreach($mobileItems as $review)
                            @include('base.components.home-reviews-card-mobile', ['review' => $review])
                        @endforeach
                    </div>
                    @endif
                </div>
            @endif
        </div>
    </section>

    @include('base.components.home-reviews-popup')
@endif
