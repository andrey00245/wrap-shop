@if($cartItemsCount === 0 || $cartItems === 0)
    <ul>
        <li>
            <div class="cart-mini-empty">

                <span style="opacity:1;font-size:28px;padding:0">🤨</span>
                <span>{{__('popup.cart_popup.cart_is_empty')}}</span>
                {{__('popup.cart_popup.not_late_to_fix_it')}}
            </div>
{{--            <div class="cart-mini-button">--}}
{{--                <div class="continion colord close-cart-popup">--}}
{{--                <a href="{{route('index')}}"><i--}}
{{--                            class="fas fa-chevron-right"></i>{{__('popup.cart_popup.shopping')}}--}}
{{--                </a>--}}
{{--            </div>--}}
{{--            </div>--}}
        </li>
    </ul>
@else
<ul>
    <li>
        <table class="table table-striped" id="cart-table">
            <tbody>
            @foreach($cartItems as $item)
                <tr class="item flex-justify" data-id="{{ $item['product']->id }}">
                <td class="image"><a
                            href="{{route('products.show', ['product' => $item['product']->slugEn])}}"><img
                                loading="lazy"
                                src="{{$item['product']->getMedia('images')[0]->getUrl('preview')}}"
                                alt="{{$item['product']->getName()}}"
                                title="{{$item['product']->getName()}}" class="img-thumbnail"></a></td>
                    <td class="name"><span class="cat">{{$item['product']->category->name}}</span><a
                            href="{{route('products.show', ['product'=>$item['product']->slugEn])}}">{{$item['product']->getName()}}</a>
                    </td>
                    <td class="price-default">
                        {{$item['product']->getPrice()}} ₴<span class="price-unit-xvr"></span>
                        <span class="length">
                @include('base.svg-icons.question'){{$item['product']->getRollSize() ? __('product-index.per_lin_m') : __('product-index.per_pc')}}
              </span>
                    </td>
                    <td class="count flex-justify" data-counter="">
                        <div class="cart-quantity-input-wrappper">
                            <div class="input-group quantity-input-group">
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-primary" id="minus-btn-cart">-</button>
                  </span>
                                <input type="text"  name="quantity" data-min="{{$item['product']->getMinOrderCount()}}" data-max="{{$item['product']->getStock()}}" data-step="{{$item['product']->getOrderStep()}}" value="{{$item['quantity']}}"
                                       class="input-quantity">
                                <span class="input-group-btn">
                    <button type="button" class="btn btn-primary colord" id="plus-btn-cart">+</button>
                  </span>
                            </div>

                            <div class="max-value-group">
                                <span class="max-value">{{__('product-show.max-quantity', ['max' => $item['product']->getStock()])}}</span>
                            </div>
                        </div>
                    </td>

                    <td class="price price-all">
                        {{$item['product']->getPriceByCount($item['quantity']) ?? $sum}} ₴
                    </td>
                    <td class="delete">
                        <button type="button" title="{{__('popup.cart_popup.delete')}}" data-id="{{$item->product->id ?? $item['product']->id}}"
                                class="btn btn-danger btn-xs remove-cart-button"><i
                                class="fas fa-trash"></i></button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </li>
    @if($cartItemsCount > 0 || count($cartItems) > 0)
    <li>
        <div class="cart-mini-bott">
            <div class="total">
                <div class="name_background_wrapper">
                    <div class="background"></div>
                    <div class="name">{{__('popup.cart_popup.total')}}</div>
                </div>
                <div class="value">{!! $sum_html !!} ₴</div>
            </div>
            <div class="cart-mini-button">
                <a href="{{route('checkout')}}" title="{{__('popup.cart_popup.checkout')}}" class="checkout colord"><i
                        class="fas fa-chevron-right"></i>{{__('popup.cart_popup.checkout')}}</a>
                <div class="continion close-cart-popup"><i class="fas fa-chevron-right"></i>{{__('popup.cart_popup.continue_shopping')}}</div>
            </div>
        </div>
    </li>
    @endif
</ul>
@endif
