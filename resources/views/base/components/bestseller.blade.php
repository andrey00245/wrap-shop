<section class="home-products row" id="homeBestseller">
    <div class="wrap">
        <div class="home-products-top flex-justify">
            <h2 class="home-title">{!! __('general-translate.leaders_of_sales') !!}</h2>
        </div>
        <div class="home-products-nav">
            <div data-cat="all" class="item button active all">{{__('general-translate.all')}}</div>
            @foreach($topSellerCategories as $category)
                <div data-cat="{{$category->id}}" class="item button">{{$category->name}}</div>
            @endforeach
        </div>


        <div class="splide home-products-list">
            <div class="splide__arrows home-products-slide-buttons">
                <div class="line"></div>
            </div>
            <div class="splide__track">
                <ul class="splide__list">
                    @foreach($topSellersProducts as $key => $item)
                        <li class="splide__slide home-products-item product-default"
                            data-ids="{{$item->product->category->id}}" id="homeLatest{{$key}}">


                            <div class="product-default-texts-wrapper">
                                <div class="top flex-justify">
                                    <div class="sale statuses">
                                        <div class="category-status category-status-1 status-inline text rectangle "
                                             style=" color:#ffffff; background-color:#d04b4b;">
                                            {{__('general-translate.sales_hit')}}
                                        </div>
                                    </div>
                                    <div
                                        class="sku">{{__('general-translate.product_card.code')}} {{$item->product->code}}</div>
                                    <div class="wishlist">
                                        <button type="button"
                                                class="button  {{$item->product->isFavorite() ? 'fas in-wishlist' : 'far'}} fa-heart"
                                                data-product-id="{{$item->product->id}}"
                                                title="{{__('general-translate.product_card.add_wishlist')}}"></button>
                                    </div>
                                </div>
                            </div>


                            <div
                                class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
                                <i class="far fa-search-plus colord"
                                   data-src="{{$item->product->getMedia('images')->first()->getUrl()}}"
                                   data-fancybox="bestseller{{$item->product->id}}" data-caption="{{$item->product->name}}"></i>
                                <a href="{{route('products.show', ['product'=>$item->product->slugEn])}}"
                                   title="{{$item->product->name}}">
                                    <div class="splide products-images">
                                        <div class="splide__track">
                                            <ul class="splide__list">
                                                @foreach($item->product->getMedia('images') as $key => $image)
                                                    <li class="splide__slide"
                                                        role="group">
                                                        @if($key>0)
                                                            <div class="hide"
                                                                 data-src="{{$image->getUrl()}}"
                                                                 data-fancybox="bestseller{{$item->product->id}}"
                                                                 data-caption="{{$item->product->name}}"></div>
                                                        @endif
                                                        <img loading="lazy"
                                                             src="{{$image->getUrl('preview_webp')}}"
                                                             alt="{{$item->product->name}}"
                                                             title="{{$item->product->name}}"
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
                                <div class="category">{{$item->product->category->name}}</div>
                                <a href="{{route('products.show', ['product'=>$item->product->slugEn])}}"
                                   title="{{$item->product->name}}" class="name">{{$item->product->name}}</a>
                                <div class="bottom flex-center">
                                    <div class="price">{{number_format($item->product->getPrice())}} ₴<span
                                            class="price-unit-xvr"></span></div>
                                    @if($item->product->getStock() > 0)
                                        <button class="button colord button-cart-product general-popup-btn"
                                                data-popup="cart-popup"
                                                data-product-quantity="{{$item->product->getDefaultQuantity()}}"
                                                data-product-id="{{$item->product->id}}">
                                            <i class="fas fa-chevron-right"></i>{{__('general-translate.product_card.add_to_cart')}}
                                        </button>
                                    @else
                                        <button class="button colord notify-available-btn general-popup-btn"
                                                data-popup="report-availability-popup"
                                                data-product-id="{{$item->product->id}}"><i class="fas fa-bell"></i><span
                                                class="hidden-xs hidden-sm hidden-md"> {{__('product-index.notify')}}</span></button>
                                    @endif
                                </div>
                            </div>
                            @if($item->product->getPurpose() ||$item->product->getStructure() || $item->product->getType())
                                <div class="hover-additional-info">
                                    @if($item->product->getPurpose())
                                        <div class="additional-info">
                                            <p class="text-left additional-title">{{$item->product->getPurpose()->name}}</p>
                                            <p class="text-left additional-text">{{$item->product->getPurpose()?->pivot?->value}}</p>
                                        </div>
                                    @endif
                                    @if($item->product->getStructure())
                                        <div class="additional-info">
                                            <p class="text-left additional-title">{{$item->product->getStructure()->name}}</p>
                                            <p class="text-left additional-text">{{$item->product->getStructure()?->pivot?->value}}</p>
                                        </div>
                                    @elseif($item->product->getType())
                                        <div class="additional-info">
                                            <p class="text-left additional-title">{{$item->product->getType()->name}}</p>
                                            <p class="text-left additional-text">{{$item->product->getType()?->pivot?->value}}</p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>


