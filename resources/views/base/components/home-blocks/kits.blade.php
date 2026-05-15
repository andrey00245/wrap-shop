@php
  /** @var \App\Models\HomeBlock $block */
  $locale = app()->getLocale();
  $items = $block->items->filter(function (\App\Models\HomeBlockItem $item) use ($locale) {
      return $item->kitsProductGroupsForDisplay($locale)->contains(fn ($row) => $row['products']->isNotEmpty());
  });
@endphp
@if($items->isNotEmpty())
  @php
    $headingPlain = trim(strip_tags((string) $block->title));
    $lead = trim((string) ($block->getTranslation('lead', $locale) ?: $block->getTranslation('lead', 'uk') ?: ''));
  @endphp
  <section class="home-block home-block--kits" aria-labelledby="home-block-kits-h-{{ $block->id }}">
    <div class="wrap">
      @if($headingPlain !== '')
        <div class="home-block-kits__header">
          <h2 id="home-block-kits-h-{{ $block->id }}" class="home-title home-block__heading home-block-kits__title">{!! $block->title !!}</h2>
          @if($lead !== '')
            <p class="home-block-kits__lead">{{ $lead }}</p>
          @endif
        </div>
      @else
        <h2 id="home-block-kits-h-{{ $block->id }}" class="visually-hidden">{{ __('general-translate.home_block_kits_sr_only') }}</h2>
      @endif

      <div class="home-block-kits__grid">
        @foreach($items as $item)
          @php
            $catUrl = $item->catalogUrl();
            $groups = $item->kitsProductGroupsForDisplay($locale);
            $eyebrow = trim((string) ($item->getTranslation('custom_tagline', $locale) ?: $item->getTranslation('custom_tagline', 'uk') ?: ''));
            if ($eyebrow === '') {
                $eyebrow = 'STARTER KIT';
            }
            $cardTitle = $item->displayTitle($locale);
            $cardDesc = trim((string) ($item->getTranslation('kit_description', $locale) ?: $item->getTranslation('kit_description', 'uk') ?: ''));
          @endphp
          <article class="home-block-kits__card">
            <header class="home-block-kits__card-head">
              <p class="home-block-kits__eyebrow">{{ $eyebrow }}</p>
              @if($catUrl)
                <a href="{{ $catUrl }}" class="home-block-kits__card-title-link">
                  <h3 class="home-block-kits__card-title">{{ $cardTitle }}</h3>
                </a>
              @else
                <h3 class="home-block-kits__card-title">{{ $cardTitle }}</h3>
              @endif
              @if($cardDesc !== '')
                <p class="home-block-kits__card-desc">{{ $cardDesc }}</p>
              @endif
            </header>

            <div class="home-block-kits__scroll" tabindex="0">
              @foreach($groups as $group)
                @php $groupLabel = $group['label'] ?? null; @endphp
                @continue($group['products']->isEmpty())
                <div class="home-block-kits__group{{ $groupLabel ? ' home-block-kits__group--labeled' : '' }}">
                  @if(filled($groupLabel))
                    <div class="home-block-kits__group-rail" aria-hidden="true">
                      <span class="home-block-kits__group-label">{{ $groupLabel }}</span>
                    </div>
                  @endif
                  <ul class="home-block-kits__list">
                    @foreach($group['products'] as $product)
                      @php
                        $pName = $product->getName();
                        $pSlug = $product->slugEn;
                        $pUrl = filled($pSlug) ? route('products.show', ['product' => $pSlug]) : null;
                        $thumb = $product->getPreviewImage();
                      @endphp
                      <li class="home-block-kits__row">
                        @if($pUrl)
                        <a href="{{ $pUrl }}" class="home-block-kits__row-link">
                        @else
                        <span class="home-block-kits__row-link home-block-kits__row-link--nohref">
                        @endif
                          <span class="home-block-kits__thumb-wrap">
                            @if($thumb !== '')
                              <img src="{{ $thumb }}" alt="" class="home-block-kits__thumb" loading="lazy" width="48" height="48">
                            @else
                              <span class="home-block-kits__thumb home-block-kits__thumb--placeholder" aria-hidden="true"></span>
                            @endif
                          </span>
                          <span class="home-block-kits__name">{{ $pName }}</span>
                        @if($pUrl)
                        </a>
                        @else
                        </span>
                        @endif
                      </li>
                    @endforeach
                  </ul>
                </div>
              @endforeach
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>
@endif
