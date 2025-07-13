

@extends('base.layouts.checkout.app')


@section('content')
    @push('scripts')
    @endpush
    @push('styles')
        @if($theme === 'dark')
            <link rel="stylesheet" type="text/css" href="{{mix('build/css/simple-dark.css')}}">
        @else
            <link rel="stylesheet" type="text/css" href="{{mix('build/css/simple-light.css')}}">
        @endif
    @endpush

    <div id="account-success" class="page-account wrap">
        <div class="page-account-wrap flex-justify">

            <div id="content" class="page-account-right success-page">
                <h1>{{__('success.checkout.your_order')}}</h1>
                <div class="success-text">{!!__('success.checkout.your_order_description')!!}</div>
                <div class="buttons">
                    <div class="pull-right"><a href="{{route('index')}}" class="btn btn-primary">{{__('success.checkout.continue')}}</a></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{mix('build/js/checkoutPage.js')}}"></script>
    @endpush

    <script>
        document.getElementById('submitBtn').addEventListener('click', function() {
            document.getElementById('checkoutForm').submit();
        });
    </script>

@endsection
