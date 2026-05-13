@php
  /** @var \App\Models\HomeBlockItem $item */
  $url = $item->catalogUrl();
  $imgSrc = $item->tileImageUrl($large);
  if ($imgSrc === '') {
      $imgSrc = \App\Models\HomeBlockItem::tileFallbackImageUrl();
  }
  $quickLinks = $large
      ? $item->quickLinks->filter(fn ($l) => $l->category_id && $l->category)
      : collect();
  $validQuickLinks = $quickLinks->filter(fn ($l) => filled($l->catalogUrl()))->values();
  $qlCount = $validQuickLinks->count();
  $lastRowCount = $qlCount > 0 ? (($qlCount % 2 === 1) ? 1 : 2) : 0;
  $restLinks = $qlCount > $lastRowCount ? $validQuickLinks->take($qlCount - $lastRowCount) : collect();
  $lastRowLinks = $qlCount > 0 ? $validQuickLinks->slice(-$lastRowCount)->values() : collect();
@endphp
@if($url)
  <article class="home-block__tile {{ $large ? 'home-block__tile--large' : 'home-block__tile--small' }}{{ $validQuickLinks->isNotEmpty() ? ' home-block__tile--has-quicklinks' : '' }}">
    <img
      class="home-block__img"
      src="{{ $imgSrc }}"
      alt=""
      loading="lazy"
      decoding="async"
      width="{{ $large ? 720 : 640 }}"
      height="{{ $large ? 1080 : 400 }}"
    >
    <div class="home-block__overlay" aria-hidden="true"></div>
    <a href="{{ $url }}" class="home-block__tile-link"><span class="visually-hidden">{{ $item->displayTitle() }}</span></a>
    <div class="home-block__content {{ $large ? '' : 'home-block__content--small' }}">
      <span class="home-block__title {{ $large ? '' : 'home-block__title--small' }}">{{ $item->displayTitle() }}</span>
      @if($lastRowLinks->isNotEmpty())
        <div class="home-block__quick-links">
          @foreach($restLinks as $link)
            @php $linkUrl = $link->catalogUrl(); @endphp
            <a href="{{ $linkUrl }}" class="home-block__pill">{{ $link->displayTitle() }}</a>
          @endforeach
          <div class="home-block__quick-links-last-row">
            @foreach($lastRowLinks as $link)
              @php $linkUrl = $link->catalogUrl(); @endphp
              <a href="{{ $linkUrl }}" class="home-block__pill">{{ $link->displayTitle() }}</a>
            @endforeach
            <span class="home-block__corner-icon home-block__corner-icon--beside-quick-links" aria-hidden="true">
              <svg class="home-block__corner-icon-svg" width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
                <path d="M1.5 11.5L11.5 1.5M11.5 1.5H4M11.5 1.5V9" stroke="#FFCE1C" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </span>
          </div>
        </div>
      @endif
    </div>
    @if($validQuickLinks->isEmpty())
      <span class="home-block__corner-icon" aria-hidden="true">
        <svg class="home-block__corner-icon-svg" width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
          <path d="M1.5 11.5L11.5 1.5M11.5 1.5H4M11.5 1.5V9" stroke="#FFCE1C" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </span>
    @elseif($large)
      {{-- Десктоп: пігулки + іконка в останньому рядку. Мобільна: пігулки приховані в CSS; іконка в куті як без підкатегорій --}}
      <span class="home-block__corner-icon home-block__corner-icon--mobile-no-sublinks" aria-hidden="true">
        <svg class="home-block__corner-icon-svg" width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false">
          <path d="M1.5 11.5L11.5 1.5M11.5 1.5H4M11.5 1.5V9" stroke="#FFCE1C" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </span>
    @endif
  </article>
@endif
