@extends('base.layouts.app')

@push('styles')
  <link rel="stylesheet" type="text/css" href="{{mix('build/css/account-dark.css')}}">
  <link rel="stylesheet" type="text/css" href="{{mix('build/css/simple-dark.css')}}">
@endpush

@section('content')
  <div class="wrap">
    <div id="content" class="restore-password">
      <h1>Відновити пароль</h1>
      <form action="#"
            method="post" enctype="multipart/form-data" class="form-horizontal">
        <fieldset>
          <legend>Введіть новий пароль.</legend>
          <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" name="password" value="" id="password" class="form-control">

            <p class="simplecheckout-error-text simplecheckout-rule">Паролі не співпадають</p>

          </div>
          <div class="form-group">
            <label for="password_confirmation">Підтвердження пароля</label>
            <input type="password" name="password_confirmation" value="" id="password_confirmation" class="form-control">
          </div>
        </fieldset>
        <div>
          <button type="submit" class="button colord"><i class="fas fa-chevron-right" aria-hidden="true"></i>Продовжити
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection
