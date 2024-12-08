@extends('base.pages.account.layout')

@section('title', __('personal-account.reviewed-products.meta_title'))

@section('account.content')
  <div id="content" class="page-account-right">
    <h1>{{__('personal-account.reviewed-products.title')}}</h1>
    <div class="account-products-list flex-wrap">

        @foreach($viewedProducts as $viewedProduct)
          <div class="item product-default" data-ids="{{$viewedProduct->id}}" id="homeSpecialItem{{$viewedProduct->id}}">
            <div class="product-default-texts-wrapper">
              <div class="top flex-justify">
                <div class="sku">{{__('general-translate.product_card.code')}} {{$viewedProduct->code}}</div>
                <div class="wishlist">
                  <button type="button" class="button {{$viewedProduct->isFavorite() ? 'fas in-wishlist' : 'far'}} fa-heart" data-product-id="{{$viewedProduct->id}}"
                    title="{{__('general-translate.product_card.add_wishlist')}}"></button>
                </div>
              </div>
              <div class="image swiper">
                <div class="swiper-wrapper">
                  @foreach($viewedProduct->getMedia('images') as $key => $image)
                    <a href="{{route('products.show', ['product' => $viewedProduct->id])}}"
                       data-src="{{$image->getUrl()}}"
                       class="swiper-slide item flex-center swiper-slide-next" data-fancybox="viewed-products{{$viewedProduct->id}}"
                       data-caption="{{$viewedProduct->name}}">
                      <img
                        src="{{$image->getUrl('preview')}}"
                        alt="{{$viewedProduct->name}}" title="{{$viewedProduct->name}}"
                        class="swiper-lazy">
                    </a>
                  @endforeach

                </div>
                <div class="swiper-button-next button"></div>
                <div class="swiper-button-prev button"></div>
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
              <div class="category">{{$viewedProduct->category?->name}}</div>
              <a href="{{route('products.show', ['product' => $viewedProduct->id])}}"
                 title="{{$viewedProduct->name}}" class="name">{{$viewedProduct->name}}</a>
              <div class="bottom flex-center">
                <div class="price">{{number_format($viewedProduct->getPrice())}} ₴<span class="price-unit-xvr"></span></div>
                <button class="button colord remarketing_cart_button" data-product_id="{{$viewedProduct->id}}"><i
                    class="fas fa-chevron-right"></i>{{__('general-translate.product_card.add_to_cart')}}
                </button>
              </div>
            </div>
          </div>
        @endforeach
    </div>

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

  </div>
@endsection
