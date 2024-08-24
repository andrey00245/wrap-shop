@extends('base.pages.account.layout')

@section('account.content')
  <h1>{{__('personal-account.editing-adding-address.title')}}</h1>
  <div class="simple-content">
    <form action="index.php?route=account/simpleaddress/insert" method="post" enctype="multipart/form-data" id="simplepage_form">
      <div class="simpleregister" id="simpleaddress">
        <div class="simpleregister-block-content">
          <fieldset class="form-horizontal">
            <div class="form-group required row-address_city">
              <label class="control-label col-sm-2" for="address_city">{{__('personal-account.editing-adding-address.locality')}}</label>
              <div class="col-sm-10">
                <input class="form-control" type="text" name="address[city]" id="address_city" value="" placeholder="{{__('personal-account.editing-adding-address.enter-city')}}" data-onchange="reloadAll">
                <div class="simplecheckout-rule-group" data-for="address_city">
                  <div style="display:none;" data-for="address_city" data-for-type="text" data-rule="notEmpty" class="simplecheckout-error-text simplecheckout-rule" data-not-empty="1" data-required="true">This field is required!</div>
                </div>
              </div>
            </div>

            <div class="form-group required row-address_address_1">
              <label class="control-label col-sm-2" for="address_address_1">{{__('personal-account.editing-adding-address.branch-address')}}</label>
              <div class="col-sm-10">
                <input class="form-control" type="text" name="address[address_1]" id="address_address_1" value="" placeholder="{{__('personal-account.editing-adding-address.enter-address')}}">
                <div class="simplecheckout-rule-group" data-for="address_address_1">
                  <div style="display:none;" data-for="address_address_1" data-for-type="text" data-rule="notEmpty" class="simplecheckout-error-text simplecheckout-rule" data-not-empty="1" data-required="true">This field is required!</div>
                </div>
              </div>
            </div>

          </fieldset>
          <input type="hidden" name="address[country_id]" id="address_country_id" value="220">
          <input type="hidden" name="address[zone_id]" id="address_zone_id" value="0">
          <input type="hidden" name="address[postcode]" id="address_postcode" value="">
          <input type="hidden" name="address[current_address_id]" id="address_current_address_id" value="0">
        </div>
        <div class="simpleregister-button-block buttons">
          <div class="simpleregister-button-right">
            <a class="button btn-primary button_oc btn" data-onclick="submit" id="simpleregister_button_confirm"><span>{{__('personal-account.editing-adding-address.save')}}</span></a>
          </div>
        </div>
      </div>
    </form>
  </div>
  </div>
@endsection
