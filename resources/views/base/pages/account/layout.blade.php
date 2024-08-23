@extends('base.layouts.app')

@push('styles')
  <link rel="stylesheet" type="text/css" href="{{mix('build/css/account-dark.css')}}">
@endpush

@section('content')
  <div id="account-account" class="page-account wrap">
    <div class="page-account-wrap flex-justify">
      <div class="page-account-left row">
        <div class="info flex-center">
          <div class="avatar flex-center"><i class="fal fa-user"></i></div>
          <div class="right">
            <span class="user">User name</span>
            <a href="#" class="link g_id_signout" title="{{__('personal-account.account.logout')}}">{{__('personal-account.account.logout')}}</a>
          </div>
          <div class="account-menu-open button"><i class="far fa-bars"></i></div>
        </div>
        <nav class="menu">
          <ul>
            <li><a href="{{route('personal-data')}}">{{__('personal-account.account.personal_data')}}</a></li>
            <li><a href="{{route('address')}}">{{__('personal-account.account.you_address')}}</a></li>
            <li><a href="{{route('order')}}">{{__('personal-account.account.order_story')}}</a></li>
            <li><a href="{{route('wishlist')}}">{{__('personal-account.account.wishlist')}} <span class="button account-wishlist-count">9</span></a></li>
            <li><a href="{{route('viewed-products')}}">{{__('personal-account.account.viewed-products')}}</a></li>
            <li><a class="cart-open" href="#">{{__('personal-account.account.cart')}} <span class="button account-cart-count">1</span></a></li>
          </ul>
        </nav>
      </div>

      <div id="content" class="page-account-right">
        @yield('account.content')
      </div>
    </div>
  </div>

@endsection
