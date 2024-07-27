@extends('base.layouts.app')


@section('content')

  <div class="home-top">
    <div class="ellipse orange"></div>
    @include('base.components.big-banners')
  </div>


  @push('scripts')

  @endpush
@endsection
