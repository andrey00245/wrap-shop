<section class="popup-products row" id="cartProductSlider">
  <div class="home-products-top flex-justify">
    <h2 class="popup-title"><span class="colord">рекомендуємо</span></h2>
    <div class="line" data-background="{{asset('assets/img/head-top.png')}}"
         style="background-image: url('{{asset('assets/img/head-top.png')}}');"></div>
    <div class="home-slide-button flex-justify">
      <div class="swiper-button-next button" tabindex="0" role="button" aria-label="Next slide"
           aria-controls="swiper-wrapper-c52685b9106c91423" aria-disabled="false"></div>
      <div class="swiper-button-prev button swiper-button-disabled" tabindex="-1" role="button"
           aria-label="Previous slide" aria-controls="swiper-wrapper-c52685b9106c91423" aria-disabled="true"></div>
    </div>
  </div>

  <div class="popup-list swiper">
    <div class="swiper-wrapper" aria-live="polite">
      @foreach($products as $key => $product)
        <div class="swiper-slide popup-products-item product-default"
             data-ids="{{$product->category->id}}" id="homeBestsellerItem{{$key}}"
             role="group">
          <div class="product-default-texts-wrapper">
            <div class="top flex-justify">
              <div class="sale statuses">
                <div class="category-status category-status-1 status-inline text rectangle "
                     style=" color:#ffffff; background-color:#d04b4b;">
                  {{__('general-translate.sales_hit')}}
                </div>
              </div>
              <div class="sku">{{__('general-translate.product_card.code')}} {{$product->code}}</div>
              <div class="wishlist">
                <button type="button" class="button {{$product->isFavorite() ? 'fas in-wishlist' : 'far'}} fa-heart"
                        data-product-id="{{$product->id}}"
                        title="{{__('general-translate.product_card.add_wishlist')}}"></button>
              </div>
            </div>
          </div>

          <div class="product-default-texts-wrapper">

          </div>
          <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
            <a href="{{route('products.show', ['product'=>$product->id])}}"
               title="{{$product->name}}" class="swiper-wrapper"
               id="swiper-wrapper-c1ba02d97e25995b" aria-live="polite">
              @foreach($product->getMedia('images') as $key => $image)
                <div class="swiper-slide item flex-center swiper-slide-active" role="group">
                  @if($key>0)
                    <div class="hide"
                         data-src="{{$image->getUrl()}}"
                         data-fancybox="cart-bestseller-gallery{{$product->id}}"
                         data-caption="{{$product->name}}"></div>
                  @endif
                  <img loading="lazy"
                       src="{{$image->getUrl('preview')}}"
                       alt="{{$product->name}}"
                       title="{{$product->name}}" class="swiper-lazy swiper-lazy-loaded"
                       width="310" height="310">
                </div>
              @endforeach
            </a>
            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
          <div class="product-default-texts-wrapper">
            <div class="category">{{$product->category->name}}</div>
            <a href="{{route('products.show', ['product'=>$product->id])}}"
               title="{{$product->name}}" class="name">{{$product->name}}</a>
            <div class="bottom flex-center">
              <div class="price">{{number_format($product->getPrice())}} ₴<span class="price-unit-xvr"></span></div>
              <button class="button colord remarketing_cart_button" data-product_id="{{$product->id}}">
                <i class="fas fa-chevron-right"></i>{{__('general-translate.product_card.add_to_cart')}}</button>
            </div>
          </div>


          <div class="hover-additional-info">
            <div class="additional-info">
              <p class="text-left additional-title">Призначення</p>
              <p class="text-left additional-text">декоративна</p>
            </div>
            <div class="additional-info">
              <p class="text-left additional-title">Структура</p>
              <p class="text-left additional-text">сатинова</p>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
