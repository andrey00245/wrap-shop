@extends('base.pages.account.layout')

@section('title', __('personal-account.you-wishlist.meta_title'))

@section('account.content')
    <h1>{{__('personal-account.you-wishlist.title')}}</h1>
    @if($wishlists->count() === 0)
        <p>{{__('personal-account.you-wishlist.empty')}}</p>
    @else
        <div class="account-products-list flex-wrap">
            @foreach($wishlists as $wishlist)
                <div class="item product-default product-default__splide" data-ids="{{$wishlist->id}}" id="homeSpecialItem{{$wishlist->id}}">
                    <div class="product-default-texts-wrapper">
                        <div class="top flex-justify">
                            <div class="sku">{{__('general-translate.product_card.code')}} {{$wishlist->code}}</div>
                            <div class="wishlist">
                                <a href="{{route('wishlist.delete', ['product'=>$wishlist->id])}}"
                                   title="{{__('general-translate.product_card.remove')}}"
                                   class="fal fa-times"></a>
                            </div>
                        </div>


                        <div
                            class="image default-products-images">
                            <i class="far fa-search-plus colord"
                               data-src="{{$wishlist->getMedia('images')->first()->getUrl()}}"
                               data-fancybox="products{{$wishlist->id}}" data-caption="{{$wishlist->name}}"></i>
                            <a class="image-link" href="{{route('products.show', ['product'=>$wishlist->slugEn])}}"
                               title="{{$wishlist->name}}">
                                <div class="splide products-images">
                                    <div class="splide__track">
                                        <ul class="splide__list">
                                            @foreach($wishlist->getMedia('images') as $key => $image)
                                                <li class="splide__slide">
                                                    @if($key>0)
                                                        <div class="hide"
                                                             data-src="{{$image->getUrl()}}"
                                                             data-fancybox="products{{$wishlist->id}}"
                                                             data-caption="{{$wishlist->name}}"></div>
                                                    @endif
                                                    <img loading="lazy"
                                                         src="{{$image->getUrl('preview')}}"
                                                         alt="{{$wishlist->name}}"
                                                         title="{{$wishlist->name}}"
                                                         class="swiper-lazy swiper-lazy-loaded"
                                                         width="310" height="310">
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="review">
                            <i class="fal fa-star"></i>
                            <i class="fal fa-star"></i>
                            <i class="fal fa-star"></i>
                            <i class="fal fa-star"></i>
                            <i class="fal fa-star"></i>
                            <div class="rating-result" style="width: 0%">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                        <div class="category">{{$wishlist->category?->name}}</div>
                        <a href="{{route('products.show', ['product' => $wishlist->slugEn])}}"
                           title="{{$wishlist->name}}" class="name">{{$wishlist->name}}</a>
                        <div class="bottom flex-center">
                            <div class="price">{{number_format($wishlist->getPrice())}} ₴<span
                                    class="price-unit-xvr"></span></div>
                            <button
                                {{$wishlist->getStock() > 0 ? '' : 'disabled'}} class="button colord button-cart-product general-popup-btn"
                                data-popup="cart-popup"
                                data-product-quantity="{{$wishlist->getDefaultQuantity()}}"
                                data-product-id="{{$wishlist->id}}">
                                <i class="fas fa-chevron-right"></i>{{__('general-translate.product_card.add_to_cart')}}
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
@push('fixed-catalog')
    @include('base.components.categories-catalog')
@endpush
