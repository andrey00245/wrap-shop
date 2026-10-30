@extends('base.layouts.app')

@section('body_class', 'page-checkout-success')

@section('content')
  <section id="account-success" class="checkout-success wrap">
    <div class="checkout-success__card">
      <h1 class="checkout-success__title">{{ __('success.checkout.your_order') }}</h1>

      <div class="checkout-success__group">
        <p class="checkout-success__lead">{{ __('success.checkout.lead') }}</p>
        <div class="checkout-success__links">
          <a href="{{ route('account') }}" class="checkout-success__link">
            {{ __('success.checkout.account') }}
          </a>
          <a href="{{ route('order') }}" class="checkout-success__link">
            {{ __('success.checkout.order_history') }}
          </a>
        </div>
      </div>

      <div class="checkout-success__group">
        <p class="checkout-success__note">
          {!! __('success.checkout.questions', ['url' => route('contacts')]) !!}
        </p>
        <p class="checkout-success__thanks">{{ __('success.checkout.thanks') }}</p>
      </div>

      <div class="checkout-success__actions">
        <a href="{{ route('index') }}" class="btn btn-primary checkout-success__btn">
          {{ __('success.checkout.continue') }}
        </a>
      </div>
    </div>
  </section>
@endsection
