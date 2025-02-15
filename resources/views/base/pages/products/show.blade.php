@extends('base.layouts.app')


@section('content')

  @push('styles')
    <link rel="stylesheet" href="{{mix('build/css/style-product-dark.css')}}">
    {{--    <link rel="stylesheet" href="https://wrap.shop/catalog/view/javascript/xvrproductquantities.css">--}}
  @endpush

  {{--  <nav class="breadcrumbs breadcrumbs-product wrap row">--}}
  {{--    <ul class="flex-center">--}}
  {{--      <li><a href="#" title="{{__('header_footer.home')}}" class="button">{{__('header_footer.home')}}</a></li>--}}
  {{--      <li><a href="#" title="{{$product->categories->first()->parent->name}}" class="button">{{$product->categories->first()->parent->name}}</a></li>--}}
  {{--      <li><a href="#" title="{{$product->categories->value('name')}}" class="button">{{$product->categories->value('name')}}</a></li>--}}
  {{--    </ul>--}}
  {{--  </nav>--}}

  <section class="product-page row" id="product-product">
    <div class="product-page-top flex-justify wrap">
      <div class="product-page-left">
        <div class="code-wishlist-wrapper">
          <div class="sku deskopt">{{__('general-translate.product_card.code')}} {{$product->code}}</div>
          <div class="wishlist desktop">
            <button type="button" title="В закладки" class="button {{$product->isFavorite() ? 'fas in-wishlist' : 'far'}} fa-heart " data-product-id="{{$product->id}}"></button>
          </div>
        </div>

        <div class="top flex-justify feedback-wrapper">
          <div class="rating-wrap">
            <div class="rating">
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

            <div class="count">(10)</div>
          </div>
          <div class="reviews-open button"><span>{{__('product-show.read-reviews')}}</span><i
              class="fas fa-chevron-right"></i></div>
        </div>

        <div class="params-title-mobil">{{__('product-show.technical-specifications')}}</div>
        <div class="params">
          <div class="list flex-column">
              @foreach($product->getProductAttributes() as $attribute)
                  @php
                      $value = $attribute->pivot->value;
                  @endphp
                      <div class="item flex-justify">{{ $attribute->name }} <span class="label">{{$value}}</span></div>
                 @endforeach
{{--            <div class="item flex-justify">Матеріал <span class="label">вініл</span></div>--}}
{{--            <div class="item flex-justify">Структура <span class="label">глянцева</span></div>--}}
{{--            <div class="item flex-justify">Основний відтінок <span class="label">коричневий</span></div>--}}
{{--            <div class="item flex-justify">Рулон, м.п. <span class="label">25</span></div>--}}
{{--            <div class="item flex-justify">Ширина, м <span class="label">1,52</span></div>--}}
{{--            <div class="item flex-justify hide">Товщина <span class="label">80 мкр</span></div>--}}
{{--            <div class="item flex-justify hide">Спосіб нанесення <span class="label">сухий</span></div>--}}
{{--            <div class="item flex-justify hide">Температура поверхні <span class="label">від +10°C до +16°C</span></div>--}}
{{--            <div class="item flex-justify hide">Температура експлуатації <span class="label">від -50°C до +110°C</span>--}}
{{--            </div>--}}
{{--            <div class="item flex-justify hide">Строк експлуатації <span class="label">до 5 років</span></div>--}}
{{--            <div class="item flex-justify hide">Технологія виробництва <span class="label">литий вініл</span></div>--}}
{{--            <div class="item flex-justify hide">Країна-виробник <span class="label">США</span></div>--}}
          </div>
          <div id="show-all" style="display: none" class="button">{{__('product-show.show-all')}}</div>
          <div id="show-less" style="display: none" class="button">{{__('product-show.show-less')}}</div>
        </div>
      </div>
      <div class="product-page-center">
          @if($product->is_top_seller)
        <div class="sale statuses">
          <div class="product-status product-status-1 status-inline text rectangle"
               style="color:#ffffff; background-color:#d04b4b; z-index: 2">
            {{__('general-translate.sales_hit')}}
          </div>
        </div>
          @endif
        <div
          class="product-slider swiper">
          <div class="swiper-wrapper">

            @foreach($product->getMedia('images') as $key => $image)
              <a
                data-fancybox="product-show-gallery"
                data-caption="Плівка глянцева Avery Gloss Metallic Brown CB1630001"
                class="swiper-slide item flex-center swiper-slide-active"
                href="{{$image->getUrl()}}"
                title="Плівка глянцева Avery Gloss Metallic Brown CB1630001"
                role="group">
                <img loading="lazy" src="{{$image->getUrl('preview')}}"
                     title="Плівка глянцева Avery Gloss Metallic Brown CB1630001"
                     alt="Плівка глянцева Avery Gloss Metallic Brown CB1630001">
              </a>
            @endforeach
          </div>
          <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>

        <div class="nav-slider-wrapper">
          <div class="product-slider-nav swiper">
            <div class="swiper-wrapper" id="swiper-wrapper-3d5845e6fc3b91055" aria-live="polite">

              @foreach($product->getMedia('images') as $key => $image)
                <div
                  class="swiper-slide item swiper-slide-visible swiper-slide-active swiper-slide-thumb-active"
                  role="group">
                  <img data-src="{{$image->getUrl('preview')}}"
                       title="{{$product->name}}"
                       alt="{{$product->name}}"
                       src="{{$image->getUrl('preview')}}">
                </div>
              @endforeach
            </div>

            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
          </div>
          <div class="swiper-button-next button"
               tabindex="-1" role="button" aria-label="Next slide"
               aria-disabled="true"></div>
          <div class="swiper-button-prev button"
               tabindex="-1" role="button" aria-label="Previous slide"
               aria-disabled="true"></div>
        </div>
      </div>
      <div class="product-page-right">
        <div class="top flex-justify">
          <h1 class="title">{{$product->name}}</h1>
        </div>
        <div class="center flex-justify">
          @php
            $count= $product->getStock()
          @endphp
          <div class="left">
            <div class="info flex-center">
              <div class="availability">
                @if($count == 0)
                  <i class="fas fa-times nonstock"></i>{{__('product-show.out-of-stock')}}
                @elseif($count>0)
                  <i class="fas fa-check"></i>{{__('product-show.in-stock')}}
                @endif
              </div>
            </div>

            <div class="price flex-wrap">
              <div class="default">
{{--                                <span class="text_price_XVR label-name">{{__('product-show.price')}} </span>--}}
                <div class="flex-center price-wrap">
                  <span class="item-price"
                        data-discount='{"0": {"discountFrom": 0, "price": {{$product->getPrice()}}},"1": {"discountFrom": 10, "price": {{$product->getSmallPrice()}}},"2": {"discountFrom": 25, "price": {{$product->getBigPrice()}}}}'
                        data-value="{{number_format($product->getPrice(), 2, '.', '')}}">

                      <span
                      class="only-price">{{number_format($product->getPrice(), 2, '.', '')}}</span> ₴</span>
                  <span class="label-lenght"> {{$product->getRollSize() ? 'за 1 м.п.' : 'за 1  шт'}}</span><span class="cur">$ {{$product->getPriceByDollars($product->getPrice())}}</span>
                </div>
              </div>
            </div>

              @if($product->getRollSize() && $count >= 10)
              <ul class="product-discounts">
              <li>
                <div class="item-text">
                  10 м.п. і більше: <span class="colord">{{$product->getSmallPrice()}} ₴</span> <span class="cur">| {{$product->getPriceByDollars($product->getSmallPrice())}} $</span>
                </div>
                <div class="item-btn">
                  <span data-quantity="10"
                        data-chosen="{{__('product-show.chosen')}}"
                        data-choose="{{__('product-show.choose')}}"
                        class="select-quantity">{{__('product-show.choose')}}</span>
                </div>
              </li>

              <li>
                <div class="item-text">
                  25 м.п. і більше: <span class="colord">{{$product->getBigPrice()}} ₴</span> <span class="cur">| {{$product->getPriceByDollars($product->getBigPrice())}} $</span>
                </div>
                <div class="item-btn">
                  <span data-quantity="25"
                        data-chosen="{{__('product-show.chosen')}}"
                        data-choose="{{__('product-show.choose')}}"
                        class="select-quantity">{{__('product-show.choose')}}</span>
                </div>
              </li>
            </ul>
              @endif
          </div>
        </div>
        <div id="product" class="bottom">
          <div class="buttons">
            <div class="input-group-quantity-xvr product-count" id="quantity-block">
              <div class="input-group-quantity-default-xvr">
                <label class="text_quantity_unit label-name" for="input-quantity-{{$product->id}}">
                  {{__('product-show.quantity')}}
                </label>
                <div class="quantity-input-wrappper">
                  <div class="input-group quantity-input-group">
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-primary" id="minus-btn">-</button>
                  </span>
                    <input type="text" name="quantity" data-min="{{$count > 0 ? $product->getMinOrderCount() : 0}}" data-max="{{$count}}" data-step="{{$count > 0 ? $product->getOrderStep(): 0}}" value="{{$count > 0 ?$product->getDefaultQuantity() : 0}}"
                           id="input-quantity-{{$product->id}}" class="input-quantity">
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-primary colord" id="plus-btn">+</button>
                  </span>
                  </div>

                  <div class="max-value-group">
                    <span class="max-value">{{__('product-show.max-quantity', ['max' => $count])}}</span>
                    <span class="select-max" data-quantity="{{$count}}">{{__('product-show.select-max')}}</span>
                  </div>
                </div>
              </div>
            </div>

              @if($count > 0)
              <div class="price flex-wrap">
                  <div class="default">
                      <div class="top-wrapper">
                          <span class="text-total-summ">{{__('product-show.total')}}</span>
                          <span class="text-discount">
                                              {!! __('product-show.discont-text')  !!}
                                            </span>
                      </div>
                      <span class="autocalc-product-price"><span class="total-price">0.00</span> ₴</span>
                  </div>
              </div>
              @endif


              <div class="alert alert-info alert-info-xvr">
              {!! __('product-show.total-description', ['min' => $product->getMinOrderCount(), 'step' => $product->getOrderStep()]) !!}
            </div>

            @if($count == 0)
              <div class="stock-in-alert-wrapper">
                <input type="text" placeholder="Введіть номер або email">
                <button type="button" class="stock-in-alert"><i
                    class="fas fa-chevron-right"></i>{!! __('product-show.stock-in-alert') !!}</button>
              </div>
            @elseif($count>0)
                  <button type="button" id="button-cart" class="cart button colord" data-product-id="{{$product->id}}">
                      <i class="fas fa-chevron-right"></i>{{__('product-show.add-to-cart')}}
                  </button>
              <button class="speed button btn-quick-order btn-lg fast-order-popup-open" type="button"
                      title="{{__('product-show.fast-buy')}}">
                <span>{{__('product-show.fast-buy')}}</span>
              </button>
            @endif

          </div>
          <div class="consult-open consult-popup-open button">
            {{__('product-show.want-to-learn-more')}}
            <span>{{__('product-show.order-a-consultation')}}</span>
          </div>
        </div>
      </div>
      <div class="product-page-bottom flex-justify">
        <div class="item delivery">
          <span class="icon"><img width="98" height="66" src="{{asset('assets/img/icons/delivery.svg')}}"
                                  alt="{{__('product-show.delivery')}}"></span>
          <span class="title">{{__('product-show.delivery')}}</span>
          <p>{{__('product-show.delivery-description')}}</p>
        </div>
        <div class="item pay">
          <span class="icon"><img width="98" height="66" src="{{asset('assets/img/icons/credit-card.svg')}}"
                                  alt="{{__('product-show.payment')}}"></span>
          <span class="title">{{__('product-show.payment')}}</span>
          <p><img alt="{{__('product-show.payment')}}" src="{{asset('assets/img/pay.svg')}}"
                  style="width: 350px; float: left;" class="note-float-left"><br></p>
        </div>
          @if($product->getWarranty())
        <div class="item garant">
          <span class="icon"><img width="68" height="79" src="{{asset('assets/img/icons/protection.svg')}}"
                                  alt="{{__('product-show.guarantee')}}"></span>
          <span class="title">{{__('product-show.guarantee')}}</span>
          <p>{{$product->getWarranty() ?? '-' }}</p>
        </div>
          @endif
      </div>

      <div class="code-wishlist-wrapper mobil">
        <div class="sku">{{__('general-translate.product_card.code')}} {{$product->code}}</div>
        <div class="wishlist">
          <button type="button" title="В закладки" class="button far fa-heart"></button>
        </div>
      </div>
    </div>

    <div class="product-page-example swiper">
      <div class="swiper-wrapper">
          @foreach($product->getBannerImages() as $image)
        <div class="swiper-slide item" >
          <img src="{{$image->getUrl()}}" title="" alt="" loading="lazy" class="swiper-lazy" >
          <div class="swiper-lazy-preloader swiper-lazy-preloader-white"></div>
        </div>
          @endforeach
      </div>
      <div class="bottom flex-center">
        <div class="swiper-button-prev button"></div>
        <div class="desc">{{$product->banner_title}}</div>
        <div class="swiper-button-next button"></div>
      </div>
    </div>

    <div class="product-page-info flex-justify wrap">
      <div class="item">
          @if($product->descriptions)
        <div class="title">{{__('product-show.description')}}</div>
        <div class="text">{{$product->descriptions}}</div>
          @endif
      </div>
        @if($product->getBenefits())
      <div class="item width-33">
        <div class="title">{{__('product-show.advantages')}}</div>
        <div class="text">{{$product->getBenefits()}}
        </div>
      </div>
        @endif
        @if($product->getApplication())
      <div class="item width-33">
        <div class="title">{{__('product-show.application')}}</div>
        <div class="text">{{$product->getApplication()}}
        </div>
      </div>
        @endif
    </div>
      @if($product->getMasterQualification() || $product->getRoomTemperature() || $product->getStoreTerms())
    <div class="product-page-claim wrap row">
      <div class="title">{{__('product-show.requirements')}}</div>
      <div class="desc">{{__('product-show.requirements_desc')}}</div>
      <div class="list flex-justify">
          @if($product->getMasterQualification())
        <div class="item">
          <div class="image"><img data-src="{{asset('assets/img/icons/Qualification-'.$product->getMasterQualification().'.svg')}}"
                                  alt="Кваліфікація майстра - Вимоги" title="Кваліфікація майстра - Вимоги"></div>
          <div class="value">{{$product->getMasterQualification()}}<sup>/5</sup></div>
          <div class="name">{{__('product-show.masters-qualification')}}</div>
        </div>
          @endif
              @if($product->getRoomTemperature())
        <div class="item">
          <div class="image"><img data-src="{{asset('assets/img/icons/Temperature.png')}}"
                                  alt="Температура приміщення - Вимоги" title="Температура приміщення - Вимоги"></div>
          <div class="value">{{__('product-show.room-temperature-val', ['temperature' => $product->getRoomTemperature()])}}</div>
          <div class="name">{{__('product-show.room-temperature')}}</div>
        </div>
              @endif
              @if($product->getStoreTerms())
        <div class="item">
          <div class="image"><img data-src="{{asset('assets/img/icons/Term.png')}}" alt="Термін зберігання - Вимоги"
                                  title="Термін зберігання - Вимоги"></div>
          <div class="value">{{$product->getStoreTerms()}}</div>
          <div class="name">{{__('product-show.expiration-date')}}</div>
        </div>
              @endif
      </div>
    </div>
      @endif
  </section>

  @include('base.components.examples-of-work')
  @include('base.components.latest')
  @include('base.components.consult-popup')
  @include('base.components.fast-order-popup')

  @push('scripts')
    <script src="{{asset('js/jquery/swiper/js/swiper.jquery.min.js')}}"></script>
    <script src="{{mix('build/js/productShow.js')}}"></script>
    <script src="{{asset('third-party/lazyloadmin.js')}}"></script>
  @endpush

@endsection
