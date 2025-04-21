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


                        <div class="splide default-products-images">
                            <div class="splide__arrows"></div>
                            <div class="splide__track">
                                <ul class="splide__list">
                                    @foreach($wishlist->getMedia('images') as $key => $image)
                                        <li class="splide__slide flex-center"
                                            role="group">
                                            <a href="{{route('products.show', ['product' => $wishlist->slugEn])}}"
                                               data-src="{{$image->getUrl()}}"
                                               class="swiper-slide item flex-center swiper-slide-next"
                                               data-fancybox="gallery{{$wishlist->id}}"
                                               data-caption="{{$wishlist->name}}">
                                                <img loading="lazy"
                                                     src="{{$image->getUrl('preview')}}"
                                                     alt="{{$wishlist->name}}"
                                                     title="{{$wishlist->name}}"
                                                     width="310" height="310">
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
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
                                {{$wishlist->getStock() > 0 ? '' : 'disabled'}} class="button button-cart-product colord remarketing_cart_button"
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
