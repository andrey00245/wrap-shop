<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


Route::group(['prefix' => LaravelLocalization::setLocale(),
  'middleware' => ['localizationRedirect', 'localeViewPath' ]], function(){

  Route::get('/', function () {
    return view('base.pages.main');
  })->name('index');

  Route::get('/shipping-and-payment', function () {
    return view('base.pages.delivery');
  })->name('delivery');

  Route::get('/videoreviews', function () {
    return view('base.pages.videoreviews');
  })->name('videoreviews');

  Route::get('/news', function () {
    return view('base.pages.news');
  })->name('news');

  Route::get('/about-us', function () {
    return view('base.pages.about-us');
  })->name('about-us');

  Route::get('/contacts', function () {
    return view('base.pages.contacts');
  })->name('contacts');

  Route::group(['prefix' => '/account'], function(){
    Route::get('/', function () {
      return view('base.pages.account.account');
    })->name('account');

    Route::get('/personal-data', function () {
      return view('base.pages.account.personal-data');
    })->name('personal-data');

    Route::get('/address', function () {
      return view('base.pages.account.address');
    })->name('address');

    Route::get('/order', function () {
      return view('base.pages.account.order');
    })->name('order');

    Route::get('/viewed-products', function () {
      return view('base.pages.account.viewed-products');
    })->name('viewed-products');

    Route::get('/wishlist', function () {
      return view('base.pages.account.wishlist');
    })->name('wishlist');
  });



});

