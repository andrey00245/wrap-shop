@php
  $homeNewsItems = collect($homeNews ?? [])->filter(fn ($news) => $news->category && filled($news->slugEn) && $news->getMedia('main')->isNotEmpty());
  $homeNewsItems = $homeNewsItems->take(10)->values();
  $useSlider = $homeNewsItems->count() > 4;
@endphp

@if($homeNewsItems->isNotEmpty())
  <section class="home-news row" id="homeNews">
    <div class="wrap">
      <div class="home-products-top flex-justify">
        <h2 class="home-title">{!! __('news.home_heading') !!}</h2>
        <a href="{{ route('news.index') }}" class="home-news__all home-reviews__open button general-popup-btn">{{ __('news.view_all') }}</a>
      </div>

      @if($useSlider)
        <div class="splide home-news__list home-products-list">
          <div class="splide__arrows home-products-slide-buttons">
            <div class="line"></div>
          </div>
          <div class="splide__track">
            <ul class="splide__list">
              @foreach($homeNewsItems as $news)
                @php
                  $mainMedia = $news->getMedia('main')->first();
                  $newsImage = $mainMedia?->hasGeneratedConversion('preview_webp')
                    ? $mainMedia->getUrl('preview_webp')
                    : ($mainMedia?->getUrl('preview') ?: $mainMedia?->getUrl());
                  $newsUrl = route('news.show', ['news_category' => $news->category->slugEn, 'news' => $news->slugEn]);
                  $newsDate = \Carbon\Carbon::parse($news->created_at)->format('d.m.Y');
                  $newsTitle = trim((string) $news->title);
                  $newsDescription = $news->plainDescription(320);
                @endphp
                <li class="splide__slide home-news__slide">
                  <a href="{{ $newsUrl }}" class="home-news-card" title="{{ $newsTitle }}">
                    <img src="{{ $newsImage }}" alt="{{ $newsTitle }}" class="home-news-card__image" loading="lazy">
                    <div class="home-news-card__body">
                      <div class="home-news-card__title">{{ $newsTitle }}</div>
                      <div class="home-news-card__text">{{ $newsDescription }}</div>
                      <div class="home-news-card__bottom">
                        <div class="home-news-card__date">
                          <i class="fal fa-calendar-alt"></i>
                          <span>{{ $newsDate }}</span>
                        </div>
                        <svg class="home-news-card__arrow" viewBox="0 0 14 14" aria-hidden="true" focusable="false">
                          <path d="M3 11L11 3"></path>
                          <path d="M5 3H11V9"></path>
                        </svg>
                      </div>
                    </div>
                  </a>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      @else
        <div class="home-news__grid">
          @foreach($homeNewsItems as $news)
            @php
              $mainMedia = $news->getMedia('main')->first();
              $newsImage = $mainMedia?->hasGeneratedConversion('preview_webp')
                ? $mainMedia->getUrl('preview_webp')
                : ($mainMedia?->getUrl('preview') ?: $mainMedia?->getUrl());
              $newsUrl = route('news.show', ['news_category' => $news->category->slugEn, 'news' => $news->slugEn]);
              $newsDate = \Carbon\Carbon::parse($news->created_at)->format('d.m.Y');
              $newsTitle = trim((string) $news->title);
              $newsDescription = $news->plainDescription(320);
            @endphp
            <a href="{{ $newsUrl }}" class="home-news-card" title="{{ $newsTitle }}">
              <img src="{{ $newsImage }}" alt="{{ $newsTitle }}" class="home-news-card__image" loading="lazy">
              <div class="home-news-card__body">
                <div class="home-news-card__title">{{ $newsTitle }}</div>
                <div class="home-news-card__text">{{ $newsDescription }}</div>
                <div class="home-news-card__bottom">
                  <div class="home-news-card__date">
                    <i class="fal fa-calendar-alt"></i>
                    <span>{{ $newsDate }}</span>
                  </div>
                  <svg class="home-news-card__arrow" viewBox="0 0 14 14" aria-hidden="true" focusable="false">
                    <path d="M3 11L11 3"></path>
                    <path d="M5 3H11V9"></path>
                  </svg>
                </div>
              </div>
            </a>
          @endforeach
        </div>
      @endif
    </div>
  </section>
@endif
