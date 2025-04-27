@extends('base.pages.account.layout')

@section('title', __('personal-account.reviewed-products.meta_title'))

@section('account.content')
  <div id="content" class="page-account-right">
    <h1>{{__('personal-account.reviewed-products.title')}}</h1>
    <div class="account-products-list flex-wrap">

        @foreach($viewedProducts as $viewedProduct)
          <div class="item product-default product-default__splide" data-ids="{{$viewedProduct->id}}" id="homeSpecialItem{{$viewedProduct->id}}">
            <div class="product-default-texts-wrapper">
              <div class="top flex-justify">
                <div class="sku">{{__('general-translate.product_card.code')}} {{$viewedProduct->code}}</div>
                <div class="wishlist">
                  <button type="button" class="button {{$viewedProduct->isFavorite() ? 'fas in-wishlist' : 'far'}} fa-heart" data-product-id="{{$viewedProduct->id}}"
                    title="{{__('general-translate.product_card.add_wishlist')}}"></button>
                </div>
              </div>
                <div
                    class="image default-products-images">
                    <i class="far fa-search-plus colord"
                       data-src="{{$viewedProduct->getMedia('images')->first()->getUrl()}}"
                       data-fancybox="products{{$viewedProduct->id}}" data-caption="{{$viewedProduct->name}}"></i>
                    <a class="image-link" href="{{route('products.show', ['product'=>$viewedProduct->slugEn])}}"
                       title="{{$viewedProduct->name}}">
                        <div class="splide products-images">
                            <div class="splide__track">
                                <ul class="splide__list">
                                    @foreach($viewedProduct->getMedia('images') as $key => $image)
                                        <li class="splide__slide">
                                            @if($key>0)
                                                <div class="hide"
                                                     data-src="{{$image->getUrl()}}"
                                                     data-fancybox="products{{$viewedProduct->id}}"
                                                     data-caption="{{$viewedProduct->name}}"></div>
                                            @endif
                                            <img loading="lazy"
                                                 src="{{$image->getUrl('preview')}}"
                                                 alt="{{$viewedProduct->name}}"
                                                 title="{{$viewedProduct->name}}"
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
              <div class="category">{{$viewedProduct->category?->name}}</div>
              <a href="{{route('products.show', ['product' => $viewedProduct->slugEn])}}"
                 title="{{$viewedProduct->name}}" class="name">{{$viewedProduct->name}}</a>
              <div class="bottom flex-center">
                <div class="price">{{number_format($viewedProduct->getPrice())}} ₴<span class="price-unit-xvr"></span></div>
                  @if($viewedProduct->getStock() > 0)
                      <button class="button colord button-cart-product general-popup-btn"
                              data-popup="cart-popup"
                              data-product-quantity="{{$viewedProduct->getDefaultQuantity()}}"
                              data-product-id="{{$viewedProduct->id}}">
                          <i class="fas fa-chevron-right"></i>{{__('general-translate.product_card.add_to_cart')}}
                      </button>
                  @else
                      <button class="button colord notify-available-btn general-popup-btn"
                              data-popup="report-availability-popup"
                              data-product-id="{{$viewedProduct->id}}"><i class="fas fa-bell"></i><span
                              class="hidden-xs hidden-sm hidden-md"> Повідомити</span></button>
                  @endif
              </div>
            </div>
          </div>
        @endforeach
    </div>
  </div>
@endsection
@push('fixed-catalog')
  @include('base.components.categories-catalog')
@endpush
