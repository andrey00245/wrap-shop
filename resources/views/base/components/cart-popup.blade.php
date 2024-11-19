<div id="cart-popup" class="popup-right">
  <div class="title">{{__('popup.cart_popup.cart_title')}}</div>
  <div class="cart-popup-body">
    <ul>
      <li>
        <div class="cart-mini-empty">

          <span style="opacity:1;font-size:28px;padding:0">🤨</span>
          <span>{{__('popup.cart_popup.cart_is_empty')}}</span>
          {{__('popup.cart_popup.not_late_to_fix_it')}}
        </div>
        <div class="cart-mini-button">
          <div class="continion colord close-cart-popup"><i
              class="fas fa-chevron-right"></i>{{__('popup.cart_popup.shopping')}}</div>
        </div>
      </li>
    </ul>


    <ul>
      <li>
        <table class="table table-striped">
          <tbody>
          <tr class="item flex-justify">
            <td class="image"><a
                href="https://wrap.shop/plivky/zahysni-plivky/antygravijna-satynova-plivka-kybertane-ppf-deep-satin"><img
                  loading="lazy"
                  src="https://wrap.shop/image/cache/catalog/demo/syncms/e359162a-0538-11ea-0a80-01b70006656a_0-70x70.png"
                  alt="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м"
                  title="Плівка антигравійна сатинова Kybertane PPF Deep Satin 1,53 м" class="img-thumbnail"></a></td>
            <td class="name"><span class="cat">Захисні плівки</span><a
                href="https://wrap.shop/plivky/zahysni-plivky/antygravijna-satynova-plivka-kybertane-ppf-deep-satin">Плівка
                антигравійна сатинова Kybertane PPF Deep Satin 1,53 м</a></td>
            <td class="price price-default">
              3360 ₴<span class="price-unit-xvr"></span>
              <span class="length">
                @include('base.svg-icons.question')м.п.
              </span>
            </td>
            <td class="count flex-justify" data-counter="">
              <div class="cart-quantity-input-wrappper">
                <div class="input-group quantity-input-group">
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-primary" id="minus-btn">-</button>
                  </span>
                  <input type="text" name="quantity" data-min="0.1" data-max="10" data-step="0.1" value="0.1"
                         id="input-quantity-1" class="input-quantity">
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-primary colord" id="plus-btn">+</button>
                  </span>
                </div>

                <div class="max-value-group">
                  <span class="max-value">{{__('product-show.max-quantity', ['max' => 10])}}</span>
                </div>
              </div>

            </td>

            <td class="price price-all">
              3600 ₴
            </td>
            <td class="delete">
              <button type="button" title="Видалити" class="btn btn-danger btn-xs"><i
                  class="fas fa-trash"></i></button>
            </td>
          </tr>
          </tbody>
        </table>
      </li>
      <li>
        <div class="cart-mini-bott">
          <div class="total">
            <div class="name_background_wrapper">
              <div class="background"></div>
              <div class="name">Разом</div>
            </div>
            <div class="value">20160<span class="coins">.00</span> ₴</div>
          </div>
          <div class="cart-mini-button">
            <a href="{{route('checkout')}}" title="Перейти до оформлення" class="checkout colord"><i
                class="fas fa-chevron-right"></i> Перейти до оформлення</a>
            <div class="continion close-cart-popup"><i
                class="fas fa-chevron-right"></i>Продовжити покупки
            </div>
          </div>
        </div>
      </li>
    </ul>


    @include('base.components.bestseller-cart')
  </div>
  <div class="close button fal fa-times close-cart-popup"></div>
</div>
