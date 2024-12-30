@extends('base.pages.account.layout')

@section('title', __('personal-account.change-password.meta_title'))

@section('account.content')
  <h1>{{__('personal-account.change-password.title')}}</h1>

  <div id="account-password">
    <form action="{{route('change-password.update')}}" method="post" enctype="multipart/form-data"
          class="form-horizontal">
      @csrf
      @method('PATCH')
      <fieldset class="form-horizontal">
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="password">{{__('personal-account.change-password.password')}}</label>
          <div class="col-sm-10">
            <input type="password" name="password" value="" placeholder="{{__('personal-account.change-password.password')}}" id="password"
                   class="form-control">
            @error('password')
            <div class="simplecheckout-rule-group">
              <div class="simplecheckout-error-text simplecheckout-rule">
                {{$errors->first('password')}}
              </div>
            </div>
            @enderror
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label"
                 for="password_confirmation">{{__('personal-account.change-password.confirm_password')}}</label>
          <div class="col-sm-10">
            <input type="password" name="password_confirmation" value=""
                   placeholder="{{__('personal-account.change-password.confirm_password')}}" id="password_confirmation"
                   class="form-control">
            @error('password_confirmation')
            <div class="simplecheckout-rule-group">
              <div class="simplecheckout-error-text simplecheckout-rule">
                {{$errors->first('password_confirmation')}}
              </div>
            </div>
            @enderror
          </div>
        </div>
      </fieldset>
      <div class="buttons flex-justify clearfix">
        <div class="pull-left"><a href="{{route('personal-data.edit')}}" class="btn btn-default">{{__('personal-account.change-password.back')}}</a>
        </div>
        <div class="pull-right">
          <input type="submit" value="{{__('personal-account.change-password.change_password')}}" class="btn btn-primary">
        </div>
      </div>
    </form>
  </div>
@endsection
@push('fixed-catalog')
  @include('base.components.categories-catalog')
@endpush
