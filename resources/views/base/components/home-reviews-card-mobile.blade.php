@php
    $authorInitial = mb_strtoupper(mb_substr($review->name ?? 'A', 0, 1, 'UTF-8'));
    $reviewImage = $review->photoUrl() ?: $review->product?->getPreviewImage();
    $productUrl = $review->product ? route('products.show', ['product' => $review->product->slugEn]) : null;
    $hasImage = !empty($reviewImage);
@endphp
<article class="home-reviews-card home-reviews-card--mobile {{ $productUrl ? 'home-reviews-card--clickable' : '' }}">
    @if($hasImage)
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
