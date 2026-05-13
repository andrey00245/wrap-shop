@php
  /** @var \App\Models\HomeBlock $block */
  $items = $block->items->filter(fn ($i) => $i->category_id && $i->category);
@endphp
@if($items->isNotEmpty())
  @php
    $layoutEnum = $block->categoriesTemplateLayout();
    $layout = $layoutEnum->value;
    $layoutClass = str_replace('_', '-', $layout);
    $headingPlain = trim(strip_tags((string) $block->title));
    $maxPerSlide = 5;
    $slides = $items->chunk($maxPerSlide);
    $useSlider = $slides->count() > 1;
    $splideId = 'homeBlockCategoriesSplide-'.$block->id;
  @endphp
  <section class="home-block home-block--categories wrap" aria-labelledby="home-block-h-{{ $block->id }}">
    @if($headingPlain !== '')
      <div class="home-products-top flex-justify">
        <h2 id="home-block-h-{{ $block->id }}" class="home-title home-block__heading">{!! $block->title !!}</h2>
      </div>
    @else
      <h2 id="home-block-h-{{ $block->id }}" class="visually-hidden">{{ __('general-translate.home_block_categories_sr_only') }}</h2>
    @endif

    @if($layoutEnum === \App\Enums\HomeBlockLayout::Grid)
      @if($useSlider)
        <div class="home-block__carousel">
          <div id="{{ $splideId }}" class="splide home-block__splide js-home-block-categories-splide" aria-roledescription="carousel">
            <div class="splide__track">
              <ul class="splide__list">
                @foreach($slides as $slideItems)
                  <li class="splide__slide">
                    <div class="home-block__grid home-block__grid--only">
                      @foreach($slideItems as $item)
                        @include('base.components.home-blocks.partials.category-tile', ['item' => $item, 'large' => false])
                      @endforeach
                    </div>
                  </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      @else
        <div class="home-block__grid home-block__grid--only">
          @foreach($items as $item)
            @include('base.components.home-blocks.partials.category-tile', ['item' => $item, 'large' => false])
          @endforeach
        </div>
      @endif
    @else
      @if($useSlider)
        <div class="home-block__carousel">
          <div id="{{ $splideId }}" class="splide home-block__splide js-home-block-categories-splide" aria-roledescription="carousel">
            <div class="splide__track">
              <ul class="splide__list">
                @foreach($slides as $slideItems)
                  <li class="splide__slide">
                    @include('base.components.home-blocks.partials.categories-mosaic-slide', [
                      'slideItems' => $slideItems,
                      'layoutClass' => $layoutClass,
                    ])
                  </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      @else
        @include('base.components.home-blocks.partials.categories-mosaic-slide', [
          'slideItems' => $items,
          'layoutClass' => $layoutClass,
        ])
      @endif
    @endif
  </section>
@endif
