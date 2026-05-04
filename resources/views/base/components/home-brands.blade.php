@php
    $homeBrandsItems = collect($homeBrands ?? [])->filter(fn ($item) => $item->getLogoUrl() !== '');
@endphp

@if($homeBrandsItems->isNotEmpty())
    <section class="home-brands row">
        <div class="home-brands__list">
            @foreach($homeBrandsItems as $brand)
                <div class="home-brands__item" title="{{ $brand->name ?? '' }}">
                    <img
                        src="{{ $brand->getLogoUrl() }}"
                        alt="{{ $brand->name ?? 'Brand logo' }}"
                        class="home-brands__image"
                        loading="lazy"
                    >
                </div>
            @endforeach
        </div>
    </section>
@endif
