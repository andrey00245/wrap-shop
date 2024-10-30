<div id="login-popup" class="popup-overlay popup-right">
  <input type="hidden" name="account" id="account" value="login">
  <div class="login-register-title">{{__('popup.login_register.enter_register')}}</div>

  <div class="popup-window active" id="popup-login" style="display: block;">
    <div class="inner form-horizontal">
      <div id="column-login" class="popup-social">
        <div class="social_block">
          <a href="{{ route('auth.facebook') }}" class="colord button_social soc_ico facebook" rel="noreferrer"
             data-toggle="tooltip" data-auth="Facebook" title="Facebook">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                 y="0px" viewBox="0 0 96.1 96.1" xml:space="preserve"><g>
                <path
                  d="M72.1,0L59.6,0c-14,0-23.1,9.3-23.1,23.7v10.9H24c-1.1,0-2,0.9-2,2v15.8c0,1.1,0.9,2,2,2h12.5v39.9c0,1.1,0.9,2,2,2h16.4 c1.1,0,2-0.9,2-2V54.3h14.7c1.1,0,2-0.9,2-2l0-15.8c0-0.5-0.2-1-0.6-1.4s-0.9-0.6-1.4-0.6H56.8v-9.2c0-4.4,1.1-6.7,6.8-6.7l8.4,0 c1.1,0,2-0.9,2-2V2C74,0.9,73.2,0,72.1,0z"></path>
              </g></svg>
          </a>

          <a href="{{ route('auth.google') }}" class="colord button_social soc_ico google" data-auth="Google"
             rel="noreferrer" data-toggle="tooltip" title="Google">
            <svg viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M896 786h725q12 67 12 128 0 217-91 387.5t-259.5 266.5-386.5 96q-157 0-299-60.5t-245-163.5-163.5-245-60.5-299 60.5-299 163.5-245 245-163.5 299-60.5q300 0 515 201l-209 201q-123-119-306-119-129 0-238.5 65t-173.5 176.5-64 243.5 64 243.5 173.5 176.5 238.5 65q87 0 160-24t120-60 82-82 51.5-87 22.5-78h-436v-264z"></path>
            </svg>
          </a>
          <a href="javascript:void(0)" class="colord button_social soc_ico apple onclick" data-auth="Apple"
             rel="noreferrer"
             data-toggle="tooltip" title="Apple">
            <svg fill="#000000" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path
                d="M 16.125 1 C 14.972 1.067 13.648328 1.7093438 12.861328 2.5273438 C 12.150328 3.2713438 11.589359 4.3763125 11.818359 5.4453125 C 13.071359 5.4783125 14.329031 4.8193281 15.082031 3.9863281 C 15.785031 3.2073281 16.318 2.12 16.125 1 z M 16.193359 5.4433594 C 14.384359 5.4433594 13.628 6.5546875 12.375 6.5546875 C 11.086 6.5546875 9.9076562 5.5136719 8.3476562 5.5136719 C 6.2256562 5.5146719 3 7.4803281 3 12.111328 C 3 16.324328 6.8176563 21 8.9726562 21 C 10.281656 21.013 10.599 20.176969 12.375 20.167969 C 14.153 20.154969 14.536656 21.011 15.847656 21 C 17.323656 20.989 18.476359 19.367031 19.318359 18.082031 C 19.922359 17.162031 20.170672 16.692344 20.638672 15.652344 C 17.165672 14.772344 16.474672 9.1716719 20.638672 8.0136719 C 19.852672 6.6726719 17.558359 5.4433594 16.193359 5.4433594 z"></path>
            </svg>
          </a>
        </div>
        <div class="or-text-wrapp">
          <div class="left-line"></div>
          <div class="text">{{__('popup.login_register.or')}}</div>
          <div class="right-line"></div>
        </div>
      </div>


      <div class="form-group">
        <div class="form">
          <div class="form-group group-telephone otpboxloginpopup" id="otpbox">

            <div class="fields-wrapper">
              <label for="name_email"
                     class="login-info-text text-center margintop20">{{__('popup.login_register.enter_phone')}}</label>
              <input type="text" id="name_email" name="phone_email" class="form-control form-field login-phone-email"
                     placeholder="{{__('popup.login_register.enter_phone_or_email_placeholder')}}">
              <div class="password-group">
                <label for="password"
                       class="login-info-text text-center margintop20">{{__('popup.login_register.password')}}</label>
                <input id="password" type="password" name="phone_email"
                       class="form-control form-field login-phone-email"
                       placeholder="{{__('popup.login_register.enter_password')}}">
              </div>
            </div>
            <div class="input-group telephone-error-loginpopup">
              <span class="input-group-btn">
					      <button class="send_otp_btn otp_btn-s btnverifyloginpopup button colord" id="button-verify-loginpopup"
                        type="button">
                  <i class="fas fa-chevron-right" aria-hidden="true"></i> {{__('popup.login_register.sign_in')}}
                </button>

                <button class="send_otp_btn otp_btn-s button-restore-password forgot_password-show"
                        id="button-restore-password"
                        type="button">
                  <i class="fas fa-chevron-right" aria-hidden="true"></i> {{__('popup.login_register.recover_password')}}
                </button>
				      </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="popup-window" id="popup-forgot_password" style="display: none;">
    <div class="inner form-horizontal">
      <p>{{__('popup.login_register.forgot_password')}}</p>
      <p>{{__('popup.login_register.enter_you_email')}}</p>
      <div class="popup-form">
        <form id="form-forgot_password" class="form" action="#" novalidate="" method="post"
              enctype="multipart/form-data">
          <div class="form-group group-email">
            <input class="form-control form-field" type="email" name="email" id="input-email-forgot"
                   placeholder="E-Mail" autocomplete="off">
            <input type="hidden" name="account" id="account_forgot" class="account_form" value="login">
          </div>
          <div class="wrap-send">
            <button type="submit" class="colord button send_btn btn-social" data-account="forgot_password"
                    id="button-forgot-popup"><i class="fas fa-chevron-right"
                                                aria-hidden="true"></i>{{__('popup.login_register.send')}}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="popup-window" id="popup-forgot_password-sucess">
    <div class="inner form-horizontal">
      <p>{{__('popup.login_register.password_sent_to_email')}}</p>
      <div class="popup-btn">
        <a href="#" data-account="login" class="colord button login-show btn-social active"><i
            class="fas fa-chevron-right" aria-hidden="true"></i>{{__('popup.login_register.enter_with_new_password')}}
        </a>
      </div>
    </div>
  </div>
  <div class="popup-window" id="popup-registration" style="display: none;">
    <div class="inner form-horizontal">
      <div id="column-login" class="popup-social">
        <div class="social_block">
          <a href="{{ route('auth.facebook') }}" class="colord button_social soc_ico facebook" rel="noreferrer"
             data-toggle="tooltip" data-auth="Facebook" title="Facebook">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                 y="0px" viewBox="0 0 96.1 96.1" xml:space="preserve"><g>
                <path
                  d="M72.1,0L59.6,0c-14,0-23.1,9.3-23.1,23.7v10.9H24c-1.1,0-2,0.9-2,2v15.8c0,1.1,0.9,2,2,2h12.5v39.9c0,1.1,0.9,2,2,2h16.4 c1.1,0,2-0.9,2-2V54.3h14.7c1.1,0,2-0.9,2-2l0-15.8c0-0.5-0.2-1-0.6-1.4s-0.9-0.6-1.4-0.6H56.8v-9.2c0-4.4,1.1-6.7,6.8-6.7l8.4,0 c1.1,0,2-0.9,2-2V2C74,0.9,73.2,0,72.1,0z"></path>
              </g></svg>
          </a>

          <a href="{{ route('auth.google') }}" class="colord button_social soc_ico google" data-auth="Google"
             rel="noreferrer" data-toggle="tooltip" title="Google">
            <svg viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M896 786h725q12 67 12 128 0 217-91 387.5t-259.5 266.5-386.5 96q-157 0-299-60.5t-245-163.5-163.5-245-60.5-299 60.5-299 163.5-245 245-163.5 299-60.5q300 0 515 201l-209 201q-123-119-306-119-129 0-238.5 65t-173.5 176.5-64 243.5 64 243.5 173.5 176.5 238.5 65q87 0 160-24t120-60 82-82 51.5-87 22.5-78h-436v-264z"></path>
            </svg>
          </a>
          <a href="javascript:void(0)" class="colord button_social soc_ico apple onclick" data-auth="Apple"
             rel="noreferrer"
             data-toggle="tooltip" title="Apple">
            <svg fill="#000000" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path
                d="M 16.125 1 C 14.972 1.067 13.648328 1.7093438 12.861328 2.5273438 C 12.150328 3.2713438 11.589359 4.3763125 11.818359 5.4453125 C 13.071359 5.4783125 14.329031 4.8193281 15.082031 3.9863281 C 15.785031 3.2073281 16.318 2.12 16.125 1 z M 16.193359 5.4433594 C 14.384359 5.4433594 13.628 6.5546875 12.375 6.5546875 C 11.086 6.5546875 9.9076562 5.5136719 8.3476562 5.5136719 C 6.2256562 5.5146719 3 7.4803281 3 12.111328 C 3 16.324328 6.8176563 21 8.9726562 21 C 10.281656 21.013 10.599 20.176969 12.375 20.167969 C 14.153 20.154969 14.536656 21.011 15.847656 21 C 17.323656 20.989 18.476359 19.367031 19.318359 18.082031 C 19.922359 17.162031 20.170672 16.692344 20.638672 15.652344 C 17.165672 14.772344 16.474672 9.1716719 20.638672 8.0136719 C 19.852672 6.6726719 17.558359 5.4433594 16.193359 5.4433594 z"></path>
            </svg>
          </a>
        </div>
        <div class="or-text-wrapp">
          <div class="left-line"></div>
          <div class="text">{{__('popup.login_register.or')}}</div>
          <div class="right-line"></div>
        </div>
      </div>


      <div class="form-group">
        <div class="form">
          <div class="form-group group-telephone otpboxloginpopup" id="otpbox">

            <div class="fields-wrapper">
              <label for="name_email"
                     class="login-info-text text-center margintop20">{{__('popup.login_register.enter_phone')}}</label>
              <input type="text" id="name_email" name="phone_email" class="form-control form-field login-phone-email"
                     placeholder="{{__('popup.login_register.enter_phone_or_email_placeholder')}}">
              <label for="password"
                     class="login-info-text text-center margintop20">{{__('popup.login_register.password')}}</label>
              <input id="password" type="password" name="phone_email"
                     class="form-control form-field login-phone-email"
                     placeholder="{{__('popup.login_register.enter_password')}}">

              <div class="form-group group-firstname" id="regular-field-firstname">
                <input autocomplete="on" type="text" id="input-name" name="name" value="" maxlength="40"
                       class="form-control form-field required" required=""
                       placeholder="{{__('popup.login_register.firstname')}}">
              </div>
              <div class="form-group group-lastname" id="regular-field-lastname">
                <input autocomplete="on" type="text" id="input-last_name" name="last_name" value="" maxlength="40"
                       class="form-control form-field required" required=""
                       placeholder="{{__('popup.login_register.lastname')}}">
              </div>
              <div class="form-group group-telephone" id="regular-field-telephone">
                <input autocomplete="on" type="tel" id="input-telephone" name="telephone" value="" maxlength="40"
                       class="form-control form-field numeric required" required=""
                       placeholder="{{__('popup.login_register.mobile')}}">
              </div>

            </div>
            <div class="input-group telephone-error-loginpopup">
              <span class="input-group-btn">
					      <button class="send_otp_btn otp_btn-s btnverifyloginpopup button colord" id="button-verify-loginpopup"
                        type="button">
                  <i class="fas fa-chevron-right" aria-hidden="true"></i> {{__('popup.login_register.create_a_profile')}}
                </button>
				      </span>
              <p class="register-description">{!! __('popup.login_register.register_conditions') !!}</p>
            </div>
          </div>
        </div>
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
