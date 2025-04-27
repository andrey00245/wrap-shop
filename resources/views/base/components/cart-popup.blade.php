<div id="cart-popup" class="popup-right general-popup">
  <div class="title">{{__('popup.cart_popup.cart_title')}}</div>
    <br>
  <div class="cart-popup-body">
      <div class="cart-shopping-items">
          @include('base.components.cart-items')
      </div>
      @include('base.components.bestseller-cart')
  </div>
  <div class="close button fal fa-times popup-close"></div>
</div>
