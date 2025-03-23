@extends('base.layouts.app')


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

        <a href="tel:{{$settings->phone ?? '#'}}" class="phone">{!! $settings->phone_view ?? '#' !!}</a>
        <a href="tel:{{$settings->phone_aditional ?? '#'}}" class="phone">{!! $settings->phone_aditional_view ?? '#' !!}</a>
      </div>
      <div class="item">
        <div class="title flex-wrap">
          <img src="{{asset('assets/img/icons/email.png')}}" alt="{{__('contacts.write')}}" title="{{__('contacts.write')}}">
          {{__('contacts.write')}}
        </div>
        <a href="mailto:{{$settings->email ?? '#'}}" class="email" title="{{$settings->email ?? '#'}}">{{$settings->email ?? '#'}}</a>
        <div class="social">
          <a href="{{$settings->telegram ?? '#'}}" title="Telegram" target="_blank" class="fab fa-telegram-plane"></a>
          <a href="{{$settings->instagram ?? '#'}}" title="Instagram" target="_blank" class="fab fa-instagram"></a>
        </div>
      </div>
      <div class="item">
        <div class="title flex-wrap">
          <img src="{{asset('assets/img/icons/delivery-1.png')}}" alt="{{__('contacts.come_here')}}" title="{{__('contacts.come_here')}}">
          {{__('contacts.come_here')}}
        </div>
        <div class="text">{{$settings->address ?? '#'}}</div>
        <a href="{{$settings->google_map_link ?? '#'}}" target="_blank" title="{{__('contacts.make_a_route')}}" class="addlink">{{__('contacts.make_a_route')}}</a>
      </div>
    </div>
  </section>
@endsection

@push('fixed-catalog')
  @include('base.components.categories-catalog')
@endpush







