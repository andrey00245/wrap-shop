@extends('base.layouts.checkout.app')


@section('content')

    {{--  <div class="home-top">--}}
    {{--    <div class="ellipse orange"></div>--}}
    @push('scripts')
        {{--    <script src="{{asset('js/jquery/swiper/js/swiper.jquery.min.js')}}"></script>--}}
    @endpush
    @push('styles')
        @if($theme === 'dark')
            <link rel="stylesheet" type="text/css" href="{{mix('build/css/simple-dark.css')}}">
        @else
            <link rel="stylesheet" type="text/css" href="{{mix('build/css/simple-light.css')}}">
        @endif

    @endpush
    {{--  </div>--}}


    <section id="content" class="page-checkout wrap row">
        @if($cartItemsCount == 0)
            <div class="simple-content">
                <div id="simplecheckout_form_0">
                    <div class="simplecheckout">
                        <div class="content">{{__('checkout.cart_empty')}}</div>
                        <div style="display:none;" id="simplecheckout_cart_total">0</div>
                        <div class="simplecheckout-button-block buttons">
                            <div class="simplecheckout-button-right right"><a href="{{route('index')}}"
                                                                              class="button btn-primary button_oc btn colord"><i
                                        class="fas fa-chevron-right"></i><span>{{__('checkout.continue')}}</span></a></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if($cartItemsCount > 0)
            <div class="simple-content">
                <div id="simplecheckout_form_0">
                    <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm" data-has-long-film="{{ collect($cartItems)->contains(function($i){ return $i['product']->getRollSize() && $i['quantity'] >= 1; }) ? '1' : '0' }}">
                        @csrf
                        <for class="simplecheckout">
                            <div class="simplecheckout-step" style="display: flex;">
                                <div class="simplecheckout-left-column flex-justify">
                                    @guest
                                        <div class="has-account">
                                            <div class="container">
                                                <div class="has-account-background">
                                                    <p><span>{{__('checkout.do_you_have_account')}}</span><span
                                                            class="login-show login-btn login-popup-open general-popup-btn" data-popup="login-popup">{{__('checkout.login')}}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endguest
                                    <div class="data-for-order">
                                        <div class="container">
                                            <div class="input-fields-wrapper">
                                                <p class="title"><span>01</span> {{__('checkout.contact_data')}}</p>
                                                <div class="input-group">
                                                    <label for="phone">{{__('checkout.phone_number')}}</label>
                                                    <input type="text" name="phone_checkout" id="phone_checkout"
                                                           placeholder="{{__('checkout.phone')}}"
                                                           value="{{ old('phone') ?? (auth()->check() ? auth()->user()->phone : '') }}">
                                                    @error('phone')
                                                    <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="input-group">
                                                    <label for="first-name">{{__('checkout.first_name')}}</label>
                                                    <input type="text" name="first-name" placeholder="{{__('checkout.first_name')}}"
                                                           id="first-name"
                                                           value="{{ old('first-name') ?? (auth()->check() ? auth()->user()->name : '') }}">
                                                    @error('first-name')
                                                    <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="input-group">
                                                    <label for="last-name">{{__('checkout.surname')}}</label>
                                                    <input type="text" name="last-name" id="last-name"
                                                           placeholder="{{__('checkout.surname')}}"
                                                           value="{{ old('last-name') ?? (auth()->check() ? auth()->user()->last_name : '') }}">
                                                    @error('last-name')
                                                    <div class="error">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                @guest
                                                    <div class="input-group">
                                                        <label for="email">Email</label>
                                                        <input type="email" name="email" id="email" placeholder="Email"
                                                               value="{{ old('email') }}">
                                                        @error('email')
                                                        <div class="error">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endguest
                                            </div>

                                            <div class="input-fields-wrapper">
                                                <p class="title"><span>02</span> {{__('checkout.delivery')}}</p>

                                                <div class="simplecheckout-block-content" id="simplecheckout_shipping"
                                                     style="overflow: visible;">
                                                    <div class="radio">
                                                        <label for="pickup" class="custom-radio">
                                                            <input type="radio" data-onchange="reloadAll"
                                                                   name="shipping_method" value="pickup"
                                                                   id="pickup" {{ old('shipping_method') == 'pickup' ? 'checked' : '' }}>
                                                            <span class="radio-label">{!! __('checkout.pickup_from_atelier') !!}</span>
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label for="flat" class="custom-radio">
                                                            <input type="radio" data-onchange="reloadAll"
                                                                   name="shipping_method" value="flat"
                                                                   id="flat" {{ old('shipping_method') == 'flat' ? 'checked' : '' }}>
                                                            <span class="radio-label">{{__('checkout.kyiv_delivery')}}</span>
                                                        </label>
                                                    </div>
                                                    <!-- Nova Poshta Group -->
                                                    <div class="nova-poshta-group">
                                                        <div class="nova-poshta-main">
                                                            <div class="radio">
                                                                <label for="novaposhta" class="custom-radio">
                                                                    <input type="radio" data-onchange="reloadAll"
                                                                           name="shipping_method" value="novaposhta"
                                                                           id="novaposhta" {{ old('shipping_method') == 'novaposhta' ? 'checked' : '' }}>
                                                                    <span class="radio-label">{{__('checkout.nova_poshta')}}</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="nova-poshta-options" id="nova-poshta-options" style="display: none;">
                                                            <div class="nova-poshta-subtitle" id="nova-poshta-subtitle">Адреса доставки</div>
                                                            <div class="nova-poshta-variants">
                                                                <div class="radio">
                                                                    <label for="novaposhta_branch" class="custom-radio">
                                                                        <input type="radio" data-onchange="reloadAll"
                                                                               name="nova_poshta_type" value="branch"
                                                                               id="novaposhta_branch" {{ old('nova_poshta_type') == 'branch' ? 'checked' : '' }}>
                                                                        <span class="radio-label">{{__('checkout.nova_poshta_branch')}}</span>
                                                                    </label>
                                                                </div>
                                                                <div class="radio" id="locker-radio" style="{{ collect($cartItems)->contains(function($i){ return $i['product']->getRollSize() && $i['quantity'] >= 1; }) ? 'display: none;' : '' }}">
                                                                    <label for="novaposhta_locker" class="custom-radio">
                                                                        <input type="radio" data-onchange="reloadAll"
                                                                               name="nova_poshta_type" value="locker"
                                                                               id="novaposhta_locker" {{ old('nova_poshta_type') == 'locker' ? 'checked' : '' }}>
                                                                        <span class="radio-label">{{__('checkout.nova_poshta_locker')}}</span>
                                                                    </label>
                                                                </div>
                                                                <div class="radio">
                                                                    <label for="novaposhta_courier" class="custom-radio">
                                                                        <input type="radio" data-onchange="reloadAll"
                                                                               name="nova_poshta_type" value="courier"
                                                                               id="novaposhta_courier" {{ old('nova_poshta_type') == 'courier' ? 'checked' : '' }}>
                                                                        <span class="radio-label">{{__('checkout.nova_poshta_courier')}}</span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <fieldset id="novaposhta_desc" class="radio-description">{{__('checkout.nova_poshta_desc')}}<br><small>{{ __('checkout.films_cargo_only') }}</small></fieldset>
                                                    </div>
                                                    @if(auth()->check() && auth()->user()->addresses->count() > 0)
                                                        <div class="radio">
                                                            <label for="my_addresses" class="custom-radio">
                                                                <input type="radio" data-onchange="reloadAll"
                                                                       name="shipping_method" value="my_addresses"
                                                                       id="my_addresses" {{ old('shipping_method') == 'my_addresses' ? 'checked' : '' }}>
                                                                <span class="radio-label">{{__('checkout.my_addresses')}}</span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="input-fields-wrapper" style="display: none"
                                                 id="simplecheckout_shipping_address">
                                                <p class="title">{{__('checkout.delivery_address')}}</p>

                                                <div class="simplecheckout-block-content">
                                                    <fieldset>
                                                        <div class="input-group">
                                                            <label for="city">{{__('checkout.city')}}</label>
                                                            @auth
                                                                <div id="city-select-wrapper" style="display:none">
                                                                    <select id="city_select" name="city_select"
                                                                            class="form-control custom-select">
                                                                        <option value="">{{__('checkout.select_city')}}</option>
                                                                        @foreach(auth()->user()?->addresses as $address)
                                                                            <option value="{{ $address->city }}"
                                                                                    data-address="{{ $address->address }}"
                                                                                {{ old('city') == $address->city ? 'selected' : '' }}>
                                                                                {{ $address->city }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                @error('city_select')
                                                                <div class="error">{{ $message }}</div>
                                                                @enderror
                                                            @endauth
                                                            <div id="city-input-wrapper">
                                                                <input type="text" id="city" name="city"
                                                                       value="{{ old('city') }}" class="form-control"
                                                                       placeholder="{{__('checkout.city')}}">
                                                                <ul class="dropdown-suggestions" id="city-suggestions"
                                                                    style="display: none;"></ul>
                                                            </div>

                                                            <!-- Поле адреса для "Мои адреса" -->
                                                            <div class="input-group" id="my-address-fields" style="display: none;">
                                                                <label for="my_address">{{__('checkout.branch_address')}}</label>
                                                                <input type="text" id="my_address" name="my_address"
                                                                       class="form-control" readonly>
                                                                @error('my_address')
                                                                <div class="error">{{ $message }}</div>
                                                                @enderror
                                                            </div>

                                                            @error('city')
                                                            <div class="error">{{ $message }}</div>
                                                            @enderror
                                                            <div style="display:none;"
                                                                 data-for="shipping_address_address_1"
                                                                 data-for-type="text"
                                                                 data-rule="notEmpty"
                                                                 class="simplecheckout-error-text simplecheckout-rule"
                                                                 data-not-empty="1" data-required="true">{{__('checkout.this_field_required')}}
                                                            </div>
                                                        </div>

                                                        <!-- Поле для доставки по Киеву -->
                                                        <div class="input-group" id="kyiv-fields" style="display: none;">
                                                            <label for="kyiv_address">{{__('checkout.delivery_address')}}</label>
                                                            <input type="text" id="kyiv_address"
                                                                   name="kyiv_address" class="form-control"
                                                                   placeholder="{{__('checkout.input_delivery_address')}}" disabled>
                                                            @error('kyiv_address')
                                                            <div class="error">{{ $message }}</div>
                                                            @enderror
                                                            <div style="display:none;"
                                                                 data-for="kyiv_address"
                                                                 data-for-type="text"
                                                                 data-rule="notEmpty"
                                                                 class="simplecheckout-error-text simplecheckout-rule"
                                                                 data-not-empty="1" data-required="true">{{__('checkout.this_field_required')}}
                                                            </div>
                                                        </div>

                                                        <!-- Поля для отделения -->
                                                        <div class="input-group" id="branch-fields">
                                                            <label for="shipping_address">{{__('checkout.branch_address')}}</label>
                                                            <input type="text" id="shipping_address"
                                                                   name="shipping_address" class="form-control"
                                                                   placeholder="{{__('checkout.input_branch')}}" disabled>
                                                            <input type="hidden" name="novaposhta_warehouse_ref" id="novaposhta_warehouse_ref">

                                                            <ul class="dropdown-suggestions" id="address-suggestions"
                                                                style="display: none;"></ul>
                                                            @error('shipping_address')
                                                            <div class="error">{{ $message }}</div>
                                                            @enderror
                                                            <div style="display:none;"
                                                                 data-for="shipping_address_address_1"
                                                                 data-for-type="text"
                                                                 data-rule="notEmpty"
                                                                 class="simplecheckout-error-text simplecheckout-rule"
                                                                 data-not-empty="1" data-required="true">{{__('checkout.this_field_required')}}
                                                            </div>
                                                        </div>

                                                        <!-- Поля для почтомата -->
                                                        <div class="input-group" id="locker-fields" style="display: none;">
                                                            <label for="locker_address">{{__('checkout.nova_poshta_locker')}}</label>
                                                            <input type="text" id="locker_address"
                                                                   name="locker_address" class="form-control"
                                                                   placeholder="{{__('checkout.input_locker')}}" disabled>
                                                            <input type="hidden" name="locker_warehouse_ref" id="locker_warehouse_ref">
                                                            <ul class="dropdown-suggestions" id="locker-suggestions"
                                                                style="display: none;"></ul>
                                                            @error('locker_address')
                                                            <div class="error">{{ $message }}</div>
                                                            @enderror
                                                            <div style="display:none;"
                                                                 data-for="locker_address"
                                                                 data-for-type="text"
                                                                 data-rule="notEmpty"
                                                                 class="simplecheckout-error-text simplecheckout-rule"
                                                                 data-not-empty="1" data-required="true">{{__('checkout.this_field_required')}}
                                                            </div>
                                                        </div>

                                                        <!-- Поля для курьера -->
                                                        <div class="input-group" id="courier-fields" style="display: none;">
                                                            <label for="courier_street">{{__('checkout.street')}}</label>
                                                            <input type="text" id="courier_street"
                                                                   name="courier_street" class="form-control"
                                                                   placeholder="{{__('checkout.street')}}" disabled>
                                                            @error('courier_street')
                                                            <div class="error">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="input-group" id="courier-house-fields" style="display: none;">
                                                            <label for="courier_house">{{__('checkout.house_apartment')}}</label>
                                                            <input type="text" id="courier_house"
                                                                   name="courier_house" class="form-control"
                                                                   placeholder="{{__('checkout.house_apartment')}}" disabled>
                                                            @error('courier_house')
                                                            <div class="error">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </fieldset>
                                                </div>
                                            </div>


                                            <div class="input-fields-wrapper">
                                                <p class="title"><span>03</span> {{__('checkout.payment')}}</p>

                                                <div class="simplecheckout-block-content" id="simplecheckout_shipping"
                                                     style="overflow: visible;">
                                                    <div class="radio">
                                                        <label for="cash" class="custom-radio">
                                                            <input type="radio" data-onchange="reloadAll"
                                                                   name="payment_method" value="cash"
                                                                   id="cash" checked="checked">
                                                            <span class="radio-label">{{__('checkout.cash')}}</span>
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label for="online" class="custom-radio">
                                                            <input type="radio" data-onchange="reloadAll"
                                                                   name="payment_method" value="online"
                                                                   id="online">
                                                            <span class="radio-label">{{__('checkout.online')}}</span>
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label for="bank_transfer" class="custom-radio">
                                                            <input type="radio" data-onchange="reloadAll"
                                                                   name="payment_method"
                                                                   value="bank_transfer" id="bank_transfer">
                                                            <span class="radio-label">{{__('checkout.bank_transfer')}}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            <div class="input-group">
                                                <label for="comment">{{__('checkout.want_leave_comment')}}</label>
                                                <input name="comment" id="comment" placeholder="{{__('checkout.comment')}}"
                                                       value="{{ old('comment') }}">
                                                @error('comment')
                                                <div class="error">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="input-group">
                                                <div id="buttons">
                                                    <button type="submit" class="button btn-primary button_oc btn"
                                                            id="submitBtn">
                                                        <i class="fas fa-chevron-right"></i><span>{{__('checkout.accept_and_checkout')}}</span>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="input-group">
                                                @include('base.components.bestseller.bestseller-checkout')
                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <div class="simplecheckout-right-column">
                                    <div class="simplecheckout-block" id="simplecheckout_cart">
                                        <div class="checkout-heading panel-heading ">{{__('checkout.you_order')}} <span
                                                class="checkout-edit cart-open general-popup-btn" data-popup="cart-popup">{{__('checkout.edit')}}</span>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="simplecheckout-cart">
                                                <thead>
                                                <tr>
                                                    <th class="image">{{__('checkout.photo')}}</th>
                                                    <th class="name">{{__('checkout.product_name')}}</th>
                                                    <th class="model">{{__('checkout.model')}}</th>
                                                    <th class="quantity"><span title="{{__('checkout.count_full')}}">{{__('checkout.count')}}</span></th>
                                                    <th class="price">{{__('checkout.price')}}</th>
                                                    <th class="total">{{__('checkout.total')}}</th>
                                                    <th class="remove"></th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                @foreach($cartItems as $item)
                                                    <tr class="item flex-justify">
                                                        <td class="image">
                                                            <a
                                                                href="{{route('products.show', ['product' => $item['product']->slugEn])}}"><img
                                                                    loading="lazy"
                                                                    src="{{ optional($item['product']->getMedia('images')->first())->getUrl('preview_webp') ?? asset('assets/img/no-image.png') }}"
                                                                    alt="{{$item['product']->name}}"
                                                                    title="{{$item['product']->name}}"
                                                                    class="img-thumbnail"></a>
                                                        </td>
                                                        <td class="name">
                                                            <span
                                                                class="cat">{{$item['product']->category->name}}</span><a
                                                                href="{{route('products.show', ['product'=>$item['product']->slugEn])}}">{{$item['product']->name}}</a>
                                                            <div class="options">
                                                            </div>
                                                        </td>
                                                        <td class="model">11059</td>
                                                        <td class="quantity">
                                                            <div class="input-group-grid-xvr">
                                                                <div class="input-group-quantity-cart-xvr">
                                                                    <div class="pull-left">
                                                                        {{__('checkout.count_full')}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="price">
                                                            {{$item['product']->getPrice()}} ₴<span
                                                                class="price-unit-xvr"></span>
                                                        </td>
                                                        <td class="total">
                                                            {{$item['product']->getPriceByCount($item['quantity']) ?? $sum}}
                                                            ₴<span
                                                                class="count">x {{$item['product']->getRollSize() ? __('checkout.quantity_mp', ['quantity' => $item['quantity']]) : __('checkout.quantity_pc', ['quantity' => $item['quantity']])}}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="line-after-products"></div>

                                        <div class="simplecheckout-cart-info flex-justify">
                                            <div class="left">
                                                <div class="simplecheckout-cart-total">
                                                    <span class="inputs-open button"><span>{{__('checkout.have_a_promo')}}</span><i
                                                            class="fal fa-tag"></i></span>
                                                    <span class="inputs form-horizontal"><input class="form-control"
                                                                                                type="text"
                                                                                                data-onchange="reloadAll"
                                                                                                name="coupon"
                                                                                                value=""></span>
                                                </div>
                                                <div class="simplecheckout-cart-total">
                                                    <span class="inputs-open button"><span>{{__('checkout.have_a_sert')}}:</span><i
                                                            class="fal fa-gift-card"></i></span>
                                                    <span class="inputs form-horizontal"><input class="form-control"
                                                                                                type="text"
                                                                                                name="voucher"
                                                                                                data-onchange="reloadAll"
                                                                                                value=""></span>
                                                </div>
                                                <div class="simplecheckout-cart-total simplecheckout-cart-buttons">
                      <span class="inputs buttons"><a id="simplecheckout_button_cart" data-onclick="reloadAll"
                                                      class="button btn-primary button_oc btn"><span>{{__('checkout.update')}}</span></a></span>
                                                </div>
                                            </div>
                                            <div class="right">
                                                <div class="simplecheckout-cart-total" id="total_sub_total">
                                                    <span class="simplecheckout-cart-total-label">{{__('checkout.summ')}}</span>
                                                    <span class="simplecheckout-cart-total-value">{{$sum}}</span> ₴
                                                </div>
                                                <div class="simplecheckout-cart-total" id="total_total">
                                                    <span class="simplecheckout-cart-total-label">{{__('checkout.total')}}</span>
                                                    <span class="simplecheckout-cart-total-value">{{$sum}}</span> ₴
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="remove" value="" id="simplecheckout_remove">
                                        <div style="display:none;" id="simplecheckout_cart_total">2</div>
                                    </div>
                                </div>
                            </div>
                        </for>
                    </form>
                </div>
            </div>
        @endif
    </section>
    @push('scripts')
        <script src="{{mix('build/js/checkoutPage.js')}}"></script>
    @endpush

@endsection

@push('fixed-catalog')
    @include('base.components.categories-catalog')
@endpush
