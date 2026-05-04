@php
  /** @var \App\Models\HomeBlock $block */
  $items = $block->items->filter(fn ($i) => $i->product_id && $i->product);
@endphp
@if($items->isNotEmpty())
  @php
    $headingPlain = trim(strip_tags((string) $block->title));
    $splideId = 'homeBlockProductsSplide-'.$block->id;
    $useSlider = $items->count() > 1;
  @endphp
  <section class="home-block-products row" aria-labelledby="home-block-products-h-{{ $block->id }}">
    <div class="wrap">
      @if($headingPlain !== '')
        <div class="home-products-top flex-justify">
          <h2 id="home-block-products-h-{{ $block->id }}" class="home-title">{!! $block->title !!}</h2>
        </div>
      @endif

      @if($useSlider)
        <div id="{{ $splideId }}" class="splide home-block-products__list home-products-list js-home-block-products-splide">
          <div class="splide__arrows home-products-slide-buttons">
            <div class="line"></div>
          </div>
          <div class="splide__track">
            <ul class="splide__list">
              @foreach($items as $item)
                @php
                  $product = $item->product;
                  $cardImage = $item->tileImageUrl(true);
                  $tagline = $item->displayTagline();
                  $name = $item->displayTitle();
                  $productUrl = route('products.show', ['product' => $product->slugEn]);
                @endphp
                <li class="splide__slide home-block-products__slide">
                  <a href="{{ $productUrl }}"
                     class="home-block-products__card"
                     title="{{ $name }}">
                    @if($cardImage)
                      <img src="{{ $cardImage }}" alt="{{ $name }}" class="home-block-products__image" loading="lazy">
                    @endif
                    <div class="home-block-products__overlay"></div>
                    <div class="home-block-products__content">
                      <div class="home-block-products__tagline">{{ $tagline }}</div>
                      <div class="home-block-products__name">{{ $name }}</div>
                      <div class="home-block-products__buy"><i class="fas fa-chevron-right"></i>{{ __('general-translate.product_card.add_to_cart') }}</div>
                    </div>
                  </a>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      @else
        <div class="home-block-products__static">
          @foreach($items as $item)
            @php
              $product = $item->product;
              $cardImage = $item->tileImageUrl(true);
              $tagline = $item->displayTagline();
              $name = $item->displayTitle();
              $productUrl = route('products.show', ['product' => $product->slugEn]);
            @endphp
            <div class="home-block-products__slide home-block-products__slide--static">
              <a href="{{ $productUrl }}"
                 class="home-block-products__card"
                 title="{{ $name }}">
                @if($cardImage)
                  <img src="{{ $cardImage }}" alt="{{ $name }}" class="home-block-products__image" loading="lazy">
                @endif
                <div class="home-block-products__overlay"></div>
                <div class="home-block-products__content">
                  <div class="home-block-products__tagline">{{ $tagline }}</div>
                  <div class="home-block-products__name">{{ $name }}</div>
                  <div class="home-block-products__buy"><i class="fas fa-chevron-right"></i>{{ __('general-translate.product_card.add_to_cart') }}</div>
                </div>
              </a>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </section>
@endif
