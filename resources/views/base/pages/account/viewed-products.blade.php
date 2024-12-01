@extends('base.pages.account.layout')

@section('title', __('personal-account.reviewed-products.meta_title'))

@section('account.content')
  <div id="content" class="page-account-right">
    <h1>{{__('personal-account.reviewed-products.title')}}</h1>
    <div class="account-products-list flex-wrap">
      <div class="item product-default" data-ids="102" id="productViewedItem1">
        <div class="product-default-texts-wrapper">
          <div class="top flex-justify">
            <div class="sku">{{__('general-translate.product_card.code')}} 10528</div>
            <div class="wishlist">
              <button type="button" class="button far fa-heart"
                      title="{{__('general-translate.product_card.add_wishlist')}}"
                      onclick="wishlist.add('432');"></button>
            </div>
          </div>
          <div class="image swiper swiper-initialized swiper-horizontal swiper-pointer-events swiper-backface-hidden">
            <div class="swiper-wrapper" id="swiper-wrapper-d89c5ec67e62e983" aria-live="polite">
              <a href="https://wrap-shop.test/storage/33/conversions/da83fc382b22d0f0ad2b983435501c36-original.png"
                 class="swiper-slide item flex-center swiper-slide-active" data-fancybox="gallery999"
                 data-caption="Плівка глянцева 3M 2080-G12 Gloss Black" role="group">
                <img alt="Плівка глянцева 3M 2080-G12 Gloss Black" title="Плівка глянцева 3M 2080-G12 Gloss Black"
                     class="swiper-lazy swiper-lazy-loaded"
                     src="https://wrap-shop.test/storage/33/conversions/da83fc382b22d0f0ad2b983435501c36-preview.png">
              </a>
              <a href="https://wrap-shop.test/storage/34/conversions/fb43d83819a9f62f54ea8f7f9bc7cc23-original.png"
                 class="swiper-slide item flex-center swiper-slide-next" data-fancybox="gallery999"
                 data-caption="Плівка глянцева 3M 2080-G12 Gloss Black" role="group" aria-label="2 / 2">
                <img
                  src="https://wrap-shop.test/storage/34/conversions/fb43d83819a9f62f54ea8f7f9bc7cc23-preview.png"
                  alt="Плівка глянцева 3M 2080-G12 Gloss Black" title="Плівка глянцева 3M 2080-G12 Gloss Black"
                  class="swiper-lazy">
              </a>
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
          <div class="category">Кольорові плівки</div>
          <a href="https://wrap.shop/plivky/kolorovi-plivky/plivka-glyanceva-3m-2080-g12-gloss-black"
             title="Плівка глянцева 3M 2080-G12 Gloss Black" class="name">Плівка глянцева 3M 2080-G12 Gloss Black</a>
          <div class="bottom flex-center">
            <div class="price">2562.00 ₴<span class="price-unit-xvr"></span></div>
            <button class="button colord remarketing_cart_button" onclick="cart.add('126');" data-product_id="126"><i
                class="fas fa-chevron-right"></i>{{__('general-translate.product_card.add_to_cart')}}
            </button>
          </div>
        </div>
      </div>

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
