@extends('base.layouts.app')


@section('content')

  <div class="home-top">
    <div class="ellipse orange"></div>
    @include('base.components.big-banners')
    @include('base.components.categories-slider')
    @include('base.components.bestseller')
{{--    @include('base.components.3m-color-wrap')--}}
{{--    @include('base.components.yellotools')--}}
    @include('base.components.latest')
    @include('base.components.examples-of-work')

    @push('scripts')
      <script src="{{mix('build/js/sliders.js')}}"></script>
    @endpush
  </div>



@endsection
