<div class="splide home-banner wrap" id="topBannersSlider">
    <div class="splide__track">
        <ul class="splide__list">
            @foreach($banners as $i => $banner)
                <li class="splide__slide swiper-slide home-banner-item">
                    <a href="{{ $banner->url }}">
                        <img
                            @if ($i === 0)
                            fetchpriority="high"
                            decoding="async"
                            @else
                            loading="lazy"
                            decoding="async"
                            @endif
                            src="{{ $banner->getPreviewImage() }}"
                            width="1312"
                            height="450"
                            alt="{{ $banner->title ?? '#' }}"
                            title="{{ $banner->title ?? '' }}">
                    </a>
                </li>
            @endforeach
        </ul>
    </div>


    <div class="home-banner-bott flex-justify">
        <ul class="splide__pagination"></ul>
        <div class="splide__arrows home-slide-buttons">
        </div>
    </div>

</div>


