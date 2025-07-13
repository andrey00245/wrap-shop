@if($volumeVariants = $component->volumeVariants())
    @if($volumeVariants->count() > 1)
        <div class="product-volume-selector">
            <span>Обʼєм:</span>
            <div class="volume-options">
                @foreach($volumeVariants as $variant)
                    <a href="{{ route('product.show', $variant->slug['uk']) }}"
                       class="volume-option {{ $variant->id === $component->product->id ? 'active' : '' }}">
                        {{ $variant->attributes['volume'] ?? '—' }} мл
                    </a>
                @endforeach
            </div>
        </div>
    @endif
@endif
<style>
    .volume-options {
        display: flex;
        gap: 8px;
    }

    .volume-option {
        padding: 6px 12px;
        border: 1px solid #ccc;
        text-decoration: none;
        color: black;
        border-radius: 4px;
    }

    .volume-option.active {
        background-color: #000;
        color: #fff;
    }
</style>
