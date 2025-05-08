@foreach($customBlocks as $customBlock)
    <section class="home-products row customBlocks">
        <div class="wrap">
            <div class="home-products-top flex-justify">
                <h2 class="home-title">{!!$customBlock->name !!}</h2>
            </div>
            @php
                $categories = $customBlock->products->pluck('category')->filter()->unique('id');
            @endphp
            <div class="home-products-nav">
                <div data-cat="all" class="item button active all">{{__('general-translate.all')}}</div>
                @foreach($categories as $category)
                    <div data-cat="{{$category->id}}" class="item button">{{$category->name}}</div>
                @endforeach
            </div>


            <div class="splide home-products-list">
                <div class="splide__arrows home-products-slide-buttons">
                    <div class="line"></div>
                </div>
                <div class="splide__track">
                    <ul class="splide__list">
                        @if($customBlock->getImage())
                            <li class="splide__slide home-products-item banner product-default"
                                role="group">
                                <a href="{{$customBlock->url ?? '#'}}" title="3m color">
                                    <img class="vertical" data-src="{{$customBlock->getImage()}}" alt="3m color"
                                         title="3m color" src="{{$customBlock->getImage()}}">
                                </a>
                            </li>
                        @endif
                        @foreach($customBlock->products as $key => $product)
                            <li class="splide__slide home-products-item product-default"
                                data-ids="{{$product->category->id}}" id="homeLatest{{$key}}">


                                <div class="product-default-texts-wrapper">
                                    <div class="top flex-justify">
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
                                            <button
                                                class="button colord general-popup-btn notify-available-btn"
                                                data-popup="report-availability-popup"
                                                data-product-id="{{$product->id}}"><i class="fas fa-bell"></i><span
                                                    class="hidden-xs hidden-sm hidden-md">{{__('product-index.notify')}}</span></button>
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
        </div>
    </section>
@endforeach
