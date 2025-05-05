<section class="home-products checkout row" id="checkoutProductSlider">
    <div class="home-products-top flex-justify">
        <h2 class="home-title">{!! __('general-translate.latest') !!}</h2>

        <div class="line"></div>

        <div class="home-slide-buttons flex-justify custom_arrows">
            <div class="custom_arrow custom__prev-arrow">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" width="40" height="40" focusable="false">
                    <path
                        d="m15.5 0.932-4.3 4.38 14.5 14.6-14.5 14.5 4.3 4.4 14.6-14.6 4.4-4.3-4.4-4.4-14.6-14.6z"></path>
                </svg>
            </div>
            <div class="custom_arrow custom__next-arrow">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" width="40" height="40" focusable="false">
                    <path
                        d="m15.5 0.932-4.3 4.38 14.5 14.6-14.5 14.5 4.3 4.4 14.6-14.6 4.4-4.3-4.4-4.4-14.6-14.6z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="splide home-products-list">
        {{--        <div class="home-slide-button flex-justify">--}}
        {{--            <div class="splide__arrows home-products-slide-buttons">--}}
        {{--                <div class="line"></div>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        <div class="splide__track">
            <ul class="splide__list">
                @foreach($products->take(5) as $key => $product)
                    <li class="splide__slide home-products-item product-default"
                        data-ids="{{$product->category->id}}" id="homeLatest{{$key}}">


                        <div class="product-default-texts-wrapper">
                            <div class="top flex-justify">
                                <div class="sale statuses">
                                    <div class="category-status category-status-1 status-inline text rectangle "
                                         style=" color:#ffffff; background-color:#d04b4b;">
                                        {{__('general-translate.sales_hit')}}
                                    </div>
                                </div>
                                <div
                                    class="sku">{{__('general-translate.product_card.code')}} {{$product->code}}</div>
                                <div class="wishlist">
                                    <button type="button"
                                            class="button  {{$product->isFavorite() ? 'fas in-wishlist' : 'far'}} fa-heart"
                                            data-product-id="{{$product->id}}"
                                            title="{{__('general-translate.product_card.add_wishlist')}}"></button>
                                </div>
                            </div>
                        </div>


                        <div
                            class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
                            <i class="far fa-search-plus colord"
                               data-src="{{$product->getMedia('images')->first()->getUrl()}}"
                               data-fancybox="bestseller{{$product->id}}" data-caption="{{$product->name}}"></i>
                            <a href="{{route('products.show', ['product'=>$product->slugEn])}}"
                               title="{{$product->name}}">
                                <div class="splide products-images">
                                    <div class="splide__track">
                                        <ul class="splide__list">
                                            @foreach($product->getMedia('images') as $key => $image)
                                                <li class="splide__slide"
                                                    role="group">
                                                    @if($key>0)
                                                        <div class="hide"
                                                             data-src="{{$image->getUrl()}}"
                                                             data-fancybox="bestseller{{$product->id}}"
                                                             data-caption="{{$product->name}}"></div>
                                                    @endif
                                                    <img loading="lazy"
                                                         src="{{$image->getUrl('preview')}}"
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
                        <div class="product-default-texts-wrapper">
                            <div class="category">{{$product->category->name}}</div>
                            <a href="{{route('products.show', ['product'=>$product->slugEn])}}"
                               title="{{$product->name}}" class="name">{{$product->name}}</a>
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
                        @if($product->getPurpose() || $product->getStructure() || $product->getType())
                            <div class="hover-additional-info">
                                @if($product->getPurpose())
                                    <div class="additional-info">
                                        <p class="text-left additional-title">{{$product->getPurpose()->name}}</p>
                                        <p class="text-left additional-text">{{$product->getPurpose()?->pivot?->value}}</p>
                                    </div>
                                @endif
                                @if($product->getStructure())
                                    <div class="additional-info">
                                        <p class="text-left additional-title">{{$product->getStructure()->name}}</p>
                                        <p class="text-left additional-text">{{$product->getStructure()?->pivot?->value}}</p>
                                    </div>
                                @elseif($product->getType())
                                    <div class="additional-info">
                                        <p class="text-left additional-title">{{$product->getType()->name}}</p>
                                        <p class="text-left additional-text">{{$product->getType()?->pivot?->value}}</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
