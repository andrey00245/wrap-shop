@extends('base.layouts.checkout.app')


@section('content')

  {{--  <div class="home-top">--}}
  {{--    <div class="ellipse orange"></div>--}}
  @push('scripts')
    <script src="{{asset('js/jquery/swiper/js/swiper.jquery.min.js')}}"></script>
  @endpush
  @push('styles')
    <link rel="stylesheet" href="{{mix('build/css/simple-dark.css')}}">

  @endpush




  {{--  </div>--}}

  <section id="content" class="page-checkout wrap row">
    <div class="simple-content">
      <div id="simplecheckout_form_0">
        <div class="simplecheckout">
          <div class="simplecheckout-step" style="display: flex;">
            <div class="simplecheckout-left-column flex-justify">
              <div class="fast-order">
                <span class="fast-order-title">швидке замовлення</span>
                <div class="container">
                  <div class="fast-order-wrapper">
                  <button class="fast-order-btn"><svg width="47" height="20" viewBox="0 0 47 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M8.90639 2.57909C9.45884 1.88478 9.83367 0.952609 9.73488 0C8.92619 0.0404249 7.93934 0.536112 7.36801 1.23089C6.85498 1.82593 6.40096 2.79714 6.51937 3.70979C7.42704 3.78889 8.33406 3.25388 8.90639 2.57909Z" fill="white"/>
                      <path d="M9.72452 3.88797C8.40619 3.80905 7.28526 4.63974 6.65565 4.63974C6.02576 4.63974 5.06164 3.92775 4.01888 3.9469C2.66169 3.96698 1.40237 4.738 0.713649 5.96428C-0.702932 8.41749 0.339823 12.0565 1.71736 14.0544C2.38629 15.0428 3.1926 16.1313 4.25487 16.0921C5.25859 16.0525 5.65202 15.4391 6.87202 15.4391C8.0912 15.4391 8.44559 16.0921 9.50805 16.0723C10.6099 16.0525 11.2987 15.0834 11.9677 14.0939C12.7351 12.9671 13.0493 11.8791 13.069 11.8193C13.0492 11.7996 10.9443 10.9882 10.9248 8.55543C10.9049 6.51844 12.5774 5.54944 12.6561 5.4894C11.7118 4.08604 10.2361 3.92775 9.72452 3.88797Z" fill="white"/>
                      <path d="M21.2037 1.13116C24.0691 1.13116 26.0644 3.11575 26.0644 6.00517C26.0644 8.90489 24.028 10.8998 21.1319 10.8998H17.9593V15.9691H15.6672V1.13116H21.2037ZM17.9592 8.96659H20.5893C22.585 8.96659 23.7208 7.88699 23.7208 6.01548C23.7208 4.14415 22.585 3.07468 20.5996 3.07468H17.9592V8.96659Z" fill="white"/>
                      <path d="M26.6633 12.8947C26.6633 11.0026 28.1063 9.84065 30.6649 9.69672L33.6121 9.52195V8.68914C33.6121 7.48606 32.8036 6.76624 31.453 6.76624C30.1734 6.76624 29.3752 7.38311 29.181 8.34981H27.0933C27.2161 6.39597 28.8738 4.95642 31.5348 4.95642C34.1443 4.95642 35.8124 6.34459 35.8124 8.51418V15.9691H33.6939V14.1902H33.6429C33.0187 15.3934 31.6575 16.1542 30.2453 16.1542C28.1371 16.1542 26.6633 14.838 26.6633 12.8947ZM33.612 11.9179V11.0644L30.9614 11.2289C29.6412 11.3215 28.8942 11.9077 28.8942 12.8331C28.8942 13.779 29.6719 14.396 30.8591 14.396C32.4044 14.3959 33.612 13.3265 33.612 11.9179Z" fill="white"/>
                      <path d="M37.8123 19.9486V18.1491C37.9758 18.1902 38.3441 18.1902 38.5284 18.1902C39.5517 18.1902 40.1044 17.7584 40.442 16.6479C40.442 16.6272 40.6366 15.9897 40.6366 15.9794L36.7479 5.15173H39.1423L41.8649 13.9538H41.9056L44.6281 5.15173H46.9612L42.9287 16.5346C42.008 19.1569 40.9437 20 38.7127 20C38.5284 20 37.9757 19.9794 37.8123 19.9486Z" fill="white"/>
                    </svg>
                  </button>
                  <button class="fast-order-btn"><svg width="51" height="20" viewBox="0 0 51 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M23.8085 15.6238H21.9423V1.0624H26.8895C28.1434 1.0624 29.2125 1.48155 30.0873 2.31975C30.9815 3.15794 31.4286 4.18132 31.4286 5.38988C31.4286 6.62771 30.9815 7.65108 30.0873 8.47952C29.2222 9.30796 28.1531 9.71735 26.8895 9.71735H23.8085V15.6238ZM23.8085 2.85578V7.93373H26.9285C27.6671 7.93373 28.2893 7.68035 28.7751 7.18325C29.2709 6.68615 29.5235 6.08192 29.5235 5.39963C29.5235 4.72711 29.2709 4.13253 28.7751 3.63553C28.2892 3.11892 27.6769 2.86553 26.9285 2.86553H23.8085V2.85578Z" fill="white"/>
                      <path d="M36.3078 5.33144C37.688 5.33144 38.7766 5.7018 39.5736 6.44252C40.3706 7.18325 40.769 8.19687 40.769 9.48348V15.6238H38.9904V14.2399H38.9126C38.1448 15.3802 37.1145 15.9455 35.8315 15.9455C34.7333 15.9455 33.8196 15.6238 33.0809 14.9708C32.3422 14.3178 31.9729 13.5088 31.9729 12.5342C31.9729 11.5011 32.3617 10.6824 33.1393 10.0781C33.9169 9.46407 34.9569 9.16191 36.2495 9.16191C37.3575 9.16191 38.2712 9.3666 38.9807 9.77589V9.34709C38.9807 8.69407 38.7281 8.14828 38.2129 7.69021C37.6977 7.23213 37.0951 7.00793 36.405 7.00793C35.365 7.00793 34.5389 7.44649 33.9362 8.33347L32.2936 7.30033C33.1975 5.98445 34.5389 5.33144 36.3078 5.33144ZM33.8974 12.5634C33.8974 13.0507 34.1015 13.4601 34.5194 13.7817C34.9277 14.1034 35.4137 14.269 35.9677 14.269C36.755 14.269 37.4547 13.9766 38.0671 13.3918C38.6795 12.807 38.9905 12.1247 38.9905 11.3353C38.4073 10.8772 37.6006 10.6433 36.5606 10.6433C35.8025 10.6433 35.1707 10.8284 34.6653 11.189C34.1501 11.5692 33.8974 12.0273 33.8974 12.5634Z" fill="white"/>
                      <path d="M50.9163 5.65301L44.6958 20H42.7713L45.0845 14.9805L40.9829 5.65301H43.0142L45.969 12.807H46.0079L48.8849 5.65301H50.9163Z" fill="white"/>
                      <path d="M16.2301 6.78262H8.40786V9.99901L12.9129 10C12.7302 11.0702 12.1421 11.9824 11.2411 12.5906L11.161 14.5131L13.9238 14.6764L13.9227 14.6774C15.4885 13.2242 16.3857 11.076 16.3857 8.53806C16.3856 7.92786 16.3312 7.34404 16.2301 6.78262Z" fill="#0085F7"/>
                      <path d="M11.2411 12.5906C10.4948 13.0951 9.53374 13.3908 8.40975 13.3908C6.23741 13.3908 4.39461 11.923 3.73467 9.94445L1.4282 9.57916L0.968918 12.0971C2.33953 14.8239 5.15602 16.6949 8.40985 16.6949C10.6583 16.6949 12.5474 15.9537 13.9227 14.6774L13.9238 14.6764L11.2411 12.5906Z" fill="#00A94B"/>
                      <path d="M3.47415 8.3479C3.47415 7.79236 3.56648 7.25533 3.73467 6.75046L3.01446 4.59837H0.968421C0.401808 5.72609 0.0830078 6.99897 0.0830078 8.3479C0.0830078 9.69684 0.403298 10.9694 0.968918 12.0971L3.73467 9.94445C3.56668 9.43998 3.47415 8.90315 3.47415 8.3479Z" fill="#FFBB00"/>
                      <path d="M8.40975 0C5.15661 0 2.33894 1.87123 0.968421 4.59837L3.73467 6.75046C4.39461 4.77191 6.23732 3.3042 8.40965 3.3042C9.63719 3.3042 10.7366 3.72822 11.6045 4.55666L13.981 2.17558C12.5377 0.827443 10.6559 0 8.40975 0Z" fill="#FF4031"/>
                    </svg>
                  </button>
                  </div>
                </div>
              </div>

              <div class="has-account">
                <div class="container">
                  <div class="has-account-background">
                    <p><span>в тебе вже є аккаунт?</span><span class="login-show login-btn">Увійти</span></p>
                  </div>
                </div>
              </div>

              <div class="data-for-order">
                <div class="container">
                  <div class="input-fields-wrapper">
                    <p class="title"><span>01</span> Контактні данні</p>
                    <div class="input-group">
                      <label for="phone">Номер телефону</label>
                      <input type="text" name="phone" id="phone">
                    </div>
                    <div class="input-group">
                      <label for="name">Ім'я</label>
                      <input type="text" name="name" id="name" placeholder="Ім'я">
                    </div>
                    <div class="input-group">
                      <label for="phone">Номер телефону</label>
                      <input type="text" name="phone" id="phone">
                    </div>
                    <div class="input-group">
                      <label for="phone">Номер телефону</label>
                      <input type="text" name="phone" id="phone">
                    </div>
                    <div class="input-group">
                      <label for="phone">Номер телефону</label>
                      <input type="text" name="phone" id="phone">
                    </div>
                  </div>

                  <div class="input-fields-wrapper">
                    <p class="title"><span>02</span> Доставка</p>
                    <div class="input-group">
                      <label for="phone">Номер телефону</label>
                      <input type="text" name="phone" id="phone">
                    </div>
                    <div class="input-group">
                      <label for="phone">Номер телефону</label>
                      <input type="text" name="phone" id="phone">
                    </div>
                    <div class="input-group">
                      <label for="phone">Номер телефону</label>
                      <input type="text" name="phone" id="phone">
                    </div>
                    <div class="input-group">
                      <label for="phone">Номер телефону</label>
                      <input type="text" name="phone" id="phone">
                    </div>
                    <div class="input-group">
                      <label for="phone">Номер телефону</label>
                      <input type="text" name="phone" id="phone">
                    </div>
                  </div>

                  <div class="input-fields-wrapper">
                    <p class="title"><span>03</span> Оплата</p>
                    <div class="input-group">
                      <label for="phone">Номер телефону</label>
                      <input type="text" name="phone" id="phone">
                    </div>
                    <div class="input-group">
                      <label for="phone">Номер телефону</label>
                      <input type="text" name="phone" id="phone">
                    </div>
                    <div class="input-group">
                      <label for="phone">Номер телефону</label>
                      <input type="text" name="phone" id="phone">
                    </div>
                    <div class="input-group">
                      <label for="phone">Номер телефону</label>
                      <input type="text" name="phone" id="phone">
                    </div>
                    <div class="input-group">
                      <label for="phone">Номер телефону</label>
                      <input type="text" name="phone" id="phone">
                    </div>
                  </div>
                </div>
              </div>

            </div>
            <div class="simplecheckout-right-column">
              <div class="simplecheckout-block" id="simplecheckout_cart">

                <script>
                  facebook_payment_data = {
                    content_type: 'product',
                    num_items: 2,
                    contents: [
                      {'id': '432', 'price': 3360, 'quantity': 1.000},{'id': '549', 'price': 1612.8, 'quantity': 1.000}],
                    value: 4972.8,
                    currency: 'UAH'
                  }</script><script>
                  ga4_payment_data = {"currency":"UAH","send_to":"G-R3BBDJBYXC","value":4972.8,"payment_type":"\u0413\u043e\u0442\u0456\u0432\u043a\u043e\u044e (\u043f\u0456\u0434 \u0447\u0430\u0441 \u043e\u0442\u0440\u0438\u043c\u0430\u043d\u043d\u044f \u0442\u043e\u0432\u0430\u0440\u0443)","items":[{"item_id":"432","item_name":"\u041f\u043b\u0456\u0432\u043a\u0430 \u0430\u043d\u0442\u0438\u0433\u0440\u0430\u0432\u0456\u0439\u043d\u0430 \u0441\u0430\u0442\u0438\u043d\u043e\u0432\u0430 Kybertane PPF Deep Satin 1,53 \u043c","quantity":"1.000","price":3360,"currency":"UAH","item_category":"\u0417\u0430\u0445\u0438\u0441\u043d\u0456 \u043f\u043b\u0456\u0432\u043a\u0438","item_brand":"Kybertane"},{"item_id":"549","item_name":"\u0411\u0435\u0437\u043f\u0435\u0447\u043d\u0438\u0439 \u043d\u0456\u0436 \u0434\u043b\u044f \u043f\u043b\u0456\u0432\u043a\u0438 Yellotools Wrap Defender Cutter","quantity":"1.000","price":1612.8,"currency":"UAH","item_category":"\u041b\u0435\u0437\u0430 \u0442\u0430 \u043d\u043e\u0436\u0456","item_brand":"Yellotools"}]};
                </script>
                <div class="checkout-heading panel-heading">У Вашому Замовленні <span class="checkout-edit" onclick="cartPopup();">Редагувати</span></div>
                <div class="table-responsive">
                  <table class="simplecheckout-cart">
                    <thead>
                    <tr>
                      <th class="image">Фото</th>
                      <th class="name">Найменування товару</th>
                      <th class="model">Модель</th>
                      <th class="quantity"><span title="Кількість">Кільк.</span></th>
                      <th class="price">Ціна</th>
                      <th class="total">Разом</th>
                      <th class="remove"></th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr class="item flex-justify">
                      <td class="image">
                        <a href="https://wrap.shop/plivky/zahysni-plivky/antygravijna-satynova-plivka-kybertane-ppf-deep-satin"><img src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/e359162a-0538-11ea-0a80-01b70006656a_0-70x70.webp" alt="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м" title="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м"></a>
                      </td>
                      <td class="name">
                        <span class="cat">Захисні плівки</span>
                        <a href="https://wrap.shop/plivky/zahysni-plivky/antygravijna-satynova-plivka-kybertane-ppf-deep-satin">Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м</a>
                        <span class="count">
                                х 1 м.п.
                                                            </span>
                        <div class="options">
                        </div>
                      </td>
                      <td class="model">11059</td>
                      <td class="quantity">

                        <div class="input-group-grid-xvr">
                          <div class="input-group-quantity-cart-xvr">
                            <div class="pull-left">
                              Кількість
                            </div>
                            <div class="pull-left input-group btn-block number-spinner-XVR number-spinner-XVR-button">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" data-dir="dwn" data-toggle="tooltip" onclick="xvrPlusMinus(this); xvrUpdateSimple();" type="button">-</button>
                        </span>
                              <input type="text" name="quantity[3076]" class="quantity_input form-control input-quantity-XVR text-center colortext1" value="1" id="input-quantity-" onkeyup="if (event.keyCode == 13) {xvrCheckForCorrectInputCart(this); xvrUpdateSimpleOnblur(this);} else {xvrCheckForCorrectInput3(this);}" onblur="xvrCheckForCorrectInputCart(this); xvrUpdateSimpleOnblur(this);">
                              <input type="hidden" name="quantity-validate[3076]" value="1" id="input-quantity-validate-">
                              <input type="hidden" name="quantity-validate[3076]" value="1" id="input-simple-quantity-validate-">
                              <input type="hidden" name="quantity_in_stock" value="2655.800" id="input-quantity_in_stock-" disabled="">
                              <input type="hidden" name="minimum" value="0.100" id="input-minimum-" disabled="">
                              <input type="hidden" name="minimum_packaging" value="0.000" id="input-minimum_packaging-" disabled="">
                              <input type="hidden" name="order_step" value="0.100" id="input-order_step-" disabled="">
                              <input type="hidden" name="maximum_order" value="0.000" id="input-maximum_order-" disabled="">
                              <input type="hidden" name="xvr_product_name" value="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м" disabled="">
                              <span class="input-group-btn">
                            <button class="btn btn-primary colord" data-dir="up" data-toggle="tooltip" onclick="xvrPlusMinus(this); xvrUpdateSimple();" type="button">+</button>
                            <button class="btn btn-danger" data-onclick="removeProduct" data-product-key="3076" data-toggle="tooltip" type="button"><i class="fa fa-times-circle"></i></button>
                        </span>
                            </div>
                          </div>
                        </div>

                      </td>
                      <td class="price">
                        <div>3360.00 ₴<span class="price-unit-xvr"></span></div>
                      </td>
                      <td class="total">
                        <span class="old"></span>
                        3360.00 ₴
                      </td>
                      <td class="remove">
                      </td>
                    </tr>

                    <tr class="item flex-justify">
                      <td class="image">
                        <a href="https://wrap.shop/instrumenty-rozhidnyky/leza-ta-nozhi/bezpechnyj-nizh-dlya-plivky-yellotools-wrap-defender-cutter"><img src="https://wrap.shop/image/cachewebp/catalog/demo/syncms/d8e297e9-8df2-11ee-0a80-11f6001b9b2a_0-70x70.webp" alt="Безпечний ніж для плівки Yellotools Wrap Defender Cutter" title="Безпечний ніж для плівки Yellotools Wrap Defender Cutter"></a>
                      </td>
                      <td class="name">
                        <span class="cat">Леза та ножі</span>
                        <a href="https://wrap.shop/instrumenty-rozhidnyky/leza-ta-nozhi/bezpechnyj-nizh-dlya-plivky-yellotools-wrap-defender-cutter">Безпечний ніж для плівки Yellotools Wrap Defender Cutter</a>
                        <span class="count">
                                х 1 шт
                                                            </span>
                        <div class="options">
                        </div>
                      </td>
                      <td class="model">13280</td>
                      <td class="quantity">

                        <div class="input-group-grid-xvr">
                          <div class="input-group-quantity-cart-xvr">
                            <div class="pull-left">
                              Кількість
                            </div>
                            <div class="pull-left input-group btn-block number-spinner-XVR number-spinner-XVR-button">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" data-dir="dwn" data-toggle="tooltip" onclick="xvrPlusMinus(this); xvrUpdateSimple();" type="button">-</button>
                        </span>
                              <input type="text" name="quantity[3075]" class="quantity_input form-control input-quantity-XVR text-center colortext1" value="1" id="input-quantity-" onkeyup="if (event.keyCode == 13) {xvrCheckForCorrectInputCart(this); xvrUpdateSimpleOnblur(this);} else {xvrCheckForCorrectInput3(this);}" onblur="xvrCheckForCorrectInputCart(this); xvrUpdateSimpleOnblur(this);">
                              <input type="hidden" name="quantity-validate[3075]" value="1" id="input-quantity-validate-">
                              <input type="hidden" name="quantity-validate[3075]" value="1" id="input-simple-quantity-validate-">
                              <input type="hidden" name="quantity_in_stock" value="14.000" id="input-quantity_in_stock-" disabled="">
                              <input type="hidden" name="minimum" value="1.000" id="input-minimum-" disabled="">
                              <input type="hidden" name="minimum_packaging" value="0.000" id="input-minimum_packaging-" disabled="">
                              <input type="hidden" name="order_step" value="1.000" id="input-order_step-" disabled="">
                              <input type="hidden" name="maximum_order" value="0.000" id="input-maximum_order-" disabled="">
                              <input type="hidden" name="xvr_product_name" value="Безпечний ніж для плівки Yellotools Wrap Defender Cutter" disabled="">
                              <span class="input-group-btn">
                            <button class="btn btn-primary colord" data-dir="up" data-toggle="tooltip" onclick="xvrPlusMinus(this); xvrUpdateSimple();" type="button">+</button>
                            <button class="btn btn-danger" data-onclick="removeProduct" data-product-key="3075" data-toggle="tooltip" type="button"><i class="fa fa-times-circle"></i></button>
                        </span>
                            </div>
                          </div>
                        </div>

                      </td>
                      <td class="price">
                        <div>1612.80 ₴<span class="price-unit-xvr"></span></div>
                      </td>
                      <td class="total">
                        <span class="old"></span>
                        1612.80 ₴
                      </td>
                      <td class="remove">
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </div>
                <div class="simplecheckout-cart-info flex-justify">
                  <div class="left">
                    <div class="simplecheckout-cart-total">
                      <span class="inputs-open button"><span>У мене є промокод</span><i class="fal fa-tag"></i></span>
                      <span class="inputs form-horizontal"><input class="form-control" type="text" data-onchange="reloadAll" name="coupon" value=""></span>
                    </div>
                    <div class="simplecheckout-cart-total">
                      <span class="inputs-open button"><span>У мене є сертифікат:</span><i class="fal fa-gift-card"></i></span>
                      <span class="inputs form-horizontal"><input class="form-control" type="text" name="voucher" data-onchange="reloadAll" value=""></span>
                    </div>
                    <div class="simplecheckout-cart-total simplecheckout-cart-buttons">
                      <span class="inputs buttons"><a id="simplecheckout_button_cart" data-onclick="reloadAll" class="button btn-primary button_oc btn"><span>Оновити</span></a></span>
                    </div>
                  </div>
                  <div class="right">
                    <div class="simplecheckout-cart-total" id="total_sub_total">
                      <span class="simplecheckout-cart-total-label">Сума</span>
                      <span class="simplecheckout-cart-total-value">4972.80 ₴</span>
                    </div>
                    <div class="simplecheckout-cart-total" id="total_total">
                      <span class="simplecheckout-cart-total-label">Разом</span>
                      <span class="simplecheckout-cart-total-value">4972.80 ₴</span>
                    </div>
                  </div>
                </div>
                <input type="hidden" name="remove" value="" id="simplecheckout_remove">
                <div style="display:none;" id="simplecheckout_cart_total">2</div>
              </div>
            </div>
          </div>
        </div>

      </div>


  </section>


@endsection
