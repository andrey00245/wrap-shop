@foreach($homeBlocks ?? [] as $block)
  @switch($block->getTypeEnum())
    @case(\App\Enums\HomeBlockType::Categories)
    @case(\App\Enums\HomeBlockType::Kits)
      @include('base.components.home-blocks.categories', ['block' => $block])
      @break
    @default
  @endswitch
@endforeach
