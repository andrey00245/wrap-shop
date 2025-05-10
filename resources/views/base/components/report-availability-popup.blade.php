<div id="report-availability-popup" class="popup-right general-popup" data-step="1">
    <div class="consult-title">{{__('popup.report_availability.title')}}</div>
    <br>
    <form class="popup-window popup-step-1" id="report-availability-form">
        <div class="inner form-horizontal">
            <div class="form-group">
                <div class="form">
                    <div class="form-group group-name">
                        <label for="name" class="login-info-text text-center margintop20">{!! __('popup.consult_popup.name') !!}</label>
                        <input type="text" id="report_order_name" name="name" class="form-control form-field"
                               placeholder="{{__('popup.consult_popup.enter_name_placeholder')}}"
                               value="{{ auth()->check() ? auth()->user()->name : '' }}">
                    </div>

                    <div class="form-group group-telephone">
                        <label for="report_order_phone" class="login-info-text text-center margintop20">{!! __('popup.consult_popup.phone') !!}</label>
                        <input type="tel" id="report_order_phone" name="phone" class="form-control form-field"
                               placeholder="{{__('popup.consult_popup.enter_phone_placeholder')}}"
                               value="{{ auth()->check() ? auth()->user()->phone : '' }}">
                    </div>
                    <div class="form-group group-email">
                        <label for="email" class="login-info-text text-center margintop20">{{__('popup.consult_popup.email')}}</label>
                        <input type="email" id="report_order_email" name="email" class="form-control form-field"
                               placeholder="{{__('popup.consult_popup.enter_email_placeholder')}}"
                               value="{{ auth()->check() ? auth()->user()->email : '' }}">
                    </div>

                    <div class="error-message-report-order" style="display: none; color:red;"></div>
                </div>
            </div>

            <div class="input-group">
            <span class="input-group-btn">
                <button class="send_otp_btn otp_btn-s btnverifyloginpopup button colord"
                        id="button-submit-report-availability"
                        type="submit">
                    <i class="fas fa-chevron-right" aria-hidden="true"></i> {{__('popup.report_availability.send')}}
                </button>
            </span>
            </div>
        </div>
    </form>

    <div class="popup-consult-thanks flex-center report-availability-success popup-step-flex-3">
        <i class="fal fa-smile"></i>
        <span>{{__('popup.report_availability.your_request_send')}}</span>
        <span>{{__('popup.report_availability.you_get_notify')}}</span>
    </div>

    <div class="close button fal fa-times popup-close"></div>
</div>
