@php
  /** @var \App\Models\HomeBlock $block */
  $items = $block->items->filter(function ($item) {
      return filled($item->bannerImageUrl());
  })->values();
@endphp

@if($items->isNotEmpty())
  @php
    $headingPlain = trim(strip_tags((string) $block->title));
    $splideId = 'homeBlockBannerSplide-'.$block->id;
    $useSlider = $items->count() > 1;
  @endphp
  <section class="home-block-banner row" id="homeBlockBanner{{ $block->id }}" aria-labelledby="home-block-banner-h-{{ $block->id }}">
    <div class="wrap">
      @if($headingPlain !== '')
        <div class="home-products-top flex-justify">
          <h2 id="home-block-banner-h-{{ $block->id }}" class="home-title">{!! $block->title !!}</h2>
        </div>
      @endif

      @if($useSlider)
        <div id="{{ $splideId }}" class="splide home-block-banner__list home-products-list js-home-block-banner-splide">
          <div class="splide__arrows home-products-slide-buttons">
            <div class="line"></div>
          </div>
          <div class="splide__track">
            <ul class="splide__list">
              @foreach($items as $item)
                @php
                  $slideImage = $item->bannerImageUrl();
                  $slideTitle = trim($item->displayTitle());
                  $slideButtonText = trim($item->displayTagline()) ?: __('general-translate.view');
                  $slideUrl = $item->bannerButtonUrl();
                @endphp
                <li class="splide__slide home-block-banner__slide">
                  <div class="home-block-banner__card">
                    <img src="{{ $slideImage }}" alt="{{ $slideTitle }}" class="home-block-banner__image" loading="lazy" width="1325" height="541" decoding="async">
                    <span class="home-block-banner__overlay"></span>
                    <span class="home-block-banner__content">
                      <span class="home-block-banner__title">{{ $slideTitle }}</span>
                      <a href="{{ $slideUrl ?: '#' }}" class="home-block-banner__button" title="{{ $slideTitle }}" @if(! $slideUrl) aria-disabled="true" @endif>{{ $slideButtonText }}</a>
                    </span>
                  </div>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      @else
        <div class="home-block-banner__static">
          @foreach($items as $item)
            @php
              $slideImage = $item->bannerImageUrl();
              $slideTitle = trim($item->displayTitle());
              $slideButtonText = trim($item->displayTagline()) ?: __('general-translate.view');
              $slideUrl = $item->bannerButtonUrl();
            @endphp
            <div class="home-block-banner__slide home-block-banner__slide--static">
              <div class="home-block-banner__card">
                <img src="{{ $slideImage }}" alt="{{ $slideTitle }}" class="home-block-banner__image" loading="lazy" width="1325" height="541" decoding="async">
                <span class="home-block-banner__overlay"></span>
                <span class="home-block-banner__content">
                  <span class="home-block-banner__title">{{ $slideTitle }}</span>
                  <a href="{{ $slideUrl ?: '#' }}" class="home-block-banner__button" title="{{ $slideTitle }}" @if(! $slideUrl) aria-disabled="true" @endif>{{ $slideButtonText }}</a>
                </span>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </section>
@endif
