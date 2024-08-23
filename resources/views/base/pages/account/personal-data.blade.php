@extends('base.pages.account.layout')

@section('account.content')
  <h1>{{__('personal-account.account.personal_data')}}</h1>
  <div class="simple-content">
    <div class="simpleedit-nav">
      <div class="button fiz active"><span class="flex-center"></span>{{__('personal-account.personal-data.physical_person')}}</div>
      <div class="button yur"><span class="flex-center"></span>{{__('personal-account.personal-data.legal_entity')}}</div>
    </div>

    @push('scripts')
      <script type="text/javascript">

        $(".simpleedit-nav .fiz").on("click", function () {
          $(".simpleedit-nav .button").removeClass("active");
          $(this).addClass("active");
          $(".simple-content .simpleregister .form-horizontal .form-group").hide();
          $(".simple-content .simpleregister .form-horizontal .form-group.required").show();
        });
        $(".simpleedit-nav .yur").on("click", function () {
          $(".simpleedit-nav .button").removeClass("active");
          $(this).addClass("active");
          $(".simple-content .simpleregister .form-horizontal .form-group").show();
          $(".simple-content .simpleregister .form-horizontal .form-group.required").hide();
        });
      </script>

    @endpush
    <form action="index.php?route=account/simpleedit" method="post" enctype="multipart/form-data" id="simplepage_form">
      <div class="simpleregister" id="simpleedit">
        <div class="simpleregister-block-content">
          <fieldset class="form-horizontal">
            <div class="form-group required row-edit_email">
              <label class="control-label col-sm-2" for="edit_email">Email</label>
              <div class="col-sm-10">
                <input class="form-control" type="email" name="edit[email]" id="edit_email" value="tosik19940@gmail.com"
                       placeholder="Введіть Email">
                <div class="simplecheckout-rule-group" data-for="edit_email">
                  <div style="display:none;" data-for="edit_email" data-for-type="email" data-rule="api"
                       class="simplecheckout-error-text simplecheckout-rule" data-method="checkEmailForUniqueness"
                       data-filter="edit_register" data-filter-type="radio" data-filter-value="1" data-required="true">
                    Адреса вже зареєстрована!
                  </div>
                  <div style="display:none;" data-for="edit_email" data-for-type="email" data-rule="regexp"
                       class="simplecheckout-error-text simplecheckout-rule"
                       data-regexp="[^@ \t\r\n]+@[^@ \t\r\n]+.[^@ \t\r\n]+" data-required="true">Некоректна адреса
                    електронної пошти!
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group required row-edit_firstname">
              <label class="control-label col-sm-2" for="edit_firstname">{{__('personal-account.personal-data.firstname')}}</label>
              <div class="col-sm-10">
                <input class="form-control" type="text" name="edit[firstname]" id="edit_firstname" value="Сергій"
                       placeholder="Введіть Ім'я">
                <div class="simplecheckout-rule-group" data-for="edit_firstname">
                  <div style="display:none;" data-for="edit_firstname" data-for-type="text" data-rule="byLength"
                       class="simplecheckout-error-text simplecheckout-rule" data-length-min="3" data-length-max="12"
                       data-required="true">Ім'я має бути від 3 до 12 символів!
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group required row-edit_lastname">
              <label class="control-label col-sm-2" for="edit_lastname">{{__('personal-account.personal-data.lastname')}}</label>
              <div class="col-sm-10">
                <input class="form-control" type="text" name="edit[lastname]" id="edit_lastname" value="Толстіков"
                       placeholder="Введіть прізвище">
                <div class="simplecheckout-rule-group" data-for="edit_lastname">
                  <div style="display:none;" data-for="edit_lastname" data-for-type="text" data-rule="byLength"
                       class="simplecheckout-error-text simplecheckout-rule" data-length-min="3" data-length-max="15"
                       data-required="true">Прізвище має бути від 3 до 15 символів!
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group required row-edit_telephone">
              <label class="control-label col-sm-2" for="edit_telephone">{{__('personal-account.personal-data.telephone')}}</label>
              <div class="col-sm-10">
                <input class="form-control" type="tel" name="edit[telephone]" id="edit_telephone" value="+380989372420"
                       placeholder="Введіть телефон" data-onchange="reloadAll">
                <div class="simplecheckout-rule-group" data-for="edit_telephone">
                  <div style="display:none;" data-for="edit_telephone" data-for-type="tel" data-rule="api"
                       class="simplecheckout-error-text simplecheckout-rule" data-method="checkNumberPhone"
                       data-filter="edit_telephone" data-filter-type="tel" data-filter-value="380989372420"
                       data-required="true">Перевірте правильність вводу номера телефону!
                  </div>
                  <div style="display:none;" data-for="edit_telephone" data-for-type="tel" data-rule="notEmpty"
                       class="simplecheckout-error-text simplecheckout-rule" data-not-empty="1" data-required="true">
                    Заповніть номер телефону
                  </div>
                </div>
              </div>
            </div>

          </fieldset>
        </div>
        <div class="simpleregister-button-block buttons flex-justify">
          <a href="#" title="{{__('personal-account.personal-data.change_password')}}" class="colord link">{{__('personal-account.personal-data.change_password')}}</a>
          <div class="simpleregister-button-right">
            <a class="button btn-primary button_oc btn" data-onclick="submit" id="simpleregister_button_confirm"><i
                class="fas fa-chevron-right"></i><span>{{__('personal-account.personal-data.save_changes')}}</span></a>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection
