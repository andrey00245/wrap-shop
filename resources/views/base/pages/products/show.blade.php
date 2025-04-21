@extends('base.layouts.app')


@section('content')

    @push('styles')
        @if($theme === 'dark')
            <link rel="stylesheet" href="{{mix('build/css/style-product-dark.css')}}">
        @else
            <link rel="stylesheet" href="{{mix('build/css/style-product-light.css')}}">
        @endif
        {{--    <link rel="stylesheet" href="https://wrap.shop/catalog/view/javascript/xvrproductquantities.css">--}}
    @endpush

    <nav class="breadcrumbs breadcrumbs-product wrap row">
        <ul class="flex-center">
            <li><a href="{{route('index')}}" title="{{__('header_footer.home')}}"
                   class="button">{{__('header_footer.home')}}</a></li>
            @php
                $category = $product->category;
                $parents[] = $category;

                while ($category->parent) {
                    $parents[] = $category->parent;
                    $category = $category->parent;
                }
                $reversedParents = array_reverse($parents);

                foreach ($reversedParents as $parent) {
                    echo '<li><a href="'.route('products.category', ['category' => $parent->slug]).'" title="12231231" class="button">'.$parent->name.'</a></li>';
                }
            @endphp
        </ul>
    </nav>

    <section class="product-page row" id="product-product">
        <div class="product-page-top flex-justify wrap">
            <div class="product-page-left">
                <div class="code-wishlist-wrapper">
                    <div class="sku deskopt">{{__('general-translate.product_card.code')}} {{$product->code}}</div>
                    <div class="wishlist desktop">
                        <button type="button" title="В закладки"
                                class="button {{$product->isFavorite() ? 'fas in-wishlist' : 'far'}} fa-heart"
                                data-product-id="{{$product->id}}"></button>
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
                            <div class="item flex-justify">{{ $attribute->name }} <span class="label">{{$value}}</span>
                            </div>
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


                <div id="product-slider" class="splide product-images-slider">
                    <div class="splide__track">
                        <ul class="splide__list">
                            @foreach($product->getMedia('images') as $key => $image)
                                <li class="splide__slide">
                                    <a
                                        data-fancybox="product-show-gallery"
                                        data-caption="{{$product->getName()}}"
                                        href="{{$image->getUrl()}}"
                                        title="{{$product->getName()}}"
                                        role="group">
                                        <img loading="lazy" src="{{$image->getUrl()}}"
                                             title="{{$product->getName()}}"
                                             alt="{{$product->getName()}}">
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div
                    id="thumbnail-carousel"
                    class="splide product-images-nav">
                    <div class="splide__arrows"></div>

                    <div class="splide__track">
                        <ul class="splide__list">
                            @foreach($product->getMedia('images') as $key => $image)
                                <li class="splide__slide">
                                    <img data-src="{{$image->getUrl('preview')}}"
                                         title="{{$product->getName()}}"
                                         alt="{{$product->getName()}}"
                                         src="{{$image->getUrl('preview')}}">
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>
            <div class="product-page-right">
                <div class="top flex-justify">
                    <h1 class="title">{{$product->getName()}}</h1>
                </div>
                <div class="center flex-justify">
                    @php
                        $count= $product->getStock();
                        $secondStock = $product->getSecondStock();
                        $thirdStock = $product->getThirdStock();
                    @endphp
                    <div class="left">
                        <div class="info flex-center">
                            <div class="availability">
                                @if($product->getUnderOrder())
                                    <i class="fas fa-times nonstock"></i>{{__('product-show.under_order')}}
                                @endif
                                @if($count == 0 && !$product->getUnderOrder())
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
                                    @if($secondStock && $thirdStock)
                                        @php
                                            $discountData = [
                                                "0" => ["discountFrom" => 0, "price" => $product->getPrice()],
                                                "1" => ["discountFrom" => $secondStock, "price" => $product->getSmallPrice()],
                                                "2" => ["discountFrom" => $thirdStock, "price" => $product->getBigPrice()]
                                            ];
                                        @endphp

                                        <span class="item-price"
                                              data-discount='@json($discountData)'
                                              data-value="{{ number_format($product->getPrice(), 2, '.', '') }}">
        <span class="only-price">{{ number_format($product->getPrice(), 2, '.', '') }}</span> ₴
    </span>
                                    @else
                                        <span class="item-price"
                                              data-discount='{"0": {"discountFrom": 0, "price": {{$product->getPrice()}}},"1": {"discountFrom": 10, "price": {{$product->getPrice()}}},"2": {"discountFrom": 25, "price": {{$product->getPrice()}}}}'
                                              data-value="{{number_format($product->getPrice(), 2, '.', '')}}">

                      <span
                          class="only-price">{{number_format($product->getPrice(), 2, '.', '')}}</span> ₴</span>
                                    @endif
                                    <span
                                        class="label-lenght"> {{$product->getRollSize() ? 'за 1 м.п.' : 'за 1  шт'}}</span><span
                                        class="cur">$ {{$product->getPriceByDollars($product->getPrice())}}</span>
                                </div>
                            </div>
                        </div>

                        @if($product->allVolumeVariants()->count())
                            <div class="volume flex-wrap">
                                @php
                                    $allVariants = $product->allVolumeVariants()->prepend($product)->unique('id');
                                @endphp

                                @foreach($allVariants as $variant)
                                    @php
                                        $volume = $variant->attributes()->where('field_name', 'volume')->first()?->pivot?->value ?? '—';
                                        $isCurrent = $variant->id === $product->id;
                                    @endphp

                                    @if($isCurrent)
                                    <a href="void:javascript(0)"
                                       class="volume-box active">
                                        {{ $volume }}
                                    </a>
                                   @else
                                        <a href="{{route('products.show',['product' => $variant->slugEn])}}"
                                           class="volume-box">
                                            {{ $volume }}
                                        </a>
                                   @endif
                                @endforeach
                            </div>
                        @endif

                        @if($product->getRollSize())
                            <ul class="product-discounts">
                                @if($product->getSecondStock() && $product->getSecondStock() <= $count)
                                    <li>
                                        <div class="item-text">
                                            {{$product->getSecondStock()}} м.п. і більше: <span class="colord">{{$product->getSmallPrice()}} ₴</span>
                                            <span class="cur">| {{$product->getPriceByDollars($product->getSmallPrice())}} $</span>
                                        </div>
                                        <div class="item-btn">
                  <span data-quantity="{{$product->getSecondStock()}}"
                        data-chosen="{{__('product-show.chosen')}}"
                        data-choose="{{__('product-show.choose')}}"
                        class="select-quantity">{{__('product-show.choose')}}</span>
                                        </div>
                                    </li>
                                @endif
                                @if($product->getThirdStock() && $product->getThirdStock() <= $count)
                                    <li>
                                        <div class="item-text">
                                            {{$product->getThirdStock()}} м.п. і більше: <span class="colord">{{$product->getBigPrice()}} ₴</span>
                                            <span class="cur">| {{$product->getPriceByDollars($product->getBigPrice())}} $</span>
                                        </div>
                                        <div class="item-btn">
                  <span data-quantity="{{$product->getThirdStock()}}"
                        data-chosen="{{__('product-show.chosen')}}"
                        data-choose="{{__('product-show.choose')}}"
                        class="select-quantity">{{__('product-show.choose')}}</span>
                                        </div>
                                    </li>
                                @endif
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
                                        <input type="text" name="quantity"
                                               data-min="{{$count > 0 ? $product->getMinOrderCount() : 0}}"
                                               data-max="{{$count}}"
                                               data-step="{{$count > 0 ? $product->getOrderStep(): 0}}"
                                               value="{{$count > 0 ?$product->getDefaultQuantity() : 0}}"
                                               id="input-quantity-{{$product->id}}" class="input-quantity quantity-input-show-page">
                                        <span class="input-group-btn">
                    <button type="button" class="btn btn-primary colord" id="plus-btn">+</button>
                  </span>
                                    </div>

                                    <div class="max-value-group">
                                        <span
                                            class="max-value">{{__('product-show.max-quantity', ['max' => $count])}}</span>
                                        <span class="select-max"
                                              data-quantity="{{$count}}">{{__('product-show.select-max')}}</span>
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
                                <button type="button" class="stock-in-alert report-availability-open" data-product-id="{{$product->id}}"><i
                                        class="fas fa-chevron-right"></i>{!! __('product-show.stock-in-alert') !!}
                                </button>
                            </div>
                        @elseif($count>0)
                            <button type="button" id="button-cart" class="cart button colord"
                                    data-product-id="{{$product->id}}">
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
                    <span class="icon">
                        @if($theme === 'dark')
                            <img width="98" height="66" src="{{asset('assets/img/icons/delivery.svg')}}"
                                 alt="{{__('product-show.delivery')}}">
                        @else
                            <img width="98" height="66" src="{{asset('assets/img/icons/delivery-light.svg')}}"
                                 alt="{{__('product-show.delivery')}}">
                        @endif
                    </span>
                    <span class="title">{{__('product-show.delivery')}}</span>
                    <p>{{__('product-show.delivery-description')}}</p>
                </div>
                <div class="item pay">
                    <span class="icon">
                        @if($theme === 'dark')
                            <img width="98" height="66" src="{{asset('assets/img/icons/credit-card.svg')}}"
                                 alt="{{__('product-show.payment')}}">
                        @else
                            <img width="98" height="66" src="{{asset('assets/img/icons/credit-card-light.svg')}}"
                                 alt="{{__('product-show.payment')}}">
                        @endif
                    </span>
                    <span class="title">{{__('product-show.payment')}}</span>
                    <p><img alt="{{__('product-show.payment')}}" src="{{asset('assets/img/pay.svg')}}"
                            style="width: 350px; float: left;" class="note-float-left"><br></p>
                </div>
                <div class="item garant">
          <span class="icon">
              @if($theme === 'dark')
                  <img width="68" height="79" src="{{asset('assets/img/icons/protection.svg')}}"
                       alt="{{__('product-show.guarantee')}}">
              @else
                  <img width="68" height="79" src="{{asset('assets/img/icons/protection-light.svg')}}"
                       alt="{{__('product-show.guarantee')}}">
              @endif
          </span>
                    <span class="title">{{__('product-show.guarantee')}}</span>
                    <p>{{$product->getWarranty() ?? '12 мiсяцiв' }}</p>
                </div>
            </div>

            <div class="code-wishlist-wrapper mobil">
                {{--                <div class="sku">{{__('general-translate.product_card.code')}} {{$product->code}}</div>--}}
                <div class="wishlist">
                    <button
                        type="button"
                        title="В закладки"
                        {{--                        class="button {{$product->isFavorite() ? 'fas in-wishlist' : 'far'}} fa-heart"--}}
                        {{--                        data-product-id="{{$product->id}}"--}}
                    ></button>
                </div>
            </div>
        </div>

        @if($product->getBannerImages()->count() !== 0)
            <div id="product-example-slider" class="splide product-page-example">
                <div class="splide__track">
                    <ul class="splide__list">
                        @foreach($product->getBannerImages() as $image)
                            <li class="splide__slide">
                                <a
                                    data-fancybox="product-show-gallery"
                                    data-caption="{{$product->getName()}}"
                                    href="{{$image->getUrl()}}"
                                    title="{{$product->getName()}}"
                                    role="group">
                                    <img loading="lazy" src="{{$image->getUrl()}}"
                                         title="{{$product->getName()}}"
                                         alt="{{$product->getName()}}">
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="bottom flex-center">
                    <div class="splide__arrows">
                        <div class="desc">{{$product->banner_title}}</div>
                    </div>
                </div>
            </div>
        @endif

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
                            <div class="image"><img
                                    data-src="{{asset('assets/img/icons/Qualification-'.$product->getMasterQualification().'.svg')}}"
                                    alt="Кваліфікація майстра - Вимоги" title="Кваліфікація майстра - Вимоги"></div>
                            <div class="value">{{$product->getMasterQualification()}}<sup>/5</sup></div>
                            <div class="name">{{__('product-show.masters-qualification')}}</div>
                        </div>
                    @endif
                    @if($product->getRoomTemperature())
                        <div class="item">
                            <div class="image"><img data-src="{{asset('assets/img/icons/Temperature.png')}}"
                                                    alt="Температура приміщення - Вимоги"
                                                    title="Температура приміщення - Вимоги"></div>
                            <div
                                class="value">{{__('product-show.room-temperature-val', ['temperature' => $product->getRoomTemperature()])}}</div>
                            <div class="name">{{__('product-show.room-temperature')}}</div>
                        </div>
                    @endif
                    @if($product->getStoreTerms())
                        <div class="item">
                            <div class="image"><img data-src="{{asset('assets/img/icons/Term.png')}}"
                                                    alt="Термін зберігання - Вимоги"
                                                    title="Термін зберігання - Вимоги"></div>
                            <div class="value">{{$product->getStoreTerms()}}</div>
                            <div class="name">{{__('product-show.expiration-date')}}</div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </section>

    @include('base.components.recommendations')
    @include('base.components.examples-of-work')
    @include('base.components.consult-popup')
    @include('base.components.fast-order-popup')

    @push('scripts')
        {{--        <script src="{{asset('js/jquery/swiper/js/swiper.jquery.min.js')}}"></script>--}}
        <script src="{{mix('build/js/productShow.js')}}"></script>
        <script src="{{asset('third-party/lazyloadmin.js')}}"></script>
    @endpush
<style>
    .volume {
        display: flex;
        gap: 10px;
        margin-top: 1rem;
        margin-bottom: 20px;
    }

    .volume-box {
        display: inline-block;
        padding: 10px;
        text-align: center;
        border: 2px solid rgb(255, 206, 28);
        border-radius: 8px;
        text-decoration: none;
        color: rgb(255, 206, 28);
        font-weight: 500;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }

    .volume-box:hover {
        border-color: rgb(255, 206, 28);
        color: rgb(255, 206, 28);
    }

    .volume-box.active {
        background: rgb(255, 206, 28);
        color: #fff;
        border-color: rgb(255, 206, 28);
    }
</style>
@endsection
