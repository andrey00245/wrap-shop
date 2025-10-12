@php
    $blockCount = 0;
    $bannerIndex = 0;
@endphp
@foreach($products as $product)
    @php
        $blockCount++;
        if($bannerIndex+1 > $productBanners->count()){
            $bannerIndex = 0;
        }
    @endphp
    @if($blockCount % 6 == 0 && isset($productBanners[$bannerIndex]))
        <a href="{{$productBanners[$bannerIndex]->url}}"
           class="category-products-item product-default product-layout banner product-grid">
            <img class="vertical"
                 src="{{$productBanners[$bannerIndex]->getVerticalPreview()}}"
            >
            <img class="gorizont"
                 src="{{$productBanners[$bannerIndex]->getHorizontalPreview()}}"
            >
        </a>
        @php
            $bannerIndex++;
            $blockCount++;
        @endphp
    @endif
    <div class="category-products-item product-default product-default__splide product-layout product-grid"
         id="categoryProductsItem{{$product->id}}">
        @if($product->is_top_seller)
            <div class="sale statuses list">
                <div class="category-status category-status-1 status-inline text rectangle "
                     style=" color:#ffffff; background-color:#d04b4b;">
                    {{__('general-translate.sales_hit')}}
                </div>
            </div>
        @endif
        <div class="product-default-texts-wrapper">

            <div class="top flex-justify">
                @if($product->is_top_seller)
                    <div class="sale statuses grid">
                        <div class="category-status category-status-1 status-inline text rectangle "
                             style=" color:#ffffff; background-color:#d04b4b;">
                            {{__('general-translate.sales_hit')}}
                        </div>
                    </div>
                @endif
                <div
                    class="sku">{{__('general-translate.product_card.code')}} {{$product->code}}</div>
                <div class="review grid">
                    <i class="fal fa-star"></i>
                    <i class="fal fa-star"></i>
                    <i class="fal fa-star"></i>
                    <i class="fal fa-star"></i>
                    <i class="fal fa-star"></i>
                    <div class="rating-result" style="width: 100%">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <div class="wishlist">
                    <button type="button"
                            class="button {{$product->isFavorite() ? 'fas in-wishlist' : 'far'}} fa-heart"
                            data-product-id="{{$product->id}}"
                            title="{{__('general-translate.product_card.add_wishlist')}}"></button>
                </div>
            </div>
        </div>
        <div
            class="image default-products-images">
            <i class="far fa-search-plus colord"
               data-src="{{$product->getPreviewImage()}}"
               data-fancybox="products{{$product->id}}" data-caption="{{$product->name}}"></i>
            <a class="image-link" href="{{route('products.show', ['product'=>$product->slugEn])}}"
               title="{{$product->name}}">
                <div class="splide products-images">
                    <div class="splide__track">
                        <ul class="splide__list">
                            @foreach($product->getMedia('images') as $key => $image)
                                <li class="splide__slide">
                                    @if($key>0)
                                        <div class="hide"
                                             data-src="{{$image->getUrl()}}"
                                             data-fancybox="products{{$product->id}}"
                                             data-caption="{{$product->name}}"></div>
                                    @endif
                                    <img loading="lazy"
                                         src="{{App\Helpers\MediaHelper::getCatalogImageUrl($image)}}"
                                         alt="{{$product->name}}"
                                         title="{{$product->name}}"
                                         class="swiper-lazy swiper-lazy-loaded"
                                         width="310" height="310">
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </a>
        </div>

        <div class="product-default-texts-wrapper list">
            <div class="center">
                <div class="category">{{$product->category?->name}}</div>
                <a href="{{route('products.show', ['product' => $product->slugEn])}}"
                   title="{{$product->getName()}}"
                   class="name">{{$product->getName()}}</a>
            </div>
            <div class="bottom flex-center">
                <div class="price">{{number_format($product->getPrice())}} ₴<span
                        class="price-unit-xvr"></span></div>
                @if($product->getStock() > 0)
                    <button class="button colord button-cart-product general-popup-btn"
                            data-popup="cart-popup"
                            data-product-quantity="{{$product->getDefaultQuantity()}}"
                            data-product-id="{{$product->id}}">
                        <i class="fas fa-chevron-right"></i>{{__('general-translate.product_card.add_to_cart')}}
                    </button>
                @else
                    <button class="button colord notify-available-btn general-popup-btn"
                            data-popup="report-availability-popup"
                            data-product-id="{{$product->id}}"><i class="fas fa-bell"></i><span
                            class="hidden-xs hidden-sm hidden-md"> {{__('product-index.notify')}}</span></button>
                @endif
            </div>
        </div>
        {{--              <div class="params">--}}
        {{--                <div class="item flex-column"> <span class="label"></span></div>--}}
        {{--                <div class="item flex-column"> <span class="label"></span></div>--}}
        {{--              </div>--}}
    </div>
    @if($blockCount === 35)
        <a href="{{$productBanners[$bannerIndex]->url}}"
           class="category-products-item product-default product-layout banner product-grid">
            <img class="vertical"
                 src="{{$productBanners[$bannerIndex]->getVerticalPreview()}}"
            >
            <img class="gorizont"
                 src="{{$productBanners[$bannerIndex]->getHorizontalPreview()}}"
            >
        </a>
    @endif
@endforeach
