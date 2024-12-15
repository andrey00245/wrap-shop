@extends('base.layouts.app')

@push('styles')
  <link rel="stylesheet" href="{{mix('build/css/all-dark.css')}}">
@endpush

@section('title', $privacy_policy->meta_title)
@section('description', $privacy_policy->meta_description)

@section('content')
  <nav class="breadcrumbs wrap row">
    <ul class="flex-center">
      <li><a href="https://wrap.shop/" title="{{__('header_footer.home')}}" class="button">{{__('header_footer.home')}}</a></li>
      <li><span class="button">{{$privacy_policy->h1}}</span></li>
    </ul>
  </nav>

  <section class="page-default wrap">
    <h1 class="default-title">{{$privacy_policy->h1}}</h1>
    <div class="page-default-desc">
      {!! $privacy_policy->content !!}
    </div>
  </section>
@endsection
