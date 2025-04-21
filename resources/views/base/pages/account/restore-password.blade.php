@extends('base.layouts.app')

@push('styles')
    @if(!isset($theme) || $theme === 'dark')
        <link rel="stylesheet" type="text/css" href="{{ mix('build/css/account-dark.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ mix('build/css/simple-dark.css') }}">
    @else
        <link rel="stylesheet" type="text/css" href="{{ mix('build/css/account-light.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ mix('build/css/simple-light.css') }}">
    @endif
@endpush

@section('content')
    <div class="wrap">
        <div id="content" class="restore-password">
            <h1>{{ __('passwords.reset_password') }}</h1>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form id="form-reset-password" method="POST" action="{{ route('password.store') }}" class="form-horizontal">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <fieldset>
                    <legend>{{ __('passwords.new_password_legend') }}</legend>

                    <div class="form-group">
                        <label for="password">{{__('passwords.password') }}</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                        @error('password')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">{{ __('passwords.password_confirmation') }}</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        @error('password_confirmation')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </fieldset>

                <div>
                    <button type="submit" class="button colord">
                        <i class="fas fa-chevron-right" aria-hidden="true"></i> {{__('passwords.submit')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('fixed-catalog')
    @include('base.components.categories-catalog')
@endpush
