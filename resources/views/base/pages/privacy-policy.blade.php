@extends('base.layouts.app')

@php
    $locale = app()->getLocale();
    $privacyTitle = $privacy_policy?->meta_title ?? __('seo.privacy-policy.title') . ' | ' . __('seo.site_name');
    $privacyDescription = $privacy_policy?->meta_description ?? __('seo.privacy-policy.description');
@endphp

@push('styles')
  <link rel="stylesheet" href="{{mix('build/css/all-dark.css')}}">
@endpush

@section('title', $privacyTitle)
@section('description', $privacyDescription)
@section('keywords', __('seo.privacy-policy.keywords'))
@section('og_type', 'website')
@section('og_title', $privacyTitle)
@section('og_description', $privacyDescription)
@section('og_image', url('assets/img/og-default.jpg'))
@section('og_url', url()->current())
@section('twitter_card', __('seo.default_twitter_card'))
@section('twitter_title', $privacyTitle)
@section('twitter_description', $privacyDescription)
@section('twitter_image', url('assets/img/og-default.jpg'))
@section('canonical', url()->current())

@section('content')
  <nav class="breadcrumbs wrap row">
    <ul class="flex-center">
      <li><a href="{{route('index')}}" title="{{__('header_footer.home')}}" class="button">{{__('header_footer.home')}}</a></li>
      <li><span class="button">{{$privacy_policy?->h1 ?? __('seo.privacy-policy.title')}}</span></li>
    </ul>
  </nav>

  <section class="page-default wrap">
    <h1 class="default-title">{{$privacy_policy?->h1 ?? __('seo.privacy-policy.title')}}</h1>
    <div class="page-default-desc">
      @if($privacy_policy && $privacy_policy->content)
        {!! $privacy_policy->content !!}
      @else
        <p>{{__('seo.privacy-policy.description')}}</p>
        <p>Сторінка знаходиться в розробці. Будь ласка, зверніться до нас для отримання детальної інформації.</p>
      @endif
    </div>
  </section>
@endsection

@push('fixed-catalog')
  @include('base.components.categories-catalog')
@endpush
