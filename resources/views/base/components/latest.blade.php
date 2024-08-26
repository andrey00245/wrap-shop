<section class="home-products row" id="homeLatest">
  <div class="wrap">
    <div class="home-products-top flex-justify">
      <h2 class="home-title">{!! __('general-translate.latest') !!}</h2>
    </div>
    <div class="home-products-nav">
      <div data-cat="all" class="item button active all">{{__('general-translate.all')}}</div>
      @foreach($categories as $category)
        <div data-cat="{{$category->id}}" class="item button">{{$category->name}}</div>
      @endforeach
    </div>
    <div class="home-products-list swiper">
      <div class="swiper-wrapper" aria-live="polite">
      @foreach($products->take(5) as $key => $product)
          <div class="swiper-slide home-products-item product-default" data-ids="{{$product->categories->value('id')}}" id="homeLatest{{$key}}"
               role="group">
            <div class="product-default-texts-wrapper">
              <div class="top flex-justify">
                <div class="sale statuses">
                </div>
                <div class="sku">{{__('general-translate.product_card.code')}} {{$product->code}}</div>
                <div class="wishlist">
                  <button type="button" class="button far fa-heart" title="{{__('general-translate.product_card.add_wishlist')}}"></button>
                </div>
              </div>
            </div>

            <div class="product-default-texts-wrapper">

            </div>
            <div class="image swiper swiper-initialized swiper-horizontal swiper-android swiper-backface-hidden">
              <i class="far fa-search-plus colord" data-src="{{$product->getMedia('images')->first()->getUrl()}}"
                 data-fancybox="gallery{{$product->id}}" data-caption="{{$product->name}}"></i>
              <a href="#"
                 title="{{$product->name}}" class="swiper-wrapper"
                 id="swiper-wrapper-c1ba02d97e25995b" aria-live="polite">
                @foreach($product->getMedia('images') as $key => $image)
                <div class="swiper-slide item flex-center swiper-slide-active" role="group">
                  @if($key>0)
                    <div class="hide"
                         data-src="{{$image->getUrl()}}"
                         data-fancybox="gallery{{$product->id}}"
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

              <div class="category">{{$product->categories->value('name')}}</div>
              <a href="#"
                 title="{{$product->name}}" class="name">{{$product->name}}</a>
              <div class="bottom flex-center">
                <div class="price">2573.00 ₴<span class="price-unit-xvr"></span></div>
                <button class="button colord remarketing_cart_button" data-product_id="{{$product->id}}">
                  <i class="fas fa-chevron-right"></i>{{__('general-translate.product_card.add_to_cart')}}</button>
              </div>
            </div>

            <div class="hover-additional-info">


              <div class="additional-info">
                <p class="text-left title">Призначення</p>
                <p class="text-left text">декоративна</p>
              </div>
              <div class="additional-info">
                <p class="text-left title">Структура</p>
                <p class="text-left text">сатинова</p>
              </div>
            </div>

          </div>
        @endforeach
      </div>
      <div class="swiper_button_wrapper">
        <div class="line" data-background="{{asset('assets/img/head-top.png')}}" style="background-image: url({{asset('assets/img/head-top.png')}});"></div>
        <div class="home-slide-button flex-justify">
          <div class="swiper-button-next button" tabindex="0" role="button" aria-label="Next slide" aria-disabled="false"></div>
          <div class="swiper-button-prev button" tabindex="-1" role="button" aria-label="Previous slide" aria-disabled="true"></div>
        </div>
      </div>
      <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>

  </div>
</section>
