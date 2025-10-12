<section class="home-products row" id="homeLatest">
    <div class="wrap">
        <div class="home-products-top flex-justify">
            <h2 class="home-title">{!! __('general-translate.latest') !!}</h2>
        </div>
        <div class="home-products-nav">
            <div data-cat="all" class="item button active all">{{__('general-translate.all')}}</div>
            @foreach($latestCategory as $category)
                <div data-cat="{{$category->id}}" class="item button">{{$category->name}}</div>
            @endforeach
        </div>


        <div class="splide home-products-list">
            <div class="splide__arrows home-products-slide-buttons">
                <div class="line"></div>
            </div>
            <div class="splide__track">
                <ul class="splide__list">
                    @foreach($products as $key => $product)
                        <li class="splide__slide home-products-item product-default"
                            data-ids="{{$product->category->id}}" id="homeLatest{{$key}}"
                            role="group">
                            <div class="product-default-texts-wrapper">
                                <div class="top flex-justify">
                                    <div class="sale statuses">
                                    </div>
                                    <div
                                        class="sku">{{__('general-translate.product_card.code')}} {{$product->code}}</div>
                                    <div class="wishlist">
                                        <button type="button"
                                                class="button {{in_array($product->id, session()->get('wishlist', []), true) ? 'fas in-wishlist' : 'far'}} fa-heart"
                                                data-product-id="{{$product->id}}"
                                                title="{{__('general-translate.product_card.add_wishlist')}}"></button>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="image">
                                <i class="far fa-search-plus colord"
                                   data-src="{{$product->getPreviewImage()}}"
                                   data-fancybox="latest{{$product->id}}" data-caption="{{$product->getName()}}"></i>
                                <a href="{{route('products.show', ['product'=>$product->slugEn])}}"
                                   title="{{$product->getName()}}">
                                    <div class="splide products-images">
                                        <div class="splide__track">
                                            <ul class="splide__list">
                                                @foreach($product->getMedia('images') as $key => $image)
                                                    @if($key>0)
                                                        <div class="hide"
                                                             data-src="{{$image->getUrl()}}"
                                                             data-fancybox="latest{{$product->id}}"
                                                             data-caption="{{$product->name}}"></div>
                                                    @endif
                                                    <li class="splide__slide"
                                                        role="group">
                                                        <img loading="lazy"
                                                             src="{{$image->getUrl('preview_webp')}}"
                                                             alt="{{$product->getName()}}"
                                                             title="{{$product->getName()}}"
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
                                   title="{{$product->getName()}}" class="name">{{$product->getName()}}</a>
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
    </div>
</section>

