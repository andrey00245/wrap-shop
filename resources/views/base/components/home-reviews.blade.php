@php
    $items = ($homeReviews ?? collect())->values();
    $itemsCount = $items->count();
    $displayCount = $itemsCount >= 6 ? intdiv($itemsCount, 6) * 6 : $itemsCount;
    $sliderItems = $items->take($displayCount);
    $chunks = $sliderItems->chunk(6);
    $useSlider = $chunks->count() > 1;
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
                                        <div class="home-reviews__grid">
                                            @php
                                                $chunkItems = collect($chunk)->values();
                                                $candidateIndexes = $chunkItems->keys()->filter(function ($idx) use ($chunkItems) {
                                                    $review = $chunkItems->get($idx);
                                                    return !empty($review?->photoUrl() ?: $review?->product?->getPreviewImage());
                                                })->values();

                                                $featuredFirstIdx = $candidateIndexes->get(0);
                                                $featuredFourthIdx = $candidateIndexes->get(1);
                                                $usedIndexes = collect([$featuredFirstIdx, $featuredFourthIdx])->filter(fn ($idx) => $idx !== null)->values();
                                                $remainingItems = $chunkItems->reject(function ($item, $idx) use ($usedIndexes) {
                                                    return $usedIndexes->contains($idx);
                                                })->values();

                                                $orderedItems = collect();
                                                $orderedItems->push($featuredFirstIdx !== null ? $chunkItems->get($featuredFirstIdx) : $remainingItems->shift());
                                                if ($remainingItems->isNotEmpty()) {
                                                    $orderedItems->push($remainingItems->shift());
                                                }
                                                if ($remainingItems->isNotEmpty()) {
                                                    $orderedItems->push($remainingItems->shift());
                                                }
                                                $orderedItems->push($featuredFourthIdx !== null ? $chunkItems->get($featuredFourthIdx) : $remainingItems->shift());
                                                $orderedItems = $orderedItems->filter();
                                                $orderedItems = $orderedItems->concat($remainingItems)->values();
                                            @endphp
                                            @foreach($orderedItems as $review)
                                                @php
                                                    $authorInitial = mb_strtoupper(mb_substr($review->name ?? 'A', 0, 1, 'UTF-8'));
                                                    $reviewImage = $review->photoUrl() ?: $review->product?->getPreviewImage();
                                                    $isFeatured = in_array($loop->index, [0, 3], true) && !empty($reviewImage);
                                                    $productUrl = $review->product ? route('products.show', ['product' => $review->product->slugEn]) : null;
                                                @endphp
                                                <article class="home-reviews-card {{ $isFeatured ? 'home-reviews-card--featured' : 'home-reviews-card--compact' }} {{ $productUrl ? 'home-reviews-card--clickable' : '' }}">
                                                    @if($isFeatured)
                                                        <div class="home-reviews-card__image">
                                                            @if($productUrl)
                                                                <a href="{{ $productUrl }}" class="home-reviews-card__image-link" title="{{ $review->product->getName() }}">
                                                                    <img src="{{ $reviewImage }}" alt="{{ $review->name }}" loading="lazy">
                                                                </a>
                                                            @else
                                                                <img src="{{ $reviewImage }}" alt="{{ $review->name }}" loading="lazy">
                                                            @endif
                                                        </div>
                                                    @endif
                                                    <div class="home-reviews-card__body">
                                                        <div class="home-reviews-card__top">
                                                            <div class="home-reviews-card__avatar"><span>{{ $authorInitial }}</span></div>
                                                            <div class="home-reviews-card__meta">
                                                                <div class="home-reviews-card__name">{{ $review->name }}</div>
                                                                <div class="home-reviews-card__rating">
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        <i class="{{ $i <= (int) $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                                                    @endfor
                                                                </div>
                                                                @if($review->product && $productUrl)
                                                                    <a href="{{ $productUrl }}" class="home-reviews-card__product home-reviews-card__product-link" title="{{ $review->product->getName() }}">
                                                                        {{ __('reviews.product_label') }}: {{ $review->product->getName() }}
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <p class="home-reviews-card__text">{{ $review->text }}</p>
                                                        <div class="home-reviews-card__date">{{ optional($review->created_at)->format('d.m.Y') }}</div>
                                                    </div>
                                                </article>
                                            @endforeach
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="home-reviews__mobile">
                    <div class="splide home-reviews__list home-reviews__list--mobile">
                        <div class="splide__arrows home-products-slide-buttons">
                            <div class="line"></div>
                        </div>
                        <div class="splide__track">
                            <ul class="splide__list">
                                @foreach($sliderItems as $review)
                                    <li class="splide__slide home-reviews__slide home-reviews__slide--mobile">
                                        @include('base.components.home-reviews-card-mobile', ['review' => $review])
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @else
                <div class="home-reviews__desktop">
                    <div class="home-reviews__list home-reviews__list--static home-reviews__list--desktop">
                        <div class="home-reviews__slide">
                            <div class="home-reviews__grid">
                                @php
                                    $chunkItems = collect($chunks->first())->values();
                                    $candidateIndexes = $chunkItems->keys()->filter(function ($idx) use ($chunkItems) {
                                        $review = $chunkItems->get($idx);
                                        return !empty($review?->photoUrl() ?: $review?->product?->getPreviewImage());
                                    })->values();

                                    $featuredFirstIdx = $candidateIndexes->get(0);
                                    $featuredFourthIdx = $candidateIndexes->get(1);
                                    $usedIndexes = collect([$featuredFirstIdx, $featuredFourthIdx])->filter(fn ($idx) => $idx !== null)->values();
                                    $remainingItems = $chunkItems->reject(function ($item, $idx) use ($usedIndexes) {
                                        return $usedIndexes->contains($idx);
                                    })->values();

                                    $orderedItems = collect();
                                    $orderedItems->push($featuredFirstIdx !== null ? $chunkItems->get($featuredFirstIdx) : $remainingItems->shift());
                                    if ($remainingItems->isNotEmpty()) {
                                        $orderedItems->push($remainingItems->shift());
                                    }
                                    if ($remainingItems->isNotEmpty()) {
                                        $orderedItems->push($remainingItems->shift());
                                    }
                                    $orderedItems->push($featuredFourthIdx !== null ? $chunkItems->get($featuredFourthIdx) : $remainingItems->shift());
                                    $orderedItems = $orderedItems->filter();
                                    $orderedItems = $orderedItems->concat($remainingItems)->values();
                                @endphp
                                @foreach($orderedItems as $review)
                                    @php
                                        $authorInitial = mb_strtoupper(mb_substr($review->name ?? 'A', 0, 1, 'UTF-8'));
                                        $reviewImage = $review->photoUrl() ?: $review->product?->getPreviewImage();
                                        $isFeatured = in_array($loop->index, [0, 3], true) && !empty($reviewImage);
                                        $productUrl = $review->product ? route('products.show', ['product' => $review->product->slugEn]) : null;
                                    @endphp
                                    <article class="home-reviews-card {{ $isFeatured ? 'home-reviews-card--featured' : 'home-reviews-card--compact' }} {{ $productUrl ? 'home-reviews-card--clickable' : '' }}">
                                        @if($isFeatured)
                                            <div class="home-reviews-card__image">
                                                @if($productUrl)
                                                    <a href="{{ $productUrl }}" class="home-reviews-card__image-link" title="{{ $review->product->getName() }}">
                                                        <img src="{{ $reviewImage }}" alt="{{ $review->name }}" loading="lazy">
                                                    </a>
                                                @else
                                                    <img src="{{ $reviewImage }}" alt="{{ $review->name }}" loading="lazy">
                                                @endif
                                            </div>
                                        @endif
                                        <div class="home-reviews-card__body">
                                            <div class="home-reviews-card__top">
                                                <div class="home-reviews-card__avatar"><span>{{ $authorInitial }}</span></div>
                                                <div class="home-reviews-card__meta">
                                                    <div class="home-reviews-card__name">{{ $review->name }}</div>
                                                    <div class="home-reviews-card__rating">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <i class="{{ $i <= (int) $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                                        @endfor
                                                    </div>
                                                    @if($review->product && $productUrl)
                                                        <a href="{{ $productUrl }}" class="home-reviews-card__product home-reviews-card__product-link" title="{{ $review->product->getName() }}">
                                                            {{ __('reviews.product_label') }}: {{ $review->product->getName() }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                            <p class="home-reviews-card__text">{{ $review->text }}</p>
                                            <div class="home-reviews-card__date">{{ optional($review->created_at)->format('d.m.Y') }}</div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="home-reviews__mobile">
                    <div class="home-reviews__list home-reviews__list--mobile home-reviews__list--mobile-static">
                        @foreach($orderedItems as $review)
                            @include('base.components.home-reviews-card-mobile', ['review' => $review])
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    @include('base.components.home-reviews-popup')
@endif
