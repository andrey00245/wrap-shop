<div class="splide home-banner wrap" id="topBannersSlider">
    <div class="splide__track">
        <ul class="splide__list">
            @foreach($banners as $i => $banner)
                @php
                    $slideUrl = trim((string) ($banner->url ?? ''));
                    $buttonText = trim((string) ($banner->button_text ?? ''));
                    $buttonUrl = trim((string) ($banner->button_url ?? '')) ?: $slideUrl;
                    $bannerLabel = trim((string) ($banner->nav_title ?? $banner->title ?? '')) ?: 'Банер ' . ($i + 1);
                    $hasButton = $buttonText !== '' && $buttonUrl !== '';
                @endphp
                <li class="splide__slide swiper-slide home-banner-item">
                    <div class="home-banner__media">
                        <picture>
                            <source srcset="{{ $banner->getHeroImageUrl() }}" type="image/webp">
                            <img
                                @if ($i === 0)
                                fetchpriority="high"
                                decoding="async"
                                @else
                                loading="lazy"
                                decoding="async"
                                @endif
                                src="{{ $banner->getImage() ?: $banner->getHeroImageUrl() }}"
                                width="1325"
                                height="450"
                                alt="{{ $bannerLabel }}"
                                title="{{ $bannerLabel }}">
                        </picture>

                        @if($slideUrl !== '' && ! $hasButton)
                            <a href="{{ $slideUrl }}" class="home-banner__full-link" aria-label="{{ $bannerLabel }}"></a>
                        @endif

                        @if($buttonText !== '' && $buttonUrl !== '')
                            <a href="{{ $buttonUrl }}" class="home-banner__button">{{ $buttonText }}</a>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    </div>


    <div class="home-banner-bott">
        <ul class="splide__pagination"></ul>
    </div>

</div>


