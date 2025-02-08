<div id="consult-popup" class="popup-overlay popup-right">
    <input type="hidden" name="product-id" id="consult-popup-product-id" value="{{$product->id}}">
    <div class="consult-title">{{__('popup.consult_popup.title')}}</div>
    <div class="popup-window active" id="popup-consultation" style="display: block;">
        <div class="inner form-horizontal">
            <div class="form-group">
                <div class="form">
                    <!-- Имя -->
                    <div class="form-group group-name">
                        <label for="name" class="login-info-text text-center margintop20">{!! __('popup.consult_popup.name') !!}</label>
                        <input type="text" id="name" name="name" required class="form-control form-field" placeholder="{{__('popup.consult_popup.enter_name_placeholder')}}">
                    </div>

                    <!-- Телефон -->
                    <div class="form-group group-telephone">
                        <label for="phone" class="login-info-text text-center margintop20">{!! __('popup.consult_popup.phone') !!}</label>
                        <input type="tel" id="phone" name="phone" required class="form-control form-field" placeholder="{{__('popup.consult_popup.enter_phone_placeholder')}}">
                    </div>

                    <!-- Email -->
                    <div class="form-group group-email">
                        <label for="email" class="login-info-text text-center margintop20">{{__('popup.consult_popup.email')}}</label>
                        <input type="email" id="email" name="email" class="form-control form-field" placeholder="{{__('popup.consult_popup.enter_email_placeholder')}}">
                    </div>

                    <!-- Комментарий -->
                    <div class="form-group group-comment">
                        <label for="comment" class="login-info-text text-center margintop20">{{__('popup.consult_popup.comment')}}</label>
                        <textarea id="comment" name="comment" class="form-control form-field" rows="4" placeholder="{{__('popup.consult_popup.enter_comment_placeholder')}}"></textarea>
                    </div>

                    <!-- Ошибки -->
                    <div class="error-message-consultation" style="display:none; color:red"></div>
                </div>
            </div>

            <!-- Кнопка отправить -->
            <div class="input-group">
            <span class="input-group-btn">
					      <button class="send_otp_btn otp_btn-s btnverifyloginpopup button colord"
                                  id="button-submit-consultation"
                                  type="button">
                  <i class="fas fa-chevron-right" aria-hidden="true"></i> {{__('popup.consult_popup.submit_button')}}
                </button>
        </span>
            </div>
        </div>
    </div>

    <div class="popup-consult-thanks flex-center" style="display:none;">
        <i class="fal fa-smile"></i>
        <span>{!! __('popup.consult_popup.thanks_message') !!}</span>
    </div>

{{--    <div class="popup-window" id="popup-registration-success">--}}
{{--        <button type="button" class="close-popup btn-close-popup"><span></span></button>--}}
{{--        <div class="inner form-horizontal">--}}
{{--            <div class="popup-title">{{__('popup.consult_popup.success_message')}}</div>--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="close button fal fa-times consult-popup-close"></div>
</div>
