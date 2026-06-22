@php
  /** @var \Illuminate\Support\Collection<int, \App\Models\HomeBlockItem> $slideItems */
  /** @var string $layoutClass */
@endphp
@if($slideItems->count() === 1)
  @php
    $only = $slideItems->first();
  @endphp
  <div class="home-block__single-slide">
    @include('base.components.home-blocks.partials.category-tile', ['item' => $only, 'large' => true])
  </div>
@else
  @php
    $hero = $slideItems->first();
    $rest = $slideItems->slice(1)->values();
  @endphp
  <div class="home-block__layout home-block__layout--{{ $layoutClass }}">
    @if($hero)
      <div class="home-block__main">
        @include('base.components.home-blocks.partials.category-tile', ['item' => $hero, 'large' => true])
      </div>
    @endif
    @if($rest->isNotEmpty())
      <div class="home-block__grid home-block__grid--mosaic">
        @foreach($rest as $item)
          @include('base.components.home-blocks.partials.category-tile', ['item' => $item, 'large' => false])
        @endforeach
      </div>
    @endif
  </div>
@endif
