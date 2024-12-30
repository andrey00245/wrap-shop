@extends('base.pages.account.layout')
@section('title', __('personal-account.account.meta_title'))

@section('account.content')
  <h1>{{__('personal-account.account.title')}}</h1>
  <div class="page-account-home flex-wrap">
    <a class="item button" href="{{route('personal-data.edit')}}"><i class="fal fa-user-edit"></i>{{__('personal-account.account.personal_data')}}</a>
    <a class="item button" href="{{route('address')}}"><i class="fal fa-map-marker-alt"></i>{{__('personal-account.account.you_address')}}</a>
    <a class="item button" href="{{route('order')}}"><i class="fal fa-box-full"></i>{{__('personal-account.account.order_story')}}</a>
    <a class="item button" href="{{route('wishlist')}}"><i class="fal fa-heart"></i>{{__('personal-account.account.wishlist')}} <span class="button account-wishlist-count">{{auth()->user()->favoriteCount()}}</span></a>
    <a class="item button cart-open" href="#"><i class="fal fa-shopping-cart"></i>{{__('personal-account.account.cart')}} <span class="button account-cart-count">1</span></a>
  </div>
@endsection
@push('fixed-catalog')
  @include('base.components.categories-catalog')
@endpush
