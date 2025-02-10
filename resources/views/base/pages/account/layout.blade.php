@extends('base.layouts.app')

@push('styles')
  <link rel="stylesheet" type="text/css" href="{{mix('build/css/account-dark.css')}}">
  <link rel="stylesheet" type="text/css" href="{{mix('build/css/simple-dark.css')}}">
@endpush

@section('content')
  <div id="account-account" class="page-account wrap">
    <div class="page-account-wrap flex-justify">
      <div class="page-account-left row">
        <div class="info flex-center">
          <div class="avatar flex-center"><i class="fal fa-user"></i></div>
          <div class="right">
            <span class="user">{{auth()->user()->name . ' ' .auth()->user()->last_name}}</span>
            <a href="#" class="link g_id_signout"
               title="{{ __('personal-account.account.logout') }}">{{ __('personal-account.account.logout') }}</a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </div>
          <div class="account-menu-open button"><i class="far fa-bars"></i></div>
        </div>
        <nav class="menu">
          <ul>
            <li><a href="{{route('personal-data.edit')}}">{{__('personal-account.account.personal_data')}}</a></li>
            <li><a href="{{route('address')}}">{{__('personal-account.account.you_address')}}</a></li>
            <li><a href="{{route('order')}}">{{__('personal-account.account.order_story')}}</a></li>
            <li><a href="{{route('wishlist')}}">{{__('personal-account.account.wishlist')}} <span
                  class="button account-wishlist-count">{{auth()->user()->favoriteCount()}}</span></a></li>
            <li><a href="{{route('viewed-products')}}">{{__('personal-account.account.viewed-products')}}</a></li>
            <li><a class="cart-open" href="#">{{__('personal-account.account.cart')}} <span
                  class="button account-cart-count">{{$cartItemsCount}}</span></a></li>
          </ul>
        </nav>
      </div>

      <div id="content" class="page-account-right">
        @if (session('success'))
          <div class="alert alert-success alert-dismissible"><i
              class="fal fa-check-circle"></i> {{ session('success') }}</div>
        @endif

        @if (session('error'))
          <div class="alert alert-warning"><i class="fal fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif

        @yield('account.content')
      </div>
    </div>
  </div>

@endsection
@push('fixed-catalog')
  @include('base.components.categories-catalog')
@endpush
