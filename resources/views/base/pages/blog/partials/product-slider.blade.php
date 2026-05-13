@php
    $productsUseSlider = $productsUseSlider ?? ($products->count() >= 3);
@endphp
<div class="blog-products{{ $productsUseSlider ? '' : ' blog-products--static' }}">
    <div class="container">
        <h2 class="products-title">{{ mb_strtoupper($productsSectionTitle ?? '') }}</h2>
        <div class="blog-products-wrapper">
            @if($productsUseSlider)
                <div class="splide home-products-list" id="blogProductsSlider">
                    <div class="splide__arrows home-products-slide-buttons">
                        <div class="line"></div>
                    </div>
                    <div class="splide__track">
                        <ul class="splide__list">
                            @forelse($products as $key => $product)
                                <li class="splide__slide home-products-item product-default blog-product-card" id="blogProduct{{ $key }}" data-ids="{{ $product->category->id ?? '' }}" role="group">
                                    @include('base.pages.blog.partials.blog-product-card', ['product' => $product])
                                </li>
                            @empty
                                <li class="splide__slide">
                                    <p>Продуктів не знайдено</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                    <ul class="blog-products-slider-dots mobile-only"></ul>
                </div>
            @else
                <div class="blog-products-static-grid">
                    @foreach($products as $key => $product)
                        <div class="home-products-item product-default blog-product-card" id="blogProduct{{ $key }}" data-ids="{{ $product->category->id ?? '' }}">
                            @include('base.pages.blog.partials.blog-product-card', ['product' => $product])
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
