@php
  /** @var \App\Models\Kit $kit */
  $groups = $kit->productGroupsForDisplay($locale);
@endphp
<div class="home-block-kits__scroll" tabindex="0">
  @foreach($groups as $group)
    @php $groupLabel = $group['label'] ?? null; @endphp
    @continue($group['products']->isEmpty())
    <div class="home-block-kits__group{{ $groupLabel ? ' home-block-kits__group--labeled' : '' }}">
      @if(filled($groupLabel))
        <div class="home-block-kits__group-rail">
          <span class="home-block-kits__group-label">{{ $groupLabel }}</span>
        </div>
      @endif
      <ul class="home-block-kits__items">
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
