@php
  $onlyTypes = collect($onlyTypes ?? [])->map(fn ($t) => (string) $t)->all();
@endphp

@foreach($homeBlocks ?? [] as $block)
  @php
    $blockType = $block->getTypeEnum()->value;
  @endphp
  @if(!empty($onlyTypes) && !in_array($blockType, $onlyTypes, true))
    @continue
  @endif
  @switch($block->getTypeEnum())
    @case(\App\Enums\HomeBlockType::Categories)
      @include('base.components.home-blocks.categories', ['block' => $block])
      @break
    @case(\App\Enums\HomeBlockType::Products)
      @include('base.components.home-blocks.products', ['block' => $block])
      @break
    @case(\App\Enums\HomeBlockType::Banner)
      @include('base.components.home-blocks.banner', ['block' => $block])
      @break
    @default
  @endswitch
@endforeach
