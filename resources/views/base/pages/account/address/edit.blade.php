@extends('base.pages.account.layout')
@section('title', __('personal-account.you-addresses.meta_title'))

@section('account.content')
  <h1>{{__('personal-account.editing-adding-address.title')}}</h1>
  <div class="simple-content">
    <form action="{{route('account.address.update', ['address'=>$address->id])}}" method="post" enctype="multipart/form-data"
          id="simplepage_form">
      @csrf
      @method('PUT')
      <div class="simpleregister" id="simpleaddress">
        <div class="simpleregister-block-content">
          <fieldset class="form-horizontal">
            <div class="form-group required row-address_city">
              <label class="control-label col-sm-2"
                     for="city">{{__('personal-account.editing-adding-address.locality')}}</label>
              <div class="col-sm-10">
                <input class="form-control" type="text" name="city" id="city" value="{{$address->city}}"
                       placeholder="{{__('personal-account.editing-adding-address.enter-city')}}">
                @error('city')
                <div class="simplecheckout-rule-group">
                  <div class="simplecheckout-error-text simplecheckout-rule">
                    {{$errors->first('city')}}
                  </div>
                </div>
                @enderror
              </div>
            </div>

            <div class="form-group required row-address_address_1">
              <label class="control-label col-sm-2"
                     for="address">{{__('personal-account.editing-adding-address.branch-address')}}</label>
              <div class="col-sm-10">
                <input class="form-control" type="text" name="address" id="address" value="{{$address->address}}"
                       placeholder="{{__('personal-account.editing-adding-address.enter-address')}}">
                @error('address')
                <div class="simplecheckout-rule-group">
                  <div class="simplecheckout-error-text simplecheckout-rule">
                    {{$errors->first('address')}}
                  </div>
                </div>
                @enderror
              </div>
            </div>
          </fieldset>
        </div>
        <div class="simpleregister-button-block buttons">
          <div class="simpleregister-button-right">
            <button class="button btn-primary button_oc btn" data-onclick="submit"
                    id="simpleregister_button_confirm"><span>{{__('personal-account.editing-adding-address.save')}}</span></button>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection
