<div id="login-popup" class="popup-overlay popup-right">
  <input type="hidden" name="account" id="account" value="login">
  <div class="reg_block simple-popup-nav flex-center">
    <a href="#" data-account="login" class="login-show item button active">{{__('popup.login_register.enter')}}</a>
    <a href="#" data-account="registration" class="registration-show reg_form_link item button">{{__('popup.login_register.register')}}</a>
  </div>
  <div class="popup-window active" id="popup-login" style="display: block;">
    <div class="inner form-horizontal">

      <p class="login-info-text text-center margintop20">{{__('popup.login_register.enter_phone')}}</p>
      <div class="form-group">
        <div class="form">
          <div class="form-group group-telephone otpboxloginpopup" id="otpbox">
            <div class="input-group telephone-error-loginpopup">
              <input type="hidden" name="page" value="loginpopup">
              <input type="hidden" name="otp_country_code" id="otp_country_code-loginpopup">
              <input type="tel" inputmode="numeric" id="tel-phone-loginpopup" name="telephone" class="form-control form-field numeric telephonenewloginpopup" value="" placeholder="Телефон" maxlength="18" autocomplete="off">
              <span class="input-group-btn">
					<button class="send_otp_btn otp_btn-s btnverifyloginpopup button colord" id="button-verify-loginpopup" type="button"><i class="fas fa-chevron-right" aria-hidden="true"></i> {{__('popup.login_register.sign_in')}}</button>
				</span>
            </div>
          </div>
        </div>
{{--        <script id="smsvar">--}}
{{--          var otppage = 'loginpopup';--}}
{{--        </script>--}}
{{--        <script id="sms" src="catalog/view/javascript/sap_sms/sap_verify.js"></script>--}}
{{--        <script id="smsContent">--}}
{{--          $(document).ready(function() {--}}
{{--            ajaxSendOTP = null;--}}
{{--            $(document).on('click', '.btnverifyloginpopup', function () {--}}
{{--              ajaxSendOTP = $.ajax({--}}
{{--                url: 'index.php?route=extension/module/sap_sms/viewOTP',--}}
{{--                type: 'post',--}}
{{--                data: $('.otpboxloginpopup input[type=\'tel\'], .otpboxloginpopup input[name=\'otp_country_code\'], .otpboxloginpopup input[name=\'page\']'),--}}
{{--                dataType: 'json',--}}
{{--                beforeSend: function() {--}}
{{--                  if(ajaxSendOTP != null) {--}}
{{--                    ajaxSendOTP.abort();--}}
{{--                  }--}}
{{--                  //$('.btnverifyloginpopup').button('loading');--}}
{{--                },--}}
{{--                complete: function() {--}}
{{--                  //$('.btnverifyloginpopup').button('reset');--}}
{{--                },--}}
{{--                success: function(json) {--}}
{{--                  $('.errordivloginpopup').remove();--}}
{{--                  if (json['error']) {--}}
{{--                    $('.telephone-error-loginpopup').after('<div class="text-danger errordivloginpopup">'+ json['error']['telephone'] + '</div>');--}}
{{--                  }--}}
{{--                  if (json['otp']) {--}}
{{--                    $('.popup-window').hide();--}}
{{--                    $('.popup-overlay').append(json['otp']);--}}
{{--                    if (json['status'] && json['message']) {--}}
{{--                      if (json['status'] == 'failed') {--}}
{{--                        showErrorMessage('notify', json['message']);--}}
{{--                      } else if (json['status'] == 'success') {--}}
{{--                        showSuccessMessage(json['message']);--}}
{{--                        if (json['error_field'] && json['error_field'] !== 'delay') {--}}
{{--                          $('.timerset_for_loginpopup').hide();--}}
{{--                          $('.resendotploginpopup').hide();--}}
{{--                        }--}}
{{--                      }--}}
{{--                    }--}}
{{--                    $('.popup-overlay').show();--}}
{{--                    $('#popup-otp').show();--}}
{{--                  }--}}
{{--                }--}}
{{--              });--}}
{{--            });--}}
{{--          });--}}
{{--        </script>--}}
      </div>

      <div id="column-login" class="popup-social">
        <p>{{__('popup.login_register.enter_social_network')}}</p>
        <div class="social_block flex-wrap">
          <div class="colord button soc_ico facebook onclick" data-auth="Facebook" rel="noreferrer" data-toggle="tooltip" title="Facebook">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 96.1 96.1" xml:space="preserve"><g><path d="M72.1,0L59.6,0c-14,0-23.1,9.3-23.1,23.7v10.9H24c-1.1,0-2,0.9-2,2v15.8c0,1.1,0.9,2,2,2h12.5v39.9c0,1.1,0.9,2,2,2h16.4 c1.1,0,2-0.9,2-2V54.3h14.7c1.1,0,2-0.9,2-2l0-15.8c0-0.5-0.2-1-0.6-1.4s-0.9-0.6-1.4-0.6H56.8v-9.2c0-4.4,1.1-6.7,6.8-6.7l8.4,0 c1.1,0,2-0.9,2-2V2C74,0.9,73.2,0,72.1,0z"></path></g></svg>
          </div>
          <div class="colord button soc_ico google onclick" data-auth="Google" rel="noreferrer" data-toggle="tooltip" title="Google">
            <svg viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M896 786h725q12 67 12 128 0 217-91 387.5t-259.5 266.5-386.5 96q-157 0-299-60.5t-245-163.5-163.5-245-60.5-299 60.5-299 163.5-245 245-163.5 299-60.5q300 0 515 201l-209 201q-123-119-306-119-129 0-238.5 65t-173.5 176.5-64 243.5 64 243.5 173.5 176.5 238.5 65q87 0 160-24t120-60 82-82 51.5-87 22.5-78h-436v-264z"></path></svg>
          </div>
          <div class="colord button soc_ico apple onclick" data-auth="Apple" rel="noreferrer" data-toggle="tooltip" title="Apple">
            <svg fill="#000000" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M 16.125 1 C 14.972 1.067 13.648328 1.7093438 12.861328 2.5273438 C 12.150328 3.2713438 11.589359 4.3763125 11.818359 5.4453125 C 13.071359 5.4783125 14.329031 4.8193281 15.082031 3.9863281 C 15.785031 3.2073281 16.318 2.12 16.125 1 z M 16.193359 5.4433594 C 14.384359 5.4433594 13.628 6.5546875 12.375 6.5546875 C 11.086 6.5546875 9.9076562 5.5136719 8.3476562 5.5136719 C 6.2256562 5.5146719 3 7.4803281 3 12.111328 C 3 16.324328 6.8176563 21 8.9726562 21 C 10.281656 21.013 10.599 20.176969 12.375 20.167969 C 14.153 20.154969 14.536656 21.011 15.847656 21 C 17.323656 20.989 18.476359 19.367031 19.318359 18.082031 C 19.922359 17.162031 20.170672 16.692344 20.638672 15.652344 C 17.165672 14.772344 16.474672 9.1716719 20.638672 8.0136719 C 19.852672 6.6726719 17.558359 5.4433594 16.193359 5.4433594 z"></path></svg>
          </div>
        </div>
      </div>

      <p>{{__('popup.login_register.enter_email_password')}}</p>
      <div class="popup-form">
        <form id="form-login" class="form" action="#" novalidate="" method="post" enctype="multipart/form-data">
          <div class="form-group group-email is_first">
            <input class="form-control form-field" type="email" name="email" id="input-email-popup" placeholder="E-Mail" autocomplete="off" required="">
            <i class="fal fa-envelope fa-lg input-icon" aria-hidden="true"></i>
          </div>
          <div class="form-group group-password">
            <input class="form-control form-field" type="password" name="password" placeholder="{{__('popup.login_register.password_placeholder')}}" id="input-password-popup" autocomplete="current-password" required=""><i class="fal fa-key fa-lg input-icon" aria-hidden="true"></i>
            <span class="password-eye" aria-label="{{__('popup.login_register.show_password_text')}}" data-target-input="#input-password-popup"></span>
          </div>
          <input type="hidden" name="account" id="account_login" class="account_form" value="login">
          <div class="wrap-send">
            <button type="submit" class="colord button send_btn btn-social" data-account="login" id="button-login-popup"><i class="fas fa-chevron-right" aria-hidden="true"></i>{{__('popup.login_register.sign_in')}}</button>
          </div>
          <div id="account-support-links">
            <a href="#" data-account="forgot_password" class="forgot_password-show forgot_password colord">{{__('popup.login_register.forgot_password')}}</a>
          </div>
        </form>
      </div>

      <div class="reg_block">
        <p>{{__('popup.login_register.dont_have_a_profile')}} <a href="#" data-account="registration" class="registration-show reg_form_link colord">{{__('popup.login_register.register')}}</a></p>
      </div>
    </div>
  </div>
  <div class="popup-window" id="popup-forgot_password" style="display: none;">
    <div class="inner form-horizontal">
      <p>{{__('popup.login_register.forgot_password')}}</p>
      <p>{{__('popup.login_register.enter_you_email')}}</p>
      <div class="popup-form">
        <form id="form-forgot_password" class="form" action="#" novalidate="" method="post" enctype="multipart/form-data">
          <div class="form-group group-email">
            <input class="form-control form-field" type="email" name="email" id="input-email-forgot" placeholder="E-Mail" autocomplete="off">
            <input type="hidden" name="account" id="account_forgot" class="account_form" value="login">
          </div>
          <div class="wrap-send">
            <button type="submit" class="colord button send_btn btn-social" data-account="forgot_password" id="button-forgot-popup"><i class="fas fa-chevron-right" aria-hidden="true"></i>{{__('popup.login_register.send')}}</button>
          </div>
        </form>
      </div>
      <div class="reg_block">
        <a href="#" data-account="login" class="login-show forgot_password colord active">{{__('popup.login_register.i_remembered_my_password')}}</a>
      </div>
    </div>
  </div>
  <div class="popup-window" id="popup-forgot_password-sucess">
    <div class="inner form-horizontal">
      <p>{{__('popup.login_register.password_sent_to_email')}}</p>
      <div class="popup-btn">
        <a href="#" data-account="login" class="colord button login-show btn-social active"><i class="fas fa-chevron-right" aria-hidden="true"></i>{{__('popup.login_register.enter_with_new_password')}}</a>
      </div>
    </div>
  </div>
  <div class="popup-window" id="popup-registration" style="display: none;">
    <div class="inner form-horizontal">
      <p>{{__('popup.login_register.register_on_website')}}</p>
      <div class="popup-form">
        <form id="form-registration" class="form" action="#" novalidate="" method="post" enctype="multipart/form-data">
          <input type="hidden" name="country_code" id="input_country_code">
          <input type="hidden" name="account" id="account_register" class="account_form" value="login">
          <div class="form-group group-firstname" id="regular-field-firstname">
            <input autocomplete="on" type="text" id="input-firstname" name="firstname" value="" maxlength="40" class="form-control form-field required" required="" placeholder="{{__('popup.login_register.firstname')}}">
          </div>
          <div class="form-group group-lastname" id="regular-field-lastname">
            <input autocomplete="on" type="text" id="input-lastname" name="lastname" value="" maxlength="40" class="form-control form-field required" required="" placeholder="{{__('popup.login_register.lastname')}}">
          </div>
          <div class="form-group group-telephone" id="regular-field-telephone">
            <input autocomplete="on" type="tel" id="input-telephone" name="telephone" value="" maxlength="40" class="form-control form-field numeric required" required="" placeholder="{{__('popup.login_register.mobile')}}">
          </div>
          <div class="form-group group-email" id="regular-field-email">
            <input autocomplete="on" type="email" id="input-email" name="email" value="" maxlength="40" class="form-control form-field required" required="" placeholder="E-Mail">
          </div>
          <div class="form-group group-password" id="regular-field-password">
            <input autocomplete="on" type="password" id="input-password" name="password" value="" maxlength="40" class="form-control form-field required" required="" placeholder="{{__('popup.login_register.password')}}">
            <span class="reg-password-eye" aria-label="{{__('popup.login_register.show_password_text')}}" data-target-input="#input-password"></span>
          </div>
          <div id="regular-field-customer_group_id" class="form-group required" style="display: none;">
            <label class="control-label" style="padding-left: 0px;">{{__('popup.login_register.buyer_group')}}</label>
            <div class="radio-toolbar">
              <div><label class="pointer"><input type="radio" class="input-radio_reg" name="customer_group_id" value="" checked="checked"> <span>Default</span></label></div>
            </div>
          </div>
          <div id="slagreep" class="form-group">
            <div class="checkbox is_checkbox">
              <label id="sagree" for="agree"><input type="checkbox" class="input-checkbox_reg" name="agree" value="" id="agree" checked="checked" style="display: none;">{{__('popup.login_register.terms_and_conditions')}}</label>
            </div>
          </div>
          <div class="wrap-send">
            <button type="submit" class="colord button send_btn btn-social" data-account="registration" id="button-register-popup"><i class="fas fa-chevron-right" aria-hidden="true"></i>{{__('popup.login_register.register')}}</button>
          </div>
        </form>
      </div>
      <div id="column-login" class="popup-social">
        <p>{{__('popup.login_register.enter_social_network')}}</p>
        <div class="social_block flex-wrap">
          <div class="colord button soc_ico facebook onclick" data-auth="Facebook" rel="noreferrer" data-toggle="tooltip" title="Facebook">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 96.1 96.1" xml:space="preserve"><g><path d="M72.1,0L59.6,0c-14,0-23.1,9.3-23.1,23.7v10.9H24c-1.1,0-2,0.9-2,2v15.8c0,1.1,0.9,2,2,2h12.5v39.9c0,1.1,0.9,2,2,2h16.4 c1.1,0,2-0.9,2-2V54.3h14.7c1.1,0,2-0.9,2-2l0-15.8c0-0.5-0.2-1-0.6-1.4s-0.9-0.6-1.4-0.6H56.8v-9.2c0-4.4,1.1-6.7,6.8-6.7l8.4,0 c1.1,0,2-0.9,2-2V2C74,0.9,73.2,0,72.1,0z"></path></g></svg>
          </div>
          <div class="colord button soc_ico google onclick" data-auth="Google" rel="noreferrer" data-toggle="tooltip" title="Google">
            <svg viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M896 786h725q12 67 12 128 0 217-91 387.5t-259.5 266.5-386.5 96q-157 0-299-60.5t-245-163.5-163.5-245-60.5-299 60.5-299 163.5-245 245-163.5 299-60.5q300 0 515 201l-209 201q-123-119-306-119-129 0-238.5 65t-173.5 176.5-64 243.5 64 243.5 173.5 176.5 238.5 65q87 0 160-24t120-60 82-82 51.5-87 22.5-78h-436v-264z"></path></svg>
          </div>
          <div class="colord button soc_ico apple onclick" data-auth="Apple" rel="noreferrer" data-toggle="tooltip" title="Apple">
            <svg fill="#000000" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M 16.125 1 C 14.972 1.067 13.648328 1.7093438 12.861328 2.5273438 C 12.150328 3.2713438 11.589359 4.3763125 11.818359 5.4453125 C 13.071359 5.4783125 14.329031 4.8193281 15.082031 3.9863281 C 15.785031 3.2073281 16.318 2.12 16.125 1 z M 16.193359 5.4433594 C 14.384359 5.4433594 13.628 6.5546875 12.375 6.5546875 C 11.086 6.5546875 9.9076562 5.5136719 8.3476562 5.5136719 C 6.2256562 5.5146719 3 7.4803281 3 12.111328 C 3 16.324328 6.8176563 21 8.9726562 21 C 10.281656 21.013 10.599 20.176969 12.375 20.167969 C 14.153 20.154969 14.536656 21.011 15.847656 21 C 17.323656 20.989 18.476359 19.367031 19.318359 18.082031 C 19.922359 17.162031 20.170672 16.692344 20.638672 15.652344 C 17.165672 14.772344 16.474672 9.1716719 20.638672 8.0136719 C 19.852672 6.6726719 17.558359 5.4433594 16.193359 5.4433594 z"></path></svg>
          </div>
        </div>
      </div>

      <div class="reg_block">
        <p>{{__('popup.login_register.already_registered')}} <a href="#" data-account="login" class="login-show colord active">{{__('popup.login_register.enter')}}</a></p>
      </div>
    </div>
  </div>
  <div class="popup-window" id="popup-registration-sucess">
    <button type="button" class="close-popup btn-close-popup"><span></span></button>
    <div class="inner form-horizontal">
      <div class="popup-title">{{__('popup.login_register.succes_registration_message')}}</div>
    </div>
  </div>
  <div class="close button fal fa-times login-popup-close"></div>
</div>
