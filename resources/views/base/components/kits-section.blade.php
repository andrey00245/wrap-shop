@php
  /** @var \App\Models\KitSection|null $kitSection */
  /** @var \Illuminate\Support\Collection<int, \App\Models\Kit> $kits */
  $locale = app()->getLocale();
  $kits = ($kits ?? collect())->filter(function (\App\Models\Kit $kit) use ($locale) {
      return $kit->productGroupsForDisplay($locale)->contains(fn ($row) => $row['products']->isNotEmpty());
  });
@endphp
@if($kits->isNotEmpty())
  @php
    $headingPlain = $kitSection ? trim(strip_tags((string) $kitSection->title)) : '';
    $lead = $kitSection
        ? trim((string) ($kitSection->getTranslation('lead', $locale) ?: $kitSection->getTranslation('lead', 'uk') ?: ''))
        : '';
    if ($lead === '') {
        $lead = __('general-translate.home_block_kits_lead');
    }
    $useSlider = $kits->count() > 4;
    $gridCount = min($kits->count(), 4);
    $splideId = 'homeKitsSplide';
  @endphp
  <section class="home-block home-block--kits" aria-labelledby="home-kits-section-h">
    <div class="wrap">
      @if($headingPlain !== '')
        <div class="home-products-top flex-justify home-block-kits__top">
          <h2 id="home-kits-section-h" class="home-title home-block-kits__title">{!! $kitSection->title !!}</h2>
        </div>
        @if($lead !== '')
          <p class="home-block-kits__lead">{{ $lead }}</p>
        @endif
      @else
        <h2 id="home-kits-section-h" class="visually-hidden">{{ __('general-translate.home_block_kits_sr_only') }}</h2>
      @endif

      <div class="home-block-kits__desktop">
        @if($useSlider)
          <div id="{{ $splideId }}" class="splide home-block-kits__slider home-products-list js-home-block-kits-splide">
            <div class="splide__arrows home-products-slide-buttons">
              <div class="line"></div>
            </div>
            <div class="splide__track">
              <ul class="splide__list">
                @foreach($kits as $kit)
                  <li class="splide__slide home-block-kits__slide">
                    @include('base.components.kits-section-card', ['kit' => $kit, 'locale' => $locale])
                  </li>
                @endforeach
              </ul>
            </div>
          </div>
        @else
          <div class="home-block-kits__grid home-block-kits__grid--{{ $gridCount }}">
            @foreach($kits as $kit)
              @include('base.components.kits-section-card', ['kit' => $kit, 'locale' => $locale])
            @endforeach
          </div>
        @endif
      </div>

      <div class="home-block-kits__accordion js-home-kits-accordion">
        @foreach($kits as $kit)
          @php
            $eyebrow = $kit->displayTagline($locale);
            $cardTitle = $kit->displayTitle($locale);
          @endphp
          <details class="home-block-kits__accordion-item">
            <summary class="home-block-kits__accordion-trigger">
              <span class="home-block-kits__accordion-trigger-inner">
                <span class="home-block-kits__eyebrow">{{ $eyebrow }}</span>
                <span class="home-block-kits__card-title">{{ $cardTitle }}</span>
              </span>
              <span class="home-block-kits__accordion-chevron" aria-hidden="true">
                <i class="fas fa-chevron-right"></i>
              </span>
            </summary>
            <div class="home-block-kits__accordion-panel">
              @include('base.components.kits-section-card-body', ['kit' => $kit, 'locale' => $locale])
            </div>
          </details>
        @endforeach
      </div>
    </div>
  </section>
@endif
