@extends('base.layouts.app')

@php
    $locale = app()->getLocale();
    $deliveryTitle = __('shipping_and_payment.title') . ' | Wrap.Shop';
    $deliveryDescription = 'Доставка та оплата Wrap.Shop - швидка доставка по Україні, зручні способи оплати. Нова Пошта, кур\'єрська доставка, оплата карткою.';
    
    if ($locale === 'ru') {
        $deliveryDescription = 'Доставка и оплата Wrap.Shop - быстрая доставка по Украине, удобные способы оплаты. Новая Почта, курьерская доставка, оплата картой.';
    } elseif ($locale === 'en') {
        $deliveryDescription = 'Shipping and payment Wrap.Shop - fast delivery across Ukraine, convenient payment methods. Nova Poshta, courier delivery, card payment.';
    }
@endphp

@section('title', $deliveryTitle)
@section('description', $deliveryDescription)
@section('keywords', 'доставка, оплата, нова пошта, кур\'єр, wrap shop, плівки для авто')
@section('og_type', 'website')
@section('og_title', $deliveryTitle)
@section('og_description', $deliveryDescription)
@section('og_image', url('assets/img/og-default.jpg'))
@section('og_url', url()->current())
@section('twitter_card', 'summary_large_image')
@section('twitter_title', $deliveryTitle)
@section('twitter_description', $deliveryDescription)
@section('twitter_image', url('assets/img/og-default.jpg'))
@section('canonical', url()->current())

@section('content')

  @push('styles')
      @if($theme === 'dark')
          <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-information-dark.css')}}">
      @else
          <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-information-light.css')}}">
      @endif
  @endpush
  <nav class="breadcrumbs wrap row">
    <ul class="flex-center">
      <li><a href="{{route('index')}}" title="{{__('header_footer.home')}}" class="button">{{__('header_footer.home')}}</a></li>
      <li><span class="button">{{__('shipping_and_payment.title')}}</span></li>
    </ul>
  </nav>
  <section class="page-payment wrap">
    <h1 class="default-title">{{__('shipping_and_payment.title')}}</h1>
    <nav class="page-payment-nav flex-center">
      <div data-href="#tab0" class="button active">{{__('shipping_and_payment.payment_options')}}</div>
      <div data-href="#tab1" class="button">{{__('shipping_and_payment.delivery_options')}}</div>
    </nav>
    <div class="page-payment-tab">
      <div class="item active" id="tab0">
        <ul class="list flex-justify">
          <li>
            <span class="name"><img loading="lazy" src="{{asset('assets/img/icons/payment-3.png')}}" alt="{{__('shipping_and_payment.privat_24_by_QR_code.title')}}" title="{{__('shipping_and_payment.privat_24_by_QR_code.title')}}">{{__('shipping_and_payment.privat_24_by_QR_code.title')}}</span>
            {!!__('shipping_and_payment.privat_24_by_QR_code.description')!!}
          </li>
          <li>
            <span class="name"><img loading="lazy" src="{{asset('assets/img/icons/payment-2.png')}}" alt="{{__('shipping_and_payment.by_visa_mastercard.title')}}" title="{{__('shipping_and_payment.by_visa_mastercard.title')}}">{{__('shipping_and_payment.by_visa_mastercard.title')}}</span>
            {!!__('shipping_and_payment.by_visa_mastercard.description')!!}
          </li>
          <li>
            <span class="name"><img loading="lazy" src="{{asset('assets/img/icons/payment-1.png')}}" alt="{{__('shipping_and_payment.cash_on_delivery.title')}}" title="{{__('shipping_and_payment.cash_on_delivery.title')}}">{{__('shipping_and_payment.cash_on_delivery.title')}}</span>
            {!!__('shipping_and_payment.cash_on_delivery.description')!!}
          </li>
        </ul>
      </div>
      <div class="item" id="tab1">
        <ul class="list flex-justify">
          <li>
            <span class="name"><img loading="lazy" src="{{asset('assets/img/icons/delivery-2.png')}}" alt="{{__('shipping_and_payment.delivery_nova_poshta.title')}}" title="{{__('shipping_and_payment.delivery_nova_poshta.title')}}">{{__('shipping_and_payment.delivery_nova_poshta.title')}}</span>
            {!!__('shipping_and_payment.delivery_nova_poshta.description')!!}
          </li>
          <li>
            <span class="name"><img loading="lazy" src="{{asset('assets/img/icons/delivery-3.png')}}" alt="{{__('shipping_and_payment.address_delivery.title')}}" title="{{__('shipping_and_payment.address_delivery.title')}}">{{__('shipping_and_payment.address_delivery.title')}}</span>
            {!!__('shipping_and_payment.address_delivery.description')!!}
          </li>
          <li>
            <span class="name"><img loading="lazy" src="{{asset('assets/img/icons/delivery-1.png')}}" alt="{{__('shipping_and_payment.pickup.title')}}" title="{{__('shipping_and_payment.pickup.title')}}">{{__('shipping_and_payment.pickup.title')}}</span>
            {!! __('shipping_and_payment.pickup.description') !!}
          </li>
        </ul>
      </div>
    </div>
  </section>

  @push('scripts')
      <script src="{{mix('build/js/delivery_tabs.js')}}"></script>
  @endpush
@endsection

@push('fixed-catalog')
  @include('base.components.categories-catalog')
@endpush










