<div id="fast-order-popup" class="popup-overlay popup-right">
    <input type="hidden" name="product-id" id="fast-order-product-id" value="{{$product->id}}">
    <div class="consult-title">{{__('popup.fast_order.title')}}</div>
    <br>
    <div id="order-popup">
        <div class="cart-popup-body">
            <div>
                <table class="table table-striped">
                    <tbody>
                    <tr class="item flex-justify">
                        <td class="image"><a
                                href="{{route('products.show', ['product' => $product->id])}}"><img
                                    loading="lazy"
                                    src="{{$product->getMedia('images')[0]->getUrl('preview')}}"
                                    alt="{{$product->name}}"
                                    title="{{$product->name}}" class="img-thumbnail"></a></td>
                        <td class="name"><span class="cat">{{$product->category->name}}</span><a
                                href="{{route('products.show', $product->id)}}">{{$product->name}}</a>
                        </td>
                        <td class="price price-default">
                            {{$product->getPrice()}} ₴<span class="price-unit-xvr"></span>
                            <span class="length">
                @include('base.svg-icons.question'){{$product->getRollSize() ? 'за 1 м.п.' : 'за 1  шт'}}.
              </span>
                        </td>
                        <td class="count flex-justify" data-counter="" id="quantity-block">
                            <div class="cart-quantity-input-wrappper">
                                <div class="input-group quantity-input-group">
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-primary" id="minus-btn">-</button>
                  </span>
                                    <input type="text" name="quantity" data-min="{{$product->getMinOrderCount()}}" data-max="{{$count}}" data-step="{{$product->getOrderStep()}}" value="{{$product->getDefaultQuantity()}}"
                                           id="input-quantity" class="input-quantity">
                                    <span class="input-group-btn">
                    <button type="button" class="btn btn-primary colord" id="plus-btn">+</button>
                  </span>
                                </div>

                                <div class="max-value-group">
                                    <span class="max-value">{{__('product-show.max-quantity', ['max' => 10])}}</span>
                                </div>
                            </div>

                        </td>

                        <td class="">
                            <div class="price flex-wrap" id="price-block-2">
                                <div class="default">
                                    <span class="autocalc-product-price"><span class="total-price" id="total-price">0.00</span> ₴</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="popup-window active" id="fast-order-form" style="display: block;">
        <div class="inner form-horizontal">
            <div class="form-group">
                <div class="form">
                    <!-- Имя -->
                    <div class="form-group group-name">
                        <label for="name" class="login-info-text text-center margintop20">{!! __('popup.consult_popup.name') !!}</label>
                        <input type="text" id="fast_order_name" name="name" required class="form-control form-field"
                               placeholder="{{__('popup.consult_popup.enter_name_placeholder')}}"
                               value="{{ auth()->check() ? auth()->user()->name : '' }}">
                    </div>

                    <!-- Телефон -->
                    <div class="form-group group-telephone">
                        <label for="phone" class="login-info-text text-center margintop20">{!! __('popup.consult_popup.phone') !!}</label>
                        <input type="tel" id="fast_order_phone" name="phone" required class="form-control form-field"
                               placeholder="{{__('popup.consult_popup.enter_phone_placeholder')}}"
                               value="{{ auth()->check() ? auth()->user()->phone : '' }}">
                    </div>

                    <!-- Email -->
                    <div class="form-group group-email">
                        <label for="email" class="login-info-text text-center margintop20">{{__('popup.consult_popup.email')}}</label>
                        <input type="email" id="fast_order_email" name="email" class="form-control form-field"
                               placeholder="{{__('popup.consult_popup.enter_email_placeholder')}}"
                               value="{{ auth()->check() ? auth()->user()->email : '' }}">
                    </div>

                    <!-- Комментарий -->
                    <div class="form-group group-comment">
                        <label for="comment" class="login-info-text text-center margintop20">{{__('popup.consult_popup.comment')}}</label>
                        <textarea id="fast_order_comment" name="fast_order_comment" class="form-control form-field" rows="4"
                                  placeholder="{{__('popup.consult_popup.enter_comment_placeholder')}}"></textarea>
                    </div>

                    <div class="error-message-fast-order" style="display: none; color:red;"></div>
                </div>
            </div>

            <div class="input-group">
            <span class="input-group-btn">
                <button class="send_otp_btn otp_btn-s btnverifyloginpopup button colord"
                        id="button-submit-fast-order"
                        type="button">
                    <i class="fas fa-chevron-right" aria-hidden="true"></i> {{__('popup.fast_order.submit')}}
                </button>
            </span>
            </div>
        </div>
    </div>

    <div class="popup-consult-thanks flex-center fast-order-success" style="display:none;">
        <i class="fal fa-smile"></i>
        <span>{!! __('popup.fast_order.thanks_message') !!}</span>
    </div>

    <div class="close button fal fa-times fast-order-popup-close"></div>
</div>
