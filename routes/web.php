<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


Route::group(['prefix' => LaravelLocalization::setLocale(),
  'middleware' => ['localizationRedirect', 'localeViewPath' ]], function(){

  Route::get('/', function () {
    return view('base.pages.main');
  });
  Route::get('/test', function () {
    return view('base.pages.main');
  });
});

