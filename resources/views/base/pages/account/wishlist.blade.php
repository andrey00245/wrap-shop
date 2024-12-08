@extends('base.pages.account.layout')

@section('title', __('personal-account.you-wishlist.meta_title'))

@section('account.content')
  <h1>{{__('personal-account.you-wishlist.title')}}</h1>
  @if($wishlists->count() === 0)
    <p>{{__('personal-account.you-wishlist.empty')}}</p>
  @else
    <div class="account-products-list flex-wrap">
      @foreach($wishlists as $wishlist)
        <div class="item product-default" data-ids="{{$wishlist->id}}" id="homeSpecialItem{{$wishlist->id}}">
          <div class="product-default-texts-wrapper">
            <div class="top flex-justify">
              <div class="sku">{{__('general-translate.product_card.code')}} {{$wishlist->code}}</div>
              <div class="wishlist">
                <a href="{{route('wishlist.delete', ['product'=>$wishlist->id])}}"
                   title="{{__('general-translate.product_card.remove')}}"
                   class="fal fa-times"></a>
              </div>
            </div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-pointer-events swiper-backface-hidden">
              <div class="swiper-wrapper" id="swiper-wrapper-d89c5ec67e62e983" aria-live="polite">
                @foreach($wishlist->getMedia('images') as $key => $image)
                  <a href="{{$image->getUrl()}}"
                     class="swiper-slide item flex-center swiper-slide-next" data-fancybox="gallery{{$wishlist->id}}"
                     data-caption="{{$wishlist->name}}">
                    <img
                      src="{{$image->getUrl('preview')}}"
                      alt="{{$wishlist->name}}" title="{{$wishlist->name}}"
                      class="swiper-lazy">
                  </a>
                @endforeach
              </div>
              <div class="swiper-button-next button swiper-button-disabled swiper-button-lock" tabindex="-1" role="button"
                   aria-label="Next slide" aria-controls="swiper-wrapper-d89c5ec67e62e983" aria-disabled="true"></div>
              <div class="swiper-button-prev button swiper-button-disabled swiper-button-lock" tabindex="-1" role="button"
                   aria-label="Previous slide" aria-controls="swiper-wrapper-d89c5ec67e62e983" aria-disabled="true"></div>
              <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
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
            <a href="{{route('products.show', ['product' => $wishlist->id])}}"
               title="{{$wishlist->name}}" class="name">{{$wishlist->name}}</a>
            <div class="bottom flex-center">
              <div class="price">{{number_format($wishlist->getPrice())}} ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" data-product_id="{{$wishlist->id}}"><i
                  class="fas fa-chevron-right"></i>{{__('general-translate.product_card.add_to_cart')}}
              </button>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @endif


  @push('scripts')
    <script>
      $(document).ready(function () {
        var homeProductsItem = new Swiper(".account-products-list .product-default .image", {
          navigation: {
            nextEl: ".account-products-list .product-default .image .swiper-button-next",
            prevEl: ".account-products-list .product-default .image .swiper-button-prev",
          },
          lazy: true,
        });
      });
    </script>
  @endpush
@endsection
