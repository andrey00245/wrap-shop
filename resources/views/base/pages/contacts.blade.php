@extends('base.layouts.app')


@section('content')
  @push('styles')
    <link rel="stylesheet" type="text/css" href="{{mix('build/css/style-information-dark.css')}}">
  @endpush

  <nav class="breadcrumbs wrap row">
    <ul class="flex-center">
      <li>
        <a href="{{route('index')}}" title="{{__('header_footer.home')}}" class="button">{{__('header_footer.home')}}</a></li>
      <li>
        <span class="button">{{__('contacts.title')}}</span>
      </li>
    </ul>
  </nav>

  <section class="page-contact wrap">
    <h1 class="default-title">{{__('contacts.title')}}</h1>
    <div class="page-contact-list">
      <div class="item">
        <div class="title flex-wrap">
          <img src="{{asset('assets/img/icons/phone.png')}}" alt="{{__('contacts.call')}}" title="{{__('contacts.call')}}">
          {{__('contacts.call')}}
        </div>

        <a href="tel:{{$settings->getPhone()}}" class="phone">{!! $settings->getPhoneView() !!}</a>
        <a href="tel:{{$settings->getAditionalPhone()}}" class="phone">{!! $settings->getAditionalPhoneView() !!}</a>
      </div>
      <div class="item">
        <div class="title flex-wrap">
          <img src="{{asset('assets/img/icons/email.png')}}" alt="{{__('contacts.write')}}" title="{{__('contacts.write')}}">
          {{__('contacts.write')}}
        </div>
        <a href="mailto:{{$settings->getEmail()}}" class="email" title="{{$settings->getEmail()}}">{{$settings->getEmail()}}</a>
        <div class="social">
          <a href="{{$settings->getTelegramUrl()}}" title="Telegram" target="_blank" class="fab fa-telegram-plane"></a>
          <a href="{{$settings->getInstagramUrl()}}" title="Instagram" target="_blank" class="fab fa-instagram"></a>
        </div>
      </div>
      <div class="item">
        <div class="title flex-wrap">
          <img src="{{asset('assets/img/icons/delivery-1.png')}}" alt="{{__('contacts.come_here')}}" title="{{__('contacts.come_here')}}">
          {{__('contacts.come_here')}}
        </div>
        <div class="text">{{$settings->getAddress()}}</div>
        <a href="{{$settings->getGoogleMapsUrl()}}" target="_blank" title="{{__('contacts.make_a_route')}}" class="addlink">{{__('contacts.make_a_route')}}</a>
      </div>
    </div>
  </section>
@endsection

@push('fixed-catalog')
  @include('base.components.categories-catalog')
@endpush







